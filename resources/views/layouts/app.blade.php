<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Community Networking Ecosystem') - Community UK</title>
    <meta name="description"
        content="@yield('meta_description', 'Connect with your community, discover professionals and businesses, exchange services, attend events and build meaningful relationships across the UK.')">

    <!-- OpenGraph Tags -->
    <meta property="og:title" content="@yield('og_title', 'Community Networking Ecosystem UK')">
    <meta property="og:description"
        content="@yield('og_description', 'Build meaningful relationships with people, professionals and businesses from your community.')">
    <meta property="og:image" content="@yield('og_image', asset('images/community-og.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Fonts: Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f7f7f7',
                            100: '#eeeeee',
                            200: '#d6d6d6',
                            500: '#171717',
                            600: '#111111',
                            700: '#0a0a0a',
                            800: '#050505',
                            900: '#000000',
                        }
                    },
                    fontFamily: {
                        sans: ['"Space Grotesk"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
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
                        <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo"
                            class="h-10 w-auto rounded-xl object-contain shadow-sm">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-700">
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
                            <a href="{{ route('super_admin.dashboard') }}"
                                class="px-4 py-2 text-sm font-semibold text-sky-700 bg-sky-50 rounded-lg hover:bg-sky-100 transition">Super
                                Admin</a>
                        @elseif(auth()->user()->isGroupAdmin())
                            <a href="{{ route('group_admin.dashboard') }}"
                                class="px-4 py-2 text-sm font-semibold text-sky-700 bg-sky-50 rounded-lg hover:bg-sky-100 transition">Group
                                Admin</a>
                        @endif
                        <a href="{{ route('member.dashboard') }}"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md transition">My
                            Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-sky-600 transition">Login</a>
                        <a href="{{ route('register') }}"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-lg shadow-md hover:shadow-lg transition">Join
                            Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div
                    class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo"
                            class="h-9 w-auto rounded-lg object-contain bg-white p-0.5">
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        A UK-wide professional community networking ecosystem bringing people together based on shared
                        background, region, culture, and professional goals.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('groups.index') }}" class="hover:text-sky-400 transition">Explore
                                Communities</a></li>
                        <li><a href="{{ route('events.index') }}" class="hover:text-sky-400 transition">Upcoming
                                Events</a></li>
                        <li><a href="{{ url('/#how-it-works') }}" class="hover:text-sky-400 transition">How It Works</a>
                        </li>
                        <li><a href="{{ route('register') }}" class="hover:text-sky-400 transition">Join Community</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-4">Legal & Privacy</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('cms.show', 'privacy') }}" class="hover:text-sky-400 transition">Privacy
                                Policy</a></li>
                        <li><a href="{{ route('cms.show', 'terms') }}" class="hover:text-sky-400 transition">Terms &
                                Conditions</a></li>
                        <li><a href="{{ route('cms.show', 'cookie') }}" class="hover:text-sky-400 transition">Cookie
                                Policy</a></li>
                        <li><a href="{{ route('cms.show', 'faq') }}" class="hover:text-sky-400 transition">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-4">Community Ecosystem</h4>
                    <p class="text-xs text-slate-400 mb-3">Empowering Gujarati, Marathi, MP, Punjabi, and regional
                        business networks across the UK.</p>
                    <div class="text-xs text-slate-500">&copy; {{ date('Y') }} Community UK Platform. All rights
                        reserved.</div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>