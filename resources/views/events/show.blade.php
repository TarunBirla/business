@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="bg-gradient-to-r from-sky-800 to-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-block px-3 py-1 bg-sky-500/30 text-sky-200 rounded-full text-xs font-bold uppercase mb-3">
            {{ $event->group->name }}
        </div>
        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-white mb-4">{{ $event->title }}</h1>
        <div class="flex flex-wrap gap-6 text-sm text-sky-100">
            <span>📅 {{ $event->start_at->format('l, F j, Y \a\t h:i A') }}</span>
            <span>📍 {{ $event->venue ?? 'Online Meeting' }}, {{ $event->city }}</span>
            <span>🎟️ Capacity: {{ $event->capacity }} Attendees</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Event Description</h2>
                <div class="text-slate-700 text-base leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Venue & Location</h3>
                <p class="text-slate-700 font-semibold">{{ $event->venue ?? 'Online Virtual Meeting' }}</p>
                @if($event->address)
                    <p class="text-sm text-slate-600 mt-1">{{ $event->address }}, {{ $event->city }}, {{ $event->country }}</p>
                @endif
                @if($event->meeting_url && auth()->check() && $isRegistered)
                    <div class="mt-4 p-4 bg-sky-50 border border-sky-200 rounded-xl">
                        <p class="text-xs font-bold text-sky-800 uppercase">Online Meeting Join Link:</p>
                        <a href="{{ $event->meeting_url }}" target="_blank" class="text-sm font-bold text-sky-600 underline hover:text-sky-700">{{ $event->meeting_url }}</a>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xl text-center sticky top-28">
                <div class="text-3xl font-bold text-slate-900 mb-2">
                    {{ $event->event_type === 'free' ? 'Free Event' : '£' . number_format($event->price, 2) }}
                </div>
                <p class="text-xs text-slate-500 mb-6 font-medium">{{ $event->available_seats }} seats remaining</p>

                @auth
                    @if($isRegistered)
                        <div class="w-full py-4 bg-emerald-100 text-emerald-800 font-bold rounded-xl text-sm">
                            ✓ You Are Registered for This Event
                        </div>
                    @else
                        <form action="{{ route('member.events.register', $event->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-base shadow-lg transition">
                                {{ $event->event_type === 'free' ? 'Confirm Free Registration' : 'Pay & Register (£' . number_format($event->price, 2) . ')' }}
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="block w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-base shadow-lg transition">
                        Register or Login to Attend
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
