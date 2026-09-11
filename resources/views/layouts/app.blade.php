@php
    $activeTheme = \App\Services\ThemeService::getActiveTheme();
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $activeTheme->slug }}" class="{{ $activeTheme->type }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Community Networking Ecosystem') - Community UK</title>
    <meta name="description" content="@yield('meta_description', 'Connect with your community, discover professionals and businesses, exchange services, attend events and build meaningful relationships across the UK.')">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- OpenGraph Tags -->
    <meta property="og:title" content="@yield('og_title', 'Community Networking Ecosystem UK')">
    <meta property="og:description" content="@yield('og_description', 'Build meaningful relationships with people, professionals and businesses from your community.')">
    <meta property="og:image" content="@yield('og_image', asset('logo.jpeg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Fonts: Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
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

        /* Primary Brand Accents & Links */
        .bg-sky-600, .bg-blue-600 {
            background-color: var(--btn-primary-bg) !important;
            color: var(--btn-primary-text) !important;
        }

        .bg-sky-600:hover, .hover\:bg-sky-700:hover, .bg-blue-600:hover, .hover\:bg-blue-700:hover {
            background-color: var(--btn-primary-hover) !important;
        }

        .text-sky-600, .text-sky-700, .text-blue-600, .text-blue-700 {
            color: var(--text-link) !important;
        }

        .text-sky-600:hover, .hover\:text-sky-600:hover, .text-blue-600:hover, .hover\:text-blue-600:hover {
            color: var(--text-link-hover) !important;
        }

        /* Navbar Styling */
        nav {
            background-color: var(--navbar-bg, #ffffff) !important;
            color: var(--navbar-text, #0f172a) !important;
        }

        /* Footer Styling Driven By Active Theme Tokens */
        footer {
            background-color: var(--footer-bg, #0f172a) !important;
            color: var(--footer-text, #f8fafc) !important;
        }

        footer h3 {
            color: var(--footer-text, #f8fafc) !important;
        }

        footer a {
            color: var(--footer-text, #f8fafc) !important;
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: var(--footer-link-hover, #38bdf8) !important;
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
</head>
<body class="min-h-screen flex flex-col justify-between bg-slate-50 antialiased">

    <!-- Navigation Header -->
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-10 w-auto rounded-xl object-contain shadow-2xs">
                        <span class="font-extrabold text-lg tracking-tight hidden sm:inline-block" style="color: var(--text-heading, #0f172a);">Community UK</span>
                    </a>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center space-x-8 text-sm font-bold">
                    <a href="{{ url('/') }}" class="transition hover:opacity-80" style="color: var(--text-primary, #0f172a);">Home</a>
                    <a href="{{ route('groups.index') }}" class="transition hover:opacity-80" style="color: var(--text-primary, #0f172a);">Communities</a>
                    <a href="{{ route('events.index') }}" class="transition hover:opacity-80" style="color: var(--text-primary, #0f172a);">Events</a>
                    <a href="{{ url('/#how-it-works') }}" class="transition hover:opacity-80" style="color: var(--text-primary, #0f172a);">How It Works</a>
                    <a href="{{ route('cms.show', 'about') }}" class="transition hover:opacity-80" style="color: var(--text-primary, #0f172a);">About</a>
                    <a href="{{ route('cms.show', 'contact') }}" class="transition hover:opacity-80" style="color: var(--text-primary, #0f172a);">Contact</a>
                </div>

                <!-- Action Buttons & Mobile Hamburger Toggle -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex items-center space-x-3">
                        @auth
                            @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition" style="background-color: var(--input-bg, #f0f9ff); color: var(--text-link, #0284c7); border-color: var(--border-color, #bae6fd);">Super Admin</a>
                            @elseif(auth()->user()->isGroupAdmin())
                                <a href="{{ route('group_admin.dashboard') }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition" style="background-color: var(--input-bg, #f0f9ff); color: var(--text-link, #0284c7); border-color: var(--border-color, #bae6fd);">Group Admin</a>
                            @endif
                            <a href="{{ route('member.dashboard') }}" class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-md transition" style="background-color: var(--btn-primary-bg, #0284c7);">My Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold hover:opacity-80 transition" style="color: var(--text-primary, #0f172a);">Login</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-xs font-bold text-white rounded-xl shadow-md transition" style="background-color: var(--btn-primary-bg, #0284c7);">Join Now</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Hamburger Button -->
                    <button onclick="togglePublicMobileNav()" id="publicNavBtn" aria-label="Toggle Navigation" class="md:hidden p-2.5 rounded-xl border transition flex items-center justify-center" style="background-color: var(--card-bg, #ffffff); border-color: var(--border-color, #e2e8f0); color: var(--text-primary, #0f172a);">
                        <i class="fa-solid fa-bars text-lg" id="publicNavIcon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Collapsible Navigation Menu Drawer -->
        <div id="publicMobileDrawer" class="hidden md:hidden border-t px-4 pt-3 pb-6 space-y-3 transition-all" style="background-color: var(--card-bg, #ffffff); border-color: var(--border-color, #e2e8f0);">
            <div class="space-y-1 py-2">
                <a href="{{ url('/') }}" onclick="closePublicMobileNav()" class="block px-4 py-2.5 rounded-xl font-bold text-sm" style="color: var(--text-primary, #0f172a);">Home</a>
                <a href="{{ route('groups.index') }}" onclick="closePublicMobileNav()" class="block px-4 py-2.5 rounded-xl font-bold text-sm" style="color: var(--text-primary, #0f172a);">Communities</a>
                <a href="{{ route('events.index') }}" onclick="closePublicMobileNav()" class="block px-4 py-2.5 rounded-xl font-bold text-sm" style="color: var(--text-primary, #0f172a);">Events</a>
                <a href="{{ url('/#how-it-works') }}" onclick="closePublicMobileNav()" class="block px-4 py-2.5 rounded-xl font-bold text-sm" style="color: var(--text-primary, #0f172a);">How It Works</a>
                <a href="{{ route('cms.show', 'about') }}" onclick="closePublicMobileNav()" class="block px-4 py-2.5 rounded-xl font-bold text-sm" style="color: var(--text-primary, #0f172a);">About</a>
                <a href="{{ route('cms.show', 'contact') }}" onclick="closePublicMobileNav()" class="block px-4 py-2.5 rounded-xl font-bold text-sm" style="color: var(--text-primary, #0f172a);">Contact</a>
            </div>

            <div class="pt-3 border-t space-y-2" style="border-color: var(--border-color, #e2e8f0);">
                @auth
                    <a href="{{ route('member.dashboard') }}" onclick="closePublicMobileNav()" class="block w-full text-center py-3 text-xs font-bold text-white rounded-xl shadow-md" style="background-color: var(--btn-primary-bg, #0284c7);">
                        My Dashboard &rarr;
                    </a>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" onclick="closePublicMobileNav()" class="block text-center py-2.5 text-xs font-bold rounded-xl border" style="background-color: var(--input-bg, #f8fafc); border-color: var(--border-color, #e2e8f0); color: var(--text-primary, #0f172a);">Login</a>
                        <a href="{{ route('register') }}" onclick="closePublicMobileNav()" class="block text-center py-2.5 text-xs font-bold text-white rounded-xl shadow-sm" style="background-color: var(--btn-primary-bg, #0284c7);">Join Now</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="max-w-7xl mx-auto px-4 mt-4">
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center space-x-2 shadow-2xs">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-bold">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t shadow-inner" style="background-color: var(--footer-bg, #0f172a); color: var(--footer-text, #f8fafc); border-color: var(--border-color, #1e293b);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-9 w-auto rounded-lg object-contain bg-white p-0.5 shadow-2xs">
                        <span class="font-extrabold text-lg text-white">Community UK</span>
                    </div>
                    <p class="text-xs leading-relaxed opacity-90">
                        A UK-wide professional community networking ecosystem bringing people together based on shared background, region, culture, and professional goals.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold mb-4 text-sm opacity-100 uppercase tracking-wider">Quick Links</h3>
                    <ul class="space-y-2.5 text-xs opacity-90">
                        <li><a href="{{ route('groups.index') }}" class="transition hover:opacity-100">Explore Communities</a></li>
                        <li><a href="{{ route('events.index') }}" class="transition hover:opacity-100">Upcoming Events</a></li>
                        <li><a href="{{ url('/#how-it-works') }}" class="transition hover:opacity-100">How It Works</a></li>
                        <li><a href="{{ route('register') }}" class="transition hover:opacity-100">Join Community</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4 text-sm opacity-100 uppercase tracking-wider">Legal & Privacy</h3>
                    <ul class="space-y-2.5 text-xs opacity-90">
                        <li><a href="{{ route('cms.show', 'privacy') }}" class="transition hover:opacity-100">Privacy Policy</a></li>
                        <li><a href="{{ route('cms.show', 'terms') }}" class="transition hover:opacity-100">Terms & Conditions</a></li>
                        <li><a href="{{ route('cms.show', 'cookie') }}" class="transition hover:opacity-100">Cookie Policy</a></li>
                        <li><a href="{{ route('cms.show', 'faq') }}" class="transition hover:opacity-100">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4 text-sm opacity-100 uppercase tracking-wider">Community Ecosystem</h3>
                    <p class="text-xs mb-3 opacity-80 leading-relaxed">Empowering Gujarati, Marathi, MP, Punjabi, and regional business networks across the UK.</p>
                    <div class="text-[11px] opacity-60">&copy; {{ date('Y') }} Community UK Platform. All rights reserved.</div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function togglePublicMobileNav() {
            const drawer = document.getElementById('publicMobileDrawer');
            const icon = document.getElementById('publicNavIcon');

            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                closePublicMobileNav();
            }
        }

        function closePublicMobileNav() {
            const drawer = document.getElementById('publicMobileDrawer');
            const icon = document.getElementById('publicNavIcon');

            if (drawer) drawer.classList.add('hidden');
            if (icon) {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        }
    </script>
</body>
</html>
