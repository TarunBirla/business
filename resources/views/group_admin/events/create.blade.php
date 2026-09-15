@extends('layouts.dashboard')

@section('title', 'Create Event - ' . $group->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-3xl font-bold text-black">Create Community Event</h1>
            <p class="text-sm text-slate-600 mt-1">Organize a free or paid event for {{ $group->name }}.</p>
        </div>
        <a href="{{ route('group_admin.events.index', $group->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('group_admin.events.store', $group->id) }}" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Banner Image Upload -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase">
                    <i class="fa-solid fa-image text-sky-600 mr-1"></i> Banner Image (Hero Background)
                </label>
                <input type="file" name="banner_img" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                <p class="text-[11px] text-slate-400">Recommended size: 1200x400px (JPG, PNG)</p>
            </div>

            <!-- Event Card Image Upload -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase">
                    <i class="fa-solid fa-square-poll-horizontal text-sky-600 mr-1"></i> Event Card Image (Thumbnail)
                </label>
                <input type="file" name="event_img" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                <p class="text-[11px] text-slate-400">Recommended size: 600x400px (JPG, PNG)</p>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Event Title *</label>
            <input type="text" name="title" required placeholder="e.g. London Business Networking Breakfast 2026" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Short Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Brief 2-3 sentence introduction to the event..."></textarea>
        </div>

        <!-- Overview Section -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                <i class="fa-solid fa-align-left text-sky-600 mr-1"></i> Event Overview (Detailed Description)
            </label>
            <textarea name="overview" rows="5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Full overview about what attendees can expect, key highlights, target audience, and guidelines..."></textarea>
        </div>

        <!-- Agenda Section -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                <i class="fa-solid fa-list-check text-sky-600 mr-1"></i> Event Agenda & Schedule
            </label>
            <textarea name="agenda" rows="5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-mono text-xs" placeholder="e.g.&#10;09:00 AM - Registration & Morning Coffee&#10;09:30 AM - Keynote Presentation&#10;10:30 AM - Open Networking Session&#10;11:30 AM - Closing Remarks"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Event Type *</label>
                <select name="event_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-bold">
                    <option value="free">Free Event</option>
                    <option value="paid">Paid Event (£ GBP)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ticket Price (£ GBP)</label>
                <input type="number" step="0.01" name="price" placeholder="0.00" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-bold">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Start Date & Time *</label>
                <input type="datetime-local" name="start_at" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Maximum Capacity *</label>
                <input type="number" name="capacity" value="100" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Venue Name / Location</label>
                <input type="text" name="venue" placeholder="e.g. Hilton London Metropole or Online Meeting" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City</label>
                <input type="text" name="city" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Online Meeting URL (Visible to approved attendees)</label>
            <input type="url" name="meeting_url" placeholder="https://zoom.us/j/123456789 or https://meet.google.com/xyz" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition text-base">
            🚀 Publish Community Event
        </button>
    </form>
</div>
@endsection
