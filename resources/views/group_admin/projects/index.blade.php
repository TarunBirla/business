@extends('layouts.dashboard')

@section('title', 'Community Projects Showcase')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-6 border-slate-200">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Community Projects Showcase</h1>
            <p class="text-sm text-slate-600 mt-1">Manage your own portfolio projects and view accomplishments shared by members across your assigned communities.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('group_admin.projects.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Project</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center border-b border-slate-200 space-x-4">
        <a href="{{ route('group_admin.projects.index', ['tab' => 'my_projects']) }}" class="py-3 px-4 text-sm font-bold border-b-2 transition flex items-center space-x-2 {{ $tab === 'my_projects' ? 'border-sky-600 text-sky-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
            <i class="fa-solid fa-user-gear"></i>
            <span>My Projects ({{ $myProjects->total() }})</span>
        </a>
        <a href="{{ route('group_admin.projects.index', ['tab' => 'member_projects']) }}" class="py-3 px-4 text-sm font-bold border-b-2 transition flex items-center space-x-2 {{ $tab === 'member_projects' ? 'border-sky-600 text-sky-600' : 'border-transparent text-slate-600 hover:text-slate-900' }}">
            <i class="fa-solid fa-users"></i>
            <span>Member Projects ({{ $memberProjects->total() }})</span>
        </a>
    </div>

    <!-- Tab 1: My Projects -->
    @if($tab === 'my_projects')
        @if($myProjects->isEmpty())
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center shadow-sm space-y-4">
                <div class="w-16 h-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-3xl mx-auto border border-sky-100">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">No Projects Added Yet</h3>
                <p class="text-slate-600 max-w-md mx-auto text-sm">Upload your past work, completed client projects, or achievements to highlight your expertise.</p>
                <div class="pt-2">
                    <a href="{{ route('group_admin.projects.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Your First Project</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($myProjects as $project)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <!-- Project Cover -->
                            <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-200">
                                @if($project->image_url)
                                    <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-slate-400 text-center p-4">
                                        <i class="fa-solid fa-folder-open text-4xl mb-2"></i>
                                        <p class="text-xs font-semibold uppercase tracking-wider">No Image Uploaded</p>
                                    </div>
                                @endif
                                <span class="absolute top-3 left-3 px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-white text-[11px] font-extrabold rounded-full shadow-sm flex items-center space-x-1">
                                    <i class="fa-solid fa-list-ol text-[10px]"></i>
                                    <span>Seq #{{ $project->sort_order ?? 1 }}</span>
                                </span>
                                @if($project->category)
                                    <span class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                        {{ $project->category }}
                                    </span>
                                @endif
                            </div>

                            <!-- Body -->
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

                        <!-- Actions for Owner -->
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
                                <a href="{{ route('group_admin.projects.edit', $project->id) }}" class="p-2 text-slate-600 hover:text-sky-600 hover:bg-white rounded-lg transition" title="Edit Project">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('group_admin.projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
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
                {{ $myProjects->appends(['tab' => 'my_projects'])->links() }}
            </div>
        @endif
    @endif

    <!-- Tab 2: Member Projects -->
    @if($tab === 'member_projects')
        <!-- Search & Community Filter Header -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('group_admin.projects.index') }}" class="w-full flex flex-col sm:flex-row items-center gap-3">
                <input type="hidden" name="tab" value="member_projects">

                <div class="w-full sm:w-64">
                    <select name="group_id" onchange="this.form.submit()" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-semibold">
                        <option value="all" {{ $selectedGroupId === 'all' ? 'selected' : '' }}>All My Communities</option>
                        @foreach($adminGroups as $g)
                            <option value="{{ $g->id }}" {{ (string)$selectedGroupId === (string)$g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by title, technology, or member name..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                </div>

                <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition w-full sm:w-auto">
                    Search
                </button>

                @if($search || $selectedGroupId !== 'all')
                    <a href="{{ route('group_admin.projects.index', ['tab' => 'member_projects']) }}" class="px-4 py-2 text-slate-500 hover:text-slate-800 text-xs font-bold transition">
                        Reset Filters
                    </a>
                @endif
            </form>
        </div>

        @if($memberProjects->isEmpty())
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center shadow-sm space-y-4">
                <div class="w-16 h-16 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center text-3xl mx-auto">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">No Member Projects Found</h3>
                <p class="text-slate-600 max-w-md mx-auto text-sm">No portfolio projects have been added by members in your selected community yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($memberProjects as $project)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <!-- Member Header Info -->
                            <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full bg-sky-600 text-white font-bold text-sm flex items-center justify-center overflow-hidden shrink-0 shadow-xs">
                                        @if(optional($project->user)->profile_photo)
                                            <img src="{{ asset('storage/' . $project->user->profile_photo) }}" alt="{{ $project->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr(optional($project->user)->first_name ?? 'M', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900 line-clamp-1">{{ optional($project->user)->name ?? 'Community Member' }}</h4>
                                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ optional($project->user)->profession ?? optional($project->user)->email }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('bizcard.show', $project->user_id) }}" target="_blank" class="p-2 bg-white hover:bg-sky-50 text-slate-600 hover:text-sky-600 rounded-lg text-xs transition border border-slate-200 shadow-2xs" title="View Business Card">
                                    <i class="fa-solid fa-id-card"></i>
                                </a>
                            </div>

                            <!-- Project Cover -->
                            <div class="h-44 bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-200">
                                @if($project->image_url)
                                    <img src="{{ $project->image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-slate-400 text-center p-4">
                                        <i class="fa-solid fa-folder-open text-3xl mb-1"></i>
                                        <p class="text-[10px] font-semibold uppercase tracking-wider">No Cover Image</p>
                                    </div>
                                @endif
                                <span class="absolute top-3 left-3 px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-white text-[11px] font-extrabold rounded-full shadow-sm flex items-center space-x-1">
                                    <i class="fa-solid fa-list-ol text-[10px]"></i>
                                    <span>Seq #{{ $project->sort_order ?? 1 }}</span>
                                </span>
                                @if($project->category)
                                    <span class="absolute top-3 right-3 px-3 py-1 bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold rounded-full shadow-sm">
                                        {{ $project->category }}
                                    </span>
                                @endif
                            </div>

                            <!-- Project Info -->
                            <div class="p-5 space-y-3">
                                <h3 class="font-bold text-base text-slate-900 leading-snug">{{ $project->title }}</h3>
                                <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    {{ $project->description ?? 'No detailed description provided.' }}
                                </p>
                                @if($project->technologies)
                                    <div class="pt-1 flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $project->technologies) as $tech)
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[11px] font-medium rounded-md border border-slate-200">
                                                {{ trim($tech) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                @if($project->project_url)
                                    <a href="{{ $project->project_url }}" target="_blank" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center space-x-1">
                                        <span>View Live Work</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Internal Case Study</span>
                                @endif
                            </div>
                            <a href="{{ route('member.chat', ['groupId' => $selectedGroupId !== 'all' ? $selectedGroupId : 1, 'receiverId' => $project->user_id]) }}" class="px-3 py-1.5 bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 rounded-lg text-xs font-bold transition flex items-center space-x-1">
                                <i class="fa-solid fa-comments text-[11px]"></i>
                                <span>Chat</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $memberProjects->appends(['tab' => 'member_projects', 'group_id' => $selectedGroupId, 'search' => $search])->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
