@extends('layouts.dashboard')

@section('title', 'Manage All Communities')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-black">Manage Communities</h1>
            <p class="text-sm text-black mt-1">Create unlimited custom communities, edit settings, and assign Group Admins.</p>
        </div>
        <a href="{{ route('super_admin.groups.create') }}" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
            + Create New Community
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-black uppercase">
                    <th class="p-4">Community Name</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Members</th>
                    <th class="p-4">Events</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($groups as $group)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4">
                            <div class="font-bold text-black">{{ $group->name }}</div>
                            <div class="text-xs text-slate-400 font-mono">/join/{{ $group->slug }}</div>
                        </td>
                        <td class="p-4 text-black">{{ $group->city ?? 'UK' }}, {{ $group->country }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $group->community_type === 'free' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                                {{ $group->community_type }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-black">{{ number_format($group->members_count) }}</td>
                        <td class="p-4 text-black">{{ $group->events_count }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase bg-slate-100 text-black">
                                {{ $group->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('groups.show', $group->slug) }}" target="_blank" class="text-xs font-bold text-sky-600 hover:underline">Public Page</a>
                            <a href="{{ route('super_admin.groups.edit', $group->id) }}" class="px-3 py-1.5 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $groups->links() }}
        </div>
    </div>
</div>
@endsection
