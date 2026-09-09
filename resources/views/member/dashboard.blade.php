@extends('layouts.dashboard')

@section('title', 'Member Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-sky-700 to-sky-900 rounded-2xl text-white p-8 mb-8 shadow-md">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold">Welcome back, {{ $user->first_name }}! 👋</h1>
            <p class="text-sky-100 text-sm mt-1">Here is what is happening across your community networks today.</p>
        </div>

        <!-- Profile Completion Widget -->
        <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 text-center min-w-[200px]">
            <div class="text-xs uppercase font-bold text-sky-200 mb-1">Profile Completion</div>
            <div class="text-2xl font-bold text-white mb-2">{{ $profileCompletion }}%</div>
            <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                <div class="bg-sky-300 h-full rounded-full" style="width: {{ $profileCompletion }}%;"></div>
            </div>
            @if($profileCompletion < 100)
                <a href="{{ route('member.profile.edit') }}" class="text-xs text-white font-bold underline mt-2 block hover:text-sky-200">Complete Profile &rarr;</a>
            @endif
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="text-xs font-bold text-slate-500 uppercase">My Communities</div>
        <div class="text-3xl font-bold text-slate-900 mt-2">{{ $myGroups->count() }}</div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="text-xs font-bold text-slate-500 uppercase">My Connections</div>
        <div class="text-3xl font-bold text-sky-600 mt-2">{{ $myConnectionsCount }}</div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="text-xs font-bold text-slate-500 uppercase">Pending Requests</div>
        <div class="text-3xl font-bold text-amber-500 mt-2">{{ $pendingConnectionsCount }}</div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="text-xs font-bold text-slate-500 uppercase">Upcoming Events</div>
        <div class="text-3xl font-bold text-emerald-600 mt-2">{{ $upcomingEvents->count() }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left 2 Cols: My Communities & Notices -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- My Communities -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">My Joined Communities</h3>
                <a href="{{ route('groups.index') }}" class="text-xs font-bold text-sky-600 hover:underline">Explore More &rarr;</a>
            </div>

            <div class="space-y-4">
                @forelse($myGroups as $group)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-lg bg-sky-600 text-white font-bold flex items-center justify-center text-lg uppercase">
                                {{ substr($group->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-base">{{ $group->name }}</h4>
                                <p class="text-xs text-slate-500">Joined {{ $group->pivot->joined_at ? \Carbon\Carbon::parse($group->pivot->joined_at)->diffForHumans() : 'Recently' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('groups.show', $group->slug) }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-800 font-bold text-xs rounded-lg transition">
                            View Page
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">You have not joined any community yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Latest Group Announcements / Notices -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Latest Community Announcements</h3>
            <div class="space-y-4">
                @forelse($latestNotices as $notice)
                    <div class="p-4 rounded-xl border border-sky-100 bg-sky-50/40">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-700 uppercase">{{ $notice->group->name }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $notice->priority === 'urgent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800' }}">
                                {{ $notice->priority }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-900 mt-2 text-base">{{ $notice->title }}</h4>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $notice->content }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No active notices.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Right Sidebar: Upcoming Events -->
    <div class="space-y-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 mb-6">My Registered Events</h3>
            <div class="space-y-4">
                @forelse($upcomingEvents as $reg)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                        <h4 class="font-bold text-slate-900 text-sm">{{ $reg->event->title }}</h4>
                        <p class="text-xs text-sky-600 font-semibold mt-1">📅 {{ $reg->event->start_at->format('d M Y, h:i A') }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">📍 {{ $reg->event->venue ?? 'Online' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No event registrations found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
