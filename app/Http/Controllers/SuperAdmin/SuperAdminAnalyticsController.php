<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsVisitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        // 1. Core Summary Metrics
        $totalPageViews = AnalyticsVisitor::count();
        $totalUniqueVisitors = AnalyticsVisitor::distinct('ip_address')->count('ip_address');
        $todayPageViews = AnalyticsVisitor::whereDate('created_at', $today)->count();
        $todayUniqueVisitors = AnalyticsVisitor::whereDate('created_at', $today)
            ->where('is_unique_daily', true)
            ->count();

        // 2. Trend Graph (Last 14 Days)
        $daysRange = 14;
        $trendDates = [];
        $trendViews = [];
        $trendUnique = [];

        for ($i = $daysRange - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $label = now()->subDays($i)->format('d M');

            $views = AnalyticsVisitor::whereDate('created_at', $date)->count();
            $unique = AnalyticsVisitor::whereDate('created_at', $date)
                ->where('is_unique_daily', true)
                ->count();

            $trendDates[] = $label;
            $trendViews[] = $views;
            $trendUnique[] = $unique;
        }

        // 3. Top 8 Visited Pages
        $topPages = AnalyticsVisitor::select('path', DB::raw('count(*) as visits_count'))
            ->groupBy('path')
            ->orderByDesc('visits_count')
            ->take(8)
            ->get();

        // 4. Top Countries
        $topCountries = AnalyticsVisitor::select('country', 'country_code', DB::raw('count(*) as total_count'))
            ->whereNotNull('country')
            ->groupBy('country', 'country_code')
            ->orderByDesc('total_count')
            ->take(8)
            ->get();

        // 5. Device & Browser Breakdown
        $deviceBreakdown = AnalyticsVisitor::select('device', DB::raw('count(*) as count'))
            ->groupBy('device')
            ->pluck('count', 'device')
            ->toArray();

        $browserBreakdown = AnalyticsVisitor::select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->take(5)
            ->pluck('count', 'browser')
            ->toArray();

        // 6. Recent Visitor Logs Table with filters
        $logsQuery = AnalyticsVisitor::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $logsQuery->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('path', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%");
            });
        }

        if ($request->filled('device') && $request->device !== 'all') {
            $logsQuery->where('device', $request->device);
        }

        $visitorLogs = $logsQuery->paginate(25)->withQueryString();

        return view('super_admin.analytics.index', compact(
            'totalPageViews',
            'totalUniqueVisitors',
            'todayPageViews',
            'todayUniqueVisitors',
            'trendDates',
            'trendViews',
            'trendUnique',
            'topPages',
            'topCountries',
            'deviceBreakdown',
            'browserBreakdown',
            'visitorLogs'
        ));
    }
}
