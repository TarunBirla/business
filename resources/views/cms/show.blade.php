@extends('layouts.app')

@section('title', $page->title)
@section('meta_description', $page->meta_description)

@section('content')
<div class="bg-gradient-to-br from-slate-900 via-sky-950 to-slate-900 py-14 text-white border-b border-slate-800">
    <div class="max-w-4xl mx-auto px-4 text-center space-y-2">
        @if($page->group)
            <span class="inline-block px-3 py-1 bg-sky-500/20 text-sky-300 border border-sky-400/30 text-xs font-bold rounded-full mb-1">
                {{ $page->group->name }} Policy
            </span>
        @endif
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">{{ $page->title }}</h1>
        <p class="text-xs text-sky-200/70 font-medium">Last updated {{ $page->updated_at ? $page->updated_at->format('F d, Y') : date('F d, Y') }}</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white p-8 md:p-12 rounded-3xl border border-slate-200 shadow-xs text-slate-800 leading-relaxed text-sm md:text-base space-y-6">
        {!! $page->content !!}
    </div>
</div>
@endsection
