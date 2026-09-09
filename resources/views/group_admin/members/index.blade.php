@extends('layouts.dashboard')

@section('title', 'Manage Members - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Group Members ({{ $group->name }})</h1>
            <p class="text-sm text-slate-600 mt-1">Manage registrations, invite new members, and export CSV reports.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('group_admin.members.export_csv', $group->id) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition">
                📥 Export CSV
            </a>
            <a href="{{ route('group_admin.members.create', $group->id) }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition">
                + Add / Invite Member
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase">
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4">Profession</th>
                    <th class="p-4">City</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Joined At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($members as $m)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-900">{{ $m->name }}</td>
                        <td class="p-4 text-slate-600">{{ $m->email }}</td>
                        <td class="p-4 text-slate-600">{{ $m->phone ?? 'N/A' }}</td>
                        <td class="p-4 text-sky-600 font-semibold">{{ $m->profession ?? 'N/A' }}</td>
                        <td class="p-4 text-slate-600">{{ $m->city ?? 'N/A' }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $m->pivot->membership_role === 'group_admin' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $m->pivot->membership_role }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-slate-500">{{ \Carbon\Carbon::parse($m->pivot->joined_at)->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
