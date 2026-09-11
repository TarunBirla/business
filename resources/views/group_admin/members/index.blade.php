@extends('layouts.dashboard')

@section('title', 'Manage Members - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Group Members ({{ $group->name }})</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Manage community registrations, invite members, and export attendance records.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('group_admin.members.export_csv', $group->id) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition inline-flex items-center space-x-1.5">
                <i class="fa-solid fa-file-csv text-slate-500"></i>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('group_admin.members.create', $group->id) }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition inline-flex items-center space-x-1.5" style="background-color: var(--btn-primary-bg, #0284c7);">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add / Invite Member</span>
            </a>
        </div>
    </div>

    <!-- Responsive Members Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[768px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                        <th class="p-4">Member Name</th>
                        <th class="p-4">Contact Info</th>
                        <th class="p-4">Profession</th>
                        <th class="p-4">City</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Joined Date</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($members as $m)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3.5 sm:p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0" style="background-color: var(--btn-primary-bg, #0284c7);">
                                        {{ strtoupper(substr($m->first_name, 0, 1) . substr($m->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm leading-tight">{{ $m->name }}</div>
                                        <div class="text-[11px] text-slate-500">ID #{{ $m->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3.5 sm:p-4">
                                <div class="space-y-0.5">
                                    <div class="font-medium text-slate-800 text-xs">{{ $m->email }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $m->phone ?? 'Phone N/A' }}</div>
                                </div>
                            </td>
                            <td class="p-3.5 sm:p-4 text-sky-600 font-semibold text-xs">{{ $m->profession ?? 'Member' }}</td>
                            <td class="p-3.5 sm:p-4 text-slate-600 text-xs">{{ $m->city ?? $group->city ?? 'UK' }}</td>
                            <td class="p-3.5 sm:p-4">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $m->pivot->membership_role === 'group_admin' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ str_replace('_', ' ', $m->pivot->membership_role ?? 'member') }}
                                </span>
                            </td>
                            <td class="p-3.5 sm:p-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($m->pivot->joined_at)->format('d M Y') }}
                            </td>
                            <td class="p-3.5 sm:p-4 text-right">
                                <a href="{{ route('member.directory.show', $m->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    <span>Profile</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-slate-500 font-semibold">
                                No registered members found in this group.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
