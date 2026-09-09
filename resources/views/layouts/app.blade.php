@php
    $activeTheme = \App\Services\ThemeService::getActiveTheme();
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $activeTheme->slug }}" class="{{ $activeTheme->type }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- JSON-LD Structured Data for SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Community UK",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('logo.jpeg') }}",
      "description": "UK Community Networking Ecosystem bringing people together based on shared background, region, culture, and professional goals."
    }
    </script>

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
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            600: '#0284c7',
                            700: '#0369a1',
                        }
                    },
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

        /* Sky Primary Brand Accents & Links */
        .bg-sky-600 {
            background-color: var(--btn-primary-bg) !important;
            color: var(--btn-primary-text) !important;
        }

        .bg-sky-600:hover, .hover\:bg-sky-700:hover {
            background-color: var(--btn-primary-hover) !important;
        }

        .text-sky-600, .text-sky-700 {
            color: var(--text-link) !important;
        }

        .text-sky-600:hover, .hover\:text-sky-600:hover {
            color: var(--text-link-hover) !important;
        }

        /* Navbar Styling */
        nav {
            background-color: var(--navbar-bg) !important;
            color: var(--navbar-text) !important;
        }

        /* Footer Styling Driven By Active Theme Tokens */
        footer {
            background-color: var(--footer-bg) !important;
            color: var(--footer-text) !important;
        }

        footer h3 {
            color: var(--footer-text) !important;
        }

        footer a {
            color: var(--footer-text) !important;
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: var(--footer-link-hover) !important;
        }

        /* Inputs */
        input[type="text"], input[type="email"], input[type="password"], input[type="url"], textarea, select {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-primary);
        }

        /* Context-Aware Dark Mode Text & Surface Rules */
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

        html.dark .text-slate-900, html.dark .text-slate-800,
        [data-theme*="dark"] .text-slate-900, [data-theme*="dark"] .text-slate-800 {
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
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-sky-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-10 w-auto rounded-xl object-contain shadow-sm">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                    <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Home</a>
                    <a href="{{ route('groups.index') }}" class="hover:text-sky-600 transition">Communities</a>
                    <a href="{{ route('events.index') }}" class="hover:text-sky-600 transition">Events</a>
                    <a href="{{ url('/#how-it-works') }}" class="hover:text-sky-600 transition">How It Works</a>
                    <a href="{{ route('cms.show', 'about') }}" class="hover:text-sky-600 transition">About</a>
                    <a href="{{ route('cms.show', 'contact') }}" class="hover:text-sky-600 transition">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-sky-700 bg-sky-50 rounded-lg hover:bg-sky-100 transition">Super Admin</a>
                        @elseif(auth()->user()->isGroupAdmin())
                            <a href="{{ route('group_admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-sky-700 bg-sky-50 rounded-lg hover:bg-sky-100 transition">Group Admin</a>
                        @endif
                        <a href="{{ route('member.dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md transition">My Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold  hover:text-sky-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md hover:shadow-lg transition">Join Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/20 shadow-inner" style="background-color: var(--footer-bg); color: var(--footer-text);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-9 w-auto rounded-lg object-contain bg-white p-0.5 shadow-sm">
                    </div>
                    <p class="text-sm leading-relaxed opacity-90">
                        A UK-wide professional community networking ecosystem bringing people together based on shared background, region, culture, and professional goals.
                    </p>
                </div>

                <div>
                    <h3 class="font-bold mb-4 text-base opacity-100">Quick Links</h3>
                    <ul class="space-y-2 text-sm opacity-90">
                        <li><a href="{{ route('groups.index') }}" class="transition">Explore Communities</a></li>
                        <li><a href="{{ route('events.index') }}" class="transition">Upcoming Events</a></li>
                        <li><a href="{{ url('/#how-it-works') }}" class="transition">How It Works</a></li>
                        <li><a href="{{ route('register') }}" class="transition">Join Community</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4 text-base opacity-100">Legal & Privacy</h3>
                    <ul class="space-y-2 text-sm opacity-90">
                        <li><a href="{{ route('cms.show', 'privacy') }}" class="transition">Privacy Policy</a></li>
                        <li><a href="{{ route('cms.show', 'terms') }}" class="transition">Terms & Conditions</a></li>
                        <li><a href="{{ route('cms.show', 'cookie') }}" class="transition">Cookie Policy</a></li>
                        <li><a href="{{ route('cms.show', 'faq') }}" class="transition">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold mb-4 text-base opacity-100">Community Ecosystem</h3>
                    <p class="text-xs mb-3 opacity-80">Empowering Gujarati, Marathi, MP, Punjabi, and regional business networks across the UK.</p>
                    <div class="text-xs opacity-70">&copy; {{ date('Y') }} Community UK Platform. All rights reserved.</div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
