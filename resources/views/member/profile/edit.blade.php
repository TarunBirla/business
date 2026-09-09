@extends('layouts.dashboard')

@section('title', 'My Profile & Privacy')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Manage Profile & Privacy Controls</h1>
        <p class="text-sm text-slate-600 mt-1">Keep your professional networking profile up to date and configure your privacy controls.</p>
    </div>

    <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-8">
        @csrf

        <!-- Basic Personal Info -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-slate-900 border-b pb-4">Personal Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Profession / Industry</label>
                    <input type="text" name="profession" value="{{ old('profession', $user->profession) }}" placeholder="e.g. Chartered Accountant, Solicitor" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Company Name</label>
                    <input type="text" name="company" value="{{ old('company', $user->company) }}" placeholder="e.g. Apex Advisory Ltd" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Region</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country ?? 'United Kingdom') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+44 7700 900000" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">LinkedIn Profile URL</label>
                <input type="url" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" placeholder="https://linkedin.com/in/username" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Biography</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Brief summary of your professional background and interests...">{{ old('description', $user->description) }}</textarea>
            </div>
        </div>

        <!-- Privacy & Contact Detail Controls -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-xl font-bold text-slate-900 border-b pb-4">Backend Privacy Controls (GDPR Compliant)</h3>
            <p class="text-xs text-slate-500">By default, your email address and phone number are kept hidden from public view until you approve a connection or contact request.</p>

            <div class="space-y-3 pt-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="show_email" value="1" {{ ($user->privacy_settings['show_email'] ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-semibold text-slate-700">Display my Email Address to all community members</span>
                </label>

                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="show_phone" value="1" {{ ($user->privacy_settings['show_phone'] ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-semibold text-slate-700">Display my Phone Number to all community members</span>
                </label>

                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="allow_connections" value="1" {{ ($user->privacy_settings['allow_connections'] ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-semibold text-slate-700">Allow other community members to send me connection requests</span>
                </label>
            </div>
        </div>

        <!-- Services I Offer & Services I Need -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-slate-900 border-b pb-4">Professional Services Exchange</h3>

            <div>
                <label class="block text-xs font-bold text-sky-700 uppercase mb-3">Services I Offer (Select all that apply)</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($allServices as $service)
                        <label class="flex items-center space-x-2 p-2.5 rounded-lg border border-slate-200 hover:bg-sky-50/50 cursor-pointer">
                            <input type="checkbox" name="services_offered[]" value="{{ $service->id }}" {{ in_array($service->id, $userServicesOffered) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-xs font-semibold text-slate-800">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-amber-700 uppercase mb-3">Services I Need (Select all that apply)</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($allServices as $service)
                        <label class="flex items-center space-x-2 p-2.5 rounded-lg border border-slate-200 hover:bg-amber-50/50 cursor-pointer">
                            <input type="checkbox" name="services_needed[]" value="{{ $service->id }}" {{ in_array($service->id, $userServicesNeeded) ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs font-semibold text-slate-800">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition">
            Save Profile & Settings
        </button>
    </form>
</div>
@endsection
