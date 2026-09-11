@extends('layouts.dashboard')

@section('title', 'Community Member Directory')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Community Member Directory</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Discover professionals, business owners, and service providers across your communities.</p>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('member.directory') }}" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3.5">
        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Search Name / Company</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Patel, Sharma, Tech..." class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-sky-500 font-medium">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Filter by Community</label>
            <select name="group_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-sky-500 font-medium">
                <option value="">All My Communities</option>
                @foreach($myGroups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Services Offered</label>
            <select name="service_offered" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-sky-500 font-medium">
                <option value="">Any Service</option>
                @foreach($allServices as $service)
                    <option value="{{ $service->id }}" {{ request('service_offered') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-grow py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl text-xs sm:text-sm shadow-sm transition flex items-center justify-center space-x-1.5" style="background-color: var(--btn-primary-bg, #0284c7);">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Search</span>
            </button>
            @if(request()->anyFilled(['search', 'group_id', 'service_offered']))
                <a href="{{ route('member.directory') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- Professional Responsive Table (Fits 14-inch Laptop Viewports) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[768px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                        <th class="p-4">Member</th>
                        <th class="p-4">Company & Location</th>
                        <th class="p-4">Services Offered</th>
                        <th class="p-4">Community</th>
                        <th class="p-4">Privacy</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($members as $member)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Member Profile -->
                            <td class="p-3.5 sm:p-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0" style="background-color: var(--btn-primary-bg, #0284c7);">
                                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('member.directory.show', $member->id) }}" class="font-bold text-slate-900 hover:text-sky-600 transition block truncate text-sm">
                                            {{ $member->name }}
                                        </a>
                                        <div class="text-xs text-sky-600 font-medium truncate">{{ $member->profession ?? 'Community Member' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Company & Location -->
                            <td class="p-3.5 sm:p-4">
                                <div class="space-y-0.5">
                                    @if($member->company)
                                        <div class="font-semibold text-slate-800 text-xs flex items-center space-x-1">
                                            <i class="fa-solid fa-building text-slate-400 text-[10px]"></i>
                                            <span class="truncate">{{ $member->company }}</span>
                                        </div>
                                    @endif
                                    <div class="text-xs text-slate-500 flex items-center space-x-1">
                                        <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                                        <span>{{ $member->city ?? 'United Kingdom' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Services Offered -->
                            <td class="p-3.5 sm:p-4">
                                @if($member->servicesOffered->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($member->servicesOffered->take(2) as $srv)
                                            <span class="px-2 py-0.5 bg-sky-50 text-sky-800 text-[11px] font-semibold rounded-md border border-sky-100">
                                                {{ $srv->name }}
                                            </span>
                                        @endforeach
                                        @if($member->servicesOffered->count() > 2)
                                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded">
                                                +{{ $member->servicesOffered->count() - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">None listed</span>
                                @endif
                            </td>

                            <!-- Community Badges -->
                            <td class="p-3.5 sm:p-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($member->groups->take(2) as $g)
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[11px] font-bold rounded-md">
                                            {{ $g->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Privacy / Status -->
                            <td class="p-3.5 sm:p-4">
                                <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                    <i class="fa-solid fa-shield-halved text-slate-400 text-[10px]"></i>
                                    <span>Protected</span>
                                </span>
                            </td>

                            <!-- Action -->
                            <td class="p-3.5 sm:p-4 text-right">
                                <a href="{{ route('member.directory.show', $member->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition">
                                    <span>Profile</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-500 font-semibold">
                                <div class="flex flex-col items-center space-y-2">
                                    <i class="fa-solid fa-users-slash text-3xl text-slate-300"></i>
                                    <p>No members found matching your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
