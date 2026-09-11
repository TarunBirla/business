@extends('layouts.dashboard')

@section('title', 'Member Portfolio & Projects')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-folder-open text-sky-600"></i>
                <span>Member Portfolio & Projects</span>
            </h1>
            <p class="text-sm text-slate-600 mt-1">Overview of all professional projects, case studies, and accomplishments published by members across the platform.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3 py-1.5 bg-sky-50 text-sky-700 text-xs font-bold rounded-xl border border-sky-200">
                Total Projects: {{ $projects->total() }}
            </span>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('super_admin.projects.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Search Projects or Users</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, tech, user name..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Category Filter</label>
                <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="">All Categories</option>
                    @foreach(['Web', 'App', 'Design', 'Consulting', 'Hardware', 'Research', 'Marketing', 'Other'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                    Filter Projects
                </button>
                @if(request()->anyFilled(['search', 'category']))
                    <a href="{{ route('super_admin.projects.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Projects Grid -->
    @if($projects->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-3">
            <div class="w-16 h-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900">No Projects Found</h3>
            <p class="text-sm text-slate-500">No member projects match your current search filters.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:border-sky-300 transition group">
                    <!-- Project Cover Image -->
                    <div class="h-44 bg-slate-100 relative overflow-hidden">
                        @if($project->image_url)
                            <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-sky-50 text-slate-400">
                                <i class="fa-solid fa-laptop-code text-4xl"></i>
                            </div>
                        @endif
                        @if($project->category)
                            <span class="absolute top-3 right-3 px-2.5 py-1 bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-bold rounded-lg shadow-sm border border-slate-200/50 uppercase">
                                {{ $project->category }}
                            </span>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <!-- Owner Info -->
                            <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                    {{ substr($project->user->first_name ?? 'M', 0, 1) }}{{ substr($project->user->last_name ?? 'U', 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $project->user->name ?? 'Unknown Member' }}</div>
                                    <div class="text-[10px] text-slate-500 truncate">{{ $project->user->email ?? '' }}</div>
                                </div>
                                <a href="{{ route('bizcard.show', $project->user_id) }}" target="_blank" class="p-1.5 bg-slate-100 hover:bg-sky-50 text-slate-600 hover:text-sky-600 rounded-lg text-xs transition" title="View Business Card">
                                    <i class="fa-solid fa-id-card"></i>
                                </a>
                            </div>

                            <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-sky-600 transition">{{ $project->title }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">{{ $project->description }}</p>
                        </div>

                        <!-- Tech Stack & Footer -->
                        <div class="space-y-3 pt-3 border-t border-slate-100">
                            @if($project->technologies)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $project->technologies) as $tech)
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-semibold rounded-md">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[10px] text-slate-400 font-medium">
                                    Added {{ $project->created_at->format('M d, Y') }}
                                </span>
                                @if($project->project_url)
                                    <a href="{{ $project->project_url }}" target="_blank" class="inline-flex items-center space-x-1 text-xs font-bold text-sky-600 hover:text-sky-700">
                                        <span>Visit Live</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @endif
                            </div>
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
