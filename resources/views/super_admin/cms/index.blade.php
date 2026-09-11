@extends('layouts.dashboard')

@section('title', 'Manage CMS Pages')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">CMS Content Manager</h1>
        <p class="text-sm text-black mt-1">Manage public pages: About, Contact, Privacy Policy, Terms & Conditions, FAQ, Cookie Policy.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($pages as $page)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-black text-lg">{{ $page->title }}</h3>
                    <p class="text-xs text-slate-400 font-mono">/page/{{ $page->slug }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('cms.show', $page->slug) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-200">View</a>
                    <a href="{{ route('super_admin.cms.edit', $page->id) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg shadow">Edit</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4">
        {{ $pages->links() }}
    </div>
</div>
@endsection
