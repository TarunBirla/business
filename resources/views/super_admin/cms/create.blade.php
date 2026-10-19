@extends('layouts.dashboard')

@section('title', 'Create New CMS Page')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Create New CMS Page</h1>
        <a href="{{ route('super_admin.cms.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-200 transition">
            Back to List
        </a>
    </div>

    <form method="POST" action="{{ route('super_admin.cms.store') }}" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Page Title *</label>
            <input type="text" id="page-title" name="title" value="{{ old('title') }}" required placeholder="e.g. Cookie Policy" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">URL Slug *</label>
            <input type="text" id="page-slug" name="slug" value="{{ old('slug') }}" required placeholder="e.g. cookie (accessed at /page/cookie)" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-mono">
            <span class="text-[11px] text-slate-400 mt-1 block">Page URL will be: https://business.nexteck.uk/page/{slug}</span>
            @error('slug') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Page Content *</label>
            {{-- 'required' hata diya hai, kyunki TinyMCE textarea ko hide kar deta hai --}}
            <textarea id="content-editor" name="content" rows="12">{{ old('content') }}</textarea>
            @error('content') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SEO Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Title for Search Engines" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SEO Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" placeholder="Brief summary for Search Engines" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div class="pt-2 flex items-center justify-end space-x-3">
            <a href="{{ route('super_admin.cms.index') }}" class="px-5 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Publish CMS Page
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@7.3.0/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#content-editor',
    license_key: 'gpl',
    height: 480,
    menubar: false,
    branding: false,
    promotion: false,
    plugins: 'lists link table code fullscreen autoresize wordcount',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table | removeformat | code fullscreen',
    block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
    content_style: 'body{font-family:Inter,system-ui,sans-serif;font-size:15px;line-height:1.7;padding:8px}',
    convert_urls: false
});

// Title se slug apne aap banao (jab tak slug manually edit na kiya ho)
(function () {
    var title = document.getElementById('page-title');
    var slug = document.getElementById('page-slug');
    var edited = slug.value.trim() !== '';
    slug.addEventListener('input', function () { edited = slug.value.trim() !== ''; });
    title.addEventListener('input', function () {
        if (edited) return;
        slug.value = title.value.toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    });
})();
</script>
@endsection