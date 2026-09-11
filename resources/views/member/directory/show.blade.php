@extends('layouts.dashboard')

@section('title', $user->name . ' - Member Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Profile Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
        <div class="w-24 h-24 rounded-full text-white font-bold text-3xl flex items-center justify-center border-4 border-white shadow-lg shrink-0" style="background-color: var(--btn-primary-bg);">
            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
        </div>
        <div class="flex-grow text-center md:text-left space-y-2">
            <h1 class="text-3xl font-bold text-black">{{ $user->name }}</h1>
            <p class="text-base text-sky-600 font-semibold">{{ $user->profession ?? 'Community Professional' }} {{ $user->company ? 'at ' . $user->company : '' }}</p>
            <p class="text-sm text-black"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $user->city ?? 'UK' }}, {{ $user->country }}</p>

            <div class="pt-4 flex flex-wrap justify-center md:justify-start gap-3">
                @if(!$connection)
                    <form action="{{ route('member.connections.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="hidden" name="group_id" value="{{ $activeGroupId }}">
                        <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm shadow transition flex items-center">
                            <i class="fa-solid fa-user-plus mr-1.5"></i> Connect
                        </button>
                    </form>
                @elseif($connection->status === 'pending')
                    <span class="px-5 py-2.5 bg-amber-50 text-amber-800 font-bold rounded-xl text-sm border border-amber-200 flex items-center">
                        <i class="fa-regular fa-clock text-amber-600 mr-1.5"></i> Connection Request Pending
                    </span>
                @elseif($connection->status === 'accepted')
                    <span class="px-5 py-2.5 bg-emerald-50 text-emerald-800 font-bold rounded-xl text-sm border border-emerald-200 flex items-center">
                        <i class="fa-solid fa-check text-emerald-600 mr-1.5"></i> Connected
                    </span>
                @endif

                <a href="{{ route('member.chat', ['groupId' => $activeGroupId, 'receiverId' => $user->id]) }}" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm shadow transition flex items-center">
                    <i class="fa-solid fa-comments text-sky-400 mr-1.5"></i> Direct Message
                </a>

                <a href="{{ route('bizcard.show', $user->id) }}" target="_blank" class="px-6 py-2.5 bg-sky-50 text-sky-700 hover:bg-sky-100 font-bold rounded-xl text-sm border border-sky-200 shadow-sm transition flex items-center">
                    <i class="fa-solid fa-id-card text-sky-600 mr-1.5"></i> Business Card
                </a>
            </div>
        </div>
    </div>

    <!-- Contact Details Card & Privacy Controls -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xl font-bold text-black border-b pb-4">Contact Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <span class="text-xs font-bold text-black uppercase block mb-1">Email Address</span>
                @if($user->privacy_settings['show_email'] ?? false)
                    <span class="text-sm font-semibold text-slate-800">{{ $user->email }}</span>
                @elseif($approvedContactRequest)
                    <span class="text-sm font-semibold text-slate-800">{{ $user->email }}</span>
                @else
                    <span class="text-xs text-slate-400 italic">Hidden (GDPR Privacy)</span>
                @endif
            </div>

            <div>
                <span class="text-xs font-bold text-black uppercase block mb-1">Phone Number</span>
                @if($user->privacy_settings['show_phone'] ?? false)
                    <span class="text-sm font-semibold text-slate-800">{{ $user->phone ?? 'N/A' }}</span>
                @elseif($approvedContactRequest)
                    <span class="text-sm font-semibold text-slate-800">{{ $user->phone ?? 'N/A' }}</span>
                @else
                    <span class="text-xs text-slate-400 italic">Hidden (GDPR Privacy)</span>
                @endif
            </div>
        </div>

        @if(!($user->privacy_settings['show_email'] ?? false) && !$approvedContactRequest)
            <div class="pt-4 border-t border-slate-100">
                @if(!$contactRequest)
                    <form action="{{ route('member.contact_request.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="px-5 py-2 bg-sky-50 text-sky-700 hover:bg-sky-100 font-bold text-xs rounded-lg transition border border-sky-200">
                            Request Contact Details Access
                        </button>
                    </form>
                @else
                    <span class="text-xs font-bold text-black bg-slate-100 px-3 py-1.5 rounded-lg inline-block">
                        Contact details request: {{ ucfirst($contactRequest->status) }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Biography -->
    @if($user->description)
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-xl font-bold text-black mb-4">About {{ $user->first_name }}</h3>
            <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $user->description }}</p>
        </div>
    @endif

    <!-- Portfolio Projects Showcase -->
    @if($user->projects->isNotEmpty())
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <h3 class="text-xl font-bold text-black">Portfolio & Key Accomplishments</h3>
                <span class="text-xs font-bold px-3 py-1 bg-sky-50 text-sky-700 rounded-full border border-sky-200">
                    {{ $user->projects->count() }} Projects
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($user->projects as $project)
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="h-36 bg-slate-200 relative overflow-hidden flex items-center justify-center">
                                @if($project->image_url)
                                    <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-folder-open text-3xl text-slate-400"></i>
                                @endif
                                @if($project->category)
                                    <span class="absolute top-2 right-2 px-2.5 py-0.5 bg-white/90 text-slate-800 text-[10px] font-bold rounded-full">
                                        {{ $project->category }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-4 space-y-2">
                                <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ $project->title }}</h4>
                                <p class="text-xs text-slate-600 line-clamp-2">{{ $project->description }}</p>
                            </div>
                        </div>

                        @if($project->project_url)
                            <div class="px-4 py-3 bg-white border-t border-slate-100 flex items-center justify-between">
                                <a href="{{ $project->project_url }}" target="_blank" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                                    <span>View Live Project</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
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
                        <i class="fa-solid fa-magnifying-glass text-sky-600 mr-1 text-xs"></i> {{ $srv->name }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400">No services requested yet.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
