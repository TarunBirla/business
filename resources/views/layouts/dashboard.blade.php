@php
    $activeTheme = \App\Services\ThemeService::getActiveTheme();
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $activeTheme->slug }}" class="{{ $activeTheme->type }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Dashboard') - Community UK</title>

    <!-- Dynamic Active Theme Variables -->
    <style id="theme-css-variables">
        :root, [data-theme] {
{!! $activeTheme->toCssVariables() !!}
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
        }

        aside {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }

        /* Primary Brand Accents & Links */
        .bg-sky-600, .bg-blue-600, .bg-sky-500, .bg-blue-500, .bg-sky-700, .bg-blue-700 {
            background-color: var(--btn-primary-bg, #0A4744) !important;
            color: var(--btn-primary-text, #ffffff) !important;
        }

        .bg-sky-600:hover, .hover\:bg-sky-700:hover, .bg-blue-600:hover, .hover\:bg-blue-700:hover,
        .hover\:bg-sky-600:hover, .bg-sky-500:hover, .hover\:bg-sky-500:hover, .hover\:bg-sky-100:hover {
            background-color: var(--btn-primary-hover, #063331) !important;
        }

        .text-sky-600, .text-sky-700, .text-sky-500, .text-blue-600, .text-blue-700, .text-blue-500 {
            color: var(--text-link, #0A4744) !important;
        }

        .text-sky-600:hover, .hover\:text-sky-600:hover, .text-blue-600:hover, .hover\:text-blue-600:hover,
        .hover\:text-sky-700:hover {
            color: var(--text-link-hover, #063331) !important;
        }

        .bg-sky-50, .bg-sky-100, .bg-blue-50, .bg-blue-100 {
            background-color: #f0f7f6 !important;
        }

        .border-sky-500, .border-sky-600, .border-blue-500, .border-blue-600 {
            border-color: var(--btn-primary-bg, #0A4744) !important;
        }

        .border-sky-100, .border-sky-200, .border-blue-100, .border-blue-200 {
            border-color: #cce5e3 !important;
        }

        /* Inputs */
        input[type="text"], input[type="email"], input[type="password"], input[type="url"], textarea, select {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
        }

        /* Dark Mode Text & Surface Rules */
        html.dark, [data-theme*="dark"], body.dark {
            color-scheme: dark;
        }

        html.dark body, [data-theme*="dark"] body {
            background-color: var(--bg-page, #0f172a) !important;
            color: var(--text-primary, #f8fafc) !important;
        }

        html.dark .bg-white, [data-theme*="dark"] .bg-white {
            background-color: var(--bg-surface, #1e293b) !important;
        }

        html.dark .bg-slate-50, html.dark .bg-slate-100,
        [data-theme*="dark"] .bg-slate-50, [data-theme*="dark"] .bg-slate-100 {
            background-color: var(--bg-page, #0f172a) !important;
        }

        html.dark .text-slate-900, html.dark .text-slate-800, html.dark .text-black,
        [data-theme*="dark"] .text-slate-900, [data-theme*="dark"] .text-slate-800, [data-theme*="dark"] .text-black {
            color: var(--text-heading, #f8fafc) !important;
        }

        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500,
        [data-theme*="dark"] .text-slate-700, [data-theme*="dark"] .text-slate-600, [data-theme*="dark"] .text-slate-500 {
            color: var(--text-secondary, #94a3b8) !important;
        }

        html.dark .border-slate-200, html.dark .border-slate-100, html.dark .border-sky-100,
        [data-theme*="dark"] .border-slate-200, [data-theme*="dark"] .border-slate-100, [data-theme*="dark"] .border-sky-100 {
            border-color: var(--border-color, #334155) !important;
        }
    </style>

    <!-- Fonts: Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row antialiased selection:bg-slate-700 selection:text-white">

    @php
        $sidebarUnreadCount = auth()->check() ? auth()->user()->receivedMessages()->where('is_read', false)->count() : 0;
        $authUser = auth()->user();
        $adminGroup = null;
        if ($authUser && ($authUser->isGroupAdmin() || $authUser->isSuperAdmin())) {
            $adminGroup = $group ?? $activeGroup ?? $authUser->groups()->wherePivot('membership_role', 'group_admin')->first() ?? \App\Models\Group::first();
        }
    @endphp

    <!-- Mobile Top Header Bar -->
    <header class="md:hidden bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40 shadow-xs">
        <a href="{{ url('/') }}" class="flex items-center space-x-2.5">
            <img src="{{ asset('logo.jpeg') }}" alt="Community Logo" class="h-9 w-auto rounded-lg object-contain shadow-2xs">
            <span class="font-extrabold text-sm text-slate-900 tracking-tight">Community UK</span>
        </a>

        <div class="flex items-center space-x-2">
            @if(auth()->check())
                <a href="{{ route('member.chat') }}" class="p-2 text-slate-600 hover:text-slate-900 relative" title="Community Chat">
                    <i class="fa-solid fa-comments text-lg"></i>
                    @if($sidebarUnreadCount > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </a>
            @endif

            <!-- Mobile Hamburger Toggle Button -->
            <button onclick="toggleMobileSidebar()" id="mobileMenuBtn" aria-label="Toggle Menu" class="p-2 rounded-xl text-slate-800 hover:bg-slate-100 border border-slate-200 transition flex items-center justify-center">
                <i class="fa-solid fa-bars text-lg" id="menuIcon"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Overlay Backdrop -->
    <div id="mobileBackdrop" onclick="closeMobileSidebar()" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

    <!-- Sidebar Navigation Drawer (Collapsible on Mobile, Fixed/Sticky on Desktop) -->
    <aside id="sidebarNav" class="hidden md:flex w-full md:w-64 bg-white border-r border-slate-200 p-5 md:p-6 flex-col justify-between shrink-0 transition-all duration-300 z-50">
        <div class="space-y-1">
            <!-- Header for Mobile Drawer (Close Button) -->
            <div class="flex items-center justify-between md:hidden pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-8 w-auto rounded-lg">
                    <span class="font-bold text-sm text-slate-900">Navigation Menu</span>
                </div>
                <button onclick="closeMobileSidebar()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Desktop Logo -->
            <a href="{{ url('/') }}" class="hidden md:flex items-center space-x-3 mb-6">
                <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-10 w-auto rounded-xl object-contain shadow-2xs">
            </a>

            <!-- Navigation Links -->
            <nav class="space-y-1">
                @php
                    $authUser = auth()->user();
                    $isSuperAdminRoute = request()->is('super-admin*') || request()->routeIs('super_admin.*');
                    $isGroupAdminRoute = request()->is('group-admin*') || request()->routeIs('group_admin.*');
                    $isMemberRoute     = request()->is('member*') || request()->routeIs('member.*') || request()->is('dashboard');

                    if ($authUser && $authUser->isSuperAdmin() && $isSuperAdminRoute) {
                        $sidebarMode = 'super_admin';
                    } elseif ($authUser && $authUser->isGroupAdmin() && $isGroupAdminRoute) {
                        $sidebarMode = 'group_admin';
                    } elseif ($isMemberRoute) {
                        $sidebarMode = 'member';
                    } else {
                        if ($authUser && $authUser->isSuperAdmin()) {
                            $sidebarMode = 'super_admin';
                        } elseif ($authUser && $authUser->isGroupAdmin()) {
                            $sidebarMode = 'group_admin';
                        } else {
                            $sidebarMode = 'member';
                        }
                    }

                    $navActiveStyle = 'background-color: #0A4744; color: #ffffff; font-weight: 700;';
                @endphp

                @if($sidebarMode === 'super_admin')
                    <div class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider flex items-center justify-between" style="color: var(--text-link, #0A4744);">
                        <span>Super Admin Panel</span>
                    </div>

                    <a href="{{ route('member.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2 rounded-xl font-bold text-xs bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition mb-3">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-user"></i>
                            <span>Switch to Member Area</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>

                    @php $active = request()->routeIs('super_admin.dashboard') || request()->is('super-admin'); @endphp
                    <a href="{{ route('super_admin.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-chart-pie w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Dashboard</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.groups*') && !request()->routeIs('super_admin.groups.pending_members*'); @endphp
                    <a href="{{ route('super_admin.groups.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-users-gear w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Communities</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.groups.pending_members*'); @endphp
                    <a href="{{ route('super_admin.groups.pending_members') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-clock w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Pending Approvals</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.group_admins*'); @endphp
                    <a href="{{ route('super_admin.group_admins.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-shield w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Group Admins</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.users*'); @endphp
                    <a href="{{ route('super_admin.users.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-users w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">All Users</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.events*'); @endphp
                    <a href="{{ route('super_admin.events.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-calendar-days w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Events</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.payments*'); @endphp
                    <a href="{{ route('super_admin.payments.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-credit-card w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Payments</span>
                    </a>

                    @php $active = request()->routeIs('announcements*'); @endphp
                    <a href="{{ route('announcements.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-bullhorn w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Announcements</span>
                    </a>

                    @php $active = request()->routeIs('notifications*'); @endphp
                    <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-bell w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Notifications</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.projects*'); @endphp
                    <a href="{{ route('super_admin.projects.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-folder-open w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Member Projects</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.cms*'); @endphp
                    <a href="{{ route('super_admin.cms.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-file-contract w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">CMS Pages</span>
                    </a>

                    @php $active = request()->routeIs('super_admin.themes*'); @endphp
                    <a href="{{ route('super_admin.themes.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-palette w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Theme Manager</span>
                    </a>

                @elseif($sidebarMode === 'group_admin')
                    <div class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider flex items-center justify-between" style="color: var(--text-link, #0A4744);">
                        <span>Group Admin Panel</span>
                    </div>

                    <a href="{{ route('member.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2 rounded-xl font-bold text-xs bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 transition mb-3">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-user"></i>
                            <span>Switch to Member Area</span>
                        </div>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>

                    @php $active = request()->routeIs('group_admin.dashboard') || request()->is('group-admin'); @endphp
                    <a href="{{ route('group_admin.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-chart-line w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Dashboard</span>
                    </a>

                    @php $active = request()->routeIs('group_admin.communities*'); @endphp
                    <a href="{{ route('group_admin.communities.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-layer-group w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Manage Communities</span>
                    </a>

                    @if($adminGroup)
                        @php $active = request()->routeIs('group_admin.members.pending*'); @endphp
                        <a href="{{ route('group_admin.members.pending', $adminGroup->id) }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-user-clock w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Pending Approvals</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.members*') && !request()->routeIs('group_admin.members.pending*'); @endphp
                        <a href="{{ route('group_admin.members.index', $adminGroup->id) }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-users w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Members List</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.events*'); @endphp
                        <a href="{{ route('group_admin.events.index', $adminGroup->id) }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-calendar-days w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Events</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.payments*'); @endphp
                        <a href="{{ route('group_admin.payments.index', $adminGroup->id) }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-credit-card w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Payments</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.services*'); @endphp
                        <a href="{{ route('group_admin.services.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-handshake w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Services</span>
                        </a>

                        @php $active = request()->routeIs('announcements*'); @endphp
                        <a href="{{ route('announcements.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-bullhorn w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Announcements</span>
                        </a>

                        @php $active = request()->routeIs('member.chat*'); @endphp
                        <a href="{{ route('member.chat') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-comments w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                                <span style="{{ $active ? 'color: #ffffff;' : '' }}">Community Chat</span>
                            </div>
                            @if($sidebarUnreadCount > 0)
                                <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                    {{ $sidebarUnreadCount }}
                                </span>
                            @endif
                        </a>

                        @php $active = request()->routeIs('notifications*'); @endphp
                        <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-bell w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Notifications</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.promotion*'); @endphp
                        <a href="{{ route('group_admin.promotion.index', $adminGroup->id) }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-qrcode w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Promote & QR</span>
                        </a>

                        @php $active = request()->routeIs('group_admin.profile*'); @endphp
                        <a href="{{ route('group_admin.profile') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                            <i class="fa-solid fa-user-gear w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">My Profile</span>
                        </a>
                    @endif

                @else
                    @if($authUser && $authUser->isSuperAdmin())
                        <a href="{{ route('super_admin.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 transition mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-user-shield text-purple-600"></i>
                                <span>Super Admin Panel</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @elseif($authUser && $authUser->isGroupAdmin())
                        <a href="{{ route('group_admin.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 transition mb-3">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-users-gear text-sky-600"></i>
                                <span>Group Admin Panel</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @endif

                    <div class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wider" style="color: var(--text-link, #0A4744);">Member Area</div>

                    @php $active = request()->routeIs('member.dashboard') || request()->is('dashboard'); @endphp
                    <a href="{{ route('member.dashboard') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-gauge-high w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Dashboard</span>
                    </a>

                    @php $active = request()->routeIs('member.directory*'); @endphp
                    <a href="{{ route('member.directory') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-address-book w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Member Directory</span>
                    </a>

                    @php $active = request()->routeIs('member.connections*'); @endphp
                    <a href="{{ route('member.connections') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-group w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Connections</span>
                    </a>

                    @php $active = request()->routeIs('member.chat*'); @endphp
                    <a href="{{ route('member.chat') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-comments w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Community Chat</span>
                        </div>
                        @if($sidebarUnreadCount > 0)
                            <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                {{ $sidebarUnreadCount }}
                            </span>
                        @endif
                    </a>

                    @php $active = request()->routeIs('notifications*'); @endphp
                    <a href="{{ route('notifications.index') }}" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-bell w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                            <span style="{{ $active ? 'color: #ffffff;' : '' }}">Notifications</span>
                        </div>
                        @if(auth()->check() && auth()->user()->unreadNotificationsCount() > 0)
                            <span class="px-2 py-0.5 bg-sky-600 text-white text-[10px] font-extrabold rounded-full shadow-2xs">
                                {{ auth()->user()->unreadNotificationsCount() }}
                            </span>
                        @endif
                    </a>

                    @php $active = request()->routeIs('member.profile*'); @endphp
                    <a href="{{ route('member.profile.edit') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-user-gear w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">My Profile</span>
                    </a>

                    @php $active = request()->routeIs('member.projects*'); @endphp
                    <a href="{{ route('member.projects.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-briefcase w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">My Projects</span>
                    </a>

                    @php $active = request()->routeIs('member.services*'); @endphp
                    <a href="{{ route('member.services.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-handshake w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Services</span>
                    </a>

                    <a href="{{ route('bizcard.show', auth()->id()) }}" target="_blank" onclick="closeMobileSidebar()" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-id-card w-5" style="color: var(--text-link, #0A4744);"></i>
                            <span>Business Card</span>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                    </a>

                    @php $active = request()->routeIs('groups*'); @endphp
                    <a href="{{ route('groups.index') }}" onclick="closeMobileSidebar()" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition {{ $active ? 'shadow-sm text-white' : 'text-slate-700 hover:bg-slate-50' }}" style="{{ $active ? $navActiveStyle : '' }}">
                        <i class="fa-solid fa-compass w-5" style="{{ $active ? 'color: #ffffff;' : 'color: var(--text-link, #0A4744);' }}"></i>
                        <span style="{{ $active ? 'color: #ffffff;' : '' }}">Browse Communities</span>
                    </a>
                @endif

                <!-- User Profile & Logout Button (Inside Nav Menu) -->
                <div class="pt-4 mt-4 border-t border-slate-200 space-y-2">
                    <div class="flex items-center space-x-3 px-3 py-1">
                        <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-bold text-xs uppercase shrink-0 shadow-2xs" style="background-color: var(--btn-primary-bg, #0A4744);">
                            {{ substr(auth()->user()->first_name ?? 'M', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-grow">
                            <div class="font-bold text-sm text-slate-900 truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->profession ?? 'Member' }}</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" onclick="closeMobileSidebar()" class="w-full text-left px-3.5 py-2.5 text-sm text-rose-600 hover:bg-rose-50 rounded-xl transition font-bold flex items-center space-x-3">
                            <i class="fa-solid fa-right-from-bracket text-sm w-5 text-rose-500"></i>
                            <span>Log out</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main View Content Area -->
    <main class="flex-grow p-3 sm:p-4 md:p-5 max-w-7xl w-full overflow-y-auto">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-2xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebarNav');
            const backdrop = document.getElementById('mobileBackdrop');
            const icon = document.getElementById('menuIcon');
            
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'w-72', 'flex', 'shadow-2xl', 'overflow-y-auto');
                backdrop.classList.remove('hidden');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
                document.body.style.overflow = 'hidden';
            } else {
                closeMobileSidebar();
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebarNav');
            const backdrop = document.getElementById('mobileBackdrop');
            const icon = document.getElementById('menuIcon');

            if (sidebar) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'w-72', 'flex', 'shadow-2xl', 'overflow-y-auto');
            }
            if (backdrop) {
                backdrop.classList.add('hidden');
            }
            if (icon) {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>
