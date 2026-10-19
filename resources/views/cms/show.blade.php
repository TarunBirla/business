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
    <div class="bg-white p-8 md:p-12 rounded-3xl border border-slate-200 shadow-sm text-slate-800 leading-relaxed text-sm md:text-base">
        <div class="cms-content">
            {!! $page->content !!}
        </div>
    </div>
</div>

<style>
.cms-content > * + * { margin-top: 1.1em; }
.cms-content h2 { font-size: 1.6rem; font-weight: 700; color: #0f172a; margin-top: 1.8em; }
.cms-content h3 { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-top: 1.5em; }
.cms-content h4 { font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-top: 1.3em; }
.cms-content ul { list-style: disc; padding-left: 1.5rem; }
.cms-content ol { list-style: decimal; padding-left: 1.5rem; }
.cms-content li { margin: .3em 0; }
.cms-content a { color: #0284c7; text-decoration: underline; }
.cms-content strong { font-weight: 700; color: #0f172a; }
.cms-content table { width: 100%; border-collapse: collapse; }
.cms-content th, .cms-content td { border: 1px solid #e2e8f0; padding: .6rem .8rem; text-align: left; }
.cms-content th { background: #f1f5f9; }
.cms-content blockquote { border-left: 3px solid #0ea5e9; padding-left: 1rem; color: #475569; }
</style>
@endsection