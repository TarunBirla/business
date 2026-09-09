@extends('layouts.dashboard')

@section('title', 'Create New Community')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Create New Community</h1>
        <p class="text-sm text-slate-600 mt-1">Configure community metadata, membership pricing, and assign Group Admin.</p>
    </div>

    <form method="POST" action="{{ route('super_admin.groups.store') }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Name *</label>
            <input type="text" name="name" required placeholder="e.g. London Business Community" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Description *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Explain the community background and purpose..."></textarea>
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
