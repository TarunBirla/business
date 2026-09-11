@extends('layouts.dashboard')

@section('title', 'Add New Group Admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Add Group Administrator</h1>
            <p class="text-sm text-slate-600 mt-1">Create a new Group Admin account and assign managed communities.</p>
        </div>
        <a href="{{ route('super_admin.group_admins.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Group Admins
        </a>
    </div>

    <form action="{{ route('super_admin.group_admins.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 space-y-6 shadow-sm">
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
                <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+44 7123 456789" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password *</label>
                <div class="relative">
                    <input type="password" id="gadm_password" name="password" required class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
                    <button type="button" onclick="togglePasswordVisibility('gadm_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 transition" title="Toggle Password Visibility">
                        <i class="fa-solid fa-eye text-base"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Password *</label>
                <div class="relative">
                    <input type="password" id="gadm_password_confirmation" name="password_confirmation" required class="w-full px-4 py-2.5 pr-11 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
                    <button type="button" onclick="togglePasswordVisibility('gadm_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 transition" title="Toggle Password Visibility">
                        <i class="fa-solid fa-eye text-base"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Profession / Title</label>
                <input type="text" name="profession" value="{{ old('profession') }}" placeholder="e.g. Community Coordinator" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Company / Organization</label>
                <input type="text" name="company" value="{{ old('company') }}" placeholder="e.g. Community Trust UK" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">City</label>
                <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. London" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500 font-semibold text-sm">
            </div>
        </div>

        <div class="space-y-3 pt-2 border-t border-slate-100">
            <label class="block text-sm font-bold text-slate-800">Assign Managed Communities</label>
            <p class="text-xs text-slate-500">Select which communities this Group Administrator will have management access to:</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-3 bg-slate-50 border border-slate-200 rounded-xl">
                @foreach($groups as $g)
                    <label class="flex items-center space-x-3 p-2 bg-white rounded-lg border border-slate-200 cursor-pointer hover:bg-sky-50/50 transition">
                        <input type="checkbox" name="group_ids[]" value="{{ $g->id }}" {{ is_array(old('group_ids')) && in_array($g->id, old('group_ids')) ? 'checked' : '' }} class="w-4 h-4 text-sky-600 rounded focus:ring-sky-500 border-slate-300">
                        <div class="text-xs">
                            <span class="font-bold text-slate-900 block">{{ $g->name }}</span>
                            <span class="text-slate-500">{{ $g->city ?? 'UK' }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('super_admin.group_admins.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                Create Group Admin
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
