@extends('layouts.dashboard')

@section('title', 'Group Admin Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-bold text-sky-600 uppercase">Group Admin Control Center</div>
            <h1 class="text-3xl font-bold text-slate-900 mt-1">{{ $activeGroup->name }}</h1>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('group_admin.members.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition">
                + Add Member
            </a>
            <a href="{{ route('group_admin.events.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow transition">
                + Create Event
            </a>
            <a href="{{ route('group_admin.notices.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition">
                + Send Notice
            </a>
            <a href="{{ route('group_admin.promotion.index', $activeGroup->id) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
                <i class="fa-solid fa-qrcode mr-1"></i> Promote & QR
            </a>
        </div>
    </div>

    <!-- Community Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Total Members</div>
            <div class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($memberCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">New (Last 30 Days)</div>
            <div class="text-3xl font-bold text-sky-600 mt-2">+{{ number_format($newMembersCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Events</div>
            <div class="text-3xl font-bold text-emerald-600 mt-2">{{ number_format($eventsCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Notices</div>
            <div class="text-3xl font-bold text-amber-600 mt-2">{{ number_format($noticesCount) }}</div>
        </div>
    </div>

    <!-- Recent Members & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-lg">Recent Registrations</h3>
                <a href="{{ route('group_admin.members.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($recentMembers as $m)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $m->name }}</div>
                            <div class="text-xs text-slate-500">{{ $m->email }} &bull; {{ $m->profession ?? 'Member' }}</div>
                        </div>
                        <span class="text-[10px] bg-sky-100 text-sky-800 font-bold px-2 py-1 rounded uppercase">
                            {{ $m->pivot->membership_role }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-lg">Upcoming Events</h3>
                <a href="{{ route('group_admin.events.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @foreach($upcomingEvents as $evt)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $evt->title }}</div>
                            <div class="text-xs text-sky-600 font-semibold mt-0.5"><i class="fa-regular fa-calendar-days text-sky-600 mr-1"></i>{{ $evt->start_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <span class="font-bold text-xs text-slate-900">
                            {{ $evt->event_type === 'free' ? 'Free' : '£' . number_format($evt->price, 2) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
