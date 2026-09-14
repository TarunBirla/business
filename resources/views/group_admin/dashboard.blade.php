@extends('layouts.dashboard')

@section('title', 'Group Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header & Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-bold text-sky-600 uppercase tracking-wider">Group Admin Control Center</div>
            <h1 class="text-3xl font-bold text-slate-900 mt-1">{{ $activeGroup->name }}</h1>
        </div>

        <!-- Quick Actions for Active Group -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('group_admin.settings', $activeGroup->id) }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-2xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-sliders"></i>
                <span>Edit Community</span>
            </a>
            <a href="{{ route('group_admin.members.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition">
                + Add Member
            </a>
            <a href="{{ route('group_admin.events.create', $activeGroup->id) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-2xs transition">
                + Create Event
            </a>
            <a href="{{ route('group_admin.promotion.index', $activeGroup->id) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-2xs transition">
                <i class="fa-solid fa-qrcode mr-1"></i> Promote & QR
            </a>
        </div>
    </div>

    <!-- Assigned Communities Table View -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">My Managed Communities</h3>
                <p class="text-xs text-slate-500 mt-0.5">Select a community to switch active view, update settings, or inspect audit history.</p>
            </div>
            <span class="px-3 py-1 bg-sky-50 text-sky-700 text-xs font-bold rounded-full self-start sm:self-auto">
                {{ $assignedGroups->count() }} Assigned {{ Str::plural('Community', $assignedGroups->count()) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-3.5 px-6">Community Name</th>
                        <th class="py-3.5 px-6">City / Region</th>
                        <th class="py-3.5 px-6 text-center">Active Members</th>
                        <th class="py-3.5 px-6 text-center">Events</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions & Audit Logs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @foreach($assignedGroups as $g)
                        <tr class="{{ $g->id === $activeGroup->id ? 'bg-sky-50/30 font-semibold' : 'hover:bg-slate-50/50' }} transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($g->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('group_admin.dashboard', ['group_id' => $g->id]) }}" class="font-bold text-slate-900 hover:text-sky-600 transition text-sm">
                                            {{ $g->name }}
                                        </a>
                                        <div class="text-[11px] text-slate-400 capitalize">{{ $g->community_type }} Community</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $g->city ?? 'N/A' }}, {{ $g->country ?? 'UK' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-bold rounded-lg text-xs">
                                    {{ number_format($g->members_count) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg text-xs">
                                    {{ number_format($g->events_count) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($g->id === $activeGroup->id)
                                    <span class="px-2.5 py-1 bg-sky-600 text-white font-extrabold text-[10px] uppercase rounded-full shadow-2xs">
                                        <i class="fa-solid fa-check mr-1"></i> Active View
                                    </span>
                                @else
                                    <a href="{{ route('group_admin.dashboard', ['group_id' => $g->id]) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                                        Switch
                                    </a>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-1.5">
                                <a href="{{ route('group_admin.settings', $g->id) }}" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition shadow-2xs inline-flex items-center">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
                                </a>
                                <button type="button" onclick="openAuditModal({{ $g->id }}, '{{ addslashes($g->name) }}')" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-lg transition shadow-2xs inline-flex items-center">
                                    <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Audit History
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Community Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Total Members</div>
            <div class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($memberCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">New (Last 30 Days)</div>
            <div class="text-3xl font-bold text-sky-600 mt-2">+{{ number_format($newMembersCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Events</div>
            <div class="text-3xl font-bold text-emerald-600 mt-2">{{ number_format($eventsCount) }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Notices</div>
            <div class="text-3xl font-bold text-amber-600 mt-2">{{ number_format($noticesCount) }}</div>
        </div>
    </div>

    <!-- Recent Members & Upcoming Events -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-lg">Recent Registrations</h3>
                <a href="{{ route('group_admin.members.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentMembers as $m)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $m->name }}</div>
                            <div class="text-xs text-slate-500">{{ $m->email }} &bull; {{ $m->profession ?? 'Member' }}</div>
                        </div>
                        <span class="text-[10px] bg-sky-100 text-sky-800 font-bold px-2 py-1 rounded-md uppercase">
                            {{ $m->pivot->membership_role }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">No recent member registrations.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-lg">Upcoming Events</h3>
                <a href="{{ route('group_admin.events.index', $activeGroup->id) }}" class="text-xs font-bold text-sky-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingEvents as $evt)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $evt->title }}</div>
                            <div class="text-xs text-sky-600 font-semibold mt-0.5"><i class="fa-regular fa-calendar-days mr-1"></i>{{ $evt->start_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <span class="font-bold text-xs text-slate-900">
                            {{ $evt->event_type === 'free' ? 'Free' : '£' . number_format($evt->price, 2) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs font-medium">No upcoming events scheduled.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Community Audit Log Modal Box -->
<div id="auditModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-3xl w-full overflow-hidden transition-all transform scale-100">
        <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-clock-rotate-left text-sky-400 text-lg"></i>
                <div>
                    <h3 class="font-bold text-base text-white" id="modalCommunityTitle">Community Audit Log</h3>
                    <p class="text-[11px] text-slate-400">Complete historical record of updates made by group admins.</p>
                </div>
            </div>
            <button type="button" onclick="closeAuditModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <div class="p-6 max-h-[60vh] overflow-y-auto" id="modalAuditContent">
            <!-- Rendered dynamically by JS -->
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeAuditModal()" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition">
                Close Modal
            </button>
        </div>
    </div>
</div>

@php
    // Prepare Audit Log Data Map for Javascript Modal Rendering
    $auditLogsData = [];
    foreach($assignedGroups as $g) {
        $auditLogsData[$g->id] = $g->auditLogs->map(function($log) {
            return [
                'user_name' => $log->user ? $log->user->name : 'System Admin',
                'user_email' => $log->user ? $log->user->email : '',
                'field_name' => ucfirst(str_replace('_', ' ', $log->field_name)),
                'old_value' => $log->old_value ?? '(Empty)',
                'new_value' => $log->new_value ?? '(Empty)',
                'date' => $log->created_at->format('d M Y, h:i A'),
                'time_ago' => $log->created_at->diffForHumans(),
            ];
        })->toArray();
    }
@endphp

<script>
    const groupAuditLogs = @json($auditLogsData);

    function openAuditModal(groupId, groupName) {
        document.getElementById('modalCommunityTitle').innerText = 'Audit History & Change Log - ' + groupName;
        const container = document.getElementById('modalAuditContent');
        const logs = groupAuditLogs[groupId] || [];

        if (logs.length === 0) {
            container.innerHTML = `
                <div class="p-12 text-center text-slate-400">
                    <i class="fa-solid fa-folder-open text-4xl mb-3 block"></i>
                    <p class="text-sm font-medium">No update history recorded yet for this community.</p>
                </div>
            `;
        } else {
            let html = `
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <th class="py-2.5 px-4">Admin User</th>
                            <th class="py-2.5 px-4">Field Changed</th>
                            <th class="py-2.5 px-4">Old Value</th>
                            <th class="py-2.5 px-4">New Value</th>
                            <th class="py-2.5 px-4 text-right">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
            `;

            logs.forEach(log => {
                html += `
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900">${log.user_name}</div>
                            <div class="text-[10px] text-slate-400">${log.user_email}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 bg-sky-50 text-sky-700 font-bold rounded text-[11px]">${log.field_name}</span>
                        </td>
                        <td class="py-3 px-4 text-rose-600 line-through font-mono text-[11px] max-w-[150px] truncate">${log.old_value}</td>
                        <td class="py-3 px-4 text-emerald-600 font-mono text-[11px] font-bold max-w-[150px] truncate">${log.new_value}</td>
                        <td class="py-3 px-4 text-right text-slate-500 text-[11px]">
                            <div>${log.date}</div>
                            <div class="text-[10px] text-slate-400">${log.time_ago}</div>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;
            container.innerHTML = html;
        }

        document.getElementById('auditModal').classList.remove('hidden');
    }

    function closeAuditModal() {
        document.getElementById('auditModal').classList.add('hidden');
    }

    // Close on Backdrop Click
    document.getElementById('auditModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAuditModal();
        }
    });
</script>
@endsection
