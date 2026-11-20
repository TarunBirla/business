@extends('layouts.dashboard')

@section('title', $survey->title . ' - Survey - Bizconn')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Top Back Link -->
    <div class="flex items-center space-x-2">
        <a href="{{ route('member.surveys.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
            &larr; Back to Surveys
        </a>
        <span class="text-xs text-slate-400">/</span>
        <span class="text-xs font-bold" style="color: var(--text-link, #0A4744);">{{ $survey->group ? $survey->group->name : 'Community' }}</span>
    </div>

    <!-- Survey Header Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-3">
        <div class="flex items-center justify-between gap-2">
            <span class="px-2.5 py-1 text-xs font-extrabold uppercase rounded-full"
                style="background-color: var(--primary-light, #e0f2fe); color: var(--text-link, #0A4744);">
                <i class="fa-solid fa-users mr-1"></i> {{ $survey->group ? $survey->group->name : 'Community Survey' }}
            </span>
            @if($isCompleted)
                <span class="px-2.5 py-1 text-xs font-extrabold uppercase rounded-full bg-emerald-100 text-emerald-800">
                    <i class="fa-solid fa-check mr-1"></i> Submitted
                </span>
            @endif
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $survey->title }}</h1>

        @if($survey->description)
            <p class="text-sm text-slate-600 leading-relaxed pt-1 border-t border-slate-100">
                {{ $survey->description }}
            </p>
        @endif

        <div class="pt-2 flex flex-wrap items-center gap-4 text-xs text-slate-500">
            <span><i class="fa-solid fa-list-check mr-1 text-slate-400"></i> {{ $questions->count() }} Questions</span>
            @if($survey->end_date)
                <span><i class="fa-regular fa-clock mr-1 text-slate-400"></i> Open until: <strong>{{ $survey->end_date->format('d M Y') }}</strong></span>
            @endif
        </div>
    </div>

    @if($isCompleted)
        <!-- Completed Info Banner -->
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm flex items-start space-x-3">
            <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5"></i>
            <div>
                <strong class="font-extrabold block">You have already submitted this survey!</strong>
                <span class="text-xs text-emerald-700">Thank you for your valuable feedback. Below are the answers you provided:</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm">
            <div class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Please answer all required questions:</div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Survey Form (or Readonly View) -->
    <form action="{{ route('member.surveys.submit', $survey->id) }}" method="POST" class="space-y-5">
        @csrf

        @foreach($questions as $idx => $q)
            @php
                $savedAns = $userAnswers[$q->id] ?? null;
            @endphp
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                <!-- Question Number & Text -->
                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full text-white text-xs font-extrabold flex items-center justify-center shrink-0"
                                style="background-color: var(--btn-primary-bg, #0A4744);">
                                {{ $idx + 1 }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Question {{ $idx + 1 }}</span>
                            @if($q->is_required && !$isCompleted)
                                <span class="text-[11px] font-bold text-rose-500 uppercase tracking-wider">* Required</span>
                            @endif
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900 leading-snug pl-8">
                            {{ $q->question_text }}
                        </h3>
                    </div>
                </div>

                <div class="pl-8 pt-1">
                    @if($isCompleted)
                        <!-- Read-only Display of Submitted Answer -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-slate-900 text-sm font-semibold">
                            @if($q->question_type === 'rating')
                                <div class="flex items-center space-x-1 text-amber-500">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fa-star {{ $s <= (int)$savedAns ? 'fa-solid' : 'fa-regular text-slate-300' }}"></i>
                                    @endfor
                                    <span class="text-xs font-bold text-slate-700 ml-2">({{ $savedAns }} / 5)</span>
                                </div>
                            @else
                                <p class="whitespace-pre-line">{{ $savedAns ?: 'No answer provided' }}</p>
                            @endif
                        </div>
                    @else
                        <!-- Interactive Input Form -->
                        @if($q->question_type === 'text')
                            <!-- Text Box -->
                            <textarea name="answers[{{ $q->id }}]" rows="3"
                                {{ $q->is_required ? 'required' : '' }}
                                placeholder="Type your answer here..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium">{{ old('answers.' . $q->id) }}</textarea>

                        @elseif($q->question_type === 'single_choice')
                            <!-- Single Choice (Radio) -->
                            @php
                                $isNumericScale = !empty($q->options) && count($q->options) > 6 && is_numeric(trim(explode(' ', $q->options[0])[0]));
                            @endphp
                            @if($isNumericScale)
                                <div class="flex flex-wrap gap-2 pt-1">
                                    @foreach($q->options as $option)
                                        <label class="flex-1 min-w-[50px] text-center p-3 rounded-xl border border-slate-200 hover:border-sky-500 hover:bg-sky-50 cursor-pointer transition has-[:checked]:bg-sky-600 has-[:checked]:text-white has-[:checked]:border-sky-600 bg-white">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $option }}"
                                                {{ $q->is_required ? 'required' : '' }}
                                                {{ old('answers.' . $q->id) === $option ? 'checked' : '' }}
                                                class="sr-only">
                                            <span class="text-xs font-bold block">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-2">
                                    @if(!empty($q->options) && is_array($q->options))
                                        @foreach($q->options as $optIdx => $option)
                                            <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition bg-white">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $option }}"
                                                    {{ $q->is_required ? 'required' : '' }}
                                                    {{ old('answers.' . $q->id) === $option ? 'checked' : '' }}
                                                    class="w-4 h-4 text-sky-600 focus:ring-0">
                                                <span class="text-sm font-semibold text-slate-800">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-xs text-slate-400 italic">No options defined by admin.</p>
                                    @endif
                                </div>
                            @endif

                        @elseif($q->question_type === 'multiple_choice')
                            <!-- Multiple Choice (Checkboxes) -->
                            <div class="space-y-2">
                                @if(!empty($q->options) && is_array($q->options))
                                    @foreach($q->options as $optIdx => $option)
                                        @php
                                            $checked = is_array(old('answers.' . $q->id)) && in_array($option, old('answers.' . $q->id));
                                        @endphp
                                        <label class="flex items-center space-x-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                            <input type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $option }}"
                                                {{ $checked ? 'checked' : '' }}
                                                class="w-4 h-4 rounded text-sky-600 focus:ring-0">
                                            <span class="text-sm font-semibold text-slate-800">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-xs text-slate-400 italic">No options defined by admin.</p>
                                @endif
                            </div>

                        @elseif($q->question_type === 'rating')
                            <!-- Rating (1 to 5 Stars) -->
                            <div class="flex items-center space-x-3 p-2 bg-slate-50 rounded-xl border border-slate-200 inline-flex">
                                @for($star = 1; $star <= 5; $star++)
                                    <label class="cursor-pointer flex items-center space-x-1 p-2 rounded-lg hover:bg-amber-100 transition">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $star }}"
                                            {{ $q->is_required ? 'required' : '' }}
                                            {{ old('answers.' . $q->id) == $star ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <i class="fa-solid fa-star text-slate-300 text-xl peer-checked:text-amber-500 hover:text-amber-400 transition"></i>
                                        <span class="text-xs font-bold text-slate-700">{{ $star }}</span>
                                    </label>
                                @endfor
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach

        @if(!$isCompleted)
            <div class="flex items-center justify-end space-x-3 pt-3">
                <a href="{{ route('member.surveys.index') }}"
                    class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-xl transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3 text-white font-extrabold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center space-x-2"
                    style="background-color: var(--btn-primary-bg, #0A4744);">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Submit Survey Answers</span>
                </button>
            </div>
        @else
            <div class="text-center pt-3">
                <a href="{{ route('member.surveys.index') }}"
                    class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition inline-block">
                    &larr; Back to All Surveys
                </a>
            </div>
        @endif
    </form>
</div>
@endsection
