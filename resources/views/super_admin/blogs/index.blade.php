@extends('layouts.dashboard')

@section('title', 'Manage Blog Articles')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-newspaper text-sky-600"></i>
                <span>Blog Articles & Content</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Manage public articles, rich content, featured images, and SEO / GEO metadata.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('blogs.index') }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center space-x-1.5 border border-slate-200">
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                <span>View Public Blogs</span>
            </a>
            <a href="{{ route('super_admin.blogs.create') }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl transition flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Write New Article</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-2 text-xs font-bold">
            <a href="{{ route('super_admin.blogs.index', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-lg transition {{ request('status', 'all') === 'all' ? 'bg-sky-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All ({{ $blogs->total() }})
            </a>
            <a href="{{ route('super_admin.blogs.index', ['status' => 'active']) }}" class="px-3 py-1.5 rounded-lg transition flex items-center space-x-1.5 {{ request('status') === 'active' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <span>Active</span>
                <span class="px-1.5 py-0.5 bg-emerald-700 text-white text-[10px] rounded-full">{{ $totalActive }}</span>
            </a>
            <a href="{{ route('super_admin.blogs.index', ['status' => 'inactive']) }}" class="px-3 py-1.5 rounded-lg transition flex items-center space-x-1.5 {{ request('status') === 'inactive' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <span>Inactive</span>
                <span class="px-1.5 py-0.5 bg-rose-700 text-white text-[10px] rounded-full">{{ $totalInactive }}</span>
            </a>
        </div>

        <form method="GET" action="{{ route('super_admin.blogs.index') }}" class="flex items-center space-x-2">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or author..." class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500 w-64">
            <button type="submit" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Search
            </button>
        </form>
    </div>

    <!-- Blogs Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase tracking-wider font-bold text-[11px]">
                        <th class="py-3.5 px-4">Article</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Views</th>
                        <th class="py-3.5 px-4">Published At</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($blogs as $blog)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        @if($blog->image_url)
                                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-bold text-sky-700 bg-sky-50 text-xs">
                                                <i class="fa-solid fa-newspaper text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-sky-600 transition line-clamp-1 max-w-md">
                                            {{ $blog->title }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 mt-0.5">By {{ $blog->author_name }} &bull; {{ $blog->reading_time }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold">
                                    {{ $blog->category ?: 'Business' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($blog->status === 'active')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[11px] inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                                        <span>Active (Live)</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full font-bold text-[11px] inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                        <span>Inactive (Hidden)</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                <i class="fa-solid fa-eye text-slate-400 mr-1 text-[10px]"></i>
                                {{ number_format($blog->views_count) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px] whitespace-nowrap">
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Draft' }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                                <!-- Status Toggle Button -->
                                <form action="{{ route('super_admin.blogs.toggle_status', $blog->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg font-bold text-[11px] transition {{ $blog->status === 'active' ? 'bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200' }}" title="Click to change visibility">
                                        {{ $blog->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('super_admin.blogs.edit', $blog->id) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition inline-block">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('super_admin.blogs.destroy', $blog->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this blog article?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-lg transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-newspaper text-3xl mb-2 text-slate-300"></i>
                                <p>No blog articles created yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($blogs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
