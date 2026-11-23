@extends('layouts.dashboard')

@section('title', 'My Connections Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Connections Center</h1>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">Manage your accepted connections, pending requests, and suggested community members.</p>
    </div>

    <!-- Received Connection Requests -->
    @if($receivedRequests->count() > 0)
        <div class="bg-amber-50/60 p-4 sm:p-6 rounded-2xl border border-amber-200">
            <h3 class="text-base sm:text-lg font-bold text-amber-900 mb-3 sm:mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-user-clock text-amber-600"></i>
                <span>Pending Connection Requests ({{ $receivedRequests->count() }})</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                @foreach($receivedRequests as $req)
                    <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-amber-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-bold text-slate-900 text-sm truncate">{{ $req->sender->name }}</div>
                            <div class="text-xs text-slate-500 truncate">{{ $req->sender->profession ?? 'Member' }} &bull; {{ $req->group->name }}</div>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0">
                            <form action="{{ route('member.connections.accept', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-2xs transition active:scale-95">Accept</button>
                            </form>
                            <form action="{{ route('member.connections.reject', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition active:scale-95">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- My Accepted Connections -->
    <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl border border-slate-200 shadow-2xs">
        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 sm:mb-6 flex items-center space-x-2">
            <i class="fa-solid fa-user-group text-sky-600" style="color: var(--text-link, #0A4744);"></i>
            <span>My Connections ({{ $myConnections->total() ?? $myConnections->count() }})</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4 md:gap-6">
            @forelse($myConnections as $conn)
                @php
                    $connectedUser = ($conn->sender_id === auth()->id()) ? $conn->receiver : $conn->sender;
                @endphp
                <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-slate-50/70 flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-10 h-10 rounded-full text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0" style="background-color: var(--btn-primary-bg, #0A4744);">
                            {{ strtoupper(substr($connectedUser->first_name, 0, 1) . substr($connectedUser->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-slate-900 text-sm truncate">{{ $connectedUser->name }}</div>
                            <div class="text-xs text-slate-500 font-medium truncate">{{ $connectedUser->profession ?? 'Member' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1.5 shrink-0">
                        <a href="{{ route('member.directory.show', $connectedUser->id) }}" class="px-3 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-100 shadow-2xs transition active:scale-95">
                            Profile
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 col-span-1 sm:col-span-2 lg:col-span-3 text-slate-500">
                    <i class="fa-solid fa-users-slash text-3xl text-slate-300 mb-2 block"></i>
                    <p class="text-xs sm:text-sm font-medium">No accepted connections yet. Explore the member directory to connect.</p>
                    <a href="{{ route('member.directory') }}" class="inline-block mt-2.5 text-xs font-bold text-sky-600 hover:underline" style="color: var(--text-link, #0A4744);">Explore Member Directory &rarr;</a>
                </div>
            @endforelse
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $myConnections->links() }}
        </div>
    </div>
</div>
@endsection
