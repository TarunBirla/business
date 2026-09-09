@extends('layouts.dashboard')

@section('title', 'Create Event - ' . $group->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Create Community Event</h1>
        <p class="text-sm text-black mt-1">Organize a free or paid event for {{ $group->name }}.</p>
    </div>

    <form method="POST" action="{{ route('group_admin.events.store', $group->id) }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Event Title *</label>
            <input type="text" name="title" required placeholder="e.g. Gujarati Community Networking Night" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Details about who should attend and agenda..."></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Event Type *</label>
                <select name="event_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="free">Free Event</option>
                    <option value="paid">Paid Event (£ GBP)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ticket Price (£ GBP)</label>
                <input type="number" step="0.01" name="price" placeholder="0.00" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Start Date & Time *</label>
                <input type="datetime-local" name="start_at" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Maximum Capacity *</label>
                <input type="number" name="capacity" value="100" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Venue Name</label>
                <input type="text" name="venue" placeholder="e.g. Hilton London Metropole or Online" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City</label>
                <input type="text" name="city" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition">
            Publish Event
        </button>
    </form>
</div>
@endsection
