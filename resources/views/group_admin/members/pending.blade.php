@extends('layouts.dashboard')

@section('title', 'Pending Member Approvals - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Pending Approvals</h1>
            <p class="text-xs text-slate-500 mt-1">Review and approve member join requests for <strong>{{ $group->name }}</strong>.</p>
        </div>
        <a href="{{ route('group_admin.members.index', $group->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Active Members
        </a>
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
                        <th class="py-3.5 px-4">Applicant Name</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Profession</th>
                        <th class="py-3.5 px-4">City</th>
                        <th class="py-3.5 px-4">Requested At</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pendingMembers as $member)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $member->name }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $member->email }}</td>
                            <td class="py-3.5 px-4 text-slate-700">{{ $member->profession ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4 text-slate-700">{{ $member->city ?? 'N/A' }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y') : '—' }}</td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <form action="{{ route('group_admin.members.approve', [$group->id, $member->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-2xs">
                                        <i class="fa-solid fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('group_admin.members.reject', [$group->id, $member->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg transition shadow-2xs">
                                        <i class="fa-solid fa-xmark mr-1"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No pending membership requests found for this community.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $pendingMembers->links() }}
        </div>
    </div>
</div>
@endsection
