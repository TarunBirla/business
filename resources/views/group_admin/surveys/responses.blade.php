@extends('layouts.dashboard')

@section('title', 'Survey Results: ' . $survey->title . ' - ' . $group->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('group_admin.surveys.index', $group->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                    &larr; Back to Surveys
                </a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-bold" style="color: var(--text-link, #0A4744);">{{ $group->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Survey Responses & Member Answers</h1>
            <p class="text-sm text-slate-600 mt-0.5">
                Review what each member answered for <strong>"{{ $survey->title }}"</strong>.
            </p>
        </div>

        @if($totalParticipants > 0)
            <a href="{{ route('group_admin.surveys.export_csv', ['group' => $group->id, 'survey' => $survey->id]) }}"
                class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                <i class="fa-solid fa-file-csv text-base"></i>
                <span>Export Responses (CSV)</span>
            </a>
        @endif
    </div>

    <!-- Overview Stats Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Respondents</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-3xl font-extrabold text-slate-900">{{ $totalParticipants }}</span>
                <span class="text-xs font-semibold text-slate-500">community members</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Questions</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-3xl font-extrabold text-slate-900">{{ $survey->questions->count() }}</span>
                <span class="text-xs font-semibold text-slate-500">questions</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Current Status</span>
            <div class="mt-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $survey->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                    <i class="fa-solid fa-circle text-[8px] mr-1 {{ $survey->status === 'active' ? 'text-emerald-500' : 'text-slate-400' }}"></i>
                    {{ ucfirst($survey->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content: Member Responses List -->
    @if($totalParticipants > 0)
        <div class="space-y-4">
            <h2 class="text-lg font-extrabold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-users text-sky-600"></i>
                <span>Member-by-Member Answers ({{ $totalParticipants }})</span>
            </h2>

            @foreach($respondentUsers as $userId => $userAnswers)
                @php
                    $member = $userAnswers->first()?->user;
                    $submittedAt = $userAnswers->first()?->created_at;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <!-- Member Header Bar -->
                    <div class="p-5 bg-slate-50/80 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full font-extrabold text-white flex items-center justify-center text-sm shadow-2xs"
                                style="background-color: var(--btn-primary-bg, #0A4744);">
                                {{ $member ? strtoupper(substr($member->name, 0, 2)) : 'U' }}
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base">
                                    {{ $member ? $member->name : 'Deleted Member' }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-x-3 text-xs text-slate-500 mt-0.5">
                                    @if($member)
                                        <span><i class="fa-regular fa-envelope mr-1"></i>{{ $member->email }}</span>
                                        @if($member->phone)
                                            <span><i class="fa-solid fa-phone mr-1"></i>{{ $member->phone }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-xs text-slate-500 font-medium sm:text-right">
                            <span class="block">Submitted on:</span>
                            <strong class="text-slate-700">{{ $submittedAt ? $submittedAt->format('d M Y, h:i A') : 'N/A' }}</strong>
                        </div>
                    </div>

                    <!-- Member Answers List -->
                    <div class="p-6 space-y-4">
                        @foreach($survey->questions as $idx => $q)
                            @php
                                $resp = $userAnswers->firstWhere('question_id', $q->id);
                                $answerText = $resp ? $resp->answer : null;
                            @endphp
                            <div class="border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center space-x-1.5">
                                    <span class="w-4 h-4 rounded-full bg-slate-200 text-slate-700 text-[10px] flex items-center justify-center font-bold">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span>{{ $q->question_text }}</span>
                                </div>
                                <div class="pl-5 text-sm font-semibold text-slate-800">
                                    @if($answerText !== null && $answerText !== '')
                                        @if($q->question_type === 'rating')
                                            <div class="flex items-center space-x-1 text-amber-500">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <i class="fa-star {{ $s <= (int)$answerText ? 'fa-solid' : 'fa-regular text-slate-300' }}"></i>
                                                @endfor
                                                <span class="text-xs font-bold text-slate-700 ml-2">({{ $answerText }} / 5)</span>
                                            </div>
                                        @else
                                            <p class="whitespace-pre-line bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-slate-900 inline-block min-w-[200px]">
                                                {{ $answerText }}
                                            </p>
                                        @endif
                                    @else
                                        <span class="text-xs italic text-slate-400">No answer provided</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Responses Yet -->
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-xl mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4 bg-amber-50 text-amber-600">
                <i class="fa-solid fa-inbox text-2xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">No Responses Submitted Yet</h3>
            <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                Members of <strong>{{ $group->name }}</strong> have not submitted any answers for this survey yet. Once members submit, their full responses will appear here in real-time.
            </p>
        </div>
    @endif
</div>
@endsection
