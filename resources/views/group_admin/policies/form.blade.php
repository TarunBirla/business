@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Policy: ' . $policy->title : 'Add Community Policy')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                {{ $isEdit ? 'Edit Policy Document' : 'Create Community Policy' }}
            </h1>
            <p class="text-sm text-slate-600 mt-1">Configure custom Terms & Conditions or Privacy Policy for your communities.</p>
        </div>
        <a href="{{ route('group_admin.policies.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Policies
        </a>
    </div>

    <form action="{{ $isEdit ? route('group_admin.policies.update', $policy->id) : route('group_admin.policies.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 space-y-6 shadow-xs">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(!$isEdit)
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Community *</label>
                    <select name="group_id" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
                        @if($managedGroups->count() > 1)
                            <option value="all">All Managed Communities (Bulk Apply)</option>
                        @endif
                        @foreach($managedGroups as $g)
                            <option value="{{ $g->id }}" {{ old('group_id', $policy->group_id) == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-[11px] text-slate-400 mt-1 block">Choose specific community or All Managed Communities.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Policy Type *</label>
                    <select name="page_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
                        <option value="terms" {{ old('page_type', $policy->page_type) === 'terms' ? 'selected' : '' }}>Terms & Conditions</option>
                        <option value="privacy" {{ old('page_type', $policy->page_type) === 'privacy' ? 'selected' : '' }}>Privacy Policy</option>
                    </select>
                </div>
            @else
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Community</label>
                    <input type="text" readonly value="{{ $policy->group ? $policy->group->name : 'Global Platform Policy' }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold bg-slate-50 text-slate-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Policy Type</label>
                    <input type="text" readonly value="{{ $policy->page_type === 'terms' ? 'Terms & Conditions' : 'Privacy Policy' }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold bg-slate-50 text-slate-700">
                </div>
            @endif
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Policy Document Title *</label>
            <input type="text" name="title" value="{{ old('title', $policy->title) }}" required placeholder="e.g. Gujarati Community UK Terms & Conditions" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Policy Content / Rules *</label>
            <textarea name="content" rows="12" required placeholder="Write or paste your community terms & conditions or privacy policy text here..." class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-sans leading-relaxed">{{ old('content', $policy->content) }}</textarea>
            <span class="text-[11px] text-slate-400 mt-1 block">Supports paragraphs, lists, and formatted text.</span>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('group_admin.policies.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-md transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-check text-xs"></i>
                <span>{{ $isEdit ? 'Save Policy Changes' : 'Save & Publish Policy' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
