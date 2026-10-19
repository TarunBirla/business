@extends('layouts.dashboard')

@section('title', 'Website Analytics & Visitors')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-chart-line text-sky-600"></i>
                <span>Traffic Analytics & Visitors</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Real-time IP logging, unique daily visitor tracking, geo-location, and visitor trends.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                Live Tracking Active
            </span>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                <span>Today's Unique</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($todayUniqueVisitors) }}</div>
            <p class="text-[11px] text-slate-500">De-duplicated visitors today (1 per IP)</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                <span>Today's Views</span>
                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($todayPageViews) }}</div>
            <p class="text-[11px] text-slate-500">Total hits/page views today</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                <span>Total Unique Visitors</span>
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($totalUniqueVisitors) }}</div>
            <p class="text-[11px] text-slate-500">All-time distinct IP visitors</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                <span>All-Time Page Views</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($totalPageViews) }}</div>
            <p class="text-[11px] text-slate-500">Total website hits recorded</p>
        </div>
    </div>

    <!-- Chart: Last 14 Days Traffic Trend -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                <i class="fa-solid fa-chart-area text-sky-600"></i>
                <span>Traffic Trend (Last 14 Days)</span>
            </h3>
            <span class="text-xs text-slate-400 font-semibold">Unique Visitors vs Total Hits</span>
        </div>
        <div class="h-72 w-full">
            <canvas id="visitorsChart"></canvas>
        </div>
    </div>

    <!-- 2 Column Breakdown: Top Pages & Top Locations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Visited Pages -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                <i class="fa-solid fa-file-lines text-sky-600"></i>
                <span>Top Visited Pages</span>
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($topPages as $page)
                    <div class="py-2.5 flex items-center justify-between">
                        <span class="font-bold text-slate-800 font-mono text-[12px] truncate max-w-[280px]" title="{{ $page->path }}">
                            {{ $page->path }}
                        </span>
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg font-extrabold text-[11px]">
                            {{ number_format($page->visits_count) }} views
                        </span>
                    </div>
                @empty
                    <p class="text-slate-400 py-4 text-center">No page traffic recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Countries / GEO -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                <i class="fa-solid fa-earth-americas text-emerald-600"></i>
                <span>Top Visitor Geographies</span>
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                @forelse($topCountries as $geo)
                    <div class="py-2.5 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center font-bold text-[10px] uppercase text-slate-600">
                                {{ $geo->country_code ?: 'GB' }}
                            </span>
                            <span class="font-bold text-slate-800">{{ $geo->country ?: 'United Kingdom' }}</span>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-extrabold text-[11px]">
                            {{ number_format($geo->total_count) }} visits
                        </span>
                    </div>
                @empty
                    <p class="text-slate-400 py-4 text-center">No geographic data logged yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Detailed Live Visitors Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-list text-sky-600"></i>
                    <span>Live Visitor Logs</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Real-time incoming request log with IP, device, and location.</p>
            </div>

            <!-- Search & Filters -->
            <form method="GET" action="{{ route('super_admin.analytics.index') }}" class="flex items-center space-x-2">
                <select name="device" onchange="this.form.submit()" class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-sky-500 bg-white">
                    <option value="all">All Devices</option>
                    <option value="desktop" {{ request('device') === 'desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="mobile" {{ request('device') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="tablet" {{ request('device') === 'tablet' ? 'selected' : '' }}>Tablet</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search IP, path, city..." class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500">
                <button type="submit" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl transition">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase tracking-wider font-bold text-[11px]">
                        <th class="py-3 px-4">Visitor IP</th>
                        <th class="py-3 px-4">Location (GEO)</th>
                        <th class="py-3 px-4">Page Path</th>
                        <th class="py-3 px-4">Browser & OS</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($visitorLogs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-slate-800">{{ $log->ip_address }}</span>
                                @if($log->is_unique_daily)
                                    <span class="ml-1.5 px-1.5 py-0.5 bg-sky-100 text-sky-700 rounded text-[9px] font-extrabold uppercase">Unique</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-slate-800">{{ $log->city ?: 'London' }}, {{ $log->country ?: 'UK' }}</div>
                                <span class="text-[10px] text-slate-400">{{ $log->region }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-mono text-sky-700 font-semibold" title="{{ $log->url }}">{{ $log->path }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-700 flex items-center space-x-1">
                                    @if(str_contains(strtolower($log->browser), 'chrome'))
                                        <i class="fa-brands fa-chrome text-amber-500"></i>
                                    @elseif(str_contains(strtolower($log->browser), 'safari'))
                                        <i class="fa-brands fa-safari text-sky-500"></i>
                                    @elseif(str_contains(strtolower($log->browser), 'firefox'))
                                        <i class="fa-brands fa-firefox text-orange-500"></i>
                                    @elseif(str_contains(strtolower($log->browser), 'edge'))
                                        <i class="fa-brands fa-edge text-blue-600"></i>
                                    @else
                                        <i class="fa-solid fa-globe text-slate-400"></i>
                                    @endif
                                    <span>{{ $log->browser }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400">{{ $log->os }} ({{ ucfirst($log->device) }})</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $log->device === 'mobile' ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                    <i class="fa-solid {{ $log->device === 'mobile' ? 'fa-mobile-screen' : 'fa-laptop' }} mr-1"></i>
                                    {{ $log->device }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-chart-line text-3xl mb-2 text-slate-300"></i>
                                <p>No visitor traffic recorded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visitorLogs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $visitorLogs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('visitorsChart').getContext('2d');
    const dates = {!! json_encode($trendDates) !!};
    const views = {!! json_encode($trendViews) !!};
    const unique = {!! json_encode($trendUnique) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'Total Hits / Page Views',
                    data: views,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointRadius: 3,
                },
                {
                    label: 'Unique Daily Visitors',
                    data: unique,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointRadius: 3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: 'Space Grotesk', weight: 'bold', size: 11 } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { family: 'Space Grotesk' } },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Space Grotesk', size: 11 } }
                }
            }
        }
    });
});
</script>
@endsection
