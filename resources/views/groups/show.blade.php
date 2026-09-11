@extends('layouts.app')

@section('title', $group->name . ' - Community Networking UK')
@section('og_title', $group->name)
@section('og_description', $group->description)

@section('content')
<!-- Community Header Banner -->
<div class="text-white relative py-16 shadow-md" style="background-color: var(--btn-primary-bg);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center md:items-end justify-between gap-6">
            <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6 text-center md:text-left">
                <div class="w-24 h-24 rounded-2xl bg-white p-2 shadow-xl border-4 border-white shrink-0">
                    <div class="w-full h-full rounded-xl flex items-center justify-center font-bold text-sky-800 text-3xl uppercase" style="background-color: var(--bg-page);">
                        {{ substr($group->name, 0, 2) }}
                    </div>
                </div>
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider mb-2">
                        <span><i class="fa-solid fa-location-dot text-rose-300 mr-1"></i>{{ $group->city ?? 'UK Wide' }}, {{ $group->country }}</span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-white">{{ $group->name }}</h1>
                    <p class="text-sky-100 text-base md:text-lg mt-2 max-w-2xl font-medium">
                        Connect. Network. Support. Grow Together.
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <button onclick="document.getElementById('shareModal').classList.remove('hidden')" class="w-full sm:w-auto px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl border border-white/20 backdrop-blur-md transition flex items-center justify-center space-x-2">
                    <span><i class="fa-solid fa-share-nodes mr-1.5"></i>Share</span>
                </button>

                @auth
                    @if(auth()->user()->isMemberOf($group->id))
                        <a href="{{ route('member.dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg transition text-center">
                            Already Joined &rarr;
                        </a>
                    @else
                        <a href="{{ route('groups.member_join', $group->id) }}" class="w-full sm:w-auto px-8 py-3.5 bg-white text-sky-800 hover:bg-sky-50 font-bold rounded-xl shadow-xl transition text-center">
                            Join Community
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}?group_id={{ $group->id }}" class="w-full sm:w-auto px-8 py-3.5 bg-white text-sky-800 hover:bg-sky-50 font-bold rounded-xl shadow-xl transition text-center">
                        Join Community
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Statistics Bar -->
<div class="bg-white border-b border-sky-100 shadow-sm py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
            <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-100">
                <div class="text-2xl md:text-3xl font-bold text-sky-700">{{ number_format($memberCount) }}</div>
                <div class="text-xs font-semibold text-black uppercase mt-1">Total Members</div>
            </div>
            <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-100">
                <div class="text-2xl md:text-3xl font-bold text-sky-700">{{ number_format($professionalsCount) }}</div>
                <div class="text-xs font-semibold text-black uppercase mt-1">Professionals</div>
            </div>
            <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-100">
                <div class="text-2xl md:text-3xl font-bold text-sky-700">{{ number_format(max(1, (int)($professionalsCount * 0.4))) }}</div>
                <div class="text-xs font-semibold text-black uppercase mt-1">Businesses</div>
            </div>
            <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-100">
                <div class="text-2xl md:text-3xl font-bold text-sky-700">{{ number_format($eventsCount) }}</div>
                <div class="text-xs font-semibold text-black uppercase mt-1">Events Hosted</div>
            </div>
            <div class="p-3 bg-sky-50/60 rounded-xl border border-sky-100 col-span-2 md:col-span-1">
                <div class="text-2xl md:text-3xl font-bold text-sky-700">{{ number_format($memberCount * 3) }}</div>
                <div class="text-xs font-semibold text-black uppercase mt-1">Connections</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Community Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

        <!-- Left Content Columns (2 cols) -->
        <div class="lg:col-span-2 space-y-12">

            <!-- About Section -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-2xl font-bold text-black mb-4 flex items-center space-x-2">
                    <span><i class="fa-solid fa-book-open text-sky-600 mr-2"></i>About The Community</span>
                </h2>
                <p class="text-slate-700 text-base leading-relaxed whitespace-pre-line">
                    {{ $group->description }}
                </p>

                @if($group->purpose)
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <h4 class="font-bold text-black text-sm mb-2">Our Purpose & Objective</h4>
                        <p class="text-sm text-black leading-relaxed">{{ $group->purpose }}</p>
                    </div>
                @endif
            </div>

            <!-- Why Join Section -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-2xl font-bold text-black mb-6">Why Join This Community?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-handshake text-lg text-sky-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-black text-sm">Build Connections</h4>
                            <p class="text-xs text-black mt-1">Meet people from your community, regional background, or language group across the UK.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-briefcase text-lg text-sky-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-black text-sm">Discover Professionals</h4>
                            <p class="text-xs text-black mt-1">Find vetted accountants, solicitors, property agents, IT experts, and business consultants.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-screwdriver-wrench text-lg text-sky-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-black text-sm">Offer & Find Services</h4>
                            <p class="text-xs text-black mt-1">Promote your own professional services and request help from trusted community members.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold shrink-0">
                            <i class="fa-solid fa-ticket text-lg text-sky-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-black text-sm">Attend Exclusive Events</h4>
                            <p class="text-xs text-black mt-1">Join networking dinners, cultural meetups, business breakfasts, and online webinars.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Categories -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-2xl font-bold text-black mb-4">Available Services in Community</h2>
                <p class="text-sm text-black mb-6">Members within this community offer and request services in these fields:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Accounting', 'Legal & Legal Advice', 'Property & Estate Agents', 'Insurance', 'IT & Software', 'Marketing & Branding', 'Recruitment', 'Business Consulting', 'Education & Tutoring', 'Photography & Media'] as $category)
                        <span class="px-3.5 py-1.5 bg-sky-50 border border-sky-100 text-sky-800 text-xs font-semibold rounded-lg flex items-center">
                            <i class="fa-solid fa-check text-sky-600 mr-1.5 text-xs"></i> {{ $category }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Community Events -->
            @if($upcomingEvents->count() > 0)
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-2xl font-bold text-black mb-6">Upcoming Community Events</h2>
                    <div class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            <div class="p-4 rounded-xl border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-sky-300 transition">
                                <div>
                                    <h4 class="font-bold text-black text-base">{{ $event->title }}</h4>
                                    <div class="text-xs text-black mt-1 space-x-3">
                                        <span><i class="fa-regular fa-calendar-days text-sky-600 mr-1"></i>{{ $event->start_at->format('d M Y, h:i A') }}</span>
                                        <span><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $event->venue ?? 'Online' }}, {{ $event->city }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="font-bold text-sm text-black">
                                        {{ $event->event_type === 'free' ? 'Free' : '£' . number_format($event->price, 2) }}
                                    </span>
                                    <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2 bg-sky-600 text-white font-bold text-xs rounded-lg hover:bg-sky-700 transition">
                                        Event Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        <!-- Right Sidebar Sticky Panel -->
        <div class="space-y-8">

            <!-- Membership Card -->
            <div class="text-white p-8 rounded-2xl shadow-xl border border-slate-700" style="background-color: var(--footer-bg);">
                <div class="text-xs uppercase tracking-widest font-bold text-sky-400 mb-2">Community Membership</div>
                <h3 class="text-2xl font-bold text-white mb-4">{{ $group->name }}</h3>

                <div class="text-3xl font-bold text-white mb-6">
                    @if($group->community_type === 'paid')
                        £{{ number_format($activePlan->price ?? 20, 2) }}<span class="text-sm font-normal text-slate-400">/year</span>
                    @else
                        Free Membership
                    @endif
                </div>

                <ul class="space-y-3 text-sm text-slate-300 mb-8 border-t border-slate-800 pt-6">
                    <li class="flex items-center space-x-2">
                        <span class="text-sky-400">✓</span> <span>Full member directory access</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="text-sky-400">✓</span> <span>Send & receive connection requests</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="text-sky-400">✓</span> <span>Request member contact details</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="text-sky-400">✓</span> <span>Post services offered & needed</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="text-sky-400">✓</span> <span>Access community notices & events</span>
                    </li>
                </ul>

                @auth
                    @if(auth()->user()->isMemberOf($group->id))
                        <a href="{{ route('member.dashboard') }}" class="block w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-center text-base rounded-xl shadow-lg transition">
                            Already Joined &rarr;
                        </a>
                    @else
                        <a href="{{ route('groups.member_join', $group->id) }}" class="block w-full py-4 bg-sky-500 hover:bg-sky-400 text-white font-bold text-center text-base rounded-xl shadow-lg transition">
                            Join This Community Now
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}?group_id={{ $group->id }}" class="block w-full py-4 bg-sky-500 hover:bg-sky-400 text-white font-bold text-center text-base rounded-xl shadow-lg transition">
                        Join This Community Now
                    </a>
                @endauth
            </div>

            <!-- Who Can Join -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-black text-lg mb-4">Who Can Join?</h3>
                <ul class="space-y-2 text-sm text-black">
                    @foreach($group->who_can_join ?? ['People living in the UK', 'Community professionals & business owners', 'Families & Students'] as $eligible)
                        <li class="flex items-center space-x-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>{{ $eligible }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- QR Code Box (Hidden if user is already a joined member) -->
            @unless(auth()->check() && auth()->user()->isMemberOf($group->id))
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <h3 class="font-bold text-black text-base mb-3">Scan to Join Community</h3>
                    <div class="mb-4">
                        {!! $qrCodeSvg !!}
                    </div>
                    <p class="text-xs text-black">Scan QR Code with mobile camera to open this shareable community link directly.</p>
                </div>
            @endunless

        </div>

    </div>
