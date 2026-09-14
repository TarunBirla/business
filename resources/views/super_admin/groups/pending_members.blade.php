@extends('layouts.dashboard')

@section('title', 'Super Admin - Global Pending Approvals')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Global Pending Approvals</h1>
            <p class="text-xs text-slate-500 mt-1">Review pending account applications and community join requests across all communities.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                        <th class="py-3.5 px-4">Applicant</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Requested Community</th>
                        <th class="py-3.5 px-4">Account Status</th>
                        <th class="py-3.5 px-4">Date Registered</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pendingUsers as $user)
                        @foreach($user->groups as $group)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $user->name }}</td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $user->email }}</td>
                                <td class="py-3.5 px-4 font-bold text-sky-600">{{ $group->name }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full font-bold text-[10px] uppercase bg-amber-100 text-amber-800">
                                        {{ $group->pivot->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="py-3.5 px-4 text-right space-x-2">
                                    <form action="{{ route('super_admin.groups.approve_member', [$group->id, $user->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition">
                                            <i class="fa-solid fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('super_admin.groups.reject_member', [$group->id, $user->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg transition">
                                            <i class="fa-solid fa-xmark mr-1"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No global pending membership requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $pendingUsers->links() }}
        </div>
    </div>
</div>
@endsection
