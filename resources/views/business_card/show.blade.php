@extends('layouts.app')

@section('title', $userModel->name . ' - Digital Business Card')
@section('og_title', $userModel->name . ' - Digital Business Card')
@section('og_description', $userModel->profession . ($userModel->company ? ' at ' . $userModel->company : '') . ' - Community UK Member')

@section('content')
<div class="py-10 md:py-16 min-h-screen flex items-center justify-center px-4 sm:px-6 transition-colors duration-300" style="background-color: var(--bg-page, #f8fafc);">
    
    <!-- Pro-Level Wide Digital Business Card Wrapper -->
    <div class="max-w-5xl w-full rounded-[32px] border shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] overflow-hidden relative transition-all duration-300" style="background-color: var(--card-bg, #ffffff); border-color: var(--border-color, #e2e8f0); color: var(--text-primary, #0f172a);">

        <!-- Top Cover Header Banner -->
        <div class="h-44 md:h-52 relative p-6 md:p-8 flex justify-between items-start overflow-hidden shadow-md" style="background: linear-gradient(135deg, var(--btn-primary-bg, #0284c7) 0%, var(--text-link-hover, #0369a1) 100%);">
            <!-- Decorative Subtle Grid Pattern Overlay -->
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1.5px,transparent_1.5px)] [background-size:20px_20px]"></div>

            <div class="relative z-10 flex items-center space-x-2 bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-white text-xs font-extrabold uppercase tracking-wider border border-white/30 shadow-sm">
                <i class="fa-solid fa-id-card"></i>
                <span>Digital Business Card</span>
            </div>
            
            <button onclick="openShareModal()" class="relative z-10 w-11 h-11 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md text-white flex items-center justify-center transition shadow-md border border-white/30 hover:scale-105" title="Share Business Card">
                <i class="fa-solid fa-share-nodes text-base"></i>
            </button>
        </div>

        <!-- Card Content Body: Responsive 2-Column Grid on Desktop -->
        <div class="p-6 md:p-10 -mt-20 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

                <!-- LEFT COLUMN: Profile Header, Actions, Bio & Services -->
                <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                    
                    <!-- Avatar Ring -->
                    <div class="w-36 h-36 rounded-full p-1.5 shadow-2xl mx-auto lg:mx-0 border-4 border-white relative z-20 group" style="background-color: var(--card-bg, #ffffff);">
                        @if($userModel->profile_photo)
                            <img src="{{ asset('storage/' . $userModel->profile_photo) }}" alt="{{ $userModel->name }}" class="w-full h-full rounded-full object-cover shadow-sm">
                        @else
                            <div class="w-full h-full rounded-full flex items-center justify-center font-extrabold text-4xl uppercase text-white shadow-inner" style="background-color: var(--btn-primary-bg, #0284c7);">
                                {{ substr($userModel->first_name, 0, 1) }}{{ substr($userModel->last_name, 0, 1) }}
                            </div>
                        @endif
                        <!-- Verified Tick Badge -->
                        <div class="absolute bottom-1 right-1 w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-lg border-2 border-white text-sm" title="Verified UK Community Member">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>

                    <!-- Profile Header Details -->
                    <div class="space-y-2">
                        <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-snug" style="color: var(--text-heading, #0f172a);">
                            {{ $userModel->name }}
                        </h1>
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-0.5">
                            <span class="px-3.5 py-1 text-xs font-black uppercase tracking-wider rounded-full border shadow-2xs" style="background-color: var(--input-bg, #f0f9ff); color: var(--text-link, #0284c7); border-color: var(--input-border, #bae6fd);">
                                {{ $userModel->profession ?? 'Community Professional' }}
                            </span>
                        </div>

                        @if($userModel->company)
                            <p class="text-xs font-bold flex items-center justify-center lg:justify-start gap-1.5 pt-1" style="color: var(--text-secondary, #64748b);">
                                <i class="fa-solid fa-building text-slate-400"></i>
                                <span>{{ $userModel->company }}</span>
                            </p>
                        @endif

                        <p class="text-xs font-medium flex items-center justify-center lg:justify-start gap-1 pt-0.5" style="color: var(--text-secondary, #64748b);">
                            <i class="fa-solid fa-location-dot text-rose-500"></i>
                            <span>{{ $userModel->city ?? 'United Kingdom' }}, {{ $userModel->country }}</span>
                        </p>
                    </div>

                    <!-- Quick 3-App Actions Hub (Call, Email, WhatsApp) -->
                    <div class="pt-2 grid grid-cols-3 gap-2.5">
                        <!-- Call -->
                        @if($showPhone && $userModel->phone)
                            <a href="tel:{{ $userModel->phone }}" class="py-3 px-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-2xl flex flex-col items-center justify-center transition shadow-2xs group">
                                <i class="fa-solid fa-phone text-lg mb-1 group-hover:scale-110 transition"></i>
                                <span class="text-[10px] font-extrabold uppercase">Call</span>
                            </a>
                        @else
                            <button disabled class="py-3 px-2 bg-slate-50 text-slate-400 border border-slate-200 rounded-2xl flex flex-col items-center justify-center opacity-60 cursor-not-allowed">
                                <i class="fa-solid fa-phone-slash text-lg mb-1"></i>
                                <span class="text-[10px] font-extrabold uppercase">Call</span>
                            </button>
                        @endif

                        <!-- Email -->
                        @if($showEmail && $userModel->email)
                            <a href="mailto:{{ $userModel->email }}" class="py-3 px-2 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-2xl flex flex-col items-center justify-center transition shadow-2xs group">
                                <i class="fa-solid fa-envelope text-lg mb-1 group-hover:scale-110 transition"></i>
                                <span class="text-[10px] font-extrabold uppercase">Email</span>
                            </a>
                        @else
                            <button disabled class="py-3 px-2 bg-slate-50 text-slate-400 border border-slate-200 rounded-2xl flex flex-col items-center justify-center opacity-60 cursor-not-allowed">
                                <i class="fa-solid fa-envelope-open text-lg mb-1"></i>
                                <span class="text-[10px] font-extrabold uppercase">Email</span>
                            </button>
                        @endif

                        <!-- WhatsApp -->
                        @if($showPhone && $userModel->phone)
                            <a href="https://api.whatsapp.com/send?phone={{ preg_replace('/[^0-9+]/', '', $userModel->phone) }}" target="_blank" class="py-3 px-2 bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 rounded-2xl flex flex-col items-center justify-center transition shadow-2xs group">
                                <i class="fa-brands fa-whatsapp text-xl mb-1 group-hover:scale-110 transition"></i>
                                <span class="text-[10px] font-extrabold uppercase">Chat</span>
                            </a>
                        @else
                            <button disabled class="py-3 px-2 bg-slate-50 text-slate-400 border border-slate-200 rounded-2xl flex flex-col items-center justify-center opacity-60 cursor-not-allowed">
                                <i class="fa-brands fa-whatsapp text-xl mb-1"></i>
                                <span class="text-[10px] font-extrabold uppercase">Chat</span>
                            </button>
                        @endif
                    </div>

                    <!-- Primary Share Action Button -->
                    <div>
                        <button onclick="openShareModal()" class="w-full py-3.5 px-4 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center justify-center space-x-2 transform active:scale-95" style="background-color: var(--btn-primary-bg, #0284c7);">
                            <i class="fa-solid fa-qrcode text-sm"></i>
                            <span>Share Digital Business Card</span>
                        </button>
                    </div>

                    <!-- About / Summary -->
                    @if($userModel->description)
                        <div class="space-y-2 text-left pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">About {{ $userModel->first_name }}</h3>
                            <div class="p-4 rounded-2xl border text-xs leading-relaxed font-normal" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0); color: var(--text-primary, #334155);">
                                {{ $userModel->description }}
                            </div>
                        </div>
                    @endif

                    <!-- Services Exchange -->
                    @if($userModel->servicesOffered->isNotEmpty() || $userModel->servicesNeeded->isNotEmpty())
                        <div class="space-y-4 text-left pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">Services Exchange</h3>

                            @if($userModel->servicesOffered->isNotEmpty())
                                <div>
                                    <div class="text-[11px] font-bold uppercase mb-2 flex items-center gap-1.5" style="color: var(--text-link, #0284c7);">
                                        <i class="fa-solid fa-hand-holding-hand"></i>
                                        <span>Services Offered</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($userModel->servicesOffered as $service)
                                            <span class="px-3 py-1 bg-sky-50 text-sky-800 border border-sky-200 text-[11px] font-bold rounded-xl">
                                                {{ $service->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($userModel->servicesNeeded->isNotEmpty())
                                <div>
                                    <div class="text-[11px] font-bold uppercase mb-2 flex items-center gap-1.5 text-amber-700">
                                        <i class="fa-solid fa-magnifying-glass-chart"></i>
                                        <span>Services Needed</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($userModel->servicesNeeded as $service)
                                            <span class="px-3 py-1 bg-amber-50 text-amber-900 border border-amber-200 text-[11px] font-bold rounded-xl">
                                                {{ $service->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- RIGHT COLUMN: Contact Details, Portfolio & Community Events -->
                <div class="lg:col-span-7 space-y-8 pt-4 lg:pt-0">

                    <!-- Direct Contact Channels -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">Contact & Social Channels</h3>
                            <span class="text-[10px] font-semibold" style="color: var(--text-secondary, #94a3b8);">Verified Direct Links</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <!-- Phone Row -->
                            <div class="p-3.5 rounded-2xl border flex items-center justify-between transition hover:border-sky-300" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Phone</div>
                                        <div class="font-bold truncate" style="color: var(--text-heading, #0f172a);">
                                            @if($showPhone && $userModel->phone)
                                                {{ $userModel->phone }}
                                            @else
                                                <span class="text-slate-400 italic text-[11px]">Protected by privacy</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($showPhone && $userModel->phone)
                                    <a href="tel:{{ $userModel->phone }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-lg shadow-2xs shrink-0 transition">
                                        Call
                                    </a>
                                @endif
                            </div>

                            <!-- Email Row -->
                            <div class="p-3.5 rounded-2xl border flex items-center justify-between transition hover:border-sky-300" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Email</div>
                                        <div class="font-bold truncate" style="color: var(--text-heading, #0f172a);">
                                            @if($showEmail && $userModel->email)
                                                {{ $userModel->email }}
                                            @else
                                                <span class="text-slate-400 italic text-[11px]">Protected by privacy</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($showEmail && $userModel->email)
                                    <a href="mailto:{{ $userModel->email }}" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-[11px] rounded-lg shadow-2xs shrink-0 transition">
                                        Email
                                    </a>
                                @endif
                            </div>

                            <!-- LinkedIn -->
                            @if($userModel->linkedin)
                                <a href="{{ $userModel->linkedin }}" target="_blank" class="p-3.5 rounded-2xl border flex items-center justify-between hover:bg-blue-50/40 transition group" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                            <i class="fa-brands fa-linkedin-in"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">LinkedIn</div>
                                            <div class="font-bold group-hover:text-blue-600 truncate" style="color: var(--text-heading, #0f172a);">Profile</div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 group-hover:text-blue-600 text-xs shrink-0 transition"></i>
                                </a>
                            @endif

                            <!-- Website -->
                            @if($userModel->website)
                                <a href="{{ $userModel->website }}" target="_blank" class="p-3.5 rounded-2xl border flex items-center justify-between hover:bg-purple-50/40 transition group" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm shrink-0">
                                            <i class="fa-solid fa-globe"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Website</div>
                                            <div class="font-bold group-hover:text-purple-600 truncate" style="color: var(--text-heading, #0f172a);">{{ parse_url($userModel->website, PHP_URL_HOST) ?? 'Visit Site' }}</div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 group-hover:text-purple-600 text-xs shrink-0 transition"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Featured Portfolio Showcase Grid -->
                    @if($userModel->projects->isNotEmpty())
                        <div class="space-y-4 pt-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">Featured Portfolio & Case Studies</h3>
                                <span class="text-[10px] font-extrabold px-3 py-1 rounded-full border shadow-2xs" style="background-color: var(--input-bg, #f0f9ff); color: var(--text-link, #0284c7); border-color: var(--input-border, #bae6fd);">
                                    {{ $userModel->projects->count() }} Showcase Projects
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($userModel->projects as $project)
                                    <div class="p-4 rounded-2xl border flex flex-col justify-between space-y-3 transition hover:border-sky-400 hover:shadow-md group" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between gap-2">
                                                @if($project->category)
                                                    <span class="text-[9px] font-extrabold px-2 py-0.5 rounded uppercase border" style="background-color: var(--card-bg, #ffffff); color: var(--text-secondary, #64748b); border-color: var(--border-color, #e2e8f0);">
                                                        {{ $project->category }}
                                                    </span>
                                                @endif
                                                @if($project->completion_date)
                                                    <span class="text-[9px] text-slate-400 font-medium">
                                                        {{ \Carbon\Carbon::parse($project->completion_date)->format('M Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <h4 class="font-bold text-sm leading-snug group-hover:text-sky-600 transition" style="color: var(--text-heading, #0f172a);">
                                                {{ $project->title }}
                                            </h4>

                                            <p class="text-xs line-clamp-3 leading-relaxed" style="color: var(--text-secondary, #64748b);">
                                                {{ $project->description }}
                                            </p>
                                        </div>

                                        <div class="pt-2 border-t border-slate-200/50 space-y-2">
                                            @if($project->technologies)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach(explode(',', $project->technologies) as $tech)
                                                        <span class="px-2 py-0.5 text-[9px] font-semibold rounded" style="background-color: var(--card-bg, #ffffff); color: var(--text-primary, #334155);">
                                                            {{ trim($tech) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if($project->project_url)
                                                <a href="{{ $project->project_url }}" target="_blank" class="inline-flex items-center space-x-1.5 text-xs font-bold pt-1" style="color: var(--text-link, #0284c7);">
                                                    <span>View Case Study</span>
                                                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upcoming Admin Scheduled Community Events -->
                    @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
                        <div class="space-y-4 pt-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">Upcoming Community Events</h3>
                                <span class="text-[10px] font-extrabold px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full">
                                    Admin Scheduled
                                </span>
                            </div>

                            <div class="space-y-3">
                                @foreach($upcomingEvents as $event)
                                    <div class="p-3.5 rounded-2xl border flex items-center justify-between space-x-3 transition hover:border-emerald-300" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                        <div class="flex items-center space-x-3.5 min-w-0">
                                            <div class="w-12 h-12 text-white rounded-xl flex flex-col items-center justify-center shrink-0 font-black shadow-sm" style="background-color: var(--btn-primary-bg, #0284c7);">
                                                <span class="text-[9px] uppercase font-extrabold tracking-wider">{{ $event->start_at ? $event->start_at->format('M') : 'UP' }}</span>
                                                <span class="text-base leading-none">{{ $event->start_at ? $event->start_at->format('d') : 'EV' }}</span>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-xs truncate" style="color: var(--text-heading, #0f172a);">{{ $event->title }}</h4>
                                                <p class="text-[10px] mt-0.5 flex items-center space-x-2" style="color: var(--text-secondary, #64748b);">
                                                    @if($event->group)
                                                        <span class="font-bold truncate" style="color: var(--text-link, #0284c7);">{{ $event->group->name }}</span>
                                                        <span>•</span>
                                                    @endif
                                                    <span class="truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $event->city ?? 'UK' }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="px-3.5 py-1.5 text-white text-[10px] font-bold rounded-xl shadow-2xs shrink-0 transition flex items-center space-x-1" style="background-color: var(--btn-primary-bg, #0284c7);">
                                            <span>Register</span>
                                            <i class="fa-solid fa-chevron-right text-[8px]"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        <!-- Footer Ecosystem Bar -->
        <div class="p-6 text-center border-t space-y-2 transition-colors" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
            <div class="flex items-center justify-center space-x-2">
                <img src="{{ asset('logo.jpeg') }}" alt="Community Logo" class="h-6 w-auto rounded object-contain shadow-2xs">
                <span class="text-xs font-bold" style="color: var(--text-heading, #0f172a);">Community UK Platform</span>
            </div>
            <p class="text-[10px] font-medium" style="color: var(--text-secondary, #94a3b8);">Verified Member Ecosystem Card • Powered by NextEck</p>
        </div>
    </div>
</div>

<!-- Share Business Card Modal -->
<div id="shareModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 space-y-6 shadow-2xl relative border border-slate-100 transform transition-all scale-100">
        <button onclick="closeShareModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="text-center space-y-1">
            <h3 class="text-xl font-black text-slate-900">Share Business Card</h3>
            <p class="text-xs text-slate-500">Scan QR Code or copy direct card URL</p>
        </div>

        <!-- High-Res QR Code Card -->
        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-center shadow-inner">
            <img src="{{ $qrCodeUrl }}" alt="Business Card QR Code" class="w-48 h-48 mx-auto rounded-xl shadow-md border-2 border-white">
            <p class="text-[11px] text-slate-600 mt-3 font-semibold">Scan with smartphone camera to view card</p>
        </div>

        <!-- Social Share Shortcuts -->
        <div class="grid grid-cols-3 gap-2.5 text-center">
            <a href="https://api.whatsapp.com/send?text={{ urlencode('View ' . $userModel->name . '\'s Digital Business Card: ' . $cardUrl) }}" target="_blank" class="p-3 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition flex flex-col items-center">
                <i class="fa-brands fa-whatsapp text-xl mb-1"></i>
                <span class="text-[10px] font-extrabold uppercase">WhatsApp</span>
            </a>

            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($cardUrl) }}" target="_blank" class="p-3 rounded-2xl bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition flex flex-col items-center">
                <i class="fa-brands fa-linkedin-in text-xl mb-1"></i>
                <span class="text-[10px] font-extrabold uppercase">LinkedIn</span>
            </a>

            <a href="mailto:?subject={{ urlencode($userModel->name . ' - Digital Business Card') }}&body={{ urlencode('View ' . $userModel->name . '\'s Digital Business Card on Community UK: ' . $cardUrl) }}" class="p-3 rounded-2xl bg-sky-50 text-sky-700 border border-sky-200 hover:bg-sky-100 transition flex flex-col items-center">
                <i class="fa-solid fa-envelope text-xl mb-1"></i>
                <span class="text-[10px] font-extrabold uppercase">Email</span>
            </a>
        </div>

        <!-- Copy Link Input -->
        <div class="flex items-center space-x-2">
            <input type="text" id="cardUrlInput" value="{{ $cardUrl }}" readonly class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none font-medium text-slate-700">
            <button onclick="copyCardLink()" id="copyBtn" class="px-4 py-2.5 text-white font-bold text-xs rounded-xl transition shrink-0 shadow-sm" style="background-color: var(--btn-primary-bg, #0284c7);">
                Copy
            </button>
        </div>
    </div>
</div>

<script>
    function openShareModal() {
        const modal = document.getElementById('shareModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeShareModal() {
        const modal = document.getElementById('shareModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function copyCardLink() {
        const input = document.getElementById('cardUrlInput');
        input.select();
        document.execCommand('copy');
        
        const copyBtn = document.getElementById('copyBtn');
        copyBtn.innerText = 'Copied!';
        copyBtn.style.backgroundColor = '#10b981';

        setTimeout(() => {
            copyBtn.innerText = 'Copy';
            copyBtn.style.backgroundColor = 'var(--btn-primary-bg, #0284c7)';
        }, 2000);
    }
</script>
@endsection
