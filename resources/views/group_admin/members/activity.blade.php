@extends('layouts.dashboard')

@section('title', 'Member Activity Log: ' . $user->name . ' - ' . $group->name)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Back to Dashboard / Members Breadcrumb -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs font-bold text-slate-500">
            <a href="{{ route('group_admin.dashboard', ['group_id' => $group->id]) }}" class="hover:text-slate-900 transition flex items-center space-x-1">
                <span>&larr; Back to Dashboard</span>
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700">{{ $group->name }}</span>
            <span class="text-slate-300">/</span>
            <span style="color: var(--text-link, #0A4744);">Member Activity Trail</span>
        </div>
    </div>

    <!-- Member Profile Overview Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center space-x-4">
                <div class="relative shrink-0">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-slate-200 shadow-xs">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white font-black text-xl flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif
                    @if($user->isOnline())
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white" title="Online Now"></span>
                    @else
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-slate-300 border-2 border-white" title="Offline"></span>
                    @endif
                </div>

                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                        @if($user->isOnline())
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Online Now</span>
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">
                                Offline
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-600 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span><i class="fa-regular fa-envelope mr-1 text-slate-400"></i>{{ $user->email }}</span>
                        @if($user->phone)
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-phone mr-1 text-slate-400"></i>{{ $user->phone }}</span>
                        @endif
                        @if($user->city)
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-location-dot mr-1 text-slate-400"></i>{{ $user->city }}, {{ $user->country }}</span>
                        @endif
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mt-1">
                        {{ $user->profession ?: 'Member' }} @if($user->company) at <strong>{{ $user->company }}</strong> @endif
                    </div>
                </div>
            </div>

            <!-- Total Connections Pill -->
            <div class="flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto p-4 sm:p-0 bg-slate-50 sm:bg-transparent rounded-xl">
                <span class="text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">Community Connections</span>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="px-3.5 py-1.5 rounded-xl font-black text-base bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs flex items-center space-x-2">
                        <i class="fa-solid fa-handshake text-indigo-600"></i>
                        <span>{{ $connectionsCount }} Connections</span>
                    </span>
                </div>
                <span class="text-[10px] text-slate-400 mt-1">Total in platform: {{ $totalConnectionsCount }}</span>
            </div>
        </div>

        <!-- 3 Tracking Highlights: Login, Logout, Last Seen -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6">
            <!-- Last Login -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                <div class="flex items-center space-x-2 text-xs font-extrabold text-slate-600 uppercase tracking-wider">
                    <i class="fa-solid fa-right-to-bracket text-emerald-600"></i>
                    <span>Last Login Timestamp</span>
                </div>
                <div class="mt-2">
                    @if($user->last_login_at)
                        <div class="text-sm font-extrabold text-slate-900">{{ $user->last_login_at->format('d M Y, h:i A') }}</div>
                        <div class="text-xs text-slate-500">{{ $user->last_login_at->diffForHumans() }}</div>
                    @else
                        <div class="text-sm text-slate-400 italic">Never recorded</div>
                    @endif
                </div>
            </div>

            <!-- Last Logout -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                <div class="flex items-center space-x-2 text-xs font-extrabold text-slate-600 uppercase tracking-wider">
                    <i class="fa-solid fa-right-from-bracket text-rose-500"></i>
                    <span>Last Logout Timestamp</span>
                </div>
                <div class="mt-2">
                    @if($user->isOnline() && (!$user->last_logout_at || $user->last_login_at > $user->last_logout_at))
                        <div class="text-sm font-extrabold text-emerald-700">Currently Active</div>
                        <div class="text-xs text-emerald-600 font-medium">Session in progress</div>
                    @elseif($user->last_logout_at)
                        <div class="text-sm font-extrabold text-slate-900">{{ $user->last_logout_at->format('d M Y, h:i A') }}</div>
                        <div class="text-xs text-slate-500">{{ $user->last_logout_at->diffForHumans() }}</div>
                    @else
                        <div class="text-sm text-slate-400 italic">Session closed</div>
                    @endif
                </div>
            </div>

            <!-- Last Active / Seen -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                <div class="flex items-center space-x-2 text-xs font-extrabold text-slate-600 uppercase tracking-wider">
                    <i class="fa-regular fa-clock text-sky-600"></i>
                    <span>Last Seen / Activity</span>
                </div>
                <div class="mt-2">
                    @if($user->last_seen_at)
                        <div class="text-sm font-extrabold text-slate-900">{{ $user->last_seen_at->format('d M Y, h:i A') }}</div>
                        <div class="text-xs text-slate-500">{{ $user->last_seen_at->diffForHumans() }}</div>
                    @else
                        <div class="text-sm text-slate-400 italic">No activity recorded</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Complete Chronological Activity Trail Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-timeline text-indigo-600"></i>
                    <span>Chronological Activity & Visited Pages Trail</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    History of member logins, logouts, viewed pages, and actions in Bizconn.
                </p>
            </div>
            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                {{ $activities->total() }} Total Events
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-extrabold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Event Type</th>
                        <th class="py-3 px-4">Activity Description</th>
                        <th class="py-3 px-4">Page / URL</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($activities as $act)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Event Type Badge -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold border {{ $act->badge_class }}">
                                    <i class="{{ $act->icon }}"></i>
                                    <span class="uppercase tracking-wider">{{ str_replace('_', ' ', $act->activity_type) }}</span>
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 text-xs">{{ $act->description }}</span>
                            </td>

                            <!-- Page / Path -->
                            <td class="py-3 px-4">
                                @if($act->path)
                                    <code class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[11px] font-mono">
                                        {{ $act->path }}
                                    </code>
                                @else
                                    <span class="text-slate-400 text-[11px]">&mdash;</span>
                                @endif
                            </td>

                            <!-- IP Address -->
                            <td class="py-3 px-4 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                {{ $act->ip_address ?: '&mdash;' }}
                            </td>

                            <!-- Timestamp -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $act->created_at->format('d M Y, h:i A') }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $act->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs font-medium">
                                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300 mb-2 block"></i>
                                No activity recorded for this member yet. Activities will appear as soon as the member logs in or browses pages.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
