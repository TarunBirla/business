@extends('layouts.app')

@section('title', 'Login to Your Account')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl border border-slate-200 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900">Welcome Back</h2>
            <p class="text-sm text-slate-600 mt-1">Log in to access your community network.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                @error('email') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                @error('password') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition text-base">
                Log In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-600">
            Don't have an account? <a href="{{ route('register') }}" class="font-bold text-sky-600 hover:underline">Join Now</a>
        </div>
    </div>
</div>
@endsection
