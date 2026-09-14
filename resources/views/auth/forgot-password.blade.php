@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-16 px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl border border-slate-200 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900">Forgot Password?</h2>
            <p class="text-sm text-slate-600 mt-1">Enter your registered email address and we'll send you a link to reset your password.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                @error('email') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition text-base">
                Send Reset Link
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-600">
            Remembered your password? <a href="{{ route('login') }}" class="font-bold text-sky-600 hover:underline">Log in</a>
        </div>
    </div>
</div>
@endsection
