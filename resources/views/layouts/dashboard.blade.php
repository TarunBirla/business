@php
    $activeTheme = \App\Services\ThemeService::getActiveTheme();
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $activeTheme->slug }}" class="{{ $activeTheme->type }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>
<body class="bg-slate-50 text-black min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-white border-r border-sky-100 p-6 flex flex-col justify-between shrink-0">
        <div>
            <a href="{{ url('/') }}" class="flex items-center space-x-3 mb-8">
                <img src="{{ asset('logo.jpeg') }}" alt="Community UK Logo" class="h-10 w-auto rounded-xl object-contain shadow-sm">
            </a>

            <nav class="space-y-1">
                @if(request()->is('super-admin*'))
                    <div class="px-3 py-2 text-xs font-bold text-sky-600 uppercase tracking-wider">Super Admin</div>
                    <a href="{{ route('super_admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-chart-pie text-sky-600 mr-1.5"></i> Dashboard</span>
                    </a>
                    <a href="{{ route('super_admin.groups.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.groups*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-users-gear text-sky-600 mr-1.5"></i> Manage Communities</span>
                    </a>
                    <a href="{{ route('super_admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.users*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-users text-sky-600 mr-1.5"></i> All Users</span>
                    </a>
                    <a href="{{ route('super_admin.projects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.projects*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-folder-open text-sky-600 mr-1.5"></i> Member Projects</span>
                    </a>
                    <a href="{{ route('super_admin.cms.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.cms*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-file-contract text-sky-600 mr-1.5"></i> CMS Pages</span>
                    </a>
                    <a href="{{ route('super_admin.themes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.themes*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-palette text-sky-600 mr-1.5"></i> Theme Manager</span>
                    </a>
                @elseif(request()->is('group-admin*'))
                    <div class="px-3 py-2 text-xs font-bold text-sky-600 uppercase tracking-wider">Group Admin</div>
                    <a href="{{ route('group_admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-chart-line text-sky-600 mr-1.5"></i> Dashboard</span>
                    </a>
                    @if(isset($group))
                        <a href="{{ route('group_admin.members.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.members*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span><i class="fa-solid fa-users text-sky-600 mr-1.5"></i> Members</span>
                        </a>
                        <a href="{{ route('group_admin.events.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.events*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span><i class="fa-solid fa-calendar-days text-sky-600 mr-1.5"></i> Events</span>
                        </a>
                        <a href="{{ route('group_admin.notices.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.notices*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span><i class="fa-solid fa-bullhorn text-sky-600 mr-1.5"></i> Notices</span>
                        </a>
                        <a href="{{ route('group_admin.promotion.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.promotion*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                            <span><i class="fa-solid fa-qrcode text-sky-600 mr-1.5"></i> Promote & QR</span>
                        </a>
                    @endif
                @else
                    <div class="px-3 py-2 text-xs font-bold text-sky-600 uppercase tracking-wider">Member Area</div>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-gauge-high text-sky-600 mr-1.5"></i> Dashboard</span>
                    </a>
                    <a href="{{ route('member.directory') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.directory*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-address-book text-sky-600 mr-1.5"></i> Member Directory</span>
                    </a>
                    <a href="{{ route('member.connections') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.connections*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-user-group text-sky-600 mr-1.5"></i> Connections</span>
                    </a>
                    @php
                        $sidebarUnreadCount = auth()->check() ? auth()->user()->receivedMessages()->where('is_read', false)->count() : 0;
                    @endphp
                    <a href="{{ route('member.chat') }}" class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.chat*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-comments text-sky-600 mr-1.5"></i> Community Chat</span>
                        @if($sidebarUnreadCount > 0)
                            <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-full shadow">
                                {{ $sidebarUnreadCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('member.profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.profile*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-user-gear text-sky-600 mr-1.5"></i> My Profile</span>
                    </a>
                    <a href="{{ route('member.projects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.projects*') ? 'bg-sky-50 text-sky-700' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span><i class="fa-solid fa-briefcase text-sky-600 mr-1.5"></i> My Projects</span>
                    </a>
                    <a href="{{ route('bizcard.show', auth()->id()) }}" target="_blank" class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50">
                        <span><i class="fa-solid fa-id-card text-sky-600 mr-1.5"></i> Business Card</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                    </a>
                    <a href="{{ route('groups.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50">
                        <span><i class="fa-solid fa-compass text-sky-600 mr-1.5"></i> Browse Communities</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="font-bold text-sm text-slate-900">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ auth()->user()->profession ?? 'Member' }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 rounded-lg transition font-medium flex items-center space-x-2">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    <span>Log out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main View -->
    <main class="flex-grow p-6 md:p-10 max-w-7xl overflow-y-auto">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
