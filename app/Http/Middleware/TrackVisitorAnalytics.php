<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AnalyticsVisitor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorAnalytics
{
    /**
     * Handle an incoming request and track visitor analytics.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET web page requests (skip assets, ajax, api, bots)
        if ($request->isMethod('GET') && !$request->expectsJson() && !$request->ajax()) {
            $path = $request->path();

            // Skip static files, admin actions, or internal routes
            $skipPatterns = [
                'api/*', 'storage/*', 'css/*', 'js/*', 'images/*', 'favicon*',
                'fix-*', '*.png', '*.jpg', '*.jpeg', '*.svg', '*.webp'
            ];

            foreach ($skipPatterns as $pattern) {
                if ($request->is($pattern)) {
                    return $response;
                }
            }

            try {
                $this->recordVisit($request);
            } catch (\Throwable $e) {
                // Silently ignore to never break the application
            }
        }

        return $response;
    }

    protected function recordVisit(Request $request): void
    {
        // 1. Resolve real client IP
        $rawIp = $request->header('CF-Connecting-IP') 
            ?? $request->header('X-Forwarded-For') 
            ?? $request->ip();

        if (str_contains($rawIp, ',')) {
            $rawIp = trim(explode(',', $rawIp)[0]);
        }

        $ip = $rawIp ?: '127.0.0.1';
        $today = now()->toDateString();

        // 2. Determine unique daily visitor (duplicate logic: count 1 unique visit per IP per day)
        $cacheUniqueKey = "analytics_unique_{$ip}_{$today}";
        $isUniqueDaily = false;

        if (!Cache::has($cacheUniqueKey)) {
            $alreadyRecordedToday = AnalyticsVisitor::where('ip_address', $ip)
                ->whereDate('created_at', $today)
                ->exists();

            if (!$alreadyRecordedToday) {
                $isUniqueDaily = true;
            }
            Cache::put($cacheUniqueKey, true, now()->endOfDay());
        }

        // 3. Detect Browser, OS, and Device from User Agent
        $userAgent = $request->userAgent() ?? '';
        $browserInfo = $this->parseUserAgent($userAgent);

        // 4. Resolve Geo Location (Cached per IP for 7 days)
        $geoData = $this->resolveGeoLocation($ip);

        // 5. Store visit record in database
        AnalyticsVisitor::create([
            'ip_address' => $ip,
            'session_id' => session()->getId(),
            'user_id' => auth()->id(),
            'url' => $request->fullUrl(),
            'path' => '/' . ltrim($request->path(), '/'),
            'method' => $request->method(),
            'referrer' => $request->header('referer'),
            'user_agent' => substr($userAgent, 0, 500),
            'browser' => $browserInfo['browser'],
            'device' => $browserInfo['device'],
            'os' => $browserInfo['os'],
            'country' => $geoData['country'] ?? 'United Kingdom',
            'country_code' => $geoData['country_code'] ?? 'GB',
            'city' => $geoData['city'] ?? 'London',
            'region' => $geoData['region'] ?? 'England',
            'is_unique_daily' => $isUniqueDaily,
        ]);
    }

    protected function resolveGeoLocation(string $ip): array
    {
        // If local or private IP, return default UK
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return [
                'country' => 'United Kingdom',
                'country_code' => 'GB',
                'city' => 'London',
                'region' => 'England',
            ];
        }

        return Cache::remember("geoip_{$ip}", 86400 * 7, function () use ($ip) {
            try {
                $res = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city");
                if ($res->successful() && $res->json('status') === 'success') {
                    return [
                        'country' => $res->json('country') ?? 'Unknown',
                        'country_code' => $res->json('countryCode') ?? 'XX',
                        'city' => $res->json('city') ?? 'Unknown',
                        'region' => $res->json('regionName') ?? 'Unknown',
                    ];
                }
            } catch (\Throwable $e) {
                // Fallback
            }

            return [
                'country' => 'United Kingdom',
                'country_code' => 'GB',
                'city' => 'London',
                'region' => 'England',
            ];
        });
    }

    protected function parseUserAgent(string $ua): array
    {
        // Device detection
        $device = 'desktop';
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $ua)) {
            $device = 'tablet';
        } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile|mobile)/i', $ua)) {
            $device = 'mobile';
        }

        // Browser detection
        $browser = 'Other';
        if (preg_match('/Edge\/\d+/i', $ua) || preg_match('/Edg\/\d+/i', $ua)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/Chrome\/\d+/i', $ua) && !preg_match('/Edg/i', $ua)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/Safari\/\d+/i', $ua) && !preg_match('/Chrome/i', $ua)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/Firefox\/\d+/i', $ua)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/MSIE|Trident/i', $ua)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Opera|OPR/i', $ua)) {
            $browser = 'Opera';
        }

        // OS detection
        $os = 'Other';
        if (preg_match('/windows|win32|win98|win95|win_95/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        }

        return [
            'device' => $device,
            'browser' => $browser,
            'os' => $os,
        ];
    }
}
