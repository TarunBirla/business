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
                <div class="relative">
                    <input type="password" id="login_password" name="password" required class="w-full px-4 py-3 pr-11 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium">
                    <button type="button" onclick="togglePasswordVisibility('login_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 transition" title="Toggle Password Visibility">
                        <i class="fa-solid fa-eye text-base"></i>
                    </button>
                </div>
                @error('password') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-slate-600">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-sky-600 hover:underline">Forgot password?</a>
            </div>

            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition text-base">
                Log In
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-100 space-y-3">
            <div class="flex items-center justify-between gap-3 text-xs">
                <span class="text-slate-600 font-medium">Don't have an account yet?</span>
                <button type="button" onclick="openJoinModal()" class="px-3.5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition shadow-2xs flex items-center space-x-1.5 shrink-0">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Join as member</span>
                </button>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-3 text-xs">
                <span class="text-slate-600 font-medium">Want to lead a community?</span>
                <a href="{{ route('public.community_request.create') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl transition border border-slate-200 shadow-2xs flex items-center space-x-1.5 shrink-0">
                    <i class="fa-solid fa-plus-circle text-sky-600 text-xs"></i>
                    <span>Request New Community</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Select Community Modal Box -->
<div id="joinCommunityModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs transition-opacity duration-300">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center space-y-4 shadow-2xl border border-slate-100 transform transition-all animate-in fade-in zoom-in duration-200">
        <div class="w-14 h-14 bg-amber-50 border border-amber-200 rounded-2xl flex items-center justify-center mx-auto text-amber-600 shadow-2xs">
            <i class="fa-solid fa-layer-group text-2xl"></i>
        </div>
        
        <div>
            <h3 class="text-lg font-bold text-slate-900">First Select Your Community</h3>
            <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                Please select your community first to proceed with member registration.
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-2">
            <a href="{{ route('groups.index') }}" class="w-full py-2.5 px-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center space-x-2">
                <span>Explore Communities</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            <button type="button" onclick="closeJoinModal()" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function openJoinModal() {
    const modal = document.getElementById('joinCommunityModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeJoinModal() {
    const modal = document.getElementById('joinCommunityModal');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
</script>
@endsection
