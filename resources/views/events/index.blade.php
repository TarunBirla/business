@extends('layouts.app')

@section('title', 'Community Events UK')

@section('content')
<div class="py-12 border-b border-sky-100 text-white" style="background-color: var(--btn-primary-bg, #0A4744);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-bold">Community Events & Networking</h1>
        <p class="text-sky-100 mt-2 text-sm sm:text-base">Attend networking meetups, workshops, cultural gatherings and seminars across the UK.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-xl transition group">
                <div>
                    <!-- Event Image / Banner -->
                    <div class="h-48 w-full overflow-hidden bg-slate-100 relative">
                        @if($event->event_image_url)
                            <img src="{{ $event->event_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @elseif($event->banner_image_url)
                            <img src="{{ $event->banner_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-teal-900 to-slate-900">
                                <i class="fa-regular fa-calendar-days text-5xl opacity-40"></i>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="inline-block px-3 py-1 bg-white/95 backdrop-blur-md text-slate-900 rounded-full text-xs font-bold shadow-md">
                                {{ $event->group->name }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $event->event_type === 'free' ? 'bg-emerald-500 text-white' : 'bg-sky-500 text-white' }} shadow-md">
                                {{ $event->event_type === 'free' ? 'Free' : '£' . number_format($event->price, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="font-bold text-xl text-slate-900 leading-snug group-hover:text-sky-600 transition">{{ $event->title }}</h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                            {{ $event->description }}
                        </p>
                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-1.5 text-xs text-slate-700">
                            <p class="flex items-center space-x-2"><i class="fa-regular fa-calendar-days text-sky-600 w-4"></i> <span class="font-semibold">{{ $event->start_at->format('d M Y, h:i A') }}</span></p>
                            <p class="flex items-center space-x-2"><i class="fa-solid fa-location-dot text-rose-500 w-4"></i> <span class="font-semibold">{{ $event->venue ?? 'Online Virtual Meeting' }}, {{ $event->city }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-900 text-sm">
                        {{ $event->event_type === 'free' ? 'Free Registration' : '£' . number_format($event->price, 2) }}
                    </span>
                    <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition">
                        View Details &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 font-semibold">No upcoming events found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
