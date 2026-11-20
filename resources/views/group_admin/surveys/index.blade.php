@extends('layouts.dashboard')

@section('title', 'Community Surveys - ' . $group->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-1 text-xs font-bold rounded-lg uppercase tracking-wider"
                    style="background-color: var(--primary-light, #e0f2fe); color: var(--text-link, #0A4744);">
                    <i class="fa-solid fa-square-poll-vertical mr-1"></i> Community Surveys
                </span>
                <span class="text-xs text-slate-500 font-medium">({{ $group->name }})</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Surveys & Member Feedback</h1>
            <p class="text-sm text-slate-600 mt-0.5">Collect member insights, ask questions, and review community answers.</p>
        </div>
        <a href="{{ route('group_admin.surveys.create', $group->id) }}"
            class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 text-white font-bold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5"
            style="background-color: var(--btn-primary-bg, #0A4744);">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Create New Survey</span>
        </a>
    </div>

    <!-- Active Community Details Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div class="flex items-center space-x-3">
            @if($group->logo)
                <img src="{{ $group->logo_url }}" alt="{{ $group->name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
            @else
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white text-sm" style="background-color: var(--btn-primary-bg, #0A4744);">
                    {{ strtoupper(substr($group->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <h3 class="font-bold text-slate-900 text-sm">{{ $group->name }}</h3>
                <p class="text-xs text-slate-500">{{ $group->activeMembers()->count() }} Active Members in Community</p>
            </div>
        </div>
        <div>
            <span class="text-xs font-bold text-slate-500">Total Surveys: <strong class="text-slate-900">{{ $surveys->total() }}</strong></span>
        </div>
    </div>

    <!-- Surveys Grid -->
    @if($surveys->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($surveys as $survey)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                    <div class="p-6">
                        <!-- Top Badges -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="px-2.5 py-1 text-[11px] font-extrabold uppercase rounded-full {{ $survey->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                <i class="fa-solid fa-circle text-[8px] mr-1 {{ $survey->status === 'active' ? 'text-emerald-500' : 'text-slate-400' }}"></i>
                                {{ ucfirst($survey->status) }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
                                {{ $survey->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="font-bold text-slate-900 text-lg line-clamp-2 leading-snug">{{ $survey->title }}</h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            {{ $survey->description ?: 'No detailed description provided.' }}
                        </p>

                        <!-- Survey Stats Box -->
                        <div class="mt-4 grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                            <div>
                                <span class="block text-base font-extrabold text-slate-900">{{ $survey->questions_count }}</span>
                                <span class="text-[11px] font-medium text-slate-500">Questions</span>
                            </div>
                            <div>
                                <span class="block text-base font-extrabold" style="color: var(--text-link, #0A4744);">
                                    {{ $survey->responses_count }}
                                </span>
                                <span class="text-[11px] font-medium text-slate-500">Submissions</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Bar -->
                    <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-2">
                        <!-- View Responses Button -->
                        <a href="{{ route('group_admin.surveys.responses', ['group' => $group->id, 'survey' => $survey->id]) }}"
                            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-2xs transition"
                            style="background-color: var(--btn-primary-bg, #0A4744);">
                            <i class="fa-solid fa-chart-pie text-xs"></i>
                            <span>Results ({{ $survey->responses_count }})</span>
                        </a>

                        <div class="flex items-center space-x-1">
                            <!-- Toggle Active/Inactive Form -->
                            <form action="{{ route('group_admin.surveys.toggle_status', ['group' => $group->id, 'survey' => $survey->id]) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="p-2 rounded-lg text-xs font-bold border transition {{ $survey->status === 'active' ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                    title="{{ $survey->status === 'active' ? 'Click to Deactivate' : 'Click to Activate' }}">
                                    <i class="fa-solid {{ $survey->status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('group_admin.surveys.edit', ['group' => $group->id, 'survey' => $survey->id]) }}"
                                class="p-2 rounded-lg text-xs font-bold border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 transition"
                                title="Edit Survey">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('group_admin.surveys.destroy', ['group' => $group->id, 'survey' => $survey->id]) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this survey and all of its member responses?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 rounded-lg text-xs font-bold border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition"
                                    title="Delete Survey">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $surveys->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-xl mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4"
                style="background-color: var(--primary-light, #e0f2fe); color: var(--text-link, #0A4744);">
                <i class="fa-solid fa-square-poll-vertical text-2xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">No Surveys Created Yet</h3>
            <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                You haven't created any surveys for <strong>{{ $group->name }}</strong> yet. Create your first survey to gather feedback and member preferences!
            </p>
            <div class="mt-6">
                <a href="{{ route('group_admin.surveys.create', $group->id) }}"
                    class="inline-flex items-center space-x-2 px-6 py-3 text-white font-bold text-sm rounded-xl shadow-md transition"
                    style="background-color: var(--btn-primary-bg, #0A4744);">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Create First Survey</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
