@extends('layouts.dashboard')

@section('title', 'Community Chat')

@section('content')
<div class="text-center py-20 bg-white rounded-2xl border border-slate-200 shadow-sm max-w-xl mx-auto">
    <div class="w-16 h-16 bg-sky-100 text-sky-700 rounded-full flex items-center justify-center font-bold text-2xl mx-auto mb-4">💬</div>
    <h2 class="text-2xl font-bold text-slate-900">Join a Community to Chat</h2>
    <p class="text-sm text-slate-600 mt-2 max-w-md mx-auto">You must join at least one community to chat with member contacts.</p>
    <a href="{{ route('groups.index') }}" class="inline-block mt-6 px-6 py-3 bg-sky-600 text-white font-bold rounded-xl text-sm shadow">
        Explore & Join Communities
    </a>
</div>
@endsection
