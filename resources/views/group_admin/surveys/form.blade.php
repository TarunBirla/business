@extends('layouts.dashboard')

@section('title', ($isEdit ? 'Edit Survey: ' . $survey->title : 'Create New Survey') . ' - ' . $group->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('group_admin.surveys.index', $group->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                    &larr; Back to Surveys
                </a>
                <span class="text-xs text-slate-400">/</span>
                <span class="text-xs font-bold" style="color: var(--text-link, #0A4744);">{{ $group->name }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                {{ $isEdit ? 'Edit Community Survey' : 'Create New Community Survey' }}
            </h1>
            <p class="text-sm text-slate-600 mt-0.5">
                Design questions and collect feedback from members of <strong>{{ $group->name }}</strong>.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm">
            <div class="font-bold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Please fix the errors below:</div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('group_admin.surveys.update', ['group' => $group->id, 'survey' => $survey->id]) : route('group_admin.surveys.store', $group->id) }}"
        method="POST" id="surveyForm" class="space-y-6">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- Card 1: Survey Basic Details -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-5">
            <h3 class="text-base font-extrabold text-slate-900 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-info-circle text-sky-600"></i>
                <span>1. Survey Information</span>
            </h3>

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-extrabold uppercase text-slate-700 tracking-wider mb-1.5">
                    Survey Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" required
                    value="{{ old('title', $survey->title) }}"
                    placeholder="e.g. Community Directory & Networking Survey"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-extrabold uppercase text-slate-700 tracking-wider mb-1.5">
                    Description & Instructions (Optional)
                </label>
                <textarea name="description" id="description" rows="3"
                    placeholder="Tell members what this survey is about and how their answers help the community..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium">{{ old('description', $survey->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-extrabold uppercase text-slate-700 tracking-wider mb-1.5">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium">
                        <option value="active" {{ old('status', $survey->status) === 'active' ? 'selected' : '' }}>Active (Accepting Answers)</option>
                        <option value="inactive" {{ old('status', $survey->status) === 'inactive' ? 'selected' : '' }}>Inactive (Draft / Closed)</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-xs font-extrabold uppercase text-slate-700 tracking-wider mb-1.5">
                        Start Date (Optional)
                    </label>
                    <input type="date" name="start_date" id="start_date"
                        value="{{ old('start_date', $survey->start_date ? $survey->start_date->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium">
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-xs font-extrabold uppercase text-slate-700 tracking-wider mb-1.5">
                        Deadline / End Date (Optional)
                    </label>
                    <input type="date" name="end_date" id="end_date"
                        value="{{ old('end_date', $survey->end_date ? $survey->end_date->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium">
                </div>
            </div>
        </div>

        <!-- Card 2: Dynamic Questions Builder -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-list-check text-emerald-600"></i>
                    <span>2. Questions & Answer Choices</span>
                </h3>
                <span class="text-xs text-slate-500 font-semibold" id="questionCountBadge">
                    {{ max(1, $questions->count()) }} Questions
                </span>
            </div>

            <!-- Questions Container -->
            <div id="questionsContainer" class="space-y-4">
                @if($questions->count() > 0)
                    {{-- Render all existing questions directly from Database --}}
                    @foreach($questions as $idx => $q)
                        @php
                            $optionsText = '';
                            if (is_array($q->options)) {
                                $optionsText = implode("\n", $q->options);
                            } elseif (is_string($q->options)) {
                                $decoded = json_decode($q->options, true);
                                if (is_array($decoded)) {
                                    $optionsText = implode("\n", $decoded);
                                } else {
                                    $optionsText = $q->options;
                                }
                            }
                            $isChoice = in_array($q->question_type, ['single_choice', 'multiple_choice']);
                        @endphp
                        <div class="question-card p-5 bg-slate-50/90 rounded-2xl border border-slate-200 space-y-4 relative transition hover:border-slate-300" data-index="{{ $idx }}">
                            <!-- Top bar of question -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-800 text-white text-xs font-extrabold flex items-center justify-center question-number">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider question-label">Question #{{ $idx + 1 }}</span>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <label class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 cursor-pointer">
                                        <input type="checkbox" name="questions[{{ $idx }}][is_required]" value="1" class="rounded text-sky-600 focus:ring-0 q-required" {{ $q->is_required ? 'checked' : '' }}>
                                        <span>Required</span>
                                    </label>
                                    <button type="button" onclick="window.removeQuestionCard(this)" class="btn-remove-q p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Delete Question">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden ID if editing -->
                            <input type="hidden" name="questions[{{ $idx }}][id]" value="{{ $q->id }}" class="q-id">

                            <!-- Question Text & Type Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                                        Question Text <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="questions[{{ $idx }}][text]" required value="{{ $q->question_text }}"
                                        placeholder="e.g. Which networking topics do you want us to cover?"
                                        class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium q-text bg-white">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                                        Answer Format <span class="text-rose-500">*</span>
                                    </label>
                                    <select name="questions[{{ $idx }}][type]" required onchange="window.handleTypeChange(this)"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs font-bold q-type bg-white">
                                        <option value="text" {{ $q->question_type === 'text' ? 'selected' : '' }}>Text Box (Open Answer)</option>
                                        <option value="single_choice" {{ $q->question_type === 'single_choice' ? 'selected' : '' }}>Single Choice (Radio Options)</option>
                                        <option value="multiple_choice" {{ $q->question_type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice (Checkboxes)</option>
                                        <option value="rating" {{ $q->question_type === 'rating' ? 'selected' : '' }}>Rating (1 - 5 Stars)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Options Container (Only for Single/Multiple Choice) -->
                            <div class="options-box p-3 bg-white rounded-xl border border-slate-200 {{ $isChoice ? '' : 'hidden' }}">
                                <label class="block text-[11px] font-extrabold uppercase text-slate-700 mb-1">
                                    Answer Options <span class="text-rose-500">*</span>
                                    <span class="text-[10px] text-slate-500 font-normal lowercase">(enter each choice on a new line)</span>
                                </label>
                                <textarea name="questions[{{ $idx }}][options]" rows="3"
                                    placeholder="Option A&#10;Option B&#10;Option C"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-sky-500 q-options">{{ $optionsText }}</textarea>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Default Blank Question #1 for Create or Empty Survey --}}
                    <div class="question-card p-5 bg-slate-50/90 rounded-2xl border border-slate-200 space-y-4 relative transition hover:border-slate-300" data-index="0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="w-6 h-6 rounded-full bg-slate-800 text-white text-xs font-extrabold flex items-center justify-center question-number">
                                    1
                                </span>
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider question-label">Question #1</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 cursor-pointer">
                                    <input type="checkbox" name="questions[0][is_required]" value="1" class="rounded text-sky-600 focus:ring-0 q-required" checked>
                                    <span>Required</span>
                                </label>
                                <button type="button" onclick="window.removeQuestionCard(this)" class="btn-remove-q p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Delete Question">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <input type="hidden" name="questions[0][id]" value="" class="q-id">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                                    Question Text <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="questions[0][text]" required value=""
                                    placeholder="e.g. Which networking topics do you want us to cover?"
                                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium q-text bg-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                                    Answer Format <span class="text-rose-500">*</span>
                                </label>
                                <select name="questions[0][type]" required onchange="window.handleTypeChange(this)"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs font-bold q-type bg-white">
                                    <option value="text">Text Box (Open Answer)</option>
                                    <option value="single_choice">Single Choice (Radio Options)</option>
                                    <option value="multiple_choice">Multiple Choice (Checkboxes)</option>
                                    <option value="rating">Rating (1 - 5 Stars)</option>
                                </select>
                            </div>
                        </div>

                        <div class="options-box p-3 bg-white rounded-xl border border-slate-200 hidden">
                            <label class="block text-[11px] font-extrabold uppercase text-slate-700 mb-1">
                                Answer Options <span class="text-rose-500">*</span>
                                <span class="text-[10px] text-slate-500 font-normal lowercase">(enter each choice on a new line)</span>
                            </label>
                            <textarea name="questions[0][options]" rows="3"
                                placeholder="Option A&#10;Option B&#10;Option C"
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-sky-500 q-options"></textarea>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Add Question Action Button -->
            <div class="pt-2">
                <button type="button" id="btnAddQuestion" onclick="window.addNewQuestionCard()"
                    class="w-full py-3.5 border-2 border-dashed border-sky-300 hover:border-sky-500 bg-sky-50/60 hover:bg-sky-100 text-sky-800 font-extrabold text-sm rounded-2xl transition flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                    <i class="fa-solid fa-circle-plus text-lg"></i>
                    <span>+ Add Another Question</span>
                </button>
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="flex items-center justify-end space-x-3 pt-3">
            <a href="{{ route('group_admin.surveys.index', $group->id) }}"
                class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 text-white font-bold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5 cursor-pointer"
                style="background-color: var(--btn-primary-bg, #0A4744);">
                <i class="fa-solid fa-check mr-1.5"></i>
                <span>{{ $isEdit ? 'Save Changes' : 'Create & Publish Survey' }}</span>
            </button>
        </div>
    </form>
</div>

<!-- INLINE JAVASCRIPT: PURE, ROBUST & GLOBAL -->
<script>
window.handleTypeChange = function(select) {
    if (!select) return;
    const card = select.closest('.question-card');
    if (!card) return;
    const box = card.querySelector('.options-box');
    if (!box) return;

    if (select.value === 'single_choice' || select.value === 'multiple_choice') {
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
};

window.reindexQuestions = function() {
    const container = document.getElementById('questionsContainer');
    if (!container) return;
    const cards = container.querySelectorAll('.question-card');
    const badge = document.getElementById('questionCountBadge');

    if (badge) {
        badge.textContent = cards.length + ' Question' + (cards.length === 1 ? '' : 's');
    }

    cards.forEach(function(card, idx) {
        const num = idx + 1;
        const numEl = card.querySelector('.question-number');
        if (numEl) numEl.textContent = num;

        const labelEl = card.querySelector('.question-label');
        if (labelEl) labelEl.textContent = 'Question #' + num;
    });
};

window.removeQuestionCard = function(btn) {
    const container = document.getElementById('questionsContainer');
    if (!container) return;
    const cards = container.querySelectorAll('.question-card');

    if (cards.length <= 1) {
        alert('A survey must have at least one question.');
        return;
    }

    const card = btn.closest('.question-card');
    if (card) {
        card.remove();
        window.reindexQuestions();
    }
};

window.addNewQuestionCard = function() {
    const container = document.getElementById('questionsContainer');
    if (!container) return;

    const currentCards = container.querySelectorAll('.question-card');
    const newIdx = Date.now(); // Unique index for array fields
    const newNum = currentCards.length + 1;

    const div = document.createElement('div');
    div.className = 'question-card p-5 bg-slate-50/90 rounded-2xl border border-slate-200 space-y-4 relative transition hover:border-slate-300';
    div.setAttribute('data-index', newIdx);
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="w-6 h-6 rounded-full bg-slate-800 text-white text-xs font-extrabold flex items-center justify-center question-number">
                    ${newNum}
                </span>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider question-label">Question #${newNum}</span>
            </div>

            <div class="flex items-center space-x-2">
                <label class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 cursor-pointer">
                    <input type="checkbox" name="questions[${newIdx}][is_required]" value="1" class="rounded text-sky-600 focus:ring-0 q-required" checked>
                    <span>Required</span>
                </label>
                <button type="button" onclick="window.removeQuestionCard(this)" class="btn-remove-q p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Delete Question">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
            </div>
        </div>

        <input type="hidden" name="questions[${newIdx}][id]" value="" class="q-id">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                    Question Text <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="questions[${newIdx}][text]" required value=""
                    placeholder="e.g. Which networking topics do you want us to cover?"
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium q-text bg-white">
            </div>

            <div>
                <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                    Answer Format <span class="text-rose-500">*</span>
                </label>
                <select name="questions[${newIdx}][type]" required onchange="window.handleTypeChange(this)"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs font-bold q-type bg-white">
                    <option value="text">Text Box (Open Answer)</option>
                    <option value="single_choice">Single Choice (Radio Options)</option>
                    <option value="multiple_choice">Multiple Choice (Checkboxes)</option>
                    <option value="rating">Rating (1 - 5 Stars)</option>
                </select>
            </div>
        </div>

        <div class="options-box p-3 bg-white rounded-xl border border-slate-200 hidden">
            <label class="block text-[11px] font-extrabold uppercase text-slate-700 mb-1">
                Answer Options <span class="text-rose-500">*</span>
                <span class="text-[10px] text-slate-500 font-normal lowercase">(enter each choice on a new line)</span>
            </label>
            <textarea name="questions[${newIdx}][options]" rows="3"
                placeholder="Option A&#10;Option B&#10;Option C"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-sky-500 q-options"></textarea>
        </div>
    `;

    container.appendChild(div);
    window.reindexQuestions();

    const input = div.querySelector('.q-text');
    if (input) input.focus();
};

document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('btnAddQuestion');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.addNewQuestionCard();
        });
    }
});
</script>
@endsection
