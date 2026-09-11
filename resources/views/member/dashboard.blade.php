@extends('layouts.dashboard')

@section('title', 'Member Dashboard')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Welcome Banner -->
    <div class="rounded-2xl md:rounded-3xl text-white p-6 sm:p-8 shadow-md" style="background-color: var(--btn-primary-bg, #0284c7);">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Welcome back, {{ $user->first_name }}! <i class="fa-solid fa-hand-sparkles text-amber-300 ml-1"></i></h1>
                <p class="text-sky-100 text-xs sm:text-sm mt-1 leading-relaxed">Here is what is happening across your community networks today.</p>
            </div>

            <!-- Profile Completion Widget -->
            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 text-center min-w-[180px] shrink-0">
                <div class="text-[11px] uppercase font-extrabold text-sky-200 mb-1">Profile Completion</div>
                <div class="text-2xl font-black text-white mb-2">{{ $profileCompletion }}%</div>
                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                    <div class="bg-sky-300 h-full rounded-full transition-all duration-500" style="width: {{ $profileCompletion }}%;"></div>
                </div>
                @if($profileCompletion < 100)
                    <a href="{{ route('member.profile.edit') }}" class="text-xs text-white font-bold underline mt-2.5 block hover:text-sky-200">Complete Profile &rarr;</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid (2 Columns on Mobile, 4 on Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Communities</div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ $myGroups->count() }}</div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Connections</div>
            <div class="text-2xl sm:text-3xl font-black text-sky-600 mt-1">{{ $myConnectionsCount }}</div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Pending</div>
            <div class="text-2xl sm:text-3xl font-black text-amber-500 mt-1">{{ $pendingConnectionsCount }}</div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Events</div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">{{ $upcomingEvents->count() }}</div>
        </div>
    </div>

    <!-- Dashboard Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Left 2 Cols: My Communities & Notices -->
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
            
            <!-- My Communities -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">My Joined Communities</h3>
                    <a href="{{ route('groups.index') }}" class="text-xs font-bold text-sky-600 hover:underline">Explore &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($myGroups as $group)
                        <div class="p-3.5 sm:p-4 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-sky-600 text-white font-black flex items-center justify-center text-sm sm:text-lg uppercase shrink-0">
                                    {{ substr($group->name, 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-sm sm:text-base truncate">{{ $group->name }}</h4>
                                    <p class="text-[11px] text-slate-500">Joined {{ $group->pivot->joined_at ? \Carbon\Carbon::parse($group->pivot->joined_at)->diffForHumans() : 'Recently' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('groups.show', $group->slug) }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-900 font-bold text-xs rounded-xl transition text-center shrink-0">
                                View Page
                            </a>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-slate-500">You have not joined any community yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Latest Group Announcements / Notices -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-5">Latest Community Announcements</h3>
                <div class="space-y-3">
                    @forelse($latestNotices as $notice)
                        <div class="p-4 rounded-xl border border-sky-100 bg-sky-50/40 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-sky-700 uppercase">{{ $notice->group->name }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $notice->priority === 'urgent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800' }}">
                                    {{ $notice->priority }}
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-snug">{{ $notice->title }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $notice->content }}</p>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-slate-500">No active notices.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Sidebar: Upcoming Events -->
        <div class="space-y-6 sm:space-y-8">
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-5">My Registered Events</h3>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $reg)
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $reg->event->title }}</h4>
                            <p class="text-[11px] text-sky-600 font-semibold"><i class="fa-regular fa-calendar-days text-sky-600 mr-1"></i>{{ $reg->event->start_at->format('d M Y, h:i A') }}</p>
                            <p class="text-[11px] text-slate-500"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $reg->event->venue ?? 'Online' }}</p>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-slate-500">No event registrations found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
