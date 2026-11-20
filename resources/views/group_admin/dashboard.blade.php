@extends('layouts.dashboard')

@section('title', 'Group Admin Dashboard - ' . $activeGroup->name)

@section('content')
<div class="space-y-8">
    <!-- Header & Community Selector -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs font-extrabold uppercase tracking-wider" style="color: var(--text-link, #0A4744);">
                    <i class="fa-solid fa-users-gear mr-1"></i> Community Control Center
                </span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs font-bold text-slate-500">ID: #{{ $activeGroup->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                {{ $activeGroup->name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Real-time monitoring of community members, live login/logout status, page browsing activity & connections.
            </p>
        </div>

        @if($assignedGroups->count() > 1)
            <!-- Multi-Community Switcher -->
            <div class="flex items-center space-x-2 bg-slate-50 p-2 rounded-xl border border-slate-200">
                <label for="communitySelect" class="text-xs font-bold text-slate-600 whitespace-nowrap pl-1">
                    <i class="fa-solid fa-repeat mr-1 text-sky-600"></i> Switch Community:
                </label>
                <select id="communitySelect" onchange="window.location.href='?group_id=' + this.value"
                    class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @foreach($assignedGroups as $g)
                        <option value="{{ $g->id }}" {{ (int)$activeGroup->id === (int)$g->id ? 'selected' : '' }}>
                            {{ $g->name }} ({{ $g->members_count }} members)
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Active Community Stats Grid (5 Cards) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- Card 1: Total Members -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Total Members</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">{{ number_format($memberCount) }}</div>
            <span class="text-[11px] text-slate-400 font-medium mt-1 inline-block">Registered in Community</span>
        </div>

        <!-- Card 2: Online Now -->
        <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-emerald-800 uppercase tracking-wider">Online Now</span>
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600 mt-2">{{ number_format($onlineMembersCount) }}</div>
            <span class="text-[11px] text-emerald-700 font-bold mt-1 inline-block">Live Active Users</span>
        </div>

        <!-- Card 3: Community Connections -->
        <div class="bg-white p-5 rounded-2xl border border-indigo-200 bg-indigo-50/20 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-indigo-800 uppercase tracking-wider">Connections</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-handshake"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-indigo-600 mt-2">{{ number_format($communityConnectionsCount) }}</div>
            <span class="text-[11px] text-indigo-700 font-bold mt-1 inline-block">Accepted in Group</span>
        </div>

        <!-- Card 4: New (Last 30 Days) -->
        <div class="bg-white p-5 rounded-2xl border border-sky-200 bg-sky-50/20 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-sky-800 uppercase tracking-wider">New (30 Days)</span>
                <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-sky-600 mt-2">+{{ number_format($newMembersCount) }}</div>
            <span class="text-[11px] text-sky-700 font-medium mt-1 inline-block">Recent Growth</span>
        </div>

        <!-- Card 5: Events & Notices -->
        <div class="bg-white p-5 rounded-2xl border border-amber-200 bg-amber-50/20 shadow-xs col-span-2 md:col-span-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold text-amber-800 uppercase tracking-wider">Events & Notices</span>
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-600 mt-2">{{ number_format($eventsCount + $noticesCount) }}</div>
            <span class="text-[11px] text-amber-700 font-medium mt-1 inline-block">{{ $eventsCount }} Events &bull; {{ $noticesCount }} Notices</span>
        </div>
    </div>

    <!-- MAIN SECTION: Community Members Real-Time Activity & Connections Tracker -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden space-y-4">
        <!-- Top Toolbar: Title, Filter Tabs & Search -->
        <div class="p-6 border-b border-slate-100 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-chart-line text-emerald-600"></i>
                        <span>Community Members Activity & Connections Tracker</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Track when community members login, logout, which pages they browse, and how many connections they have formed.
                    </p>
                </div>

                <!-- Export CSV & Quick Links -->
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="{{ route('group_admin.members.export_csv', $activeGroup->id) }}"
                        class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i>
                        <span>Export Members CSV</span>
                    </a>
                </div>
            </div>

            <!-- Filters & Search Form -->
            <form method="GET" action="{{ route('group_admin.dashboard') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-2">
                <input type="hidden" name="group_id" value="{{ $activeGroup->id }}">

                <!-- Status Filter Tabs -->
                <div class="flex items-center space-x-1.5 bg-slate-100 p-1 rounded-xl">
                    <a href="{{ route('group_admin.dashboard', ['group_id' => $activeGroup->id, 'search' => request('search')]) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ !request('status_filter') ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        All Members ({{ $memberCount }})
                    </a>
                    <a href="{{ route('group_admin.dashboard', ['group_id' => $activeGroup->id, 'status_filter' => 'online', 'search' => request('search')]) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition flex items-center space-x-1.5 {{ request('status_filter') === 'online' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:bg-emerald-50' }}">
                        <span class="w-2 h-2 rounded-full {{ request('status_filter') === 'online' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                        <span>Online ({{ $onlineMembersCount }})</span>
                    </a>
                    <a href="{{ route('group_admin.dashboard', ['group_id' => $activeGroup->id, 'status_filter' => 'offline', 'search' => request('search')]) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ request('status_filter') === 'offline' ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Offline
                    </a>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, email, role..."
                        class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @if(request('search'))
                        <a href="{{ route('group_admin.dashboard', ['group_id' => $activeGroup->id, 'status_filter' => request('status_filter')]) }}"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Members Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Member</th>
                        <th class="py-3 px-4">Live Status</th>
                        <th class="py-3 px-4">Last Login</th>
                        <th class="py-3 px-4">Last Logout</th>
                        <th class="py-3 px-4 text-center">Group Connections</th>
                        <th class="py-3 px-4">Recent Pages Visited / Activities</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($communityMembers as $m)
                        @php
                            $isOnline = $m->isOnline();
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- Member Info -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="relative shrink-0">
                                        @if($m->profile_photo_url)
                                            <img src="{{ $m->profile_photo_url }}" alt="{{ $m->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-slate-800 text-white font-extrabold flex items-center justify-center text-xs">
                                                {{ strtoupper(substr($m->first_name, 0, 1) . substr($m->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        @if($isOnline)
                                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white" title="Online Now"></span>
                                        @else
                                            <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-slate-300 border-2 border-white" title="Offline"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm flex items-center space-x-1.5">
                                            <span>{{ $m->name }}</span>
                                            @if($m->membership_role === 'group_admin')
                                                <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[9px] font-extrabold rounded uppercase">Admin</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500">{{ $m->email }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">
                                            {{ $m->profession ?: ($m->job_title ?: 'Member') }}
                                            @if($m->company) &bull; {{ $m->company }} @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Live Status -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($isOnline)
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Online Now</span>
                                    </span>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        Active {{ $m->last_seen_at ? $m->last_seen_at->diffForHumans() : 'just now' }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Offline</span>
                                    </span>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $m->last_seen_at ? 'Seen ' . $m->last_seen_at->diffForHumans() : 'No recent visit' }}
                                    </div>
                                @endif
                            </td>

                            <!-- Last Login -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($m->last_login_at)
                                    <div class="font-bold text-slate-800 text-xs flex items-center space-x-1">
                                        <i class="fa-solid fa-right-to-bracket text-emerald-600 text-[10px]"></i>
                                        <span>{{ $m->last_login_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-medium">
                                        {{ $m->last_login_at->format('h:i A') }} ({{ $m->last_login_at->diffForHumans() }})
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Never recorded</span>
                                @endif
                            </td>

                            <!-- Last Logout -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($isOnline && (!$m->last_logout_at || $m->last_login_at > $m->last_logout_at))
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-bold">
                                        Active Session
                                    </span>
                                @elseif($m->last_logout_at)
                                    <div class="font-bold text-slate-700 text-xs flex items-center space-x-1">
                                        <i class="fa-solid fa-right-from-bracket text-rose-500 text-[10px]"></i>
                                        <span>{{ $m->last_logout_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-medium">
                                        {{ $m->last_logout_at->format('h:i A') }} ({{ $m->last_logout_at->diffForHumans() }})
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Session Closed</span>
                                @endif
                            </td>

                            <!-- Connections Count -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-xl text-xs font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <i class="fa-solid fa-handshake text-indigo-600"></i>
                                    <span>{{ $m->group_connections_count }}</span>
                                </span>
                                <div class="text-[10px] text-slate-400 mt-0.5 font-medium">in this community</div>
                            </td>

                            <!-- Recent Pages Visited / Activities -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-1.5 max-w-md">
                                    @if(isset($m->recent_activities) && $m->recent_activities->count() > 0)
                                        @foreach($m->recent_activities->take(3) as $act)
                                            <div class="flex items-center space-x-2 text-[11px] font-medium text-slate-700">
                                                <i class="{{ $act->icon }} text-xs shrink-0" title="{{ $act->activity_type }}"></i>
                                                <span class="truncate" title="{{ $act->description }}">
                                                    {{ $act->description }}
                                                </span>
                                                <span class="text-[10px] text-slate-400 shrink-0 font-normal">
                                                    &bull; {{ $act->created_at->diffForHumans(null, true, true) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-slate-400 text-[11px] italic">No recent page activity recorded yet.</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('group_admin.members.activity', ['group' => $activeGroup->id, 'user' => $m->id]) }}"
                                    class="inline-flex items-center space-x-1 px-3 py-1.5 bg-white border border-slate-300 hover:border-sky-500 hover:bg-sky-50 text-slate-700 hover:text-sky-700 font-bold text-xs rounded-xl shadow-2xs transition">
                                    <i class="fa-solid fa-clock-rotate-left text-sky-600 text-xs"></i>
                                    <span>Activity Log</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm font-medium">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-lg">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                No members found matching your search/filter criteria in this community.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($communityMembers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $communityMembers->links() }}
            </div>
        @endif
    </div>

    <!-- Upcoming Events Grid -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-slate-900 text-lg flex items-center space-x-2">
                <i class="fa-regular fa-calendar-days text-purple-600"></i>
                <span>Upcoming Community Events</span>
            </h3>
            <a href="{{ route('group_admin.events.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">
                View All Events &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($upcomingEvents as $evt)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                    <div class="font-extrabold text-slate-900 text-sm truncate">{{ $evt->title }}</div>
                    <div class="text-xs text-sky-600 font-semibold flex items-center space-x-1.5">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>{{ $evt->start_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-1 border-t border-slate-200 text-xs">
                        <span class="text-slate-500">{{ $evt->location ?? 'Online Meeting' }}</span>
                        <span class="font-extrabold text-slate-900">
                            {{ $evt->event_type === 'free' ? 'Free' : '£' . number_format($evt->price, 2) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs font-medium col-span-3">
                    No upcoming events scheduled for this community.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
