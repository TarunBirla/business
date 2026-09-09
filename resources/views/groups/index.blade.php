@extends('layouts.app')

@section('title', 'Explore Communities UK')

@section('content')
<div class="bg-sky-50/50 py-12 border-b border-sky-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-900">Community Directory</h1>
        <p class="text-slate-600 mt-2">Find and join community networking groups across the UK.</p>

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('groups.index') }}" class="mt-8 bg-white p-4 rounded-2xl border border-sky-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Search Name or City</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Gujarati, London, Marathi..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Filter by City</label>
                <input type="text" name="city" value="{{ request('city') }}" placeholder="e.g. London, Birmingham" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Community Type</label>
                <select name="type" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-sky-500">
                    <option value="">All Types</option>
                    <option value="free" {{ request('type') == 'free' ? 'selected' : '' }}>Free</option>
                    <option value="paid" {{ request('type') == 'paid' ? 'selected' : '' }}>Paid Membership</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-lg text-sm shadow transition">
                    Filter Communities
                </button>
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($groups as $group)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                <div>
                    <div class="h-40 bg-gradient-to-r from-sky-400 to-sky-600 relative p-4 flex items-end">
                        <div class="w-16 h-16 rounded-xl bg-white p-1 shadow-md absolute -bottom-6 left-6 border-2 border-white">
                            <div class="w-full h-full bg-sky-100 rounded-lg flex items-center justify-center font-bold text-sky-700 text-xl uppercase">
                                {{ substr($group->name, 0, 2) }}
                            </div>
                        </div>
                        <span class="ml-auto bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-slate-800">
                            {{ ucfirst($group->community_type) }}
                        </span>
                    </div>
                    <div class="pt-8 px-6 pb-6">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-sky-600 transition">{{ $group->name }}</h3>
                        <p class="text-xs text-sky-600 font-medium mt-1 flex items-center">
                            <span><i class="fa-solid fa-location-dot text-rose-500 mr-1.5"></i>{{ $group->city ?? 'UK Wide' }}, {{ $group->country }}</span>
                        </p>
                        <p class="text-sm text-slate-600 mt-3 line-clamp-3 leading-relaxed">
                            {{ $group->description }}
                        </p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <div class="text-xs text-slate-500 font-semibold space-x-2">
                        <span><i class="fa-solid fa-users text-sky-600 mr-1"></i>{{ number_format($group->members_count) }} Members</span> &bull; 
                        <span><i class="fa-regular fa-calendar-days text-sky-600 mr-1"></i>{{ $group->events_count }} Events</span>
                    </div>
                    <a href="{{ route('groups.show', $group->slug) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                        View Community
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200">
                <p class="text-slate-500 font-semibold">No communities found matching your filter criteria.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $groups->links() }}
    </div>
</div>
@endsection
