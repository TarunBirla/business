@extends('layouts.dashboard')

@section('title', 'Community Terms & Privacy Policies')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Community Policies & Guidelines</h1>
            <p class="text-xs text-slate-500 mt-1">Manage custom Terms & Conditions and Privacy Policies for your managed communities.</p>
        </div>
        <a href="{{ route('group_admin.policies.create') }}" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow-md transition inline-flex items-center space-x-2 shrink-0">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>Add Community Policy</span>
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
            <h3 class="text-base font-bold text-slate-900">Policy Documents List</h3>
            <span class="text-xs font-bold text-slate-400">Total: {{ $policies->total() }}</span>
        </div>

        @if($policies->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-file-contract text-4xl mb-3 block text-slate-300"></i>
                <p class="text-sm font-medium">No community custom policies created yet.</p>
                <p class="text-xs text-slate-400 mt-1">Registration forms currently use default platform policy fallback.</p>
                <a href="{{ route('group_admin.policies.create') }}" class="mt-4 inline-block px-4 py-2 bg-sky-600 text-white font-bold text-xs rounded-xl hover:bg-sky-700 transition shadow-xs">
                    Create Community Policy
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-700 font-extrabold border-b border-slate-200 uppercase tracking-wider">
                            <th class="py-3.5 px-4">Community</th>
                            <th class="py-3.5 px-4">Policy Type</th>
                            <th class="py-3.5 px-4">Document Title</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Last Updated</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800 font-medium">
                        @foreach($policies as $policy)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4">
                                    @if($policy->group)
                                        <span class="px-2.5 py-1 bg-sky-50 text-sky-800 border border-sky-200 text-[11px] font-bold rounded-lg inline-block">
                                            <i class="fa-solid fa-users text-[10px] mr-1"></i>{{ $policy->group->name }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold rounded-lg inline-block">
                                            Global Platform Policy
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($policy->page_type === 'terms' || $policy->slug === 'terms')
                                        <span class="px-2.5 py-1 bg-purple-50 text-purple-800 border border-purple-200 text-[11px] font-extrabold rounded-lg uppercase">
                                            Terms & Conditions
                                        </span>
                                    @elseif($policy->page_type === 'privacy' || $policy->slug === 'privacy')
                                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-800 border border-indigo-200 text-[11px] font-extrabold rounded-lg uppercase">
                                            Privacy Policy
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-bold rounded-lg uppercase">
                                            {{ strtoupper($policy->page_type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                    {{ $policy->title }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-full">
                                        Active
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                    {{ $policy->updated_at->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-1">
                                    @if($policy->group_id)
                                        <a href="{{ route('cms.show', ['slug' => $policy->page_type, 'group_id' => $policy->group_id]) }}" target="_blank" class="px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 font-bold text-[11px] rounded-lg transition inline-flex items-center space-x-1" title="View Public Page">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                            <span>View</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('group_admin.policies.edit', $policy->id) }}" class="px-3 py-1.5 bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 font-bold text-[11px] rounded-lg transition inline-flex items-center space-x-1" title="Edit Policy">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        <span>Edit</span>
                                    </a>
                                    @if($policy->group_id)
                                        <form action="{{ route('group_admin.policies.destroy', $policy->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this community policy?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 font-bold text-[11px] rounded-lg transition inline-flex items-center space-x-1" title="Delete Policy">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $policies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
