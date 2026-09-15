@extends('layouts.app')

@section('title', $event->title)

@section('content')
<!-- Hero Header Section with Banner Image -->
<div class="relative text-white py-16 md:py-24 shadow-md bg-slate-900 overflow-hidden">
    @if($event->banner_image_url)
        <img src="{{ $event->banner_image_url }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover opacity-35 filter brightness-90">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/80 to-teal-950/60"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-r from-teal-900 to-slate-900"></div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center space-x-2 px-3.5 py-1 bg-white/20 backdrop-blur-md text-white rounded-full text-xs font-bold uppercase tracking-wide mb-4 border border-white/20">
            <i class="fa-solid fa-users text-sky-300"></i>
            <span>{{ $event->group->name }}</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white mb-6 leading-tight max-w-4xl">{{ $event->title }}</h1>
        <div class="flex flex-wrap items-center gap-6 text-sm text-sky-100 font-medium">
            <span class="flex items-center"><i class="fa-regular fa-calendar-days text-sky-300 mr-2 text-base"></i>{{ $event->start_at->format('l, F j, Y \a\t h:i A') }}</span>
            <span class="flex items-center"><i class="fa-solid fa-location-dot text-rose-300 mr-2 text-base"></i>{{ $event->venue ?? 'Online Virtual Meeting' }}, {{ $event->city }}</span>
            <span class="flex items-center"><i class="fa-solid fa-ticket text-sky-300 mr-2 text-base"></i>Capacity: {{ $event->capacity }} Attendees</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Event Content (Left 2 Columns) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Featured Event Image (if uploaded) -->
            @if($event->event_image_url)
                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100 max-h-[420px]">
                    <img src="{{ $event->event_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Event Description Card -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-circle-info text-sky-600 mr-2.5"></i> Event Summary
                </h2>
                <div class="text-slate-700 text-base leading-relaxed whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            <!-- Event Overview Section -->
            @if($event->overview)
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center">
                        <i class="fa-solid fa-align-left text-sky-600 mr-2.5"></i> Event Overview
                    </h2>
                    <div class="text-slate-700 text-base leading-relaxed whitespace-pre-line">
                        {{ $event->overview }}
                    </div>
                </div>
            @endif

            <!-- Event Agenda & Schedule Section -->
            @if($event->agenda)
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center">
                        <i class="fa-solid fa-list-check text-sky-600 mr-2.5"></i> Agenda & Schedule
                    </h2>
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-slate-800 text-sm leading-relaxed whitespace-pre-line font-mono">
                        {{ $event->agenda }}
                    </div>
                </div>
            @endif

            <!-- Venue & Location Details Card -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h3 class="text-xl font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-map-location-dot text-rose-500 mr-2.5"></i> Venue & Location
                </h3>
                <p class="text-slate-800 font-bold text-base">{{ $event->venue ?? 'Online Virtual Meeting' }}</p>
                @if($event->address)
                    <p class="text-sm text-slate-600">{{ $event->address }}, {{ $event->city }}, {{ $event->country }}</p>
                @endif

                @if($event->meeting_url && auth()->check() && $userRegistration && in_array($userRegistration->registration_status, ['confirmed', 'approved']))
                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1">
                        <p class="text-xs font-bold text-emerald-800 uppercase flex items-center">
                            <i class="fa-solid fa-video mr-1.5"></i> Online Meeting Join Link:
                        </p>
                        <a href="{{ $event->meeting_url }}" target="_blank" class="text-sm font-bold text-emerald-700 underline hover:text-emerald-800 break-all">{{ $event->meeting_url }}</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Registration Card (Right Column) -->
        <div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xl text-center sticky top-28 space-y-6">
                <div>
                    <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Ticket Price</span>
                    <div class="text-4xl font-black text-slate-900 mt-1">
                        {{ $event->event_type === 'free' ? 'Free Event' : '£' . number_format($event->price, 2) }}
                    </div>
                    <p class="text-xs text-slate-500 mt-2 font-semibold">
                        <i class="fa-solid fa-chair text-sky-600 mr-1"></i> {{ $event->available_seats }} seats remaining
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    @auth
                        @if($userRegistration)
                            @if($userRegistration->registration_status === 'pending')
                                <div class="w-full py-4 bg-amber-50 text-amber-900 font-bold rounded-xl text-sm border border-amber-300 flex items-center justify-center space-x-2">
                                    <i class="fa-regular fa-clock text-amber-600"></i>
                                    <span>Registration Pending Approval</span>
                                </div>
                            @elseif($userRegistration->registration_status === 'rejected')
                                <div class="w-full py-4 bg-rose-50 text-rose-800 font-bold rounded-xl text-sm border border-rose-300 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-xmark text-rose-600"></i>
                                    <span>Registration Request Declined</span>
                                </div>
                            @else
                                <div class="w-full py-4 bg-emerald-50 text-emerald-800 font-bold rounded-xl text-sm border border-emerald-200 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-check text-emerald-600"></i>
                                    <span>You Are Registered for This Event</span>
                                </div>
                            @endif
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

                <div class="pt-4 border-t border-slate-100 text-left space-y-2">
                    <span class="text-xs font-bold uppercase text-slate-400">Organized By</span>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-600 text-white font-black flex items-center justify-center text-sm uppercase">
                            {{ substr($event->group->name, 0, 2) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $event->group->name }}</h4>
                            <a href="{{ route('groups.show', $event->group->slug) }}" class="text-xs text-sky-600 font-semibold hover:underline">View Community &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
