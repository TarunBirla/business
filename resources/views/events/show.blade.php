@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">

    <!-- Top Banner: Image with subtle gradient overlay for polish -->
    @if($event->banner_image_url)
        <div class="w-full bg-slate-900 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="relative w-full h-[240px] sm:h-[360px] md:h-[460px] rounded-2xl md:rounded-3xl overflow-hidden shadow-xl ring-1 ring-black/5 bg-slate-950">
                    <img src="{{ $event->banner_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/0 to-slate-950/10"></div>

                    <!-- Floating status chip on banner -->
                    <div class="absolute top-4 left-4 sm:top-6 sm:left-6 flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold uppercase tracking-wide backdrop-blur-md {{ $event->event_type === 'free' ? 'bg-emerald-500/90 text-white' : 'bg-sky-500/90 text-white' }} shadow-lg">
                            <i class="fa-solid fa-tag text-[10px]"></i>
                            {{ $event->event_type === 'free' ? 'Free Event' : '£' . number_format($event->price, 2) . ' Ticket' }}
                        </span>
                        @if($event->status === 'published')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-bold uppercase tracking-wide bg-white/90 text-slate-900 backdrop-blur-md shadow-lg">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-[10px]"></i>
                                Confirmed
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Event Title & Info Header -->
    <div class="bg-white border-b border-slate-200 py-8 md:py-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="space-y-4 max-w-4xl">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('groups.show', $event->group->slug) }}" class="inline-flex items-center space-x-1.5 px-3 py-1 bg-teal-50 text-teal-800 rounded-full text-xs font-bold border border-teal-200 hover:bg-teal-100 transition">
                            <i class="fa-solid fa-users text-teal-600 text-[10px]"></i>
                            <span>{{ $event->group->name }}</span>
                        </a>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $event->event_type === 'free' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-sky-100 text-sky-800 border border-sky-300' }}">
                            {{ $event->event_type === 'free' ? 'Free Event' : 'Paid Event (£' . number_format($event->price, 2) . ')' }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-[1.1]">
                        {{ $event->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-y-3 gap-x-6 text-xs sm:text-sm text-slate-600 font-semibold pt-1">
                        <div class="flex items-center space-x-2">
                            <span class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-calendar-days text-sky-600 text-sm"></i>
                            </span>
                            <span>{{ $event->start_at->format('l, F j, Y \a\t h:i A') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot text-rose-500 text-sm"></i>
                            </span>
                            <span>{{ $event->venue ?? 'Online Virtual Meeting' }}, {{ $event->city }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-ticket text-teal-600 text-sm"></i>
                            </span>
                            <span>{{ $event->capacity }} Total Seats</span>
                        </div>
                    </div>
                </div>

                <!-- Quick share / actions (desktop) -->
                <div class="hidden md:flex items-center gap-2 shrink-0">
                    <button type="button" class="w-11 h-11 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-500 hover:text-sky-600 transition shadow-xs" title="Share">
                        <i class="fa-solid fa-share-nodes"></i>
                    </button>
                    <button type="button" class="w-11 h-11 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-500 hover:text-rose-500 transition shadow-xs" title="Save">
                        <i class="fa-regular fa-bookmark"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">

            <!-- Left 2 Columns: Main Details -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Featured Event Card Image (If provided) -->
                @if($event->event_image_url && $event->event_image_url !== $event->banner_image_url)
                    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <img src="{{ $event->event_image_url }}" alt="{{ $event->title }}" class="w-full h-auto max-h-[400px] object-cover rounded-xl">
                    </div>
                @endif

                <!-- Short Summary / Introduction -->
                @if($event->description)
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow space-y-3">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2.5 border-b border-slate-100 pb-4">
                            <span class="w-9 h-9 rounded-lg bg-sky-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-align-left text-sky-600 text-sm"></i>
                            </span>
                            Event Summary
                        </h2>
                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line font-normal pt-1">
                            {{ $event->description }}
                        </div>
                    </div>
                @endif

                <!-- Detailed Event Overview -->
                @if($event->overview)
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow space-y-3">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2.5 border-b border-slate-100 pb-4">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-circle-info text-teal-600 text-sm"></i>
                            </span>
                            Event Overview
                        </h2>
                        <div class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line pt-1">
                            {{ $event->overview }}
                        </div>
                    </div>
                @endif

                <!-- Event Agenda & Schedule -->
                @if($event->agenda)
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow space-y-3">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2.5 border-b border-slate-100 pb-4">
                            <span class="w-9 h-9 rounded-lg bg-sky-50 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock-rotate-left text-sky-600 text-sm"></i>
                            </span>
                            Agenda &amp; Schedule
                        </h2>
                        <div class="bg-slate-50 p-5 sm:p-6 rounded-xl border border-slate-200 text-slate-800 text-xs sm:text-sm leading-relaxed whitespace-pre-line font-mono">
                            {{ $event->agenda }}
                        </div>
                    </div>
                @endif

                <!-- Venue & Location Section -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow space-y-4">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2.5 border-b border-slate-100 pb-4">
                        <span class="w-9 h-9 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-map-location-dot text-rose-500 text-sm"></i>
                        </span>
                        Venue &amp; Location Details
                    </h3>
                    <div class="pt-1">
                        <p class="text-slate-900 font-bold text-base">{{ $event->venue ?? 'Online Virtual Meeting' }}</p>
                        @if($event->address)
                            <p class="text-xs sm:text-sm text-slate-600 mt-1.5 flex items-start gap-1.5">
                                <i class="fa-solid fa-building text-slate-400 mt-0.5"></i>
                                <span>{{ $event->address }}, {{ $event->city }}, {{ $event->country }}</span>
                            </p>
                        @endif
                    </div>

                    @if($event->meeting_url && auth()->check() && $userRegistration && in_array($userRegistration->registration_status, ['confirmed', 'approved']))
                        <div class="mt-2 p-4 bg-emerald-50 border border-emerald-200 rounded-xl space-y-1.5">
                            <p class="text-xs font-bold text-emerald-800 uppercase flex items-center gap-1.5">
                                <i class="fa-solid fa-video"></i> Online Meeting Join Link
                            </p>
                            <a href="{{ $event->meeting_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-700 underline hover:text-emerald-800 break-all">
                                {{ $event->meeting_url }}
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
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

                        @php
                            $totalSeats = max((int) $event->capacity, 1);
                            $available = (int) $event->available_seats;
                            $filledPct = max(0, min(100, round((($totalSeats - $available) / $totalSeats) * 100)));
                        @endphp

                        <div class="mt-4">
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $filledPct >= 90 ? 'bg-rose-500' : 'bg-teal-500' }}" style="width: {{ $filledPct }}%"></div>
                            </div>
                            <p class="text-xs text-slate-500 mt-2 font-bold flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-chair text-teal-600"></i>
                                {{ $event->available_seats }} of {{ $event->capacity }} seats remaining
                            </p>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-100">
                        @auth
                            @if($userRegistration)
                                @if($userRegistration->registration_status === 'pending')
                                    <div class="w-full py-3.5 bg-amber-50 text-amber-900 font-bold rounded-xl text-xs sm:text-sm border border-amber-300 flex items-center justify-center gap-2">
                                        <i class="fa-regular fa-clock text-amber-600"></i>
                                        <span>Registration Pending Approval</span>
                                    </div>
                                @elseif($userRegistration->registration_status === 'rejected')
                                    <div class="w-full py-3.5 bg-rose-50 text-rose-800 font-bold rounded-xl text-xs sm:text-sm border border-rose-300 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-xmark text-rose-600"></i>
                                        <span>Registration Request Declined</span>
                                    </div>
                                @else
                                    <div class="w-full py-3.5 bg-emerald-50 text-emerald-800 font-bold rounded-xl text-xs sm:text-sm border border-emerald-300 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check text-emerald-600"></i>
                                        <span>You Are Registered for This Event</span>
                                    </div>
                                @endif
                            @else
                                <form action="{{ route('member.events.register', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 active:scale-[0.98] text-white font-bold rounded-xl text-sm sm:text-base shadow-md shadow-sky-600/20 transition-all">
                                        {{ $event->event_type === 'free' ? 'Confirm Free Registration' : 'Pay & Register (£' . number_format($event->price, 2) . ')' }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="block w-full py-3.5 bg-sky-600 hover:bg-sky-700 active:scale-[0.98] text-white font-bold rounded-xl text-sm sm:text-base shadow-md shadow-sky-600/20 transition-all">
                                Register or Login to Attend
                            </a>
                        @endauth

                        <p class="text-[11px] text-slate-400 font-semibold mt-3 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            Secure registration &middot; No spam, ever
                        </p>
                    </div>

                    <div class="pt-5 border-t border-slate-100 text-left space-y-3">
                        <span class="text-[11px] font-extrabold uppercase text-slate-400 tracking-wider">Host Community</span>
                        <div class="flex items-center space-x-3">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-600 to-teal-800 text-white font-black flex items-center justify-center text-sm uppercase shrink-0 shadow-sm">
                                {{ substr($event->group->name, 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">{{ $event->group->name }}</h4>
                                <a href="{{ route('groups.show', $event->group->slug) }}" class="text-xs text-sky-600 font-semibold hover:underline inline-flex items-center gap-1 mt-0.5">
                                    View Community <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection