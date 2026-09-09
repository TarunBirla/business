<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Community UK</title>
    <!-- Fonts: Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    <style>
        body { font-family: 'Space Grotesk', sans-serif; background-color: #f8fafc; color: #0f172a; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-white border-r border-sky-100 p-6 flex flex-col justify-between shrink-0">
        <div>
            <a href="{{ url('/') }}" class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-sky-600 flex items-center justify-center text-white font-bold text-xl shadow-md">C</div>
                <span class="text-xl font-bold tracking-tight text-slate-900">Community<span class="text-sky-600">UK</span></span>
            </a>

            <nav class="space-y-1">
                @if(request()->is('super-admin*'))
                    <div class="px-3 py-2 text-xs font-bold text-sky-600 uppercase tracking-wider">Super Admin</div>
                    <a href="{{ route('super_admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('super_admin.groups.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.groups*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Manage Communities</span>
                    </a>
                    <a href="{{ route('super_admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.users*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>All Users</span>
                    </a>
                    <a href="{{ route('super_admin.cms.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('super_admin.cms*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>CMS Pages</span>
                    </a>
                @elseif(request()->is('group-admin*'))
                    <div class="px-3 py-2 text-xs font-bold text-sky-600 uppercase tracking-wider">Group Admin</div>
                    <a href="{{ route('group_admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Dashboard</span>
                    </a>
                    @if(isset($group))
                        <a href="{{ route('group_admin.members.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.members*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>Members</span>
                        </a>
                        <a href="{{ route('group_admin.events.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.events*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>Events</span>
                        </a>
                        <a href="{{ route('group_admin.notices.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.notices*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>Notices</span>
                        </a>
                        <a href="{{ route('group_admin.promotion.index', $group->id) }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('group_admin.promotion*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>Promote & QR</span>
                        </a>
                    @endif
                @else
                    <div class="px-3 py-2 text-xs font-bold text-sky-600 uppercase tracking-wider">Member Area</div>
                    <a href="{{ route('member.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('member.directory') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.directory*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Member Directory</span>
                    </a>
                    <a href="{{ route('member.connections') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.connections*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Connections</span>
                    </a>
                    @php
                        $sidebarUnreadCount = auth()->check() ? auth()->user()->receivedMessages()->where('is_read', false)->count() : 0;
                    @endphp
                    <a href="{{ route('member.chat') }}" class="flex items-center justify-between px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.chat*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>💬 Community Chat</span>
                        @if($sidebarUnreadCount > 0)
                            <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-full shadow">
                                {{ $sidebarUnreadCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('member.profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm {{ request()->routeIs('member.profile*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('groups.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-600 hover:bg-slate-50">
                        <span>Browse Communities</span>
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
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 rounded-lg transition font-medium">Log out</button>
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
