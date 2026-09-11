@extends('layouts.dashboard')

@section('title', 'Group Admins Directory')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Group Administrators Directory</h1>
            <p class="text-sm text-slate-600 mt-1">Manage community administrators, assigned groups, and access permissions.</p>
        </div>
        <div class="flex items-center space-x-3">
            <form action="{{ route('super_admin.group_admins.index') }}" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search admins..." class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-sky-500">
                <button type="submit" class="px-4 py-2 bg-sky-600 text-white font-bold text-sm rounded-xl hover:bg-sky-700 transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <a href="{{ route('super_admin.group_admins.create') }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition flex items-center space-x-2 shrink-0">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add Group Admin</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase">
                    <th class="p-4">Admin Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4">Managed Communities</th>
                    <th class="p-4">Global Role</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($groupAdmins as $admin)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4">
                            <div class="font-bold text-slate-900">{{ $admin->name }}</div>
                            <div class="text-xs text-slate-500">{{ $admin->profession ?? 'Community Leader' }}</div>
                        </td>
                        <td class="p-4 text-slate-700 font-medium">{{ $admin->email }}</td>
                        <td class="p-4 text-slate-600">{{ $admin->phone ?? 'N/A' }}</td>
                        <td class="p-4">
                            @php
                                $managedGroups = $admin->groups->filter(function($g) {
                                    return $g->pivot->membership_role === 'group_admin';
                                });
                            @endphp
                            @if($managedGroups->count() > 0)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($managedGroups as $g)
                                        <a href="{{ route('super_admin.groups.members', $g->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 hover:bg-sky-100 transition">
                                            <i class="fa-solid fa-people-group mr-1.5 text-[10px]"></i>
                                            {{ $g->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No assigned communities</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $admin->isSuperAdmin() ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $admin->global_role }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $admin->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $admin->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 font-semibold">
                            No Group Administrators found matching criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $groupAdmins->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
