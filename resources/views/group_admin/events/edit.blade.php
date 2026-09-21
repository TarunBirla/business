@extends('layouts.dashboard')

@section('title', 'Edit Event - ' . $group->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Community Event</h1>
            <p class="text-xs text-slate-500 mt-1">Update event details for <strong>{{ $event->title }}</strong> in {{ $group->name }}.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="px-4 py-2 bg-sky-50 hover:bg-sky-100 text-sky-700 font-bold text-xs rounded-xl border border-sky-200 transition">
                <i class="fa-solid fa-eye mr-1.5"></i> View Event Page
            </a>
            <a href="{{ route('group_admin.events.index', $group->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Back
            </a>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs">
        <form action="{{ route('group_admin.events.update', [$group->id, $event->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Banner Image Upload -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        <i class="fa-solid fa-image text-sky-600 mr-1"></i> Banner Image (Hero Background)
                    </label>
                    @if($event->banner_image_url)
                        <img src="{{ $event->banner_image_url }}" alt="Banner" class="w-full h-24 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="banner_img" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                </div>

                <!-- Event Card Image Upload -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        <i class="fa-solid fa-square-poll-horizontal text-sky-600 mr-1"></i> Event Card Image (Thumbnail)
                    </label>
                    @if($event->event_image_url)
                        <img src="{{ $event->event_image_url }}" alt="Card Image" class="w-full h-24 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="event_img" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Event Title *</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Short Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('description', $event->description) }}</textarea>
            </div>

            <!-- Overview Section -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    <i class="fa-solid fa-align-left text-sky-600 mr-1"></i> Event Overview (Detailed Description)
                </label>
                <textarea name="overview" rows="5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('overview', $event->overview) }}</textarea>
            </div>

            <!-- Agenda Section -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    <i class="fa-solid fa-list-check text-sky-600 mr-1"></i> Event Agenda & Schedule
                </label>
                <textarea name="agenda" rows="5" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-mono text-xs">{{ old('agenda', $event->agenda) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Event Type *</label>
                    <select name="event_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-bold">
                        <option value="free" {{ $event->event_type === 'free' ? 'selected' : '' }}>Free Event</option>
                        <option value="paid" {{ $event->event_type === 'paid' ? 'selected' : '' }}>Paid Event (£ GBP)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Price (£ GBP)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $event->price) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Start Date & Time *</label>
                    <input type="datetime-local" name="start_at" value="{{ $event->start_at ? \Carbon\Carbon::parse($event->start_at)->format('Y-m-d\TH:i') : '' }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Capacity *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Venue Name / Location</label>
                    <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $event->city) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Online Meeting URL (Visible to approved attendees)</label>
                <input type="url" name="meeting_url" value="{{ old('meeting_url', $event->meeting_url) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-md">
                Update Event Details
            </button>
        </form>
    </div>
</div>
@endsection
