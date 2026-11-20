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
                    placeholder="e.g. Q4 Networking Preferences & Community Feedback"
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
                <span class="text-xs text-slate-500 font-semibold" id="questionCountBadge">0 Questions</span>
            </div>

            <!-- Questions Container -->
            <div id="questionsContainer" class="space-y-4">
                <!-- Javascript will inject question cards here or PHP will render existing -->
            </div>

            <!-- Add Question Action Button -->
            <div class="pt-2">
                <button type="button" id="btnAddQuestion"
                    class="w-full py-3 border-2 border-dashed border-sky-300 hover:border-sky-500 bg-sky-50/50 hover:bg-sky-50 text-sky-700 font-extrabold text-sm rounded-2xl transition flex items-center justify-center space-x-2 shadow-2xs">
                    <i class="fa-solid fa-circle-plus text-base"></i>
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
                class="px-6 py-2.5 text-white font-bold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5"
                style="background-color: var(--btn-primary-bg, #0A4744);">
                <i class="fa-solid fa-check mr-1.5"></i>
                <span>{{ $isEdit ? 'Save Changes' : 'Create & Publish Survey' }}</span>
            </button>
        </div>
    </form>
</div>

<!-- Question Card Template (Hidden) -->
<template id="questionTemplate">
    <div class="question-card p-5 bg-slate-50/90 rounded-2xl border border-slate-200 space-y-4 relative transition hover:border-slate-300" data-index="{INDEX}">
        <!-- Top bar of question -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="w-6 h-6 rounded-full bg-slate-800 text-white text-xs font-extrabold flex items-center justify-center question-number">
                    {NUM}
                </span>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Question #{NUM}</span>
            </div>

            <div class="flex items-center space-x-2">
                <label class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 cursor-pointer">
                    <input type="checkbox" name="questions[{INDEX}][is_required]" value="1" class="rounded text-sky-600 focus:ring-0 q-required" checked>
                    <span>Required</span>
                </label>
                <button type="button" class="btn-remove-q p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Delete Question">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Hidden ID if editing -->
        <input type="hidden" name="questions[{INDEX}][id]" value="{QUESTION_ID}" class="q-id">

        <!-- Question Text & Type Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                    Question Text <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="questions[{INDEX}][text]" required value="{TEXT}"
                    placeholder="e.g. Which networking topics do you want us to cover?"
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-sm font-medium q-text bg-white">
            </div>

            <div>
                <label class="block text-[11px] font-extrabold uppercase text-slate-600 mb-1">
                    Answer Format <span class="text-rose-500">*</span>
                </label>
                <select name="questions[{INDEX}][type]" required
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 text-xs font-bold q-type bg-white">
                    <option value="text" {SELECTED_TEXT}>Text Box (Open Answer)</option>
                    <option value="single_choice" {SELECTED_SINGLE}>Single Choice (Radio Options)</option>
                    <option value="multiple_choice" {SELECTED_MULTI}>Multiple Choice (Checkboxes)</option>
                    <option value="rating" {SELECTED_RATING}>Rating (1 - 5 Stars)</option>
                </select>
            </div>
        </div>

        <!-- Options Container (Only for Single/Multiple Choice) -->
        <div class="options-box p-3 bg-white rounded-xl border border-slate-200 {OPTIONS_HIDDEN}">
            <label class="block text-[11px] font-extrabold uppercase text-slate-700 mb-1">
                Answer Options <span class="text-rose-500">*</span>
                <span class="text-[10px] text-slate-500 font-normal lowercase">(enter each choice on a new line or separated by commas)</span>
            </label>
            <textarea name="questions[{INDEX}][options]" rows="3"
                placeholder="Option A&#10;Option B&#10;Option C"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium focus:outline-none focus:ring-1 focus:ring-sky-500 q-options">{OPTIONS}</textarea>
        </div>
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('questionsContainer');
    const btnAdd = document.getElementById('btnAddQuestion');
    const template = document.getElementById('questionTemplate').innerHTML;
    const badge = document.getElementById('questionCountBadge');

    let questionIndex = 0;

    // Existing questions data passed from controller
    const initialQuestions = @json($questions->map(function($q) {
        return [
            'id' => $q->id,
            'text' => $q->question_text,
            'type' => $q->question_type,
            'options' => is_array($q->options) ? implode("\n", $q->options) : '',
            'is_required' => $q->is_required ? true : false,
        ];
    }));

    function updateNumbers() {
        const cards = container.querySelectorAll('.question-card');
        badge.textContent = `${cards.length} Question${cards.length === 1 ? '' : 's'}`;
        cards.forEach((card, idx) => {
            const num = idx + 1;
            const numEl = card.querySelector('.question-number');
            if (numEl) numEl.textContent = num;
            const labelEl = card.querySelector('span.uppercase');
            if (labelEl) labelEl.textContent = `Question #${num}`;
        });
    }

    function addQuestion(data = null) {
        const idx = questionIndex++;
        const num = container.querySelectorAll('.question-card').length + 1;

        let html = template
            .replace(/{INDEX}/g, idx)
            .replace(/{NUM}/g, num)
            .replace(/{QUESTION_ID}/g, data ? data.id : '')
            .replace(/{TEXT}/g, data ? (data.text || '').replace(/"/g, '&quot;') : '')
            .replace(/{OPTIONS}/g, data ? data.options || '' : '')
            .replace(/{SELECTED_TEXT}/g, data && data.type === 'text' ? 'selected' : '')
            .replace(/{SELECTED_SINGLE}/g, data && data.type === 'single_choice' ? 'selected' : '')
            .replace(/{SELECTED_MULTI}/g, data && data.type === 'multiple_choice' ? 'selected' : '')
            .replace(/{SELECTED_RATING}/g, data && data.type === 'rating' ? 'selected' : '');

        const isChoice = data && (data.type === 'single_choice' || data.type === 'multiple_choice');
        html = html.replace(/{OPTIONS_HIDDEN}/g, isChoice ? '' : 'hidden');

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newCard = tempDiv.firstElementChild;

        // Set is_required checkbox if provided
        if (data && data.is_required === false) {
            const reqBox = newCard.querySelector('.q-required');
            if (reqBox) reqBox.checked = false;
        }

        // Attach type change listener
        const typeSelect = newCard.querySelector('.q-type');
        const optionsBox = newCard.querySelector('.options-box');
        typeSelect.addEventListener('change', function () {
            if (this.value === 'single_choice' || this.value === 'multiple_choice') {
                optionsBox.classList.remove('hidden');
            } else {
                optionsBox.classList.add('hidden');
            }
        });

        // Attach remove listener
        const btnRemove = newCard.querySelector('.btn-remove-q');
        btnRemove.addEventListener('click', function () {
            if (container.querySelectorAll('.question-card').length <= 1) {
                alert('A survey must have at least one question.');
                return;
            }
            newCard.remove();
            updateNumbers();
        });

        container.appendChild(newCard);
        updateNumbers();
    }

    // Load initial or add 1 default
    if (initialQuestions && initialQuestions.length > 0) {
        initialQuestions.forEach(q => addQuestion(q));
    } else {
        addQuestion();
    }

    btnAdd.addEventListener('click', () => addQuestion());
});
</script>
@endpush
@endsection
