@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', $page->meta_description)

@section('content')
<div class="bg-sky-50/60 py-12 border-b border-sky-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-black">{{ $page->title }}</h1>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="bg-white p-8 md:p-12 rounded-2xl border border-slate-200 shadow-sm text-black leading-relaxed whitespace-pre-line text-base">
        {!! nl2br(e($page->content)) !!}
    </div>
</div>
@endsection
