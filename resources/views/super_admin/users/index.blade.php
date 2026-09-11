@extends('layouts.dashboard')

@section('title', 'Manage Platform Users')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-black">Platform Users Directory</h1>
            <p class="text-sm text-black mt-1">View all registered platform members, roles, and status controls.</p>
        </div>
        <a href="{{ route('super_admin.users.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
            <i class="fa-solid fa-user-plus"></i>
            <span>Add New User</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-black uppercase">
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Profession</th>
                    <th class="p-4">Communities Joined</th>
                    <th class="p-4">Global Role</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($users as $u)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-black">{{ $u->name }}</td>
                        <td class="p-4 text-black">{{ $u->email }}</td>
                        <td class="p-4 text-sky-600 font-semibold">{{ $u->profession ?? 'Member' }}</td>
                        <td class="p-4 text-black font-bold">{{ $u->groups->count() }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $u->isSuperAdmin() ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-black' }}">
                                {{ $u->global_role }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $u->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $u->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            @if(!$u->isSuperAdmin())
                                <form action="{{ route('super_admin.users.toggle_status', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-black font-bold text-xs rounded-lg">
                                        {{ $u->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
