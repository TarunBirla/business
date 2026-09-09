@extends('layouts.app')

@section('title', 'Community Events UK')

@section('content')
<div class="bg-sky-50/50 py-12 border-b border-sky-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-black">Community Events & Networking</h1>
        <p class="text-black mt-2">Attend networking meetups, workshops, cultural gatherings and seminars across the UK.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-lg transition">
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-sky-100 text-sky-800 rounded-full text-xs font-bold mb-3">
                        {{ $event->group->name }}
                    </span>
                    <h3 class="font-bold text-xl text-black leading-snug">{{ $event->title }}</h3>
                    <p class="text-xs text-black mt-3 line-clamp-2 leading-relaxed">
                        {{ $event->description }}
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-1 text-xs text-black">
                        <p class="flex items-center space-x-1.5"><span><i class="fa-regular fa-calendar-days text-sky-600"></i></span> <span>{{ $event->start_at->format('d M Y, h:i A') }}</span></p>
                        <p class="flex items-center space-x-1.5"><span><i class="fa-solid fa-location-dot text-rose-500"></i></span> <span>{{ $event->venue ?? 'Online' }}, {{ $event->city }}</span></p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-black text-sm">
                        {{ $event->event_type === 'free' ? 'Free Registration' : '£' . number_format($event->price, 2) }}
                    </span>
                    <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition">
                        View Event
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200">
                <p class="text-black font-semibold">No upcoming events found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