</div>

<!-- Share & QR Modal -->
<div id="shareModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between mb-4 border-b pb-3">
            <h3 class="text-lg font-bold text-black">Share {{ $group->name }}</h3>
            <button onclick="document.getElementById('shareModal').classList.add('hidden')" class="text-slate-400 hover:text-black text-xl font-bold">&times;</button>
        </div>

        <p class="text-sm text-black mb-4">Share this community link on social media or scan QR Code directly.</p>

        <!-- Real Scannable QR Code inside Modal -->
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-center mb-5">
            {!! $qrCodeSvg !!}
            <p class="text-xs text-black mt-2 font-medium">Scan with camera to open shareable join link</p>
        </div>

        <div class="space-y-3 mb-6">
            <a href="https://api.whatsapp.com/send?text={{ urlencode('Join ' . $group->name . ' on Community UK: ' . $group->join_url) }}" target="_blank" class="flex items-center justify-center space-x-2 w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition text-sm">
                <span class="flex items-center"><i class="fa-brands fa-whatsapp mr-2 text-base"></i> Share on WhatsApp</span>
            </a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($group->join_url) }}" target="_blank" class="w-full py-3 bg-sky-50 hover:bg-sky-100 text-sky-800 font-bold rounded-xl text-sm transition flex items-center justify-center space-x-2 border border-sky-100">
                <span class="flex items-center"><i class="fa-brands fa-linkedin text-sky-700 mr-2 text-base"></i> Share on LinkedIn</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($group->join_url) }}" target="_blank" class="w-full py-3 bg-sky-50 hover:bg-sky-100 text-sky-800 font-bold rounded-xl text-sm transition flex items-center justify-center space-x-2 border border-sky-100">
                <span class="flex items-center"><i class="fa-brands fa-facebook text-blue-600 mr-2 text-base"></i> Share on Facebook</span>
            </a>
        </div>

        <div class="border-t pt-4">
            <label class="block text-xs font-bold text-black mb-1">Direct Shareable Link</label>
            <div class="flex space-x-2">
                <input type="text" readonly value="{{ $group->join_url }}" class="flex-grow px-3 py-2 border border-slate-200 rounded-lg text-xs bg-slate-50">
                <button onclick="navigator.clipboard.writeText('{{ $group->join_url }}'); alert('Link copied to clipboard!');" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-slate-800 transition">
                    Copy Link
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
