@extends('layouts.app')

@section('title', 'Connect With Your Community & Network')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-sky-50 via-white to-slate-50 py-16 lg:py-16 overflow-hidden border-b border-sky-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-sky-100/80 text-sky-800 font-semibold text-xs tracking-wide uppercase mb-6 border border-sky-200">
            <span>🇬🇧 UK Community Networking Ecosystem</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 leading-tight max-w-4xl mx-auto">
            Connect With Your Community. <br class="hidden sm:inline" /><span class="text-sky-600">Build Meaningful Relationships.</span>
        </h1>
        <p class="mt-6 text-lg md:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
            Discover people you already have something in common with, connect with professionals and businesses, exchange services, attend events and stay connected with your community.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('groups.index') }}" class="px-8 py-4 text-base font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-lg hover:shadow-sky-500/20 transition transform hover:-translate-y-0.5">
                Explore Communities
            </a>
            <a href="{{ route('register') }}" class="px-8 py-4 text-base font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-sm transition">
                Join Now
            </a>
        </div>
    </div>
</section>

<!-- Discover Communities Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Discover Communities</h2>
                <p class="text-slate-600 mt-2">Explore active cultural, regional, and business networks across the UK.</p>
            </div>
            <a href="{{ route('groups.index') }}" class="mt-4 md:mt-0 font-bold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                <span>View All Communities</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredGroups as $group)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                    <div>
                        <div class="h-40 bg-gradient-to-r from-sky-400 to-sky-600 relative p-4 flex items-end">
                            <div class="w-16 h-16 rounded-xl bg-white p-1 shadow-md absolute -bottom-6 left-6 border-2 border-white">
                                <div class="w-full h-full bg-sky-100 rounded-lg flex items-center justify-center font-bold text-sky-700 text-xl uppercase">
                                    {{ substr($group->name, 0, 2) }}
                                </div>
                            </div>
                            <span class="ml-auto bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-slate-800">
                                {{ ucfirst($group->community_type) }}
                            </span>
                        </div>
                        <div class="pt-8 px-6 pb-6">
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-sky-600 transition">{{ $group->name }}</h3>
                            <p class="text-xs text-sky-600 font-medium mt-1 flex items-center">
                                <span>📍 {{ $group->city ?? 'UK Wide' }}, {{ $group->country }}</span>
                            </p>
                            <p class="text-sm text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                                {{ $group->description }}
                            </p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <div class="text-xs text-slate-500 font-semibold">
                            <span>👥 {{ number_format($group->members_count) }} Members</span> &bull; 
                            <span>📅 {{ $group->events_count }} Events</span>
                        </div>
                        <a href="{{ route('groups.show', $group->slug) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                            Join Community
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 bg-sky-50/60 border-y border-sky-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold text-slate-900">How It Works</h2>
            <p class="text-slate-600 mt-2">Connecting with your community in four simple steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-sky-100 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">1</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Find Your Community</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Browse regional, state, cultural, or business groups based on your background.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-sky-100 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">2</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Register & Join</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Create your profile, set your privacy options, and join free or paid memberships.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-sky-100 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">3</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Discover Members</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Filter directory by profession, services offered, services needed, and city.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-sky-100 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">4</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Connect & Participate</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Send connection requests, exchange professional services, and attend community events.</p>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
@if($upcomingEvents->count() > 0)
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Upcoming Community Events</h2>
                <p class="text-slate-600 mt-2">Attend networking nights, workshops, and community meetups.</p>
            </div>
            <a href="{{ route('events.index') }}" class="font-bold text-sky-600 hover:text-sky-700">View All Events &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($upcomingEvents as $event)
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between">
                    <div class="p-6">
                        <span class="inline-block px-3 py-1 bg-sky-100 text-sky-800 rounded-full text-xs font-bold mb-3">
                            {{ $event->group->name }}
                        </span>
                        <h4 class="font-bold text-lg text-slate-900 leading-snug">{{ $event->title }}</h4>
                        <div class="mt-4 space-y-1 text-xs text-slate-500">
                            <p class="flex items-center space-x-1"><span>📅</span> <span>{{ $event->start_at->format('d M Y, h:i A') }}</span></p>
                            <p class="flex items-center space-x-1"><span>📍</span> <span>{{ $event->venue ?? 'Online' }}, {{ $event->city }}</span></p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="font-bold text-slate-900 text-sm">
                            {{ $event->event_type === 'free' ? 'Free' : '£' . number_format($event->price, 2) }}
                        </span>
                        <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg transition">
                            Event Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call To Action -->
<section class="py-20 bg-gradient-to-r from-sky-600 to-sky-800 text-white text-center">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-6">
            Your Community. Your Connections. Your Opportunities.
        </h2>
        <p class="text-sky-100 text-lg mb-8 max-w-2xl mx-auto">
            Join thousands of members across the UK who are connecting, exchanging professional services, and supporting each other.
        </p>
        <a href="{{ route('groups.index') }}" class="px-8 py-4 bg-white text-sky-700 hover:bg-sky-50 font-bold text-lg rounded-xl shadow-lg transition">
            Find Your Community
        </a>
    </div>
</section>

@endsection
