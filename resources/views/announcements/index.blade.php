@extends('layouts.dashboard')

@section('title', 'Manage Announcements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Broadcast Announcements</h1>
            <p class="text-xs text-slate-500 mt-1">Manage in-app broadcast announcements and email notifications for your community.</p>
        </div>
        <a href="{{ route('announcements.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md transition inline-flex items-center space-x-2 shrink-0">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>Create New Announcement</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800"><i class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Announcements List</h3>
            <span class="text-xs font-bold text-slate-400">Total: {{ $announcements->total() }}</span>
        </div>

        @if($announcements->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-bullhorn text-4xl mb-3 block text-slate-300"></i>
                <p class="text-sm font-medium">No announcements published yet.</p>
                <a href="{{ route('announcements.create') }}" class="mt-3 inline-block px-4 py-2 bg-sky-600 text-white font-bold text-xs rounded-xl hover:bg-sky-700 transition">
                    Publish First Announcement
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 font-extrabold border-b border-slate-200 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Image</th>
                            <th class="py-3.5 px-4">Title & Content</th>
                            <th class="py-3.5 px-4">Target Community</th>
                            <th class="py-3.5 px-4">Target Audience</th>
                            <th class="py-3.5 px-4">Author</th>
                            <th class="py-3.5 px-4">Date</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800 font-medium">
                        @foreach($announcements as $announcement)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4">
                                    @if($announcement->image_url)
                                        <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-14 h-10 rounded-lg object-cover border border-slate-200 shadow-2xs">
                                    @else
                                        <div class="w-14 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                            <i class="fa-solid fa-image text-xs"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-xs">
                                    <div class="font-bold text-slate-900 text-sm mb-0.5">{{ $announcement->title }}</div>
                                    <div class="text-slate-500 text-[11px] line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($announcement->content), 90) }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($announcement->group)
                                        <span class="px-2.5 py-1 bg-sky-50 text-sky-800 border border-sky-200 text-[11px] font-bold rounded-lg inline-block">
                                            {{ $announcement->group->name }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold rounded-lg inline-block">
                                            Global (All Communities)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-bold rounded-lg uppercase">
                                        {{ $announcement->target_role === 'all' ? 'All Members' : ucfirst($announcement->target_role) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900">{{ $announcement->creator ? $announcement->creator->name : 'Admin' }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px] whitespace-nowrap">
                                    {{ $announcement->created_at->format('M d, Y') }}<br>
                                    <span class="text-slate-400">{{ $announcement->created_at->format('H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('announcements.edit', $announcement->id) }}" class="px-3 py-1.5 bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 font-bold text-[11px] rounded-lg transition inline-flex items-center space-x-1" title="Edit Announcement">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-bold text-[11px] rounded-lg transition inline-flex items-center space-x-1" title="Delete Announcement">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
