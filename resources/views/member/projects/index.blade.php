@extends('layouts.dashboard')

@section('title', 'My Portfolio & Projects')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-6 border-slate-200">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">My Portfolio & Projects</h1>
            <p class="text-sm text-slate-600 mt-1">Showcase your professional work, client case studies, and key accomplishments on your profile & Digital Business Card.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('bizcard.show', auth()->id()) }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm rounded-xl transition flex items-center space-x-2 border border-slate-300">
                <i class="fa-solid fa-id-card text-sky-600"></i>
                <span>View My Business Card</span>
            </a>
            <a href="{{ route('member.projects.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Project</span>
            </a>
        </div>
    </div>

    @if($projects->isEmpty())
        <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center shadow-sm space-y-4">
            <div class="w-16 h-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-3xl mx-auto border border-sky-100">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">No Projects Added Yet</h3>
            <p class="text-slate-600 max-w-md mx-auto text-sm">Upload your past work, completed client projects, or achievements to highlight your expertise to community members.</p>
            <div class="pt-2">
                <a href="{{ route('member.projects.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Your First Project</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <!-- Project Image / Placeholder -->
                        <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-200">
                            @if($project->image_url)
                                <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-slate-400 text-center p-4">
                                    <i class="fa-solid fa-folder-open text-4xl mb-2"></i>
                                    <p class="text-xs font-semibold uppercase tracking-wider">No Image Uploaded</p>
                                </div>
                            @endif
                            @if($project->category)
                                <span class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                    {{ $project->category }}
                                </span>
                            @endif
                        </div>

                        <!-- Project Body -->
                        <div class="p-6 space-y-3">
                            <h3 class="font-bold text-lg text-slate-900 leading-snug">{{ $project->title }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $project->description ?? 'No detailed description provided.' }}
                            </p>
                            @if($project->technologies)
                                <div class="pt-2 flex flex-wrap gap-1.5">
                                    @foreach(explode(',', $project->technologies) as $tech)
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-md border border-slate-200">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            @if($project->project_url)
                                <a href="{{ $project->project_url }}" target="_blank" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                                    <span>Live Link</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 font-medium">Internal Case Study</span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('member.projects.edit', $project->id) }}" class="p-2 text-slate-600 hover:text-sky-600 hover:bg-white rounded-lg transition" title="Edit Project">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('member.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-white rounded-lg transition" title="Delete Project">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
