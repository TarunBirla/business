@extends('layouts.dashboard')

@section('title', 'Manage Communities')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="text-xs font-bold text-sky-600 uppercase tracking-wider">Community Management</div>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">My Managed Communities</h1>
            <p class="text-xs text-slate-500 mt-0.5">Select a community to update settings, manage members, or inspect full audit logs.</p>
        </div>
        <span class="px-3.5 py-1.5 bg-sky-50 text-sky-700 text-xs font-bold rounded-xl self-start md:self-auto border border-sky-100">
            {{ $assignedGroups->count() }} Assigned {{ Str::plural('Community', $assignedGroups->count()) }}
        </span>
    </div>

    <!-- Assigned Communities Table View -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <th class="py-4 px-6">Community Name</th>
                        <th class="py-4 px-6">City / Region</th>
                        <th class="py-4 px-6 text-center">Active Members</th>
                        <th class="py-4 px-6 text-center">Events</th>
                        <th class="py-4 px-6 text-center">Notices</th>
                        <th class="py-4 px-6 text-right">Actions & Audit Logs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($assignedGroups as $g)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($g->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('group_admin.settings', $g->id) }}" class="font-bold text-slate-900 hover:text-sky-600 transition text-sm">
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
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 font-bold rounded-lg text-xs">
                                    {{ number_format($g->notices_count) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('groups.show', $g->slug) }}" target="_blank" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-2xs inline-flex items-center">
                                    <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> View Page
                                </a>
                                <a href="{{ route('group_admin.settings', $g->id) }}" class="px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl transition shadow-2xs inline-flex items-center">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit Settings
                                </a>
                                <button type="button" onclick="openAuditModal({{ $g->id }}, '{{ addslashes($g->name) }}')" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition shadow-2xs inline-flex items-center">
                                    <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Audit History
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 block"></i>
                                <p class="text-sm font-medium">No assigned communities found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

    document.getElementById('auditModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAuditModal();
        }
    });
</script>
@endsection
