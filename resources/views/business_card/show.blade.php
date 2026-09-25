@extends('layouts.app')

@section('hide_header_footer', true)

@section('title', $userModel->name . ' - Digital Business Card')
@section('og_title', $userModel->name . ' - Digital Business Card')
@section('og_description', $userModel->profession . ($userModel->company ? ' at ' . $userModel->company : '') . ' - Community UK Member')

<link rel="manifest" href="{{ route('bizcard.manifest', $userModel->id) }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ $userModel->first_name ?: $userModel->name }}">

@section('content')
<div class="py-10 md:py-16 min-h-screen flex items-center justify-center px-4 sm:px-6 transition-colors duration-300" style="background-color: var(--bg-page, #f8fafc);">
    
    <!-- Pro-Level Wide Digital Business Card Wrapper -->
    <div class="max-w-5xl w-full rounded-[32px] border shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] overflow-hidden relative transition-all duration-300" style="background-color: var(--card-bg, #ffffff); border-color: var(--border-color, #e2e8f0); color: var(--text-primary, #0f172a);">

        <!-- Top Cover Header Banner -->
        <div class="h-44 md:h-52 relative p-6 md:p-8 flex justify-between items-start overflow-hidden shadow-md"
            style="background: {{ $userModel->banner_photo_url ? 'url(' . e($userModel->banner_photo_url) . ') center/cover no-repeat' : 'linear-gradient(135deg, var(--btn-primary-bg, #0A4744) 0%, var(--text-link-hover, #0369a1) 100%)' }};">
            
            <!-- Dark Gradient & Subtle Pattern Overlay for Text Contrast -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-black/30 z-0"></div>
            <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#fff_1.5px,transparent_1.5px)] [background-size:20px_20px] z-0"></div>

            <div class="relative z-10 flex items-center space-x-2 bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-white text-xs font-extrabold uppercase tracking-wider border border-white/30 shadow-sm">
                <i class="fa-solid fa-id-card"></i>
                <span>Digital Business Card</span>
            </div>
        </div>

        <!-- Card Content Body: Responsive 2-Column Grid on Desktop -->
        <div class="p-6 md:p-10 -mt-20 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

                <!-- LEFT COLUMN: Profile Header, Actions, Bio & Services -->
                <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                    
                    <!-- Avatar Ring -->
                    <div class="w-36 h-36 rounded-full p-1.5 shadow-2xl mx-auto lg:mx-0 border-4 border-white relative z-20 group" style="background-color: var(--card-bg, #ffffff);">
                        @if($userModel->profile_photo_url)
                            <img src="{{ $userModel->profile_photo_url }}" alt="{{ $userModel->name }}" class="w-full h-full rounded-full object-cover shadow-sm">
                        @else
                            <div class="w-full h-full rounded-full flex items-center justify-center font-extrabold text-4xl uppercase text-white shadow-inner" style="background-color: var(--btn-primary-bg, #0A4744);">
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
                            <span class="px-3.5 py-1 text-xs font-black uppercase tracking-wider rounded-full border shadow-2xs" style="background-color: var(--input-bg, #f0f7f6); color: var(--text-link, #0A4744); border-color: var(--input-border, #cce5e3);">
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

                    <!-- Primary Share & App Install Action Buttons -->
                    <div class="space-y-2.5">
                        <button onclick="openShareModal()" class="w-full py-3.5 px-4 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center justify-center space-x-2 transform active:scale-95" style="background-color: var(--btn-primary-bg, #0A4744);">
                            <i class="fa-solid fa-qrcode text-sm"></i>
                            <span>Share Digital Business Card</span>
                        </button>

                        <button onclick="openInstallAppModal()" class="w-full py-3.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center justify-center space-x-2 transform active:scale-95">
                            <i class="fa-solid fa-mobile-screen-button text-sm"></i>
                            <span>Download / Install Card App</span>
                        </button>

                        <a href="{{ route('bizcard.vcard', $userModel->id) }}" class="w-full py-3.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-lg transition flex items-center justify-center space-x-2 transform active:scale-95">
                            <i class="fa-solid fa-address-card text-sm"></i>
                            <span>Save Contact</span>
                        </a>
                    </div>

                    <!-- Prominent QR Code Card (On Page) -->
                    <div class="p-5 rounded-2xl border text-center space-y-3 shadow-xs" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                        <div class="flex items-center justify-center space-x-2 text-xs font-extrabold uppercase tracking-wider" style="color: var(--text-heading, #0f172a);">
                            <i class="fa-solid fa-qrcode" style="color: var(--text-link, #0A4744);"></i>
                            <span>Scan QR Code</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl inline-block shadow-sm border border-slate-200">
                            <img src="{{ $qrCodeUrl }}" alt="{{ $userModel->name }} Business Card QR Code" class="w-36 h-36 mx-auto rounded-lg">
                        </div>
                        <p class="text-[11px] font-medium text-slate-500">Scan with camera to open business card on mobile</p>
                        
                    </div>

                    

                   
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

                    <!-- About / Summary Section -->
                    @if($userModel->description)
                        <div class="space-y-2 text-left pt-2">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full" style="background-color: var(--text-link, #0A4744);"></span>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider" style="color: var(--text-heading, #0f172a);">About {{ $userModel->first_name }}</h3>
                            </div>
                            <div class="relative p-5 rounded-2xl border shadow-xs leading-relaxed text-xs font-medium space-y-2 overflow-hidden transition"
                                 style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0); color: var(--text-primary, #334155); border-left: 4px solid var(--text-link, #0A4744);">
                                <i class="fa-solid fa-quote-left absolute -bottom-2 -right-1 text-slate-200/50 dark:text-slate-700/20 text-5xl pointer-events-none"></i>
                                <p class="relative z-10 italic leading-relaxed text-slate-700 dark:text-slate-300">
                                    "{{ $userModel->description }}"
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Featured Portfolio Showcase Grid -->
                    @if($userModel->projects->isNotEmpty())
                        <div class="space-y-4 pt-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">Featured Portfolio & Case Studies</h3>
                                <span class="text-[10px] font-extrabold px-3 py-1 rounded-full border shadow-2xs" style="background-color: var(--input-bg, #f0f7f6); color: var(--text-link, #0A4744); border-color: var(--input-border, #cce5e3);">
                                    {{ $userModel->projects->count() }} Showcase Projects
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($userModel->projects as $project)
                                    <div class="p-4 rounded-2xl border flex flex-col justify-between space-y-3 transition hover:border-sky-400 hover:shadow-md group overflow-hidden" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                        <div class="space-y-2">
                                            @if($project->image_url)
                                                <div class="h-36 -mx-4 -mt-4 mb-3 overflow-hidden bg-slate-100 border-b border-slate-200/60 relative">
                                                    <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    <span class="absolute top-2.5 left-2.5 text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase shadow-xs border bg-slate-900/80 backdrop-blur-md text-white border-white/20">
                                                        <i class="fa-solid fa-list-ol text-[8px] mr-1 text-sky-400"></i>Seq #{{ $project->sort_order ?? $loop->iteration }}
                                                    </span>
                                                </div>
                                            @endif

                                            <div class=" items-center  gap-2">
                                                <div class=" items-center space-x-1.5">
                                                    
                                                    @if($project->category)
                                                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded uppercase border" style="background-color: var(--card-bg, #ffffff); color: var(--text-secondary, #64748b); border-color: var(--border-color, #e2e8f0);">
                                                            {{ $project->category }}
                                                        </span>
                                                    @endif
                                                </div>
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
                                                <a href="{{ $project->project_url }}" target="_blank" class="inline-flex items-center space-x-1.5 text-xs font-bold pt-1" style="color: var(--text-link, #0A4744);">
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

                    <!-- Member Activity Portal Section (Announcements, Service Requests & Connection Requests) -->
                    @if(auth()->check() && (int)auth()->id() === (int)$userModel->id)
                        <div class="space-y-4 pt-4 border-t border-slate-200/60">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">
                                    <i class="fa-solid fa-bullhorn text-sky-600 mr-1.5"></i>Community Activity & Requests
                                </h3>
                                <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check text-[9px] mr-1"></i>Logged In
                                </span>
                            </div>

                            <!-- Logged-in User Activity Tabs / Blocks -->
                            <div class="space-y-4">
                                <!-- Announcements (Unread Only) -->
                                <div class="p-4 rounded-2xl border space-y-2" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                        <span class="text-xs font-bold flex items-center space-x-1.5" style="color: var(--text-heading, #0f172a);">
                                            <i class="fa-solid fa-bullhorn text-sky-600"></i>
                                            <span>Unread Announcements</span>
                                        </span>
                                        <span id="announcement_count_badge" class="text-[10px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-full border border-sky-200">
                                            {{ $userAnnouncements->count() }} Unread
                                        </span>
                                    </div>
                                    <div id="announcements_container">
                                        @if($userAnnouncements->isEmpty())
                                            <p class="text-xs text-slate-500 italic py-2">No unread announcements found.</p>
                                        @else
                                            <div class="divide-y divide-slate-100">
                                                @foreach($userAnnouncements as $ann)
                                                    <div id="announcement_row_{{ $ann->id }}" class="py-2.5 space-y-1.5 text-left transition-all">
                                                        <div class="flex items-start justify-between gap-2">
                                                            <div>
                                                                <h5 class="font-bold text-xs" style="color: var(--text-heading, #0f172a);">{{ $ann->title }}</h5>
                                                                <span class="text-[10px] text-slate-400">{{ $ann->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <button type="button" onclick="markAnnouncementAsRead({{ $ann->id }}, this)" class="px-2.5 py-1 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-bold text-[10px] rounded-lg shadow-2xs transition inline-flex items-center space-x-1 shrink-0">
                                                                <i class="fa-solid fa-check text-emerald-600"></i>
                                                                <span>Mark as Read</span>
                                                            </button>
                                                        </div>
                                                        <p class="text-[11px] line-clamp-2" style="color: var(--text-secondary, #64748b);">{{ Str::limit(strip_tags($ann->content), 120) }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Service Requests (Pending Only) -->
                                <div class="p-4 rounded-2xl border space-y-2" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                        <span class="text-xs font-bold flex items-center space-x-1.5" style="color: var(--text-heading, #0f172a);">
                                            <i class="fa-solid fa-handshake text-emerald-600"></i>
                                            <span>Pending Service Requests</span>
                                        </span>
                                    </div>
                                    <div id="service_requests_container">
                                        @if($userServicesRequests->isEmpty())
                                            <p class="text-xs text-slate-500 italic py-2">No pending service requests.</p>
                                        @else
                                            <div class="divide-y divide-slate-100">
                                                @foreach($userServicesRequests as $req)
                                                    <div id="service_req_row_{{ $req->id }}" class="py-2.5 flex items-center justify-between gap-2 text-left transition-all">
                                                        <div class="min-w-0">
                                                            <div class="font-bold text-xs truncate" style="color: var(--text-heading, #0f172a);">{{ $req->service->title ?? 'Service Request' }}</div>
                                                            <div class="text-[10px] text-slate-500 truncate">
                                                                {{ $req->provider_id === auth()->id() ? 'From: ' . ($req->requester->name ?? 'Member') : 'To: ' . ($req->provider->name ?? 'Member') }}
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-1.5 shrink-0">
                                                            <button type="button" onclick="respondServiceRequest({{ $req->id }}, 'approve', this)" class="px-2.5 py-1 text-white font-bold text-[10px] rounded-lg shadow-2xs transition hover:opacity-90" style="background-color: var(--btn-primary-bg, #0A4744);">
                                                                Accept
                                                            </button>
                                                            <button type="button" onclick="respondServiceRequest({{ $req->id }}, 'reject', this)" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[10px] rounded-lg transition">
                                                                Reject
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                 <!-- Pending Connection Requests -->
                                <div class="p-4 rounded-2xl border space-y-2" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                        <span class="text-xs font-bold flex items-center space-x-1.5" style="color: var(--text-heading, #0f172a);">
                                            <i class="fa-solid fa-user-plus" style="color: var(--btn-primary-bg, #0A4744);"></i>
                                            <span>Pending Connection Requests</span>
                                        </span>
                                    </div>
                                    <div id="connection_requests_container">
                                        @if($userConnectionRequests->isEmpty())
                                            <p class="text-xs text-slate-500 italic py-2">No pending connection requests.</p>
                                        @else
                                            <div class="divide-y divide-slate-100">
                                                @foreach($userConnectionRequests as $conn)
                                                    <div id="conn_req_row_{{ $conn->id }}" class="py-2.5 flex items-center justify-between gap-2 text-left transition-all">
                                                        <div class="flex items-center space-x-2 min-w-0">
                                                            <div class="w-7 h-7 rounded-full text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-2xs" style="background-color: var(--btn-primary-bg, #0A4744);">
                                                                {{ strtoupper(substr($conn->sender->first_name ?? 'U', 0, 1)) }}
                                                            </div>
                                                            <div class="min-w-0">
                                                                <div class="font-bold text-xs truncate" style="color: var(--text-heading, #0f172a);">{{ $conn->sender->name ?? 'Member' }}</div>
                                                                <div class="text-[10px] text-slate-400">{{ $conn->created_at->diffForHumans() }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-1.5 shrink-0">
                                                            <button type="button" onclick="respondConnectionRequest({{ $conn->id }}, 'accept', this)" class="px-2.5 py-1 text-white font-bold text-[10px] rounded-lg shadow-2xs transition hover:opacity-90" style="background-color: var(--btn-primary-bg, #0A4744);">
                                                                Accept
                                                            </button>
                                                            <button type="button" onclick="respondConnectionRequest({{ $conn->id }}, 'reject', this)" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] rounded-lg transition">
                                                                Reject
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(!auth()->check())
                        <div class="space-y-4 pt-4 border-t border-slate-200/60">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-secondary, #94a3b8);">
                                    <i class="fa-solid fa-bullhorn text-sky-600 mr-1.5"></i>Community Activity & Requests
                                </h3>
                            </div>

                            <!-- Login Prompt for Non-Logged In Users -->
                            <div class="p-6 rounded-2xl border text-center space-y-3 shadow-2xs transition" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0);">
                                <div class="w-11 h-11 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center text-lg mx-auto shadow-2xs">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-extrabold text-sm" style="color: var(--text-heading, #0f172a);">Member Activity Locked</h4>
                                    <p class="text-xs max-w-md mx-auto" style="color: var(--text-secondary, #64748b);">Log in to view Announcements, Service Requests, and Connection Requests associated with your card.</p>
                                </div>
                                <div class="pt-1">
                                    <button type="button" onclick="openBizcardLoginModal()" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center space-x-2">
                                        <i class="fa-solid fa-right-to-bracket"></i>
                                        <span>Log In to View Activity</span>
                                    </button>
                                </div>
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
            <p class="text-xs text-slate-500">Share via social media or copy direct card link</p>
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
            <button onclick="copyCardLink()" id="copyBtn" class="px-4 py-2.5 text-white font-bold text-xs rounded-xl transition shrink-0 shadow-sm" style="background-color: var(--btn-primary-bg, #0A4744);">
                Copy
            </button>
        </div>
    </div>
</div>

<!-- Install Card App Modal -->
<div id="installAppModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl relative border border-slate-100 transform transition-all scale-100">
        <button onclick="closeInstallAppModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center justify-center mx-auto shadow-sm text-2xl">
                <i class="fa-solid fa-mobile-screen-button"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Install {{ $userModel->first_name }}'s Card App</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Install as a standalone app on your mobile home screen. Opening the app will launch <strong>only {{ $userModel->name }}'s card</strong>!</p>
        </div>

        <!-- OS Selector Tabs (Android / iOS) -->
        <div class="flex p-1 bg-slate-100 rounded-xl space-x-1 text-xs font-bold">
            <button id="androidTabBtn" onclick="switchOsTab('android')" class="flex-1 py-2.5 rounded-lg transition flex items-center justify-center space-x-2 bg-white text-slate-900 shadow-2xs">
                <i class="fa-brands fa-android text-emerald-600 text-base"></i>
                <span>Android</span>
            </button>
            <button id="iosTabBtn" onclick="switchOsTab('ios')" class="flex-1 py-2.5 rounded-lg transition flex items-center justify-center space-x-2 text-slate-600 hover:text-slate-900">
                <i class="fa-brands fa-apple text-slate-800 text-base"></i>
                <span>iPhone / iOS</span>
            </button>
        </div>

        <!-- Android Tab Content -->
        <div id="androidContent" class="space-y-4">
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl space-y-2 text-left">
                <h4 class="text-xs font-bold text-emerald-900 uppercase flex items-center">
                    <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i> Fast App Installation
                </h4>
                <p class="text-xs text-emerald-800 leading-relaxed">Tap below to add {{ $userModel->first_name }}'s Digital Card directly onto your Android home screen.</p>
                <button id="pwaNativeInstallBtn" onclick="triggerPwaPrompt()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-download"></i>
                    <span>Install App on Android</span>
                </button>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-left space-y-2 text-xs text-slate-600">
                <p class="font-bold text-slate-800">Manual Steps (Chrome / Edge):</p>
                <ol class="list-decimal list-inside space-y-1 text-slate-600">
                    <li>Tap the <strong>⋮ (3 dots)</strong> menu in browser.</li>
                    <li>Tap <strong>"Install App"</strong> or <strong>"Add to Home screen"</strong>.</li>
                    <li>Confirm <strong>Install</strong>. Opening the app loads only this card!</li>
                </ol>
            </div>
        </div>

        <!-- iOS Tab Content -->
        <div id="iosContent" class="space-y-4 hidden">
            <div class="p-4 bg-sky-50 border border-sky-200 rounded-2xl space-y-2 text-left">
                <h4 class="text-xs font-bold text-sky-900 uppercase flex items-center">
                    <i class="fa-brands fa-apple text-sky-700 mr-1.5"></i> iPhone / Safari Instructions
                </h4>
                <ol class="list-decimal list-inside space-y-2 text-xs text-sky-900 font-medium">
                    <li>Tap the <strong>Share</strong> button <i class="fa-solid fa-arrow-up-from-bracket text-sky-600 mx-1"></i> at the bottom of Safari.</li>
                    <li>Scroll down and tap <strong>"Add to Home Screen"</strong> <i class="fa-regular fa-square-plus text-sky-600 mx-1"></i>.</li>
                    <li>Tap <strong>Add</strong> at top right. Launch the app from your iPhone screen anytime!</li>
                </ol>
            </div>
        </div>

        <button onclick="closeInstallAppModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
            Close
        </button>
    </div>
</div>

<!-- In-Page Login Modal for Business Card Page -->
<div id="bizcardLoginModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-[28px] max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl relative border border-slate-100 transform transition-all">
        <button type="button" onclick="closeBizcardLoginModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-sky-50 border border-sky-200 text-sky-600 flex items-center justify-center mx-auto shadow-2xs text-2xl">
                <i class="fa-solid fa-right-to-bracket"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Member Account Login</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Enter your credentials to unlock your announcements and activity portal.</p>
        </div>

        <div id="bizcard_login_error" class="hidden p-3 bg-rose-50 text-rose-800 border border-rose-200 rounded-xl text-xs font-bold text-center"></div>

        <form id="bizcardLoginForm" onsubmit="handleBizcardLogin(event)" class="space-y-4">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address *</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    <input type="email" name="email" id="bizcard_login_email" required placeholder="you@example.com" class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password *</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    <input type="password" name="password" id="bizcard_login_password" required placeholder="••••••••" class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="font-bold text-slate-600">Remember Me</span>
                </label>
                <a href="{{ route('password.request') }}" target="_blank" class="font-bold text-sky-600 hover:underline">Forgot Password?</a>
            </div>

            <div class="pt-2">
                <button type="submit" id="bizcard_login_submit_btn" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold text-xs rounded-xl shadow-sm transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Log In Now</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let deferredPrompt = null;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
    });

    function openBizcardLoginModal() {
        const modal = document.getElementById('bizcardLoginModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBizcardLoginModal() {
        const modal = document.getElementById('bizcardLoginModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        const err = document.getElementById('bizcard_login_error');
        if (err) err.classList.add('hidden');
    }

    async function handleBizcardLogin(e) {
        e.preventDefault();
        const errorDiv = document.getElementById('bizcard_login_error');
        const submitBtn = document.getElementById('bizcard_login_submit_btn');

        errorDiv.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Logging in...</span>`;

        const form = document.getElementById('bizcardLoginForm');
        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('login') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.reload();
            } else {
                errorDiv.innerText = data.message || 'The provided credentials do not match our records.';
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = `<i class="fa-solid fa-right-to-bracket"></i> <span>Log In Now</span>`;
            }
        } catch (err) {
            errorDiv.innerText = 'Login failed. Please check your credentials or internet connection.';
            errorDiv.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = `<i class="fa-solid fa-right-to-bracket"></i> <span>Log In Now</span>`;
        }
    }

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

    function openInstallAppModal() {
        const modal = document.getElementById('installAppModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Auto-detect iOS vs Android
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            switchOsTab('ios');
        } else {
            switchOsTab('android');
        }
    }

    function closeInstallAppModal() {
        const modal = document.getElementById('installAppModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    function switchOsTab(os) {
        const androidBtn = document.getElementById('androidTabBtn');
        const iosBtn = document.getElementById('iosTabBtn');
        const androidContent = document.getElementById('androidContent');
        const iosContent = document.getElementById('iosContent');

        if (os === 'android') {
            androidBtn.className = "flex-1 py-2.5 rounded-lg transition flex items-center justify-center space-x-2 bg-white text-slate-900 shadow-2xs font-bold";
            iosBtn.className = "flex-1 py-2.5 rounded-lg transition flex items-center justify-center space-x-2 text-slate-600 hover:text-slate-900 font-semibold";
            androidContent.classList.remove('hidden');
            iosContent.classList.add('hidden');
        } else {
            iosBtn.className = "flex-1 py-2.5 rounded-lg transition flex items-center justify-center space-x-2 bg-white text-slate-900 shadow-2xs font-bold";
            androidBtn.className = "flex-1 py-2.5 rounded-lg transition flex items-center justify-center space-x-2 text-slate-600 hover:text-slate-900 font-semibold";
            iosContent.classList.remove('hidden');
            androidContent.classList.add('hidden');
        }
    }

    function triggerPwaPrompt() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the PWA install prompt');
                }
                deferredPrompt = null;
            });
        } else {
            alert('To install on Android:\n1. Tap browser menu (⋮)\n2. Select "Install App" or "Add to Home screen".');
        }
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
            copyBtn.style.backgroundColor = 'var(--btn-primary-bg, #0A4744)';
        }, 2000);
    }

    async function markAnnouncementAsRead(id, btn) {
        btn.disabled = true;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin text-emerald-600"></i>`;
        try {
            const url = "{{ url('/bizcard/announcements') }}/" + id + "/read";
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                const row = document.getElementById('announcement_row_' + id);
                if (row) row.remove();
                const container = document.getElementById('announcements_container');
                const remaining = container.querySelectorAll('[id^="announcement_row_"]');
                const badge = document.getElementById('announcement_count_badge');
                if (badge) badge.innerText = remaining.length + ' Unread';
                if (remaining.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-500 italic py-2">No unread announcements found.</p>`;
                }
            } else {
                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-check text-emerald-600"></i><span>Mark as Read</span>`;
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = `<i class="fa-solid fa-check text-emerald-600"></i><span>Mark as Read</span>`;
        }
    }

    async function respondServiceRequest(id, action, btn) {
        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i>`;
        try {
            const url = action === 'approve' 
                ? "{{ url('/member/services/requests') }}/" + id + "/approve"
                : "{{ url('/member/services/requests') }}/" + id + "/reject";
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                const row = document.getElementById('service_req_row_' + id);
                if (row) row.remove();
                const container = document.getElementById('service_requests_container');
                const remaining = container.querySelectorAll('[id^="service_req_row_"]');
                if (remaining.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-500 italic py-2">No pending service requests.</p>`;
                }
            } else {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }

    async function respondConnectionRequest(id, action, btn) {
        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i>`;
        try {
            const url = action === 'accept'
                ? "{{ url('/member/connections') }}/" + id + "/accept"
                : "{{ url('/member/connections') }}/" + id + "/reject";
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                const row = document.getElementById('conn_req_row_' + id);
                if (row) row.remove();
                const container = document.getElementById('connection_requests_container');
                const remaining = container.querySelectorAll('[id^="conn_req_row_"]');
                if (remaining.length === 0) {
                    container.innerHTML = `<p class="text-xs text-slate-500 italic py-2">No pending connection requests.</p>`;
                }
            } else {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    }
</script>
@endsection
