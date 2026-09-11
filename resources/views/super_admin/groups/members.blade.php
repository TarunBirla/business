@extends('layouts.dashboard')

@section('title', 'Community Members - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('super_admin.groups.index') }}" class="text-sm font-bold text-sky-600 hover:text-sky-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Communities
                </a>
                <span class="text-slate-400">/</span>
                <span class="text-sm font-bold text-slate-600">Member List</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 mt-1">{{ $group->name }} - Members</h1>
            <p class="text-sm text-slate-600 mt-1">Viewing all {{ $group->members->count() }} active members registered in this community.</p>
        </div>

        <div class="flex items-center space-x-3">
            <form action="{{ route('super_admin.groups.members', $group->id) }}" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search members..." class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-sky-500">
                <button type="submit" class="px-4 py-2 bg-sky-600 text-white font-bold text-sm rounded-xl hover:bg-sky-700 transition">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase">
                    <th class="p-4">Member Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Profession</th>
                    <th class="p-4">City</th>
                    <th class="p-4">Group Role</th>
                    <th class="p-4">Joined Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($members as $m)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-900">
                            {{ $m->name }}
                        </td>
                        <td class="p-4 text-slate-700">{{ $m->email }}</td>
                        <td class="p-4 text-sky-600 font-semibold">{{ $m->profession ?? 'Member' }}</td>
                        <td class="p-4 text-slate-600">{{ $m->city ?? $group->city ?? 'N/A' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $m->pivot->membership_role === 'group_admin' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-800' }}">
                                {{ str_replace('_', ' ', $m->pivot->membership_role ?? 'member') }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500 text-xs">
                            {{ $m->pivot->joined_at ? \Carbon\Carbon::parse($m->pivot->joined_at)->format('d M Y') : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-500 font-semibold">
                            No members found in this community matching search criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
