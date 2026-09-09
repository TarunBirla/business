@extends('layouts.dashboard')

@section('title', $user->name . ' - Member Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Profile Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
        <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-sky-600 to-sky-400 text-white font-bold text-3xl flex items-center justify-center border-4 border-white shadow-lg shrink-0">
            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
        </div>
        <div class="flex-grow text-center md:text-left space-y-2">
            <h1 class="text-3xl font-bold text-slate-900">{{ $user->name }}</h1>
            <p class="text-base text-sky-600 font-semibold">{{ $user->profession ?? 'Community Professional' }} {{ $user->company ? 'at ' . $user->company : '' }}</p>
            <p class="text-sm text-slate-500">📍 {{ $user->city ?? 'UK' }}, {{ $user->country }}</p>

            <div class="pt-4 flex flex-wrap justify-center md:justify-start gap-3">
                @if(!$connection)
                    <form action="{{ route('member.connections.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="hidden" name="group_id" value="{{ $activeGroupId }}">
                        <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm shadow transition">
                            🤝 Connect
                        </button>
                    </form>
                @elseif($connection->status === 'pending')
                    <span class="px-5 py-2.5 bg-amber-50 text-amber-800 font-bold rounded-xl text-sm border border-amber-200">
                        ⏳ Connection Request Pending
                    </span>
                @elseif($connection->status === 'accepted')
                    <span class="px-5 py-2.5 bg-emerald-50 text-emerald-800 font-bold rounded-xl text-sm border border-emerald-200">
                        ✓ Connected
                    </span>
                @endif

                <a href="{{ route('member.chat', ['groupId' => $activeGroupId, 'receiverId' => $user->id]) }}" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm shadow transition">
                    💬 Direct Message
                </a>
            </div>
        </div>
    </div>

    <!-- Contact Details Card & Privacy Controls -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xl font-bold text-slate-900 border-b pb-4">Contact Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase block mb-1">Email Address</span>
                @if($canSeeEmail)
                    <span class="text-sm font-bold text-slate-900">{{ $user->email }}</span>
                @else
                    <span class="text-sm font-medium text-slate-400 italic">Protected (Hidden by member privacy)</span>
                @endif
            </div>

            <div>
                <span class="text-xs font-bold text-slate-500 uppercase block mb-1">Phone Number</span>
                @if($canSeePhone)
                    <span class="text-sm font-bold text-slate-900">{{ $user->phone ?? 'Not provided' }}</span>
                @else
                    <span class="text-sm font-medium text-slate-400 italic">Protected (Hidden by member privacy)</span>
                @endif
            </div>
        </div>

        @if(!$canSeeEmail || !$canSeePhone)
            <div class="pt-4 border-t border-slate-100 mt-4">
                @if(!$contactRequest)
                    <form action="{{ route('member.contact_request.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="hidden" name="group_id" value="{{ $activeGroupId }}">
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition">
                            📞 Request Contact Details Access
                        </button>
                    </form>
                @else
                    <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg inline-block">
                        Contact details request: {{ ucfirst($contactRequest->status) }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Biography -->
    @if($user->description)
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 mb-4">About {{ $user->first_name }}</h3>
            <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $user->description }}</p>
        </div>
    @endif

    <!-- Services Offered & Needed -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-sky-800 mb-4">Services Offered</h3>
            <div class="flex flex-wrap gap-2">
                @forelse($user->servicesOffered as $srv)
                    <span class="px-3 py-1 bg-sky-50 text-sky-800 text-xs font-semibold rounded-lg border border-sky-100">
                        ✓ {{ $srv->name }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400">No services listed yet.</span>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-amber-800 mb-4">Services Needed</h3>
            <div class="flex flex-wrap gap-2">
                @forelse($user->servicesNeeded as $srv)
                    <span class="px-3 py-1 bg-amber-50 text-amber-800 text-xs font-semibold rounded-lg border border-amber-100">
                        🔍 {{ $srv->name }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400">No services requested yet.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
