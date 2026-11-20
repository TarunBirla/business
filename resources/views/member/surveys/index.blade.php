@extends('layouts.dashboard')

@section('title', 'Community Surveys & Feedback - Bizconn')

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
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Surveys & Feedback</h1>
            <p class="text-sm text-slate-600 mt-0.5">Share your voice and answer surveys from the communities you belong to.</p>
        </div>
    </div>

    <!-- Community Filter (If member belongs to multiple groups) -->
    @if($joinedGroups->count() > 1)
        <div class="flex items-center space-x-2 border-b border-slate-200 pb-3 overflow-x-auto">
            <span class="text-xs font-extrabold uppercase text-slate-400 mr-2 shrink-0">Filter by Community:</span>
            <a href="{{ route('member.surveys.index') }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ !$selectedGroupId ? 'text-white shadow-2xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
                style="{{ !$selectedGroupId ? 'background-color: var(--btn-primary-bg, #0A4744);' : '' }}">
                All My Communities
            </a>
            @foreach($joinedGroups as $jg)
                <a href="{{ route('member.surveys.index', ['group_id' => $jg->id]) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition shrink-0 {{ (int)$selectedGroupId === (int)$jg->id ? 'text-white shadow-2xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
                    style="{{ (int)$selectedGroupId === (int)$jg->id ? 'background-color: var(--btn-primary-bg, #0A4744);' : '' }}">
                    {{ $jg->name }}
                </a>
            @endforeach
        </div>
    @endif

    <!-- Surveys Grid -->
    @if($surveys->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($surveys as $survey)
                @php
                    $isCompleted = in_array($survey->id, $completedSurveyIds);
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                    <div class="p-6">
                        <!-- Top Community Badge & Status -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center space-x-1.5 text-xs font-extrabold text-slate-700 truncate max-w-[200px]">
                                <i class="fa-solid fa-users text-sky-600 text-xs"></i>
                                <span>{{ $survey->group ? $survey->group->name : 'Community' }}</span>
                            </span>

                            @if($isCompleted)
                                <span class="px-2.5 py-1 text-[11px] font-extrabold uppercase rounded-full bg-emerald-100 text-emerald-800 shrink-0">
                                    <i class="fa-solid fa-check-circle mr-1 text-emerald-600"></i> Completed
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[11px] font-extrabold uppercase rounded-full bg-amber-100 text-amber-800 shrink-0">
                                    <i class="fa-solid fa-clock mr-1 text-amber-600"></i> Action Needed
                                </span>
                            @endif
                        </div>

                        <!-- Title & Description -->
                        <h3 class="font-bold text-slate-900 text-lg line-clamp-2 leading-snug">{{ $survey->title }}</h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            {{ $survey->description ?: 'Answer the survey questions to share your feedback with the community.' }}
                        </p>

                        <!-- Survey Details Info -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span><i class="fa-solid fa-list-check mr-1 text-slate-400"></i> {{ $survey->questions_count }} Questions</span>
                            @if($survey->end_date)
                                <span><i class="fa-regular fa-calendar-xmark mr-1 text-slate-400"></i> Due: {{ $survey->end_date->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Bottom Action Bar -->
                    <div class="p-6 pt-0">
                        @if($isCompleted)
                            <a href="{{ route('member.surveys.show', $survey->id) }}"
                                class="w-full inline-flex items-center justify-center space-x-2 py-2.5 rounded-xl text-xs font-bold border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 transition">
                                <i class="fa-solid fa-eye text-xs"></i>
                                <span>View Your Submitted Answers</span>
                            </a>
                        @else
                            <a href="{{ route('member.surveys.show', $survey->id) }}"
                                class="w-full inline-flex items-center justify-center space-x-2 py-2.5 rounded-xl text-xs font-bold text-white shadow-md transition transform hover:-translate-y-0.5"
                                style="background-color: var(--btn-primary-bg, #0A4744);">
                                <i class="fa-solid fa-pen-nib text-xs"></i>
                                <span>Take Survey & Answer</span>
                            </a>
                        @endif
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
            <h3 class="text-xl font-extrabold text-slate-900">No Active Surveys</h3>
            <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                There are currently no active surveys available in your joined communities. When a Community Admin posts a survey, it will appear here for you to answer.
            </p>
        </div>
    @endif
</div>
@endsection
