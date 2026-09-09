@extends('layouts.dashboard')

@section('title', 'Send Notice - ' . $group->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Publish Community Notice</h1>
        <p class="text-sm text-slate-600 mt-1">Broadcast an announcement to {{ $group->name }} members.</p>
    </div>

    <form method="POST" action="{{ route('group_admin.notices.store', $group->id) }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notice Title *</label>
            <input type="text" name="title" required placeholder="e.g. Annual General Meeting Announcement" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Priority Level *</label>
            <select name="priority" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                <option value="low">Low Priority</option>
                <option value="medium" selected>Medium Priority</option>
                <option value="high">High Priority</option>
                <option value="urgent">Urgent Announcement</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Content / Message *</label>
            <textarea name="content" rows="6" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Type notice content here..."></textarea>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow transition">
            Publish Notice
        </button>
    </form>
</div>
@endsection
