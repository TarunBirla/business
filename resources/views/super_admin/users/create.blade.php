@extends('layouts.dashboard')

@section('title', 'Add New User')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Add New User</h1>
            <p class="text-sm text-slate-600 mt-1">Create a new user account and assign platform permissions.</p>
        </div>
        <a href="{{ route('super_admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Users
        </a>
    </div>

    <form action="{{ route('super_admin.users.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 space-y-6 shadow-sm">
        @csrf

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Global Role *</label>
                <select name="global_role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
                    <option value="member" {{ old('global_role') === 'member' ? 'selected' : '' }}>Member</option>
                    <option value="group_admin" {{ old('global_role') === 'group_admin' ? 'selected' : '' }}>Group Admin</option>
                    <option value="super_admin" {{ old('global_role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password *</label>
                <div class="relative">
                    <input type="password" id="usr_password" name="password" required class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
                    <button type="button" onclick="togglePasswordVisibility('usr_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 transition" title="Toggle Password Visibility">
                        <i class="fa-solid fa-eye text-base"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Password *</label>
                <div class="relative">
                    <input type="password" id="usr_password_confirmation" name="password_confirmation" required class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
                    <button type="button" onclick="togglePasswordVisibility('usr_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 transition" title="Toggle Password Visibility">
                        <i class="fa-solid fa-eye text-base"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Profession</label>
                <input type="text" name="profession" value="{{ old('profession') }}" placeholder="e.g. Software Engineer" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Company / Business</label>
                <input type="text" name="company" value="{{ old('company') }}" placeholder="e.g. Acme Inc" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">City</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. London" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+44 7123 456789" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('super_admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                Create User
            </button>
        </div>
    </form>
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
</script>
@endsection
