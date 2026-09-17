@extends('layouts.dashboard')

@section('title', 'Manage CMS Pages')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">CMS Content Manager</h1>
            <p class="text-xs text-slate-500 mt-1">Manage public pages: About, Contact, Privacy Policy, Terms & Conditions, FAQ, Cookie Policy.</p>
        </div>
        <a href="{{ route('super_admin.cms.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-sm transition inline-flex items-center space-x-2 shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Create New Page</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($pages as $page)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base leading-snug">{{ $page->title }}</h3>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">/page/{{ $page->slug }}</p>
                    </div>
                    @if($page->group_id)
                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[11px] font-bold rounded-lg border border-amber-100 shrink-0">
                            Community Page
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-[11px] font-bold rounded-lg border border-sky-100 shrink-0">
                            Global Page
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <a href="{{ route('cms.show', $page->slug) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-200 transition flex items-center space-x-1">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <span>View Page</span>
                    </a>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('super_admin.cms.edit', $page->id) }}" class="px-3.5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg shadow-xs transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                        </a>
                        <form action="{{ route('super_admin.cms.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this page?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-lg border border-rose-200 transition">
                                <i class="fa-solid fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-4">
        {{ $pages->links() }}
    </div>
</div>
@endsection
