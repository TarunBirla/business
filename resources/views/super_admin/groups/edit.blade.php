@extends('layouts.dashboard')

@section('title', 'Edit Community - ' . $group->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Edit Community</h1>
        <p class="text-sm text-slate-600 mt-1">Update details, status, pricing, and assigned Group Admins.</p>
    </div>

    <form method="POST" action="{{ route('super_admin.groups.update', $group->id) }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Name *</label>
            <input type="text" name="name" value="{{ old('name', $group->name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Description *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('description', $group->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Type *</label>
                <select name="community_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="free" {{ $group->community_type === 'free' ? 'selected' : '' }}>Free Community</option>
                    <option value="paid" {{ $group->community_type === 'paid' ? 'selected' : '' }}>Paid Subscription Community</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Annual Subscription Fee (£ GBP)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $subscription->price ?? 20.00) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
            <select name="status" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                <option value="active" {{ $group->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="draft" {{ $group->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="suspended" {{ $group->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="archived" {{ $group->status === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Assigned Group Admins</label>
            <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3 space-y-2">
                @foreach($users as $user)
                    <label class="flex items-center space-x-2 text-xs font-semibold cursor-pointer">
                        <input type="checkbox" name="group_admins[]" value="{{ $user->id }}" {{ in_array($user->id, $groupAdmins) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600">
                        <span>{{ $user->name }} ({{ $user->email }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition">
            Update Community
        </button>
    </form>
</div>
@endsection
