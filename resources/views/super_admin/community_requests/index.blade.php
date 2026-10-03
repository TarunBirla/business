@extends('layouts.dashboard')

@section('title', 'Community Creation Requests')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-paper-plane text-sky-600"></i>
                <span>Community Creation Requests</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Review applicant requests to create new communities, approve and automatically initialize communities & group admins.</p>
        </div>
        @if($pendingCount > 0)
            <div class="px-3.5 py-1.5 bg-amber-50 border border-amber-200 text-amber-800 font-bold text-xs rounded-xl flex items-center space-x-2">
                <i class="fa-solid fa-clock text-amber-600"></i>
                <span>{{ $pendingCount }} Pending Approval{{ $pendingCount > 1 ? 's' : '' }}</span>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl font-bold flex items-center space-x-2 shadow-2xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl font-bold flex items-center space-x-2 shadow-2xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Status Tabs & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center space-x-2 text-xs font-bold">
            <a href="{{ route('super_admin.community_requests.index', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-lg transition {{ $status === 'all' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Requests
            </a>
            <a href="{{ route('super_admin.community_requests.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg transition flex items-center space-x-1.5 {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <span>Pending</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.5 bg-amber-600 text-white text-[10px] rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('super_admin.community_requests.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-lg transition {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Approved
            </a>
            <a href="{{ route('super_admin.community_requests.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-lg transition {{ $status === 'rejected' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Rejected
            </a>
        </div>

        <form method="GET" action="{{ route('super_admin.community_requests.index') }}" class="flex items-center space-x-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search applicant or community..." class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500 w-64">
            <button type="submit" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Search
            </button>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                        <th class="py-3.5 px-4">Community Requested</th>
                        <th class="py-3.5 px-4">Applicant (Group Leader)</th>
                        <th class="py-3.5 px-4">Location & Type</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Submitted At</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($communityRequests as $req)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden font-bold text-sky-700 uppercase">
                                        @if($req->image_url)
                                            <img src="{{ $req->image_url }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($req->community_name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $req->community_name }}</p>
                                        <p class="text-[11px] text-slate-500 line-clamp-1 max-w-xs">{{ $req->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-800">{{ $req->applicant_name }}</p>
                                <p class="text-[11px] text-slate-500">{{ $req->applicant_email }}</p>
                                @if($req->applicant_phone)
                                    <p class="text-[10px] text-slate-400"><i class="fa-solid fa-phone mr-1"></i>{{ $req->applicant_phone }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-slate-700">{{ $req->city ?? 'UK Wide' }}, {{ $req->country }}</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->community_type === 'paid' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $req->community_type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($req->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-bold text-[11px] flex items-center space-x-1 w-fit">
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                        <span>Pending Review</span>
                                    </span>
                                @elseif($req->status === 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[11px] flex items-center space-x-1 w-fit">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Approved</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full font-bold text-[11px] flex items-center space-x-1 w-fit">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                        <span>Rejected</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                {{ $req->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1">
                                <button type="button" onclick="openDetailsModal('{{ $req->id }}')" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition text-[11px]">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>

                                @if($req->status === 'pending')
                                    <form action="{{ route('super_admin.community_requests.approve', $req->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this request and create {{ addslashes($req->community_name) }}?');">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition text-[11px] shadow-2xs">
                                            <i class="fa-solid fa-check mr-1"></i> Approve & Create
                                        </button>
                                    </form>

                                    <button type="button" onclick="openRejectModal('{{ $req->id }}')" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-lg transition text-[11px]">
                                        <i class="fa-solid fa-xmark mr-1"></i> Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-paper-plane text-3xl mb-2 text-slate-300"></i>
                                <p>No community creation requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($communityRequests->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $communityRequests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Details Modal -->
@foreach($communityRequests as $req)
    <div id="detailsModal_{{ $req->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-slate-100 text-left max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                    <i class="fa-solid fa-users text-sky-600"></i>
                    <span>{{ $req->community_name }}</span>
                </h3>
                <button type="button" onclick="closeDetailsModal('{{ $req->id }}')" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
            </div>

            <div class="space-y-3 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <p class="font-bold text-slate-800 uppercase text-[10px]">Applicant (Group Leader)</p>
                    <p class="text-slate-900 font-bold text-sm">{{ $req->applicant_name }}</p>
                    <p class="text-slate-600">{{ $req->applicant_email }} | Phone: {{ $req->applicant_phone ?? 'N/A' }}</p>
                </div>

                @if($req->image_url)
                    <div>
                        <p class="font-bold text-slate-700 mb-1 uppercase text-[10px]">Cover / Logo Image</p>
                        <img src="{{ $req->image_url }}" class="w-full h-36 object-cover rounded-xl border">
                    </div>
                @endif

                <div>
                    <p class="font-bold text-slate-700 uppercase text-[10px]">Description</p>
                    <p class="text-slate-800 whitespace-pre-line leading-relaxed">{{ $req->description }}</p>
                </div>

                @if($req->purpose)
                    <div>
                        <p class="font-bold text-slate-700 uppercase text-[10px]">Purpose</p>
                        <p class="text-slate-800 whitespace-pre-line leading-relaxed">{{ $req->purpose }}</p>
                    </div>
                @endif

                @if($req->why_join)
                    <div>
                        <p class="font-bold text-slate-700 uppercase text-[10px]">Why Join</p>
                        <p class="text-slate-800 whitespace-pre-line leading-relaxed">{{ $req->why_join }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-2 text-slate-600 pt-2 border-t">
                    <p><strong>Type:</strong> {{ ucfirst($req->community_type) }}</p>
                    <p><strong>Location:</strong> {{ $req->city ?? 'UK Wide' }}, {{ $req->country }}</p>
                </div>
            </div>

            <div class="pt-3 border-t flex justify-end space-x-2">
                @if($req->status === 'pending')
                    <form action="{{ route('super_admin.community_requests.approve', $req->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs">
                            Approve & Create Community
                        </button>
                    </form>
                @endif
                <button type="button" onclick="closeDetailsModal('{{ $req->id }}')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal_{{ $req->id }}" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-100 text-left">
            <h3 class="font-bold text-slate-900 text-base">Reject Request - {{ $req->community_name }}</h3>
            <form action="{{ route('super_admin.community_requests.reject', $req->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Reason for Rejection (Optional)</label>
                    <textarea name="rejection_reason" rows="3" class="w-full px-3 py-2 border rounded-xl text-xs focus:outline-none focus:border-rose-500" placeholder="State why this application was not approved..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeRejectModal('{{ $req->id }}')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
function openDetailsModal(id) {
    const el = document.getElementById('detailsModal_' + id);
    if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
}
function closeDetailsModal(id) {
    const el = document.getElementById('detailsModal_' + id);
    if (el) { el.classList.remove('flex'); el.classList.add('hidden'); }
}
function openRejectModal(id) {
    const el = document.getElementById('rejectModal_' + id);
    if (el) { el.classList.remove('hidden'); el.classList.add('flex'); }
}
function closeRejectModal(id) {
    const el = document.getElementById('rejectModal_' + id);
    if (el) { el.classList.remove('flex'); el.classList.add('hidden'); }
}
</script>
@endsection
