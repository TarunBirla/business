@extends('layouts.dashboard')

@section('title', 'Member Dashboard')

@section('content')
<div class="space-y-5 sm:space-y-6 md:space-y-8">
    <!-- Welcome Banner (Responsive for all mobile screens, desktop untouched) -->
    <div class="rounded-2xl md:rounded-3xl text-white p-4 sm:p-6 md:p-8 shadow-md" style="background-color: var(--btn-primary-bg, #0A4744);">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight">
                    Welcome back, {{ $user->first_name }}! <i class="fa-solid fa-hand-sparkles text-amber-300 ml-1"></i>
                </h1>
                <p class="text-sky-100/90 text-xs sm:text-sm mt-1 leading-relaxed">Here is what is happening across your community networks today.</p>
            </div>

            <!-- Profile Completion Widget (App-like compact widget on mobile) -->
            <div class="bg-white/10 backdrop-blur-md p-3.5 sm:p-4 rounded-2xl border border-white/20 text-center min-w-0 sm:min-w-[190px] shrink-0">
                <div class="flex items-center justify-between md:justify-center md:flex-col gap-2">
                    <div class="text-[11px] uppercase font-extrabold text-sky-200 tracking-wider">Profile Completion</div>
                    <div class="text-xl sm:text-2xl font-black text-white">{{ $profileCompletion }}%</div>
                </div>
                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden mt-2">
                    <div class="bg-sky-300 h-full rounded-full transition-all duration-500" style="width: {{ $profileCompletion }}%;"></div>
                </div>
                @if($profileCompletion < 100)
                    <a href="{{ route('member.profile.edit') }}" class="text-xs text-white font-bold underline mt-2 block hover:text-sky-200">Complete Profile &rarr;</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile-Only Quick App Actions Bar (Native App Shortcuts: Hidden on Desktop) -->
    <div class="grid grid-cols-4 gap-2 sm:gap-3 md:hidden">
        <a href="{{ route('member.directory') }}" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs flex flex-col items-center text-center space-y-1.5 transition active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base" style="background-color: var(--badge-info-bg, #f0f7f6); color: var(--text-link, #0A4744);">
                <i class="fa-solid fa-users"></i>
            </div>
            <span class="text-[11px] font-bold text-slate-800 tracking-tight">Members</span>
        </a>

        <a href="{{ route('member.chat') }}" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs flex flex-col items-center text-center space-y-1.5 transition active:scale-95 relative">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-base" style="background-color: var(--badge-info-bg, #f0f7f6); color: var(--text-link, #0A4744);">
                <i class="fa-solid fa-comments"></i>
            </div>
            <span class="text-[11px] font-bold text-slate-800 tracking-tight">Chat</span>
            @if(isset($sidebarUnreadCount) && $sidebarUnreadCount > 0)
                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
            @endif
        </a>

        <a href="{{ route('bizcard.show', auth()->id()) }}" target="_blank" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs flex flex-col items-center text-center space-y-1.5 transition active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <span class="text-[11px] font-bold text-slate-800 tracking-tight">My Card</span>
        </a>

        <a href="{{ route('events.index') }}" class="bg-white p-3 rounded-2xl border border-slate-200 shadow-2xs flex flex-col items-center text-center space-y-1.5 transition active:scale-95">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <span class="text-[11px] font-bold text-slate-800 tracking-tight">Events</span>
        </a>
    </div>

    <!-- Stats Grid (2 Columns on Mobile, 4 on Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <div class="bg-white p-3.5 sm:p-5 md:p-6 rounded-2xl border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Communities</div>
                <div class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 mt-1">{{ $myGroups->count() }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-700 bg-slate-100 shrink-0">
                <i class="fa-solid fa-layer-group text-base"></i>
            </div>
        </div>

        <div class="bg-white p-3.5 sm:p-5 md:p-6 rounded-2xl border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Connections</div>
                <div class="text-xl sm:text-2xl md:text-3xl font-black text-sky-600 mt-1" style="color: var(--text-link, #0A4744);">{{ $myConnectionsCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-sky-50 text-sky-700 shrink-0" style="background-color: var(--badge-info-bg, #f0f7f6); color: var(--text-link, #0A4744);">
                <i class="fa-solid fa-user-group text-base"></i>
            </div>
        </div>

        <div class="bg-white p-3.5 sm:p-5 md:p-6 rounded-2xl border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Pending</div>
                <div class="text-xl sm:text-2xl md:text-3xl font-black text-amber-500 mt-1">{{ $pendingConnectionsCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600 shrink-0">
                <i class="fa-solid fa-user-clock text-base"></i>
            </div>
        </div>

        <div class="bg-white p-3.5 sm:p-5 md:p-6 rounded-2xl border border-slate-200 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Events</div>
                <div class="text-xl sm:text-2xl md:text-3xl font-black text-emerald-600 mt-1">{{ $upcomingEvents->count() }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600 shrink-0">
                <i class="fa-solid fa-calendar-check text-base"></i>
            </div>
        </div>
    </div>

    <!-- Dashboard Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 md:gap-8">
        <!-- Left 2 Cols: My Communities & Notices -->
        <div class="lg:col-span-2 space-y-5 sm:space-y-6 md:space-y-8">
            
            <!-- My Communities -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <div class="flex items-center justify-between mb-4 sm:mb-5">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-users text-sky-600" style="color: var(--text-link, #0A4744);"></i>
                        <span>My Joined Communities</span>
                    </h3>
                    <a href="{{ route('groups.index') }}" class="text-xs font-bold text-sky-600 hover:underline" style="color: var(--text-link, #0A4744);">Explore &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($myGroups as $group)
                        <div class="p-3 sm:p-4 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl text-white font-black flex items-center justify-center text-sm sm:text-lg uppercase shrink-0 shadow-2xs" style="background-color: var(--btn-primary-bg, #0A4744);">
                                    {{ substr($group->name, 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-sm sm:text-base truncate">{{ $group->name }}</h4>
                                    <p class="text-[11px] text-slate-500">Joined {{ $group->pivot->joined_at ? \Carbon\Carbon::parse($group->pivot->joined_at)->diffForHumans() : 'Recently' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('groups.show', $group->slug) }}" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-900 font-bold text-xs rounded-xl transition text-center shrink-0 active:scale-95 shadow-2xs">
                                View Page
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fa-solid fa-users-slash text-2xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs sm:text-sm text-slate-500">You have not joined any community yet.</p>
                            <a href="{{ route('groups.index') }}" class="inline-block mt-2 text-xs font-bold text-sky-600 hover:underline" style="color: var(--text-link, #0A4744);">Join a Community &rarr;</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Latest Group Announcements / Notices -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 mb-4 sm:mb-5 flex items-center space-x-2">
                    <i class="fa-solid fa-bullhorn text-amber-500"></i>
                    <span>Latest Community Announcements</span>
                </h3>
                <div class="space-y-3">
                    @forelse($latestNotices as $notice)
                        <div class="p-3.5 sm:p-4 rounded-xl border border-sky-100 bg-sky-50/40 space-y-1.5" style="border-color: var(--border-color, #e2e8f0); background-color: var(--badge-info-bg, #f0f7f6);">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold uppercase truncate pr-2" style="color: var(--text-link, #0A4744);">{{ $notice->group->name }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase shrink-0 {{ $notice->priority === 'urgent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800' }}">
                                    {{ $notice->priority }}
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-snug">{{ $notice->title }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $notice->content }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fa-regular fa-bell-slash text-2xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs sm:text-sm text-slate-500">No active notices.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Sidebar: Upcoming Events -->
        <div class="space-y-5 sm:space-y-6 md:space-y-8">
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <div class="flex items-center justify-between mb-4 sm:mb-5">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 flex items-center space-x-2">
                        <i class="fa-regular fa-calendar-days text-emerald-600"></i>
                        <span>Registered Events</span>
                    </h3>
                    <a href="{{ route('events.index') }}" class="text-xs font-bold text-sky-600 hover:underline" style="color: var(--text-link, #0A4744);">All Events &rarr;</a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingEvents as $reg)
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 space-y-1">
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ $reg->event->title }}</h4>
                            <p class="text-[11px] font-semibold" style="color: var(--text-link, #0A4744);"><i class="fa-regular fa-calendar-days mr-1"></i>{{ $reg->event->start_at->format('d M Y, h:i A') }}</p>
                            <p class="text-[11px] text-slate-500"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $reg->event->venue ?? 'Online' }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <i class="fa-regular fa-calendar-xmark text-2xl text-slate-300 mb-2 block"></i>
                            <p class="text-xs sm:text-sm text-slate-500">No event registrations found.</p>
                            <a href="{{ route('events.index') }}" class="inline-block mt-2 text-xs font-bold text-sky-600 hover:underline" style="color: var(--text-link, #0A4744);">Browse Upcoming Events &rarr;</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
