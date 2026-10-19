@extends('layouts.dashboard')

@section('title', 'Write New Blog Article')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Write New Blog Article</h1>
            <p class="text-xs text-slate-500 mt-1">Create engaging articles with rich text, images, and search engine optimization.</p>
        </div>
        <a href="{{ route('super_admin.blogs.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
            &larr; Back to Articles
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            <span class="font-bold">Please correct the following errors:</span>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super_admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Article Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. 5 Strategies to Accelerate Business Networking in the UK" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-semibold">
        </div>

        <!-- Category & Author & Status -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category</label>
                <input type="text" name="category" value="{{ old('category', 'Business Networking') }}" placeholder="e.g. Technology, Real Estate, Legal" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Author Name</label>
                <input type="text" name="author_name" value="{{ old('author_name', 'Bizconn Editorial') }}" placeholder="e.g. John Doe" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
                <select name="status" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-sky-500">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Visible on Frontend)</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <!-- Featured Image -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Featured Cover Image</label>
            <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl bg-white cursor-pointer">
            <p class="text-[11px] text-slate-400 mt-1">Recommended size: 1200x630px for high quality social sharing.</p>
        </div>

        <!-- Short Excerpt -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Short Excerpt / Summary</label>
            <textarea name="excerpt" rows="2" placeholder="Brief summary of the article (displays in cards and meta description)..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('excerpt') }}</textarea>
        </div>

        <!-- Rich Text Content Editor -->
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1 flex items-center justify-between">
                <span>Article Content (Rich Text) *</span>
                <span class="text-[10px] text-slate-400 font-normal">Formatted text, images & headings supported</span>
            </label>
            <textarea id="blog_content_editor" name="content" rows="12" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('content') }}</textarea>
        </div>

        <!-- SEO, ARO & GEO Meta Settings (Collapsible Box) -->
        <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50 space-y-4">
            <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
                <i class="fa-solid fa-bullseye text-sky-600"></i>
                <h3 class="font-bold text-slate-900 text-sm">SEO, ARO (Audience Optimization) & GEO Targeting</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Meta Title (SEO / ARO)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Custom browser title (defaults to article title)" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-sky-500 bg-white">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">SEO Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="e.g. UK networking, enterprise, London business" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-sky-500 bg-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">GEO Region (Location Targeting)</label>
                    <input type="text" name="geo_region" value="{{ old('geo_region', 'GB-UKM') }}" placeholder="e.g. GB-UKM, GB-ENG" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-sky-500 bg-white">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">GEO Place Name</label>
                    <input type="text" name="geo_placename" value="{{ old('geo_placename', 'United Kingdom') }}" placeholder="e.g. London, United Kingdom" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-sky-500 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" placeholder="Search engine snippet description..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-sky-500 bg-white">{{ old('meta_description') }}</textarea>
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-md flex items-center justify-center space-x-2">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Publish / Save Blog Article</span>
        </button>
    </form>
</div>

<!-- Simple Rich Text Editor Integration (Summernote Lite CDN) -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#blog_content_editor').summernote({
        placeholder: 'Write your full blog post here...',
        tabsize: 2,
        height: 340,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endsection
