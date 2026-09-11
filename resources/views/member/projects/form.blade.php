@extends('layouts.dashboard')

@section('title', $project->exists ? 'Edit Project' : 'Add New Project')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between border-b pb-6 border-slate-200">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">{{ $project->exists ? 'Edit Project' : 'Add New Portfolio Project' }}</h1>
            <p class="text-sm text-slate-600 mt-1">Fill in details about your work, case study, or client accomplishment.</p>
        </div>
        <a href="{{ route('member.projects.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
            &larr; Back to Projects
        </a>
    </div>

    <form method="POST" action="{{ $project->exists ? route('member.projects.update', $project->id) : route('member.projects.store') }}" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        @csrf
        @if($project->exists)
            @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Project Title *</label>
            <input type="text" name="title" value="{{ old('title', $project->title) }}" required placeholder="e.g. Corporate Rebranding & E-Commerce Web Platform" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category / Industry</label>
                <input type="text" name="category" value="{{ old('category', $project->category) }}" placeholder="e.g. Web Development, Legal Advisory, Construction" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Completion Date</label>
                <input type="date" name="completion_date" value="{{ old('completion_date', optional($project->completion_date)->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Live Project / Website URL</label>
            <input type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" placeholder="https://example.com/project" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            @error('project_url') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Technologies / Key Skills Used</label>
            <input type="text" name="technologies" value="{{ old('technologies', $project->technologies) }}" placeholder="e.g. Laravel, React, Tax Audit, Architectural Design (comma separated)" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Project Image / Cover Photo</label>
            @if($project->image_url)
                <div class="mb-3 flex items-center space-x-4">
                    <img src="{{ $project->image_url }}" alt="Current Project Image" class="w-24 h-24 object-cover rounded-xl border border-slate-200 shadow-sm">
                    <span class="text-xs text-slate-500">Upload a new photo to replace current image.</span>
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
            <p class="text-xs text-slate-400 mt-1">Accepted formats: JPG, PNG, WEBP. Max file size: 5MB.</p>
            @error('image') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Detailed Description</label>
            <textarea name="description" rows="5" placeholder="Describe the goal of the project, your specific role, client outcome, and key achievements..." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('description', $project->description) }}</textarea>
            @error('description') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 flex items-center justify-end space-x-4 border-t border-slate-100">
            <a href="{{ route('member.projects.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition flex items-center space-x-2">
                <i class="fa-solid fa-check"></i>
                <span>{{ $project->exists ? 'Update Project' : 'Save Project' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
