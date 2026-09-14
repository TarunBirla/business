@extends('layouts.dashboard')

@section('title', 'Group Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header & Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-bold text-sky-600 uppercase tracking-wider">Group Admin Control Center</div>
            <h1 class="text-3xl font-bold text-slate-900 mt-1">{{ $activeGroup->name }}</h1>
        </div>

        <!-- Quick Actions for Active Group -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('group_admin.communities.index') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-layer-group"></i>
                <span>Manage Communities</span>
            </a>
            <a href="{{ route('group_admin.settings', $activeGroup->id) }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-sliders"></i>
                <span>Edit Settings</span>
            </a>
            <a href="{{ route('group_admin.members.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-2xs transition">
                + Add Member
            </a>
            <a href="{{ route('group_admin.events.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-2xs transition">
                + Create Event
            </a>
        </div>
    </div>

    <!-- Active Community Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Total Members</div>
            <div class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($memberCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">New (Last 30 Days)</div>
            <div class="text-3xl font-bold text-sky-600 mt-2">+{{ number_format($newMembersCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Events</div>
            <div class="text-3xl font-bold text-emerald-600 mt-2">{{ number_format($eventsCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Notices</div>
            <div class="text-3xl font-bold text-amber-600 mt-2">{{ number_format($noticesCount) }}</div>
        </div>
    </div>

    <!-- Recent Members & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-lg">Recent Registrations</h3>
                <a href="{{ route('group_admin.members.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentMembers as $m)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $m->name }}</div>
                            <div class="text-xs text-slate-500">{{ $m->email }} &bull; {{ $m->profession ?? 'Member' }}</div>
                        </div>
                        <span class="text-[10px] bg-sky-100 text-sky-800 font-bold px-2 py-1 rounded-md uppercase">
                            {{ $m->pivot->membership_role }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">No recent member registrations.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-lg">Upcoming Events</h3>
                <a href="{{ route('group_admin.events.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingEvents as $evt)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $evt->title }}</div>
                            <div class="text-xs text-sky-600 font-semibold mt-0.5"><i class="fa-regular fa-calendar-days mr-1"></i>{{ $evt->start_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <span class="font-bold text-xs text-slate-900">
                            {{ $evt->event_type === 'free' ? 'Free' : '£' . number_format($evt->price, 2) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">No upcoming events scheduled.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
