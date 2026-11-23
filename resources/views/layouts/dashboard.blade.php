@php
    $activeTheme = \App\Services\ThemeService::getActiveTheme();
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $activeTheme->slug }}" class="{{ $activeTheme->type }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    @php
        $gaId = config('services.google_analytics_id', env('GOOGLE_ANALYTICS_ID', 'G-309DTHMEG5'));
    @endphp
    @if($gaId)
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{ $gaId }}');
    </script>
    @endif

    <title>@yield('title', 'Dashboard') | Bizconn</title>
<link rel="icon" type="image/png" href="{{ asset('nlogo.png') }}">
    <!-- Dynamic Active Theme Variables -->
    <style id="theme-css-variables">
        :root,
        [data-theme] {
            {!! $activeTheme->toCssVariables() !!}
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--bg-page, #f8fafc);
            color: var(--text-primary, #0f172a);
        }

        aside {
            background-color: var(--card-bg, #ffffff);
            border-color: var(--border-color, #e2e8f0);
        }

        /* Primary Brand Accents & Buttons across all color variants */
        .bg-sky-600,
        .bg-blue-600,
        .bg-sky-500,
        .bg-blue-500,
        .bg-sky-700,
        .bg-blue-700,
        .bg-teal-600,
        .bg-teal-700,
        .bg-teal-500,
        .bg-emerald-600,
        .bg-emerald-700,
        .bg-emerald-500 {
            background-color: var(--btn-primary-bg, #0A4744) !important;
            color: var(--btn-primary-text, #ffffff) !important;
        }

        .bg-sky-600:hover,
        .hover\:bg-sky-700:hover,
        .bg-blue-600:hover,
        .hover\:bg-blue-700:hover,
        .hover\:bg-sky-600:hover,
        .bg-sky-500:hover,
        .hover\:bg-sky-500:hover,
        .bg-teal-600:hover,
        .hover\:bg-teal-700:hover,
        .bg-emerald-600:hover,
        .hover\:bg-emerald-700:hover,
        .hover\:bg-emerald-600:hover,
        .bg-emerald-500:hover,
        .hover\:bg-emerald-500:hover {
            background-color: var(--btn-primary-hover, #063331) !important;
        }

        .text-sky-600,
        .text-sky-700,
        .text-sky-500,
        .text-blue-600,
        .text-blue-700,
        .text-blue-500,
        .text-teal-600,
        .text-teal-700,
        .text-teal-500,
        .text-emerald-600,
        .text-emerald-700,
        .text-emerald-500 {
            color: var(--text-link, #0A4744) !important;
        }

        .text-sky-600:hover,
        .hover\:text-sky-600:hover,
        .text-blue-600:hover,
        .hover\:text-blue-600:hover,
        .hover\:text-sky-700:hover,
        .text-teal-600:hover,
        .hover\:text-teal-600:hover,
        .text-emerald-600:hover,
        .hover\:text-emerald-600:hover {
            color: var(--text-link-hover, #063331) !important;
        }

        .bg-sky-50,
        .bg-sky-100,
        .bg-blue-50,
        .bg-blue-100,
        .bg-teal-50,
        .bg-teal-100,
        .bg-emerald-50,
        .bg-emerald-100 {
            background-color: var(--badge-info-bg, #f0f7f6) !important;
            color: var(--badge-info-text, var(--text-primary, #0f172a)) !important;
        }

        .border-sky-500,
        .border-sky-600,
        .border-blue-500,
        .border-blue-600,
        .border-teal-500,
        .border-teal-600,
        .border-emerald-500,
        .border-emerald-600 {
            border-color: var(--btn-primary-bg, #0A4744) !important;
        }

        .border-sky-100,
        .border-sky-200,
        .border-blue-100,
        .border-blue-200,
        .border-teal-100,
        .border-teal-200,
        .border-emerald-100,
        .border-emerald-200 {
            border-color: var(--border-color, #e2e8f0) !important;
        }

        /* Inputs */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="url"],
        textarea,
        select {
            background-color: var(--input-bg, #ffffff) !important;
            border-color: var(--input-border, #cbd5e1) !important;
            color: var(--text-primary, #0f172a) !important;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="url"]:focus,
        textarea:focus,
        select:focus {
            border-color: var(--input-focus, var(--btn-primary-bg, #0A4744)) !important;
        }

        /* Tables */
        table thead {
            background-color: var(--table-header-bg, #f8fafc) !important;
            color: var(--table-header-text, #475569) !important;
        }

        table tbody tr {
            background-color: var(--table-row-bg, #ffffff) !important;
            color: var(--table-row-text, #0f172a) !important;
            border-color: var(--table-border, #e2e8f0) !important;
        }

        table tbody tr:nth-child(even) {
            background-color: var(--table-row-alt, #f1f5f9) !important;
        }

        /* Dark Mode Text & Surface Rules */
        html.dark,
        [data-theme*="dark"],
        body.dark {
            color-scheme: dark;
        }

        html.dark body,
        [data-theme*="dark"] body {
            background-color: var(--bg-page, #0f172a) !important;
            color: var(--text-primary, #f8fafc) !important;
        }

        html.dark .bg-white,
        [data-theme*="dark"] .bg-white {
            background-color: var(--bg-surface, #1e293b) !important;
        }

        html.dark .bg-slate-50,
        html.dark .bg-slate-100,
        [data-theme*="dark"] .bg-slate-50,
        [data-theme*="dark"] .bg-slate-100 {
            background-color: var(--bg-page, #0f172a) !important;
        }

        html.dark .text-slate-900,
        html.dark .text-slate-800,
        html.dark .text-black,
        [data-theme*="dark"] .text-slate-900,
        [data-theme*="dark"] .text-slate-800,
        [data-theme*="dark"] .text-black {
            color: var(--text-heading, #f8fafc) !important;
        }

        html.dark .text-slate-700,
        html.dark .text-slate-600,
        html.dark .text-slate-500,
        [data-theme*="dark"] .text-slate-700,
        [data-theme*="dark"] .text-slate-600,
        [data-theme*="dark"] .text-slate-500 {
            color: var(--text-secondary, #94a3b8) !important;
        }

        html.dark .border-slate-200,
        html.dark .border-slate-100,
        html.dark .border-slate-300,
        html.dark .border-sky-100,
        [data-theme*="dark"] .border-slate-200,
        [data-theme*="dark"] .border-slate-100,
        [data-theme*="dark"] .border-slate-300,
        [data-theme*="dark"] .border-sky-100 {
            border-color: var(--border-color, #334155) !important;
        }

        /* Mobile App Responsiveness (Strictly scoped to screens < 768px, Desktop 100% untouched) */
        @media (max-width: 767.98px) {
            /* Prevent iOS automatic zoom on inputs when user taps by setting 16px font size */
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="url"],
            input[type="number"],
            input[type="tel"],
            input[type="search"],
            textarea,
            select {
                font-size: 16px !important;
            }

            /* Prevent horizontal overflow across all mobile screens */
            html, body {
                overflow-x: hidden !important;
                width: 100% !important;
                max-width: 100vw !important;
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
            }

            /* Touch action manipulation for snappy 0ms tap response */
            button, a, input, select, textarea {
                touch-action: manipulation;
            }

            /* Smooth scroll for mobile tabs */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }
            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Safe area bottom padding for notch / home indicator */
            .mobile-safe-bottom {
                padding-bottom: max(1.5rem, env(safe-area-inset-bottom)) !important;
            }
        }
    </style>

    <!-- Fonts: Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body
    class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row antialiased selection:bg-slate-700 selection:text-white">

    @php
        $sidebarUnreadCount = auth()->check() ? auth()->user()->receivedMessages()->where('is_read', false)->count() : 0;
        $authUser = auth()->user();
        $adminGroup = null;
        if ($authUser && ($authUser->isGroupAdmin() || $authUser->isSuperAdmin())) {
            $adminGroup = $group ?? $activeGroup ?? $authUser->groups()->wherePivot('membership_role', 'group_admin')->first() ?? \App\Models\Group::first();
        }
    @endphp

    <!-- Mobile Top Header Bar (Native App Style Header) -->
    <header
        class="md:hidden bg-white/95 backdrop-blur-md border-b border-slate-200 px-3.5 py-2.5 flex items-center justify-between sticky top-0 z-40 shadow-xs">
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('nlogo.png') }}" alt="Bizconn Logo"
                class="h-8 w-auto rounded-lg object-contain shadow-2xs">
            <span class="font-extrabold text-sm text-slate-900 tracking-tight">Bizconn</span>
        </a>

        <div class="flex items-center space-x-1.5">
            @if(auth()->check())
                @php
                    $headerUnreadNotifs = auth()->user()->unreadNotificationsCount();
                @endphp
                <!-- Mobile Top Chat Icon -->
                <a href="{{ route('member.chat') }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-700 hover:bg-slate-100 relative transition"
                    title="Community Chat">
                    <i class="fa-solid fa-comments text-base"></i>
                    @if($sidebarUnreadCount > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </a>

                <!-- Mobile Top Notifications Bell -->
                <a href="{{ route('notifications.index') }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-700 hover:bg-slate-100 relative transition"
                    title="Notifications">
                    <i class="fa-solid fa-bell text-base"></i>
                    @if($headerUnreadNotifs > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-sky-600 rounded-full ring-2 ring-white" style="background-color: var(--btn-primary-bg, #0A4744);"></span>
                    @endif
                </a>

                <!-- Mobile Top User Avatar / Menu Toggle -->
                <button onclick="toggleMobileSidebar()" id="mobileMenuBtn" aria-label="Toggle Menu"
                    class="w-9 h-9 rounded-xl text-white font-extrabold text-xs uppercase flex items-center justify-center shadow-2xs transition active:scale-95 ml-1"
                    style="background-color: var(--btn-primary-bg, #0A4744);">
                    {{ strtoupper(substr($authUser->first_name ?? 'M', 0, 1) . substr($authUser->last_name ?? 'U', 0, 1)) }}
                </button>
            @else
                <button onclick="toggleMobileSidebar()" id="mobileMenuBtn" aria-label="Toggle Menu"
                    class="p-2 rounded-xl text-slate-800 hover:bg-slate-100 border border-slate-200 transition flex items-center justify-center">
                    <i class="fa-solid fa-bars text-lg" id="menuIcon"></i>
                </button>
            @endif
        </div>
    </header>

    <!-- Mobile Overlay Backdrop -->
    <div id="mobileBackdrop" onclick="closeMobileSidebar()"
        class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

    <!-- Sidebar Navigation Drawer (Collapsible on Mobile, Fixed/Sticky on Desktop) -->
    <aside id="sidebarNav"
        class="hidden md:flex w-full md:w-64 bg-white border-r border-slate-200 p-5 md:p-6 flex-col justify-between shrink-0 transition-all duration-300 z-50">
        <div class="space-y-1">
            <!-- Header for Mobile Drawer (Close Button & User Card) -->
            <div class="md:hidden pb-3 mb-2 border-b border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('nlogo.png') }}" alt="Bizconn Logo" class="h-7 w-auto rounded-lg">
                        <span class="font-bold text-sm text-slate-900">Bizconn Navigation</span>
                    </div>
                    <button onclick="closeMobileSidebar()"
                        class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition active:scale-95">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                @if(auth()->check())
                    <!-- Mobile User Profile Card in Drawer -->
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center space-x-3">
                        <div class="w-11 h-11 rounded-full text-white font-extrabold flex items-center justify-center text-sm uppercase shrink-0 shadow-2xs"
                            style="background-color: var(--btn-primary-bg, #0A4744);">
                            {{ strtoupper(substr($authUser->first_name ?? 'M', 0, 1) . substr($authUser->last_name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-grow">
                            <div class="font-bold text-sm text-slate-900 truncate">{{ $authUser->name }}</div>
                            <div class="text-[11px] text-slate-500 truncate">{{ $authUser->profession ?? 'Community Member' }}</div>
                            <div class="mt-1 flex items-center space-x-2">
                                <a href="{{ route('member.profile.edit') }}" onclick="closeMobileSidebar()"
                                    class="text-[11px] font-bold text-sky-600 hover:underline"
                                    style="color: var(--text-link, #0A4744);">
                                    Edit Profile &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @php
                $sidebarLogo = asset('nlogo.png');
                $targetGroupForLogo = $group ?? $currentAdminGroup ?? null;
                if ($targetGroupForLogo && $targetGroupForLogo->logo_url) {
                    $sidebarLogo = $targetGroupForLogo->logo_url;
                }
            @endphp
            <!-- Desktop Logo -->
            <a href="{{ url('/') }}" class="hidden md:flex items-center space-x-3 mb-6">
                <img src="{{ $sidebarLogo }}" alt="Community Logo"
                    class="h-10 w-auto max-w-[180px] max-h-12 rounded-xl object-contain shadow-2xs">
            </a>

            <!-- Navigation Links -->
            <nav class="space-y-1">
                @php
                    $authUser = auth()->user();
                    $isSuperAdminRoute = request()->is('super-admin*') || request()->routeIs('super_admin.*');
                    $isGroupAdminRoute = request()->is('group-admin*') || request()->routeIs('group_admin.*');

                    if ($authUser && $authUser->isSuperAdmin()) {
                        if ($isGroupAdminRoute) {
                            $sidebarMode = 'group_admin';
                        } else {
                            $sidebarMode = 'super_admin';
                        }
                    } elseif ($authUser && $authUser->isGroupAdmin()) {
                        $sidebarMode = 'group_admin';
                    } else {
                        $sidebarMode = 'member';
                    }

                    $navActiveStyle = 'background-color: var(--navbar-active, var(--btn-primary-bg, #0A4744)); color: var(--btn-primary-text, #ffffff); font-weight: 700;';
                @endphp

                @if($sidebarMode === 'super_admin')
                    <div class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider flex items-center justify-between"
                        style="color: var(--text-link, #0A4744);">
                        <span>Super Admin Panel</span>
                    </div>



                    @php $active = request()->routeIs('super_admin.dashboard') || request()->is('super-admin'); @endphp
                    <a href="{{ route('super_admin.dashboard') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-chart-pie w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Dashboard</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.groups*') && !request()->routeIs('super_admin.groups.pending_members*'); @endphp
                    <a href="{{ route('super_admin.groups.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-users-gear w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Communities</span>
                    </a>

                    @php
                        $active = request()->routeIs('super_admin.community_requests*');
                        $communityReqCount = \Illuminate\Support\Facades\Schema::hasTable('community_requests') ? \App\Models\CommunityRequest::where('status', 'pending')->count() : 0;
                    @endphp
                    <a href="{{ route('super_admin.community_requests.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-paper-plane w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Community Requests</span>
                        </div>
                        @if($communityReqCount > 0)
                            <span
                                class="px-2 py-0.5 bg-amber-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                {{ $communityReqCount }}
                            </span>
                        @endif
                    </a>

                    @php
                        $active = request()->routeIs('super_admin.groups.pending_members*');
                        $superPendingCount = \DB::table('group_user')->where('status', 'pending')->count();
                    @endphp
                    <a href="{{ route('super_admin.groups.pending_members') }}" onclick="closeMobileSidebar()"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-user-clock w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Pending Approvals</span>
                        </div>
                        @if($superPendingCount > 0)
                            <span
                                class="px-2 py-0.5 bg-amber-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                {{ $superPendingCount }}
                            </span>
                        @endif
                    </a>

                    @php $active = request()->routeIs('super_admin.group_admins*'); @endphp
                    <a href="{{ route('super_admin.group_admins.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-shield w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Group Admins</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.users*'); @endphp
                    <a href="{{ route('super_admin.users.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-users w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">All Users</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.events*'); @endphp
                    <a href="{{ route('super_admin.events.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-calendar-days w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Events</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.payments*'); @endphp
                    <a href="{{ route('super_admin.payments.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-credit-card w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Payments</span>
                    </a>

                    @php $active = request()->routeIs('announcements*'); @endphp
                    <a href="{{ route('announcements.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-bullhorn w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Announcements</span>
                    </a>

                    @php $active = request()->routeIs('notifications*'); @endphp
                    <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-bell w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Notifications</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.projects*'); @endphp
                    <a href="{{ route('super_admin.projects.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-folder-open w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Member Projects</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.cms*'); @endphp
                    <a href="{{ route('super_admin.cms.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-file-contract w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">CMS Pages</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.themes*'); @endphp
                    <a href="{{ route('super_admin.themes.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-palette w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Theme Manager</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.analytics*'); @endphp
                    <a href="{{ route('super_admin.analytics.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-chart-line w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Analytics & Visitors</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.blogs*'); @endphp
                    <a href="{{ route('super_admin.blogs.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-newspaper w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Blogs & Articles</span>
                    </a>

                @elseif($sidebarMode === 'group_admin')
                    @php
                        $myAdminGroups = $authUser->isSuperAdmin()
                            ? \App\Models\Group::all()
                            : $authUser->groups()->wherePivot('membership_role', 'group_admin')->get();
                        $currentAdminGroup = $group ?? $activeGroup ?? $myAdminGroups->first();
                    @endphp

                    <div class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider flex items-center justify-between"
                        style="color: var(--text-link, #0A4744);">
                        <span>Group Admin Panel</span>
                        @if($myAdminGroups->count() > 1)
                            <span class="text-[9px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-extrabold">
                                {{ $myAdminGroups->count() }} Communities
                            </span>
                        @endif
                    </div>

                    @if($myAdminGroups->count() > 1)
                        <!-- Sidebar Active Community Selector Dropdown -->
                        <div class="px-3 mb-3">
                            <label class="block text-[10px] font-extrabold uppercase text-slate-400 mb-1">Select Active
                                Community:</label>
                            <select onchange="window.location.href=this.value"
                                class="w-full text-xs font-bold py-2 px-2.5 rounded-xl border bg-slate-50 border-slate-200 text-slate-800 shadow-2xs focus:ring-2 focus:ring-sky-500 transition cursor-pointer">
                                @foreach($myAdminGroups as $mag)
                                    @php
                                        $selUrl = route('group_admin.promotion.index', $mag->id);
                                        if (request()->routeIs('group_admin.members.pending*')) {
                                            $selUrl = route('group_admin.members.pending', $mag->id);
                                        } elseif (request()->routeIs('group_admin.members*')) {
                                            $selUrl = route('group_admin.members.index', $mag->id);
                                        } elseif (request()->routeIs('group_admin.events*')) {
                                            $selUrl = route('group_admin.events.index', $mag->id);
                                        } elseif (request()->routeIs('group_admin.payments*')) {
                                            $selUrl = route('group_admin.payments.index', $mag->id);
                                        } elseif (request()->routeIs('group_admin.surveys*')) {
                                            $selUrl = route('group_admin.surveys.index', $mag->id);
                                        } elseif (request()->routeIs('group_admin.promotion*')) {
                                            $selUrl = route('group_admin.promotion.index', $mag->id);
                                        } elseif (request()->routeIs('group_admin.dashboard')) {
                                            $selUrl = route('group_admin.dashboard', ['group_id' => $mag->id]);
                                        }
                                    @endphp
                                    <option value="{{ $selUrl }}" {{ ($currentAdminGroup && $currentAdminGroup->id === $mag->id) ? 'selected' : '' }}>
                                        {{ $mag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($currentAdminGroup)
                        <div class="px-3 mb-2">
                            <span
                                class="inline-block text-[11px] font-extrabold px-2.5 py-1 rounded-lg bg-sky-50 text-sky-800 border border-sky-200 truncate max-w-full"
                                title="{{ $currentAdminGroup->name }}">
                                <i class="fa-solid fa-users text-[10px] mr-1"></i> {{ $currentAdminGroup->name }}
                            </span>
                        </div>
                    @endif

                    @php $active = request()->routeIs('group_admin.dashboard') || request()->is('group-admin'); @endphp
                    <a href="{{ route('group_admin.dashboard', ['group_id' => $currentAdminGroup?->id]) }}"
                        onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-chart-line w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Dashboard</span>
                    </a>

                    @php $active = request()->routeIs('group_admin.communities*'); @endphp
                    <a href="{{ route('group_admin.communities.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-layer-group w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Manage Communities</span>
                    </a>

                    @if($currentAdminGroup)
                        @php
                            $active = request()->routeIs('group_admin.members.pending*');
                            $pendingCount = $currentAdminGroup ? $currentAdminGroup->pendingMembers()->count() : 0;
                        @endphp
                        <a href="{{ route('group_admin.members.pending', $currentAdminGroup->id) }}"
                            onclick="closeMobileSidebar()"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-user-clock w-5"
                                    style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                <span style="{{ $active ? 'color: #ffffff;' : '' }}">Pending Approvals</span>
                            </div>
                            @if($pendingCount > 0)
                                <span
                                    class="px-2 py-0.5 bg-amber-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>

                        @php $active = request()->routeIs('group_admin.members*') && !request()->routeIs('group_admin.members.pending*'); @endphp
                        <a href="{{ route('group_admin.members.index', $currentAdminGroup->id) }}"
                            onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-users w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Members List</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.events*'); @endphp
                        <a href="{{ route('group_admin.events.index', $currentAdminGroup->id) }}" onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-calendar-days w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Events</span>
                        </a>

                        @php $active = request()->routeIs('announcements*'); @endphp
                        <a href="{{ route('announcements.index') }}" onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-bullhorn w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Announcements</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.surveys*'); @endphp
                        <a href="{{ route('group_admin.surveys.index', $currentAdminGroup->id) }}"
                            onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-square-poll-vertical w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Surveys</span>
                        </a>
                        @php $active = request()->routeIs('group_admin.promotion*'); @endphp
                        <a href="{{ route('group_admin.promotion.index', $currentAdminGroup->id) }}"
                            onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-qrcode w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Promote & QR</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.connections*'); @endphp
                        <!-- <a href="{{ route('group_admin.connections') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-user-group w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Connections</span>
                                    </div>
                                    @php
                                        $pendingAdminConnCount = auth()->check() ? \App\Models\Connection::where('receiver_id', auth()->id())->where('status', 'pending')->count() : 0;
                                    @endphp
                                    @if($pendingAdminConnCount > 0)
                                        <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                            {{ $pendingAdminConnCount }}
                                        </span>
                                    @endif
                                </a> -->

                        @php $active = request()->routeIs('group_admin.payments*'); @endphp
                        <a href="{{ route('group_admin.payments.index', $currentAdminGroup->id) }}"
                            onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-credit-card w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Payments</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.themes*'); @endphp
                        <a href="{{ route('group_admin.themes.index', $currentAdminGroup->id) }}" onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-palette w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Community Themes</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.policies*'); @endphp
                        <a href="{{ route('group_admin.policies.index') }}" onclick="closeMobileSidebar()"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-file-contract w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Terms & Policies</span>
                        </a>



                        <!-- @php $active = request()->routeIs('group_admin.services*'); @endphp
                                <a href="{{ route('group_admin.services.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                                    <i class="fa-solid fa-handshake w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                    <span style="{{ $active ? 'color: #ffffff;' : '' }}"> Manage Services</span>
                                </a> -->

                        <!-- @php $active = request()->routeIs('group_admin.projects*'); @endphp
                                <a href="{{ route('group_admin.projects.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                                    <i class="fa-solid fa-briefcase w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                    <span style="{{ $active ? 'color: #ffffff;' : '' }}">Member Projects</span>
                                </a> -->



                        @php $active = request()->routeIs('member.chat*'); @endphp
                        <a href="{{ route('member.chat') }}" onclick="closeMobileSidebar()"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                            style="{{ $active ? $navActiveStyle : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-comments w-5"
                                    style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                <span style="{{ $active ? 'color: #ffffff;' : '' }}">Community Chat</span>
                            </div>
                            @if($sidebarUnreadCount > 0)
                                <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                    {{ $sidebarUnreadCount }}
                                </span>
                            @endif
                        </a>

                        <!-- @php $active = request()->routeIs('notifications*'); @endphp
                                <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                                    <i class="fa-solid fa-bell w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                    <span style="{{ $active ? 'color: #ffffff;' : '' }}">Notifications</span>
                                </a> -->



                        <a href="{{ route('bizcard.show', auth()->id()) }}" target="_blank" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid fa-id-card w-5" style="color: var(--text-link, #0A4744);"></i>
                                        <span>Business Card</span>
                                    </div>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                                </a>

                        @php $active = request()->routeIs('group_admin.profile*'); @endphp
                                <a href="{{ route('group_admin.profile') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                                    <i class="fa-solid fa-user-gear w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                    <span style="{{ $active ? 'color: #ffffff;' : '' }}">My Profile</span>
                                </a>
                    @endif

                @else
                    @if($authUser && $authUser->isSuperAdmin())
                        <a href="{{ route('super_admin.dashboard') }}" onclick="closeMobileSidebar()"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-user-shield text-purple-600"></i>
                                <span>Super Admin Panel</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @elseif($authUser && $authUser->isGroupAdmin())
                        <a href="{{ route('group_admin.dashboard') }}" onclick="closeMobileSidebar()"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 transition mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-users-gear text-sky-600"></i>
                                <span>Group Admin Panel</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @endif

                    <div class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider"
                        style="color: var(--text-link, #0A4744);">Member Area</div>

                    @php $active = request()->routeIs('member.dashboard') || request()->is('dashboard'); @endphp
                    <a href="{{ route('member.dashboard') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-gauge-high w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Dashboard</span>
                    </a>

                    @php $active = request()->routeIs('member.directory*'); @endphp
                    <a href="{{ route('member.directory') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-address-book w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Member Directory</span>
                    </a>

                    @php $active = request()->routeIs('member.connections*'); @endphp
                    <a href="{{ route('member.connections') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-group w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Connections</span>
                    </a>

                    @php $active = request()->routeIs('member.chat*'); @endphp
                    <a href="{{ route('member.chat') }}" onclick="closeMobileSidebar()"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-comments w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Community Chat</span>
                        </div>
                        @if($sidebarUnreadCount > 0)
                            <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                {{ $sidebarUnreadCount }}
                            </span>
                        @endif
                    </a>

                    @php $active = request()->routeIs('notifications*'); @endphp
                    <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-bell w-5"
                                style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Notifications</span>
                        </div>
                        @if(auth()->check() && auth()->user()->unreadNotificationsCount() > 0)
                            <span class="px-2 py-0.5 bg-sky-600 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                {{ auth()->user()->unreadNotificationsCount() }}
                            </span>
                        @endif
                    </a>

                    @php $active = request()->routeIs('member.surveys*'); @endphp
                    <a href="{{ route('member.surveys.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-square-poll-vertical w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Surveys & Feedback</span>
                    </a>

                    @php $active = request()->routeIs('member.profile*'); @endphp
                    <a href="{{ route('member.profile.edit') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-gear w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">My Profile</span>
                    </a>

                    @php $active = request()->routeIs('member.projects*'); @endphp
                    <a href="{{ route('member.projects.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-briefcase w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">My Projects</span>
                    </a>

                    @php $active = request()->routeIs('member.services*'); @endphp
                    <a href="{{ route('member.services.index') }}" onclick="closeMobileSidebar()"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-handshake w-5"
                            style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Services</span>
                    </a>

                    <a href="{{ route('bizcard.show', auth()->id()) }}" target="_blank" onclick="closeMobileSidebar()"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-id-card w-5" style="color: var(--text-link, #0A4744);"></i>
                            <span>Business Card</span>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                    </a>

                @endif

                <!-- User Profile & Logout Button (Inside Nav Menu) -->
                <div class="pt-4 mt-4 border-t border-slate-200 space-y-2">
                    <div class="flex items-center space-x-3 px-3 py-1">
                        <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-xs uppercase shrink-0 shadow-2xs"
                            style="background-color: var(--btn-primary-bg, #0A4744);">
                            {{ substr(auth()->user()->first_name ?? 'M', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-grow">
                            <div class="font-bold text-sm text-slate-900 truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->profession ?? 'Member' }}
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" onclick="closeMobileSidebar()"
                            class="w-full text-left px-3.5 py-2.5 text-sm text-rose-600 hover:bg-rose-50 rounded-xl transition font-bold flex items-center space-x-3">
                            <i class="fa-solid fa-right-from-bracket text-sm w-5 text-rose-500"></i>
                            <span>Log out</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main View Content Area -->
    <main class="flex-grow p-3 sm:p-4 md:p-5 max-w-7xl w-full overflow-y-auto pb-24 md:pb-6">
        @if(session('success'))
            <div
                class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div
                class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-2xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')

        <!-- Dashboard Short Footer -->
        <footer class="mt-12 pt-6 border-t border-slate-200 text-center text-xs text-slate-500 font-medium space-y-1">
            <p class="font-bold text-slate-700">Developed and Powered by <strong class="text-sky-600">Nexteck 2026©</strong></p>
            <p class="space-x-3 text-[11px] text-slate-400 pt-0.5">
                <span><i class="fa-solid fa-envelope mr-1 text-sky-600"></i><a href="mailto:info@thenexteck.com" class="hover:underline text-slate-600">info@thenexteck.com</a></span>
                <span>&bull;</span>
                <span><i class="fa-solid fa-phone mr-1 text-emerald-600"></i><a href="tel:+447879175585" class="hover:underline text-slate-600">+44 7879175585</a></span>
            </p>
        </footer>

    </main>

    @if(auth()->check())
    <!-- App-Like Mobile Bottom Navigation Bar (Hidden on Desktop: md:hidden) -->
    <nav id="mobileBottomNav" class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 pt-1 pb-[calc(0.4rem+env(safe-area-inset-bottom))] transition-all">
        <div class="grid grid-cols-5 items-center gap-1 max-w-md mx-auto text-center">
            <!-- 1. Home / Dashboard -->
            @php $isDash = request()->routeIs('member.dashboard') || request()->is('dashboard') || request()->routeIs('group_admin.dashboard') || request()->routeIs('super_admin.dashboard'); @endphp
            <a href="{{ $authUser->isSuperAdmin() ? route('super_admin.dashboard') : ($authUser->isGroupAdmin() ? route('group_admin.dashboard') : route('member.dashboard')) }}" 
               class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-95 {{ $isDash ? 'font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}"
               style="{{ $isDash ? 'color: var(--text-link, #0A4744);' : '' }}">
                <i class="fa-solid fa-gauge-high text-lg mb-0.5 {{ $isDash ? 'scale-110' : '' }} transition-transform"></i>
                <span class="text-[10px] tracking-tight">Home</span>
            </a>

            <!-- 2. Members / Directory -->
            @php $isDir = request()->routeIs('member.directory*'); @endphp
            <a href="{{ route('member.directory') }}" 
               class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-95 {{ $isDir ? 'font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}"
               style="{{ $isDir ? 'color: var(--text-link, #0A4744);' : '' }}">
                <i class="fa-solid fa-users text-lg mb-0.5 {{ $isDir ? 'scale-110' : '' }} transition-transform"></i>
                <span class="text-[10px] tracking-tight">Members</span>
            </a>

            <!-- 3. Community Chat -->
            @php $isChat = request()->routeIs('member.chat*'); @endphp
            <a href="{{ route('member.chat') }}" 
               class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-95 relative {{ $isChat ? 'font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}"
               style="{{ $isChat ? 'color: var(--text-link, #0A4744);' : '' }}">
                <div class="relative inline-block">
                    <i class="fa-solid fa-comments text-lg mb-0.5 {{ $isChat ? 'scale-110' : '' }} transition-transform"></i>
                    @if($sidebarUnreadCount > 0)
                        <span class="absolute -top-1 -right-2 px-1.5 py-0.2 bg-rose-500 text-white text-[9px] font-black rounded-full ring-2 ring-white">
                            {{ $sidebarUnreadCount > 9 ? '9+' : $sidebarUnreadCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] tracking-tight">Chat</span>
            </a>

            <!-- 4. Alerts / Notifications -->
            @php 
                $isNotif = request()->routeIs('notifications*');
                $unreadNotifCount = auth()->user()->unreadNotificationsCount();
            @endphp
            <a href="{{ route('notifications.index') }}" 
               class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl transition active:scale-95 relative {{ $isNotif ? 'font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}"
               style="{{ $isNotif ? 'color: var(--text-link, #0A4744);' : '' }}">
                <div class="relative inline-block">
                    <i class="fa-solid fa-bell text-lg mb-0.5 {{ $isNotif ? 'scale-110' : '' }} transition-transform"></i>
                    @if($unreadNotifCount > 0)
                        <span class="absolute -top-1 -right-2 px-1.5 py-0.2 text-white text-[9px] font-black rounded-full ring-2 ring-white"
                              style="background-color: var(--btn-primary-bg, #0A4744);">
                            {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] tracking-tight">Alerts</span>
            </a>

            <!-- 5. App Menu Drawer -->
            <button onclick="toggleMobileSidebar()" 
                    type="button"
                    aria-label="Toggle App Menu" 
                    class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-slate-500 font-medium hover:text-slate-900 transition active:scale-95">
                <i class="fa-solid fa-bars text-lg mb-0.5"></i>
                <span class="text-[10px] tracking-tight">Menu</span>
            </button>
        </div>
    </nav>
    @endif

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebarNav');
            const backdrop = document.getElementById('mobileBackdrop');

            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'w-80', 'max-w-[85vw]', 'flex', 'shadow-2xl', 'overflow-y-auto', 'z-50');
                if (backdrop) backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                closeMobileSidebar();
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebarNav');
            const backdrop = document.getElementById('mobileBackdrop');

            if (sidebar) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'w-80', 'max-w-[85vw]', 'flex', 'shadow-2xl', 'overflow-y-auto', 'z-50');
            }
            if (backdrop) {
                backdrop.classList.add('hidden');
            }
            document.body.style.overflow = '';
        }
    </script>

    @stack('scripts')
</body>

</html>