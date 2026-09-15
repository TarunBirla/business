@extends('layouts.dashboard')

@section('title', 'Group Admin Connections')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-bold text-sky-600 uppercase tracking-wider">Group Admin Control</div>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">Community Member Connections</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage connection requests from community members. Once accepted, your email and phone number will be shared with the member.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-200 flex items-center space-x-1.5">
                <i class="fa-solid fa-user-check text-emerald-600"></i>
                <span>{{ $myConnections->total() }} Active Connections</span>
            </span>
            @if($receivedRequests->count() > 0)
                <span class="px-3.5 py-1.5 bg-rose-50 text-rose-800 text-xs font-bold rounded-xl border border-rose-200 flex items-center space-x-1.5">
                    <i class="fa-solid fa-bell text-rose-600 animate-pulse"></i>
                    <span>{{ $receivedRequests->count() }} Pending Requests</span>
                </span>
            @endif
        </div>
    </div>

    <!-- PENDING RECEIVED CONNECTION REQUESTS -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b pb-3 border-slate-100">
            <h3 class="font-bold text-lg text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-user-clock text-amber-500"></i>
                <span>Pending Received Connection Requests</span>
            </h3>
            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-full border border-amber-200">
                {{ $receivedRequests->count() }} Pending
            </span>
        </div>

        @if($receivedRequests->isEmpty())
            <div class="p-8 text-center text-slate-400">
                <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                <p class="text-xs font-medium">No pending connection requests received.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($receivedRequests as $req)
                    @php $sender = $req->sender; @endphp
                    @if($sender)
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col justify-between space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-11 h-11 rounded-full text-white font-bold flex items-center justify-center text-sm shadow-2xs shrink-0" style="background-color: var(--btn-primary-bg, #0A4744);">
                                    {{ strtoupper(substr($sender->first_name, 0, 1) . substr($sender->last_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-sm text-slate-900 truncate">{{ $sender->name }}</h4>
                                    <p class="text-xs text-sky-600 font-semibold truncate">{{ $sender->profession ?? 'Member' }}</p>
                                    <p class="text-[11px] text-slate-500 truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $sender->city ?? 'UK' }} &bull; {{ $req->group->name ?? 'Community' }}</p>
                                </div>
                            </div>

                            <div class="p-2.5 bg-amber-50/70 border border-amber-200/60 rounded-xl text-[11px] text-amber-900 font-medium">
                                <i class="fa-solid fa-lock text-amber-600 mr-1"></i> Accept connection to reveal email & phone details.
                            </div>

                            <div class="flex items-center space-x-2 pt-1">
                                <form action="{{ route('group_admin.connections.accept', $req->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-2xs flex items-center justify-center space-x-1">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Accept</span>
                                    </button>
                                </form>
                                <form action="{{ route('group_admin.connections.reject', $req->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center space-x-1">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>Decline</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    <!-- ACCEPTED / CONNECTED MEMBERS LIST -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b pb-3 border-slate-100">
            <h3 class="font-bold text-lg text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-user-check text-emerald-600"></i>
                <span>Connected Members & Unlocked Contacts</span>
            </h3>
            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-200">
                {{ $myConnections->total() }} Connected
            </span>
        </div>

        @if($myConnections->isEmpty())
            <div class="p-8 text-center text-slate-400">
                <i class="fa-solid fa-users text-3xl mb-2 block"></i>
                <p class="text-xs font-medium">No accepted member connections yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($myConnections as $conn)
                    @php
                        $otherUser = ($conn->sender_id === auth()->id()) ? $conn->receiver : $conn->sender;
                    @endphp
                    @if($otherUser)
                        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-2xs hover:shadow-md transition flex flex-col justify-between space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 rounded-full text-white font-bold flex items-center justify-center text-sm shadow-2xs shrink-0" style="background-color: var(--btn-primary-bg, #0A4744);">
                                    {{ strtoupper(substr($otherUser->first_name, 0, 1) . substr($otherUser->last_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-sm text-slate-900 truncate">{{ $otherUser->name }}</h4>
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-extrabold rounded-full uppercase">Connected</span>
                                    </div>
                                    <p class="text-xs text-sky-600 font-semibold truncate">{{ $otherUser->profession ?? 'Member' }}</p>
                                    <p class="text-[11px] text-slate-500 truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $otherUser->city ?? 'UK' }}</p>
                                </div>
                            </div>

                            <!-- Unlocked Contact Information -->
                            <div class="p-3 bg-emerald-50/60 border border-emerald-200/60 rounded-xl space-y-1.5 text-xs">
                                <div class="flex items-center space-x-2 text-slate-800">
                                    <i class="fa-solid fa-envelope text-sky-600 w-4"></i>
                                    <a href="mailto:{{ $otherUser->email }}" class="font-bold hover:underline truncate">{{ $otherUser->email }}</a>
                                </div>
                                <div class="flex items-center space-x-2 text-slate-800">
                                    <i class="fa-solid fa-phone text-emerald-600 w-4"></i>
                                    <a href="tel:{{ $otherUser->phone }}" class="font-bold hover:underline">{{ $otherUser->phone ?? 'Phone N/A' }}</a>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 pt-1 border-t border-slate-100">
                                <a href="{{ route('member.directory.show', $otherUser->id) }}" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition text-center flex items-center justify-center space-x-1">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('bizcard.show', $otherUser->id) }}" target="_blank" class="flex-1 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl transition text-center flex items-center justify-center space-x-1" style="background-color: var(--btn-primary-bg, #0A4744);">
                                    <i class="fa-solid fa-id-card text-[10px]"></i>
                                    <span>Card</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="pt-2">
                {{ $myConnections->links() }}
            </div>
        @endif
    </div>

    <!-- PENDING SENT CONNECTION REQUESTS -->
    @if($sentRequests->isNotEmpty())
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-slate-100">
                <h3 class="font-bold text-base text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane text-sky-600"></i>
                    <span>Pending Sent Requests</span>
                </h3>
                <span class="px-2.5 py-1 bg-sky-50 text-sky-800 text-xs font-bold rounded-full border border-sky-200">
                    {{ $sentRequests->count() }} Sent
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($sentRequests as $sReq)
                    @php $receiver = $sReq->receiver; @endphp
                    @if($receiver)
                        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-9 h-9 rounded-full text-white font-bold flex items-center justify-center text-xs shrink-0" style="background-color: var(--btn-primary-bg, #0A4744);">
                                    {{ strtoupper(substr($receiver->first_name, 0, 1) . substr($receiver->last_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-sm text-slate-900 truncate">{{ $receiver->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $receiver->profession ?? 'Member' }}</div>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 text-[10px] font-bold rounded-full border border-amber-200 shrink-0">
                                Awaiting Approval
                            </span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
