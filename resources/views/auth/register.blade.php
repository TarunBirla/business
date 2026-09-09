@extends('layouts.app')

@section('title', 'Join Community Platform')

@section('content')
<div class="py-16 px-4 max-w-2xl mx-auto">
    <div class="bg-white p-8 md:p-10 rounded-2xl border border-slate-200 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900">Create Member Account</h2>
            @if(isset($group))
                <div class="mt-2 inline-block px-4 py-1.5 bg-sky-100 text-sky-800 rounded-full font-bold text-xs">
                    Joining {{ $group->name }}
                </div>
            @else
                <p class="text-sm text-slate-600 mt-1">Join the UK's leading community networking ecosystem.</p>
            @endif
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            @if(isset($group))
                <input type="hidden" name="group_id" value="{{ $group->id }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    @error('first_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    @error('last_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    @error('email') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+44 7700 900000" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Profession / Sector</label>
                    <input type="text" name="profession" value="{{ old('profession') }}" placeholder="e.g. Solicitor, Accountant, IT Consultant" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Location</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. London, Birmingham, Leicester" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password *</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    @error('password') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="terms" required class="mt-1 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-xs text-slate-600">I accept the <a href="{{ route('cms.show', 'terms') }}" target="_blank" class="text-sky-600 font-bold underline">Terms & Conditions</a> and <a href="{{ route('cms.show', 'privacy') }}" target="_blank" class="text-sky-600 font-bold underline">Privacy Policy</a>.</span>
                </label>
            </div>

            <button type="submit" class="w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition text-base mt-4">
                {{ (isset($group) && $group->community_type === 'paid') ? 'Proceed to Membership Review' : 'Create Account & Join' }}
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-600">
            Already registered? <a href="{{ route('login') }}" class="font-bold text-sky-600 hover:underline">Log in here</a>
        </div>
    </div>
</div>
@endsection
