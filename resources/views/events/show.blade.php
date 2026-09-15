@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Top Container: Pure Clean Banner Image (No Text Overlay, 100% Crisp Opacity) -->
    @if($event->banner_image_url)
        <div class="w-full bg-slate-900 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="w-full h-[260px] sm:h-[380px] md:h-[460px] rounded-2xl md:rounded-3xl overflow-hidden shadow-lg bg-slate-950">
                    <img src="{{ $event->banner_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    @endif

    <!-- Event Title & Info Header (Below Banner Image) -->
    <div class="bg-white border-b border-slate-200 py-8 md:py-10 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3 max-w-4xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 bg-teal-50 text-teal-800 rounded-full text-xs font-bold border border-teal-200">
                            <i class="fa-solid fa-users text-teal-600 text-[10px]"></i>
                            <span>{{ $event->group->name }}</span>
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $event->event_type === 'free' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-sky-100 text-sky-800 border border-sky-300' }}">
                            {{ $event->event_type === 'free' ? 'Free Event' : 'Paid Event (£' . number_format($event->price, 2) . ')' }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $event->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-xs sm:text-sm text-slate-600 font-semibold pt-1">
                        <div class="flex items-center space-x-2">
                            <i class="fa-regular fa-calendar-days text-sky-600 text-base"></i>
                            <span>{{ $event->start_at->format('l, F j, Y \a\t h:i A') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-location-dot text-rose-500 text-base"></i>
                            <span>{{ $event->venue ?? 'Online Virtual Meeting' }}, {{ $event->city }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-ticket text-teal-600 text-base"></i>
                            <span>{{ $event->capacity }} Total Seats</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left 2 Columns: Main Details -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Featured Event Card Image (If provided) -->
                @if($event->event_image_url && $event->event_image_url !== $event->banner_image_url)
                    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <img src="{{ $event->event_image_url }}" alt="{{ $event->title }}" class="w-full h-auto max-h-[400px] object-cover rounded-xl">
                    </div>
                @endif

                <!-- Short Summary / Introduction -->
                @if($event->description)
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-align-left text-sky-600 mr-2.5"></i> Event Summary
                        </h2>
                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line font-normal">
                            {{ $event->description }}
                        </div>
                    </div>
                @endif

                <!-- Detailed Event Overview -->
                @if($event->overview)
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-circle-info text-teal-600 mr-2.5"></i> Event Overview
                        </h2>
                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                            {{ $event->overview }}
                        </div>
                    </div>
                @endif

                <!-- Event Agenda & Schedule -->
                @if($event->agenda)
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-clock-rotate-left text-sky-600 mr-2.5"></i> Agenda & Schedule
                        </h2>
                        <div class="bg-slate-50/80 p-5 sm:p-6 rounded-xl border border-slate-200 text-slate-800 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-mono">
                            {{ $event->agenda }}
                        </div>
                    </div>
                @endif

                <!-- Venue & Location Section -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-map-location-dot text-rose-500 mr-2.5"></i> Venue & Location Details
                    </h3>
                    <div>
                        <p class="text-slate-900 font-bold text-base">{{ $event->venue ?? 'Online Virtual Meeting' }}</p>
                        @if($event->address)
                            <p class="text-xs sm:text-sm text-slate-600 mt-1"><i class="fa-solid fa-building text-slate-400 mr-1.5"></i>{{ $event->address }}, {{ $event->city }}, {{ $event->country }}</p>
                        @endif
                    </div>

                    @if($event->meeting_url && auth()->check() && $userRegistration && in_array($userRegistration->registration_status, ['confirmed', 'approved']))
                        <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1">
                            <p class="text-xs font-bold text-emerald-800 uppercase flex items-center">
                                <i class="fa-solid fa-video mr-1.5"></i> Online Meeting Join Link:
                            </p>
                            <a href="{{ $event->meeting_url }}" target="_blank" class="text-xs sm:text-sm font-bold text-emerald-700 underline hover:text-emerald-800 break-all">{{ $event->meeting_url }}</a>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Column: Registration Box & Host Details -->
            <div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-lg text-center sticky top-28 space-y-6">
                    <div>
                        <span class="text-xs font-extrabold uppercase text-slate-400 tracking-wider">Ticket Price</span>
                        <div class="text-3xl sm:text-4xl font-black text-slate-900 mt-1">
                            {{ $event->event_type === 'free' ? 'Free Event' : '£' . number_format($event->price, 2) }}
                        </div>
                        <p class="text-xs text-slate-500 mt-2 font-bold">
                            <i class="fa-solid fa-chair text-teal-600 mr-1"></i> {{ $event->available_seats }} seats remaining
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        @auth
                            @if($userRegistration)
                                @if($userRegistration->registration_status === 'pending')
                                    <div class="w-full py-3.5 bg-amber-50 text-amber-900 font-bold rounded-xl text-xs sm:text-sm border border-amber-300 flex items-center justify-center space-x-2">
                                        <i class="fa-regular fa-clock text-amber-600"></i>
                                        <span>Registration Pending Approval</span>
                                    </div>
                                @elseif($userRegistration->registration_status === 'rejected')
                                    <div class="w-full py-3.5 bg-rose-50 text-rose-800 font-bold rounded-xl text-xs sm:text-sm border border-rose-300 flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-xmark text-rose-600"></i>
                                        <span>Registration Request Declined</span>
                                    </div>
                                @else
                                    <div class="w-full py-3.5 bg-emerald-50 text-emerald-800 font-bold rounded-xl text-xs sm:text-sm border border-emerald-300 flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-check text-emerald-600"></i>
                                        <span>You Are Registered for This Event</span>
                                    </div>
                                @endif
                            @else
                                <form action="{{ route('member.events.register', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm sm:text-base shadow-md transition">
                                        {{ $event->event_type === 'free' ? 'Confirm Free Registration' : 'Pay & Register (£' . number_format($event->price, 2) . ')' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="block w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm sm:text-base shadow-md transition">
                                Register or Login to Attend
                            </a>
                        @endauth
                    </div>

                    <div class="pt-4 border-t border-slate-100 text-left space-y-3">
                        <span class="text-[11px] font-extrabold uppercase text-slate-400">Host Community</span>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-700 text-white font-black flex items-center justify-center text-sm uppercase shrink-0">
                                {{ substr($event->group->name, 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">{{ $event->group->name }}</h4>
                                <a href="{{ route('groups.show', $event->group->slug) }}" class="text-xs text-sky-600 font-semibold hover:underline block mt-0.5">View Community &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
