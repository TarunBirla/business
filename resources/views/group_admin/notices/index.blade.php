@extends('layouts.dashboard')

@section('title', 'Community Notices - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-black">Group Notices & Announcements</h1>
            <p class="text-sm text-black mt-1">Publish important notices to members of {{ $group->name }}.</p>
        </div>
        <a href="{{ route('group_admin.notices.create', $group->id) }}" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow transition">
            + Send New Notice
        </a>
    </div>

    <div class="space-y-4">
        @foreach($notices as $notice)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded text-xs font-bold uppercase {{ $notice->priority === 'urgent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-black' }}">
                        Priority: {{ $notice->priority }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Published {{ $notice->published_at->format('d M Y') }}</span>
                </div>
                <h3 class="font-bold text-black text-xl mt-3">{{ $notice->title }}</h3>
                <p class="text-sm text-slate-700 mt-2 leading-relaxed whitespace-pre-line">{{ $notice->content }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
