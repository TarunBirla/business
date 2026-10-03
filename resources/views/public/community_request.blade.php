@extends('layouts.app')

@section('title', 'Request a New Community - Bizconn')

@section('content')
<div class="py-12 border-b border-slate-200 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="px-3.5 py-1.5 bg-sky-50 text-sky-700 font-bold text-xs rounded-full border border-sky-200 inline-block uppercase tracking-wider">
            <i class="fa-solid fa-layer-group text-sky-600 mr-1.5"></i> Community Request
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Request a New Community</h1>
        <p class="text-sm text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Want to build, lead, and organize a community on Bizconn? Fill out the application below and our Super Admin team will review and set up your community network.
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(session('success'))
        <div class="mb-8 p-6 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl shadow-sm space-y-2">
            <div class="flex items-center space-x-2 text-emerald-700 font-bold text-base">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span>Application Submitted Successfully!</span>
            </div>
            <p class="text-xs text-emerald-800 leading-relaxed">
                {{ session('success') }}
            </p>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-bold text-emerald-700 hover:underline">
                    &larr; Return to Home Page
                </a>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            <div class="font-bold text-sm flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please correct the errors below:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('public.community_request.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-8">
        @csrf

        <!-- Section 1: Applicant Information -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-user-tie text-sky-600"></i>
                <h2 class="text-base font-bold text-slate-900">1. Applicant & Group Leader Information</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name *</label>
                    <input type="text" name="applicant_name" value="{{ old('applicant_name') }}" required placeholder="e.g. Rahul Sharma" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address *</label>
                    <input type="email" name="applicant_email" value="{{ old('applicant_email') }}" required placeholder="rahul@example.com" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone / Mobile Number (Optional)</label>
                <input type="text" name="applicant_phone" value="{{ old('applicant_phone') }}" placeholder="+44 7123 456789" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <!-- Section 2: Proposed Community Details -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-users text-sky-600"></i>
                <h2 class="text-base font-bold text-slate-900">2. Community Details</h2>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Proposed Community Name *</label>
                <input type="text" name="community_name" value="{{ old('community_name') }}" required placeholder="e.g. London Tech Founders, UK Professionals Network" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Logo / Cover Image (Optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl bg-white cursor-pointer">
                <p class="text-[11px] text-slate-500 mt-1">Upload logo or banner photo for your community (PNG, JPG, WebP max 5MB).</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Description *</label>
                <textarea name="description" rows="4" required placeholder="Describe the mission, members, and purpose of this community..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Purpose of Community</label>
                    <textarea name="purpose" rows="3" placeholder="What goals will members achieve?" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('purpose') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Why Members Should Join</label>
                    <textarea name="why_join" rows="3" placeholder="Key benefits for joining members..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('why_join') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Type *</label>
                    <select name="community_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500">
                        <option value="free" {{ old('community_type', 'free') === 'free' ? 'selected' : '' }}>Free Community</option>
                        <option value="paid" {{ old('community_type') === 'paid' ? 'selected' : '' }}>Paid Subscription</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Region</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', 'United Kingdom') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-md flex items-center justify-center space-x-2">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Submit Community Request to Super Admin</span>
            </button>
        </div>
    </form>
</div>
@endsection
