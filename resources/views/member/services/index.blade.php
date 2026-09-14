@extends('layouts.dashboard')

@section('title', 'Services Exchange')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Services & Marketplace</h1>
            <p class="text-xs text-slate-500 mt-1">Offer your skills, explore professional services from community members, and connect directly.</p>
        </div>
        <button onclick="openCreateServiceModal()" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-2 shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Offer a Service</span>
        </button>
    </div>

    <!-- Main Navigation Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('member.services.index', ['tab' => 'my_services']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center space-x-2 {{ $tab === 'my_services' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fa-solid fa-user-gear"></i>
            <span>My Services ({{ $myServices->count() }})</span>
        </a>
        <a href="{{ route('member.services.index', ['tab' => 'others_services']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center space-x-2 {{ $tab === 'others_services' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fa-solid fa-handshake"></i>
            <span>Other Services ({{ $othersServices->count() }})</span>
        </a>
        <a href="{{ route('member.services.index', ['tab' => 'requests']) }}" 
           class="px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition flex items-center space-x-2 {{ $tab === 'requests' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200' }}">
            <i class="fa-solid fa-envelope-open-text"></i>
            <span>Service Requests ({{ $receivedRequests->where('status', 'pending')->count() }} New)</span>
        </a>
    </div>

    <!-- Filter & Search Bar (For Services Tabs) -->
    @if($tab !== 'requests')
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <form action="{{ route('member.services.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex-grow w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search services by keyword..." class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500 font-medium">
                </div>
                <select name="category" class="w-full sm:w-48 px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500 font-medium">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-900 text-white font-bold text-xs rounded-xl hover:bg-slate-800 transition">
                    Filter
                </button>
            </form>
        </div>
    @endif

    <!-- TAB CONTENT: MY SERVICES -->
    @if($tab === 'my_services')
        @if($myServices->isEmpty())
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-4">
                <div class="w-16 h-16 bg-sky-50 text-sky-600 rounded-full flex items-center justify-center text-2xl mx-auto border border-sky-100">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">No Services Offered Yet</h3>
                    <p class="text-xs text-slate-500 mt-1">Publish services you offer to connect with members and grow your professional reach.</p>
                </div>
                <button onclick="openCreateServiceModal()" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition inline-flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Your First Service</span>
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myServices as $service)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between overflow-hidden">
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-[11px] font-bold rounded-lg border border-sky-100">
                                    {{ $service->category }}
                                </span>
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg {{ $service->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $service->title }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{!! nl2br(e($service->description)) !!}</p>

                            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                                <span class="font-bold text-slate-900">
                                    @if($service->price_type === 'free')
                                        Free Service
                                    @elseif($service->price_type === 'quote')
                                        Quote on Request
                                    @else
                                        £{{ number_format($service->price, 2) }} {{ $service->price_type === 'hourly' ? '/ hr' : '' }}
                                    @endif
                                </span>
                                <span class="text-slate-500 font-medium">
                                    <i class="fa-solid fa-layer-group text-sky-600 mr-1"></i>{{ $service->group ? $service->group->name : 'All My Communities' }}
                                </span>
                            </div>
                        </div>

                        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-bold">
                                <i class="fa-solid fa-inbox mr-1"></i>{{ $service->requests->count() }} Requests
                            </span>
                            <div class="flex items-center space-x-2">
                                <button onclick='openEditServiceModal(@json($service))' class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-lg border border-slate-200 transition">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                                </button>
                                <form action="{{ route('member.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-lg border border-rose-200 transition">
                                        <i class="fa-solid fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    <!-- TAB CONTENT: OTHER SERVICES -->
    @if($tab === 'others_services')
        @if($othersServices->isEmpty())
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-2xl mx-auto border border-slate-200">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900">No Other Services Found</h3>
                <p class="text-xs text-slate-500">There are no services listed by other members matching your search criteria.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($othersServices as $service)
                    @php
                        $userReq = $service->requests->first();
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between overflow-hidden hover:shadow-md transition">
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-[11px] font-bold rounded-lg border border-sky-100">
                                    {{ $service->category }}
                                </span>
                                <span class="text-[11px] font-semibold text-slate-500">
                                    <i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $service->group ? $service->group->name : 'All Joined Communities' }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $service->title }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{!! nl2br(e($service->description)) !!}</p>

                            <!-- Service Provider Details -->
                            <div class="flex items-center space-x-3 pt-2 border-t border-slate-100">
                                <div class="w-8 h-8 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                    {{ strtoupper(substr($service->user->first_name, 0, 1) . substr($service->user->last_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $service->user->name }}</div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $service->user->profession ?? 'Community Member' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-bold text-slate-900 text-xs">
                                @if($service->price_type === 'free')
                                    Free
                                @elseif($service->price_type === 'quote')
                                    Quote
                                @else
                                    £{{ number_format($service->price, 2) }} {{ $service->price_type === 'hourly' ? '/ hr' : '' }}
                                @endif
                            </span>

                            @if(!$userReq)
                                <button onclick='openRequestServiceModal({{ $service->id }}, @json($service->title), @json($service->user->name))' 
                                        class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Request Service</span>
                                </button>
                            @elseif($userReq->status === 'pending')
                                <span class="px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-xl inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-clock"></i>
                                    <span>Request Pending</span>
                                </span>
                            @elseif($userReq->status === 'accepted')
                                <div class="flex items-center space-x-2">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-xl">
                                        Accepted
                                    </span>
                                    <a href="{{ route('member.chat', ['groupId' => $service->group_id ?? 1, 'receiverId' => $service->user_id]) }}" 
                                       class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                        <i class="fa-solid fa-comments"></i>
                                        <span>Chat Now</span>
                                    </a>
                                </div>
                            @elseif($userReq->status === 'rejected')
                                <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl">
                                    Declined
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    <!-- TAB CONTENT: REQUESTS -->
    @if($tab === 'requests')
        <div class="space-y-6">
            <!-- Received Requests -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-inbox text-sky-600"></i>
                        <span>Requests Received For Your Services</span>
                    </h3>
                </div>
                @if($receivedRequests->isEmpty())
                    <div class="p-8 text-center text-slate-400 text-xs font-medium">
                        No service requests received yet.
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($receivedRequests as $req)
                            <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-sm text-slate-900">{{ $req->requester->name }}</span>
                                        <span class="text-xs text-slate-500">requested</span>
                                        <span class="font-bold text-xs text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-md border border-sky-100">{{ $req->service->title }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 italic">"{{ $req->message }}"</p>
                                    <div class="text-[11px] text-slate-400">{{ $req->created_at->diffForHumans() }}</div>
                                </div>

                                <div class="flex items-center space-x-2 shrink-0">
                                    @if($req->status === 'pending')
                                        <form action="{{ route('member.services.requests.approve', $req->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1">
                                                <i class="fa-solid fa-check"></i>
                                                <span>Accept</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('member.services.requests.reject', $req->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition flex items-center space-x-1">
                                                <i class="fa-solid fa-xmark"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @elseif($req->status === 'accepted')
                                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-xl mr-2">
                                            Accepted
                                        </span>
                                        <a href="{{ route('member.chat', ['groupId' => $req->service->group_id ?? 1, 'receiverId' => $req->requester_id]) }}" 
                                           class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                            <i class="fa-solid fa-comments"></i>
                                            <span>Chat with {{ $req->requester->first_name }}</span>
                                        </a>
                                    @elseif($req->status === 'rejected')
                                        <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl">
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sent Requests -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-paper-plane text-sky-600"></i>
                        <span>Requests You Have Sent</span>
                    </h3>
                </div>
                @if($sentRequests->isEmpty())
                    <div class="p-8 text-center text-slate-400 text-xs font-medium">
                        You have not sent any service requests yet.
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($sentRequests as $req)
                            <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-slate-500">Service:</span>
                                        <span class="font-bold text-xs text-slate-900">{{ $req->service->title }}</span>
                                        <span class="text-xs text-slate-500">offered by</span>
                                        <span class="font-bold text-xs text-sky-600">{{ $req->provider->name }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-400">Sent {{ $req->created_at->diffForHumans() }}</div>
                                </div>

                                <div class="flex items-center space-x-2 shrink-0">
                                    @if($req->status === 'pending')
                                        <span class="px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-xl flex items-center space-x-1">
                                            <i class="fa-solid fa-clock"></i>
                                            <span>Pending Provider Approval</span>
                                        </span>
                                    @elseif($req->status === 'accepted')
                                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-xl mr-2">
                                            Request Accepted
                                        </span>
                                        <a href="{{ route('member.chat', ['groupId' => $req->service->group_id ?? 1, 'receiverId' => $req->provider_id]) }}" 
                                           class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                            <i class="fa-solid fa-comments"></i>
                                            <span>Chat with {{ $req->provider->first_name }}</span>
                                        </a>
                                    @elseif($req->status === 'rejected')
                                        <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl">
                                            Declined
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Modal: Offer New Service -->
<div id="createServiceModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 relative">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-plus-circle text-sky-600"></i>
                <span>Offer a New Service</span>
            </h3>
            <button type="button" onclick="closeCreateServiceModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('member.services.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Title *</label>
                <input type="text" name="title" required placeholder="e.g., Professional Tax & Accounting Consultation" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category *</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Community</label>
                    <select name="group_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        <option value="">All My Communities</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pricing Model *</label>
                    <select name="price_type" id="create_price_type" onchange="togglePriceInput(this, 'create_price_container')" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        <option value="fixed">Fixed Rate (£)</option>
                        <option value="hourly">Hourly Rate (£/hr)</option>
                        <option value="quote">Quote on Request</option>
                        <option value="free">Free Community Service</option>
                    </select>
                </div>
                <div id="create_price_container">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Price (£)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description *</label>
                <textarea name="description" rows="4" required placeholder="Describe what your service includes, deliverables, requirements..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeCreateServiceModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-sm transition">
                    Publish Service
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Request Service -->
<div id="requestServiceModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6 space-y-4 relative">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-paper-plane text-sky-600"></i>
                <span>Request Service</span>
            </h3>
            <button type="button" onclick="closeRequestServiceModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="requestServiceForm" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Service</label>
                <div id="req_modal_title" class="font-bold text-sm text-slate-900 bg-slate-50 p-2.5 rounded-xl border border-slate-200"></div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Message to <span id="req_modal_owner"></span></label>
                <textarea name="message" rows="4" required placeholder="Introduce yourself and explain what assistance or quote you need..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeRequestServiceModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-sm transition">
                    Send Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Service -->
<div id="editServiceModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 relative">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-pen-to-square text-sky-600"></i>
                <span>Edit Service</span>
            </h3>
            <button type="button" onclick="closeEditServiceModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editServiceForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Title *</label>
                <input type="text" name="title" id="edit_title" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category *</label>
                    <select name="category" id="edit_category" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" id="edit_status" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pricing Model *</label>
                    <select name="price_type" id="edit_price_type" onchange="togglePriceInput(this, 'edit_price_container')" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        <option value="fixed">Fixed Rate (£)</option>
                        <option value="hourly">Hourly Rate (£/hr)</option>
                        <option value="quote">Quote on Request</option>
                        <option value="free">Free Community Service</option>
                    </select>
                </div>
                <div id="edit_price_container">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Price (£)</label>
                    <input type="number" step="0.01" name="price" id="edit_price" placeholder="0.00" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Community</label>
                <select name="group_id" id="edit_group_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="">All My Communities</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description *</label>
                <textarea name="description" id="edit_description" rows="4" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeEditServiceModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-sm transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateServiceModal() {
        document.getElementById('createServiceModal').classList.remove('hidden');
    }
    function closeCreateServiceModal() {
        document.getElementById('createServiceModal').classList.add('hidden');
    }

    function openRequestServiceModal(serviceId, title, ownerName) {
        document.getElementById('requestServiceForm').action = `/member/services/${serviceId}/request`;
        document.getElementById('req_modal_title').innerText = title;
        document.getElementById('req_modal_owner').innerText = ownerName;
        document.getElementById('requestServiceModal').classList.remove('hidden');
    }
    function closeRequestServiceModal() {
        document.getElementById('requestServiceModal').classList.add('hidden');
    }

    function openEditServiceModal(service) {
        document.getElementById('editServiceForm').action = `/member/services/${service.id}`;
        document.getElementById('edit_title').value = service.title;
        document.getElementById('edit_category').value = service.category;
        document.getElementById('edit_status').value = service.status;
        document.getElementById('edit_price_type').value = service.price_type;
        document.getElementById('edit_price').value = service.price || '';
        document.getElementById('edit_group_id').value = service.group_id || '';
        document.getElementById('edit_description').value = service.description;

        togglePriceInput(document.getElementById('edit_price_type'), 'edit_price_container');
        document.getElementById('editServiceModal').classList.remove('hidden');
    }
    function closeEditServiceModal() {
        document.getElementById('editServiceModal').classList.add('hidden');
    }

    function togglePriceInput(selectElem, containerId) {
        const container = document.getElementById(containerId);
        if (selectElem.value === 'quote' || selectElem.value === 'free') {
            container.style.display = 'none';
        } else {
            container.style.display = 'block';
        }
    }
</script>
@endsection
