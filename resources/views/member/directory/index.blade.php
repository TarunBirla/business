@extends('layouts.dashboard')

@section('title', 'Community Member Directory')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-black">Community Member Directory</h1>
        <p class="text-sm text-black mt-1">Discover professionals, business owners, and service providers within your joined communities.</p>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('member.directory') }}" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-bold text-black mb-1">Search Name / Company</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Patel, Sharma, Apex..." class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-black mb-1">Filter by Community</label>
            <select name="group_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                <option value="">All My Communities</option>
                @foreach($myGroups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-black mb-1">Services Offered</label>
            <select name="service_offered" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                <option value="">Any Service</option>
                @foreach($allServices as $service)
                    <option value="{{ $service->id }}" {{ request('service_offered') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-sm shadow transition">
                Search Members
            </button>
        </div>
    </form>

    <!-- Member Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($members as $member)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-sky-100 text-sky-800 font-bold text-xl flex items-center justify-center border border-sky-200 shrink-0">
                            {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-black text-lg leading-tight">{{ $member->name }}</h3>
                            <p class="text-xs text-sky-600 font-medium mt-0.5">{{ $member->profession ?? 'Community Member' }}</p>
                            <p class="text-xs text-black mt-0.5"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $member->city ?? 'UK' }}</p>
                        </div>
                    </div>

                    @if($member->company)
                        <div class="text-xs text-black font-semibold mb-3">
                            <i class="fa-solid fa-building text-sky-600 mr-1"></i>{{ $member->company }}
                        </div>
                    @endif

                    <!-- Services Badges -->
                    @if($member->servicesOffered->count() > 0)
                        <div class="mb-3">
                            <span class="text-[10px] font-bold text-sky-700 uppercase block mb-1">Services Offered:</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach($member->servicesOffered->take(3) as $srv)
                                    <span class="px-2 py-0.5 bg-sky-50 text-sky-800 text-[11px] font-semibold rounded border border-sky-100">
                                        {{ $srv->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-4">
                    <span class="text-[11px] text-slate-400 font-medium">Privacy Protected <i class="fa-solid fa-lock text-slate-400 ml-1"></i></span>
                    <a href="{{ route('member.directory.show', $member->id) }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg transition">
                        View Profile & Connect
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200">
                <p class="text-black font-semibold">No members found matching your search criteria.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $members->links() }}
    </div>
</div>
@endsection
