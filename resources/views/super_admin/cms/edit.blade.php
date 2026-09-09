@extends('layouts.dashboard')

@section('title', 'Edit CMS Page - ' . $page->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Edit Page: {{ $page->title }}</h1>
    </div>

    <form method="POST" action="{{ route('super_admin.cms.update', $page->id) }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Page Title *</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Page Content *</label>
            <textarea name="content" rows="12" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-sans text-sm leading-relaxed">{{ old('content', $page->content) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SEO Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SEO Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition">
            Save Page Changes
        </button>
    </form>
</div>
@endsection
