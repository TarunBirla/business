@extends('layouts.dashboard')

@section('title', 'Create New Community')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Create New Community</h1>
        <p class="text-sm text-black mt-1">Configure community metadata, membership pricing, and assign Group Admin.</p>
    </div>

    <form method="POST" action="{{ route('super_admin.groups.store') }}" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Name *</label>
            <input type="text" name="name" required placeholder="e.g. London Business Community" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Description *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Explain the community background and purpose..."></textarea>
        </div>

        <!-- Community Gallery Images (Multiple, Optional) -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                <i class="fa-solid fa-images text-sky-600 mr-1"></i> Community Photos (Multiple - Optional / Nullable)
            </label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl">
            <p class="text-[11px] text-slate-400 mt-1">First photo will automatically be set as the main thumbnail.</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Region</label>
                <input type="text" name="city" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Country</label>
                <input type="text" name="country" value="United Kingdom" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Type *</label>
                <select name="community_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="free">Free Community</option>
                    <option value="paid">Paid Subscription Community</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Annual Fee (£ GBP)</label>
                <input type="number" step="0.01" name="price" value="20.00" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assign Initial Group Admin</label>
            <select name="admin_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                <option value="">None (Super Admin Managed)</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition">
            Create Community
        </button>
    </form>
</div>
@endsection
