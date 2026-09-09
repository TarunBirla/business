@extends('layouts.dashboard')

@section('title', 'Manage Events - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Community Events ({{ $group->name }})</h1>
            <p class="text-sm text-slate-600 mt-1">Create and manage networking events, workshops, and attendee lists.</p>
        </div>
        <a href="{{ route('group_admin.events.create', $group->id) }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition">
            + Create New Event
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($events as $event)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $event->event_type === 'free' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                            {{ $event->event_type === 'free' ? 'Free Event' : 'Paid (£' . number_format($event->price, 2) . ')' }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">Status: {{ ucfirst($event->status) }}</span>
                    </div>

                    <h3 class="font-bold text-slate-900 text-lg mt-3">{{ $event->title }}</h3>
                    <p class="text-xs text-sky-600 font-semibold mt-1">📅 {{ $event->start_at->format('d M Y, h:i A') }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">📍 {{ $event->venue ?? 'Online' }}, {{ $event->city }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-4">
                    <span class="text-xs font-bold text-slate-700">🎟️ {{ $event->registrations_count }} Registered</span>
                    <a href="{{ route('group_admin.events.attendees', [$group->id, $event->id]) }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg transition">
                        View Attendees & CSV
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
