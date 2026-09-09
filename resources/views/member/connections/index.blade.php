@extends('layouts.dashboard')

@section('title', 'My Connections Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Connections Center</h1>
        <p class="text-sm text-slate-600 mt-1">Manage your accepted connections, pending requests, and suggested community members.</p>
    </div>

    <!-- Received Connection Requests -->
    @if($receivedRequests->count() > 0)
        <div class="bg-amber-50/60 p-6 rounded-2xl border border-amber-200">
            <h3 class="text-lg font-bold text-amber-900 mb-4">Pending Connection Requests ({{ $receivedRequests->count() }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($receivedRequests as $req)
                    <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-sm flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $req->sender->name }}</div>
                            <div class="text-xs text-slate-500">{{ $req->sender->profession }} &bull; {{ $req->group->name }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('member.connections.accept', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow">Accept</button>
                            </form>
                            <form action="{{ route('member.connections.reject', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-lg">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- My Accepted Connections -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <h3 class="text-xl font-bold text-slate-900 mb-6">My Connections ({{ $myConnections->count() }})</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($myConnections as $conn)
                @php
                    $connectedUser = ($conn->sender_id === auth()->id()) ? $conn->receiver : $conn->sender;
                @endphp
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-sm">
                            {{ substr($connectedUser->first_name, 0, 1) }}{{ substr($connectedUser->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $connectedUser->name }}</div>
                            <div class="text-xs text-sky-600 font-medium">{{ $connectedUser->profession ?? 'Member' }}</div>
                        </div>
                    </div>
                    <a href="{{ route('member.directory.show', $connectedUser->id) }}" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-100">
                        Profile
                    </a>
                </div>
            @empty
                <p class="text-sm text-slate-500 col-span-3">No accepted connections yet. Explore the member directory to connect.</p>
            @endforelse
        </div>
    </div>

    <!-- Suggested Connections -->
    @if($suggestedConnections->count() > 0)
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Suggested Connections for You</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($suggestedConnections as $sug)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between space-y-3">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $sug->name }}</div>
                            <div class="text-xs text-sky-600 font-medium">{{ $sug->profession ?? 'Member' }}</div>
                            <div class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $sug->city }}</div>
                        </div>
                        <a href="{{ route('member.directory.show', $sug->id) }}" class="w-full py-2 bg-sky-600 hover:bg-sky-700 text-white text-center font-bold text-xs rounded-lg transition">
                            View Profile & Connect
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
