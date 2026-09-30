@extends('layouts.app')

@section('title', 'Connect With Your Community & Network')

@section('content')

<!-- Hero Section -->
<section class="relative py-16 lg:py-16 overflow-hidden border-b border-slate-200" style="background-color: var(--bg-page);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-sky-100/80 text-sky-900 font-bold text-xs tracking-wide uppercase mb-6 border border-sky-300 shadow-2xs">
            <span><i class="fa-solid fa-building text-sky-600 mr-1.5"></i> A Nexteck Company Product | Bizconn Ecosystem</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-slate-900 leading-tight max-w-4xl mx-auto">
            Connect With Your Network. <br class="hidden sm:inline" /><span class="text-sky-600">Powered by Nexteck Innovation.</span>
        </h1>
        <p class="mt-6 text-lg md:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
            Bizconn is a flagship digital business network created by <strong>Nexteck Company</strong> to bring entrepreneurs, professionals, trade experts, and business communities together in one seamless platform.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('groups.index') }}" class="px-8 py-4 text-base font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-lg hover:shadow-sky-500/20 transition transform hover:-translate-y-0.5">
                Explore Communities
            </a>
            <a href="{{ route('cms.show', 'about') }}" class="px-8 py-4 text-base font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-sm transition">
                About Nexteck & Bizconn
            </a>
        </div>
    </div>
</section>

<!-- Why Bizconn Section -->
<section class="py-16 md:py-20 bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
            <span class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full bg-sky-100 text-sky-900 text-xs font-extrabold uppercase tracking-wider border border-sky-300 shadow-2xs">
                <i class="fa-solid fa-star text-sky-600"></i>
                <span>Enterprise Business Ecosystem</span>
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Why Choose Bizconn?</h2>
            <p class="text-slate-600 text-base leading-relaxed">
                Engineered by <strong>Nexteck Company</strong>, Bizconn provides a trusted, high-performance platform designed to help business owners, trade experts, and regional networks connect, collaborate, and scale.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1: Powered by Nexteck -->
            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold border border-sky-100 group-hover:bg-sky-600 group-hover:text-white transition">
                    <i class="fa-solid fa-building-shield"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition">Powered by Nexteck</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Built on enterprise-grade software architecture by Nexteck Company, ensuring high security, data reliability, and continuous digital innovation.
                </p>
            </div>

            <!-- Feature 2: Verified Directory -->
            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition">Verified Directory</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Connect directly with verified community members, trade specialists, legal advisors, accountants, and IT leaders in a trusted ecosystem.
                </p>
            </div>

            <!-- Feature 3: Services Exchange -->
            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition">
                    <i class="fa-solid fa-handshake font-bold"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition">Services Exchange</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Easily list professional services you offer or submit service requests. Match with local expertise within your community network seamlessly.
                </p>
            </div>

            <!-- Feature 4: Events & Summits -->
            <div class="bg-white p-7 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-3 group">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-600 transition">Events & Summits</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Participate in exclusive community networking breakfasts, webinars, and annual summits with QR-code entry and instant attendee connections.
                </p>
            </div>
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
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-2xl mb-5 border border-sky-100">
                    <i class="fa-solid fa-users-rays"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Regional & Cultural Networks</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Join verified state, cultural, and professional communities across the UK to stay connected with members who share your roots and values.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-2xl mb-5 border border-sky-100">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Verified Member Directory</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Search and filter community members by profession, city, services offered, and services needed. Exchange business and career opportunities easily.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-2xl mb-5 border border-sky-100">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Dedicated Admin Governance</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Group admins maintain community standards, approve new member requests, manage local events, and broadcast announcements seamlessly.</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 border-y border-slate-200" style="background-color: var(--bg-page);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold text-slate-900">How It Works</h2>
            <p class="text-slate-600 mt-2">Connecting with your community in four simple steps.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">1</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Find Your Community</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Browse regional, state, cultural, or business groups based on your background.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">2</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Register & Join</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Create your profile, set your privacy options, and join free or paid memberships.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">3</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Discover Members</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Filter directory by profession, services offered, services needed, and city.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-xl mx-auto mb-6">4</div>
                <h3 class="font-bold text-lg text-slate-900 mb-2">Connect & Participate</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Send connection requests, exchange professional services, and attend community events.</p>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Upcoming Community Events</h2>
                <p class="text-slate-600 mt-2">Attend networking nights, workshops, and community meetups across the UK.</p>
            </div>
            <a href="{{ route('events.index') }}" class="font-bold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                <span>View All Events</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-2xl mb-5 border border-sky-100">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Live Workshops & Meetups</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Participate in both online and in-person community gatherings, professional seminars, and networking sessions tailored for members.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-2xl mb-5 border border-sky-100">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Digital RSVP & Event QR Passes</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Register for free or paid events with a single click. Manage your active registrations and download digital event passes from your dashboard.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition">
                <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center font-bold text-2xl mb-5 border border-sky-100">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Instant Broadcast Alerts</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Receive instant notifications and email updates about upcoming events, venue changes, and urgent community announcements.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action -->
<section class="py-20 text-white text-center shadow-inner" style="background-color: var(--btn-primary-bg);">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-6">
            Your Community. Your Connections. Your Opportunities.
        </h2>
        <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
            Join thousands of members across the UK who are connecting, exchanging professional services, and supporting each other.
        </p>
        <a href="{{ route('groups.index') }}" class="px-8 py-4 bg-white text-slate-900 hover:bg-slate-100 font-bold text-lg rounded-xl shadow-lg transition">
            Find Your Community
        </a>
    </div>
</section>

@endsection
