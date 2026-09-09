@extends('layouts.dashboard')

@section('title', 'Add Member - ' . $group->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Add / Invite Member</h1>
        <p class="text-sm text-black mt-1">Manually register or invite a new member to {{ $group->name }}.</p>
    </div>

    <form method="POST" action="{{ route('group_admin.members.store', $group->id) }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name *</label>
                <input type="text" name="first_name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
                <input type="text" name="last_name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Address *</label>
            <input type="email" name="email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                <input type="text" name="phone" placeholder="+44 7700 900000" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Profession</label>
                <input type="text" name="profession" placeholder="e.g. Solicitor, Accountant" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City</label>
            <input type="text" name="city" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition">
            Add Member & Send Invitation
        </button>
    </form>
</div>
@endsection
