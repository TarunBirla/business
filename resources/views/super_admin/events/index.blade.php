@extends('layouts.dashboard')

@section('title', 'Super Admin - Events Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Events Management</h1>
            <p class="text-sm text-slate-600 mt-1">Manage events across all communities.</p>
        </div>
        <a href="{{ route('super_admin.events.create') }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition flex items-center space-x-2 shrink-0">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Create Event</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('super_admin.events.index') }}" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search event title or city..." class="px-4 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500 w-full sm:w-64">
            <select name="group_id" class="px-4 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500 w-full sm:w-64">
                <option value="">All Communities</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase">
                    <th class="p-4">Event Title</th>
                    <th class="p-4">Community</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Date & Time</th>
                    <th class="p-4">Registrations</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($events as $event)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-900">{{ $event->title }}</td>
                        <td class="p-4 font-bold text-sky-600">{{ $event->group ? $event->group->name : 'Global' }}</td>
                        <td class="p-4 uppercase text-xs font-bold text-slate-500">{{ $event->event_type }}</td>
                        <td class="p-4 text-slate-600 text-xs">{{ \Carbon\Carbon::parse($event->start_at)->format('M d, Y H:i') }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $event->registrations_count }}/{{ $event->capacity }}</td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('super_admin.events.edit', $event->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                                Edit
                            </a>
                            <form action="{{ route('super_admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold text-xs rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
