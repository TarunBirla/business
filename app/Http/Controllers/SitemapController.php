<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\CmsPage;
use App\Models\Event;
use App\Models\Group;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = config('app.url', 'https://bizconn.co.uk');
        $baseUrl = rtrim($baseUrl, '/');

        $urls = [];

        // 1. Static Public Pages
        $urls[] = [
            'loc' => $baseUrl . '/',
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/groups',
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/events',
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/blogs',
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/request-community',
            'lastmod' => now()->toDateString(),
            'changefreq' => 'monthly',
            'priority' => '0.6',
        ];

        // 2. Active Public Groups
        try {
            $groups = Group::where('status', 'active')
                ->where('visibility_type', 'public')
                ->select(['slug', 'updated_at'])
                ->get();

            foreach ($groups as $group) {
                if (!empty($group->slug)) {
                    $urls[] = [
                        'loc' => $baseUrl . '/groups/' . rawurlencode($group->slug),
                        'lastmod' => $group->updated_at ? $group->updated_at->toDateString() : now()->toDateString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully if database fails
        }

        // 3. Published Events
        try {
            $events = Event::where('status', 'published')
                ->select(['slug', 'updated_at'])
                ->get();

            foreach ($events as $event) {
                if (!empty($event->slug)) {
                    $urls[] = [
                        'loc' => $baseUrl . '/events/' . rawurlencode($event->slug),
                        'lastmod' => $event->updated_at ? $event->updated_at->toDateString() : now()->toDateString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        // 4. Published Blogs
        try {
            $blogs = Blog::where('status', 'active')
                ->select(['slug', 'updated_at'])
                ->get();

            foreach ($blogs as $blog) {
                if (!empty($blog->slug)) {
                    $urls[] = [
                        'loc' => $baseUrl . '/blogs/' . rawurlencode($blog->slug),
                        'lastmod' => $blog->updated_at ? $blog->updated_at->toDateString() : now()->toDateString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        // 5. Published CMS Pages
        try {
            $pages = CmsPage::where('is_published', true)
                ->whereNull('group_id')
                ->select(['slug', 'updated_at'])
                ->get();

            foreach ($pages as $page) {
                if (!empty($page->slug)) {
                    $urls[] = [
                        'loc' => $baseUrl . '/page/' . rawurlencode($page->slug),
                        'lastmod' => $page->updated_at ? $page->updated_at->toDateString() : now()->toDateString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.5',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        // Build XML output
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $item) {
            $xml .= "    <url>\n";
            $xml .= '        <loc>' . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= '        <lastmod>' . $item['lastmod'] . "</lastmod>\n";
            $xml .= '        <changefreq>' . $item['changefreq'] . "</changefreq>\n";
            $xml .= '        <priority>' . $item['priority'] . "</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
