@extends('layouts.dashboard')

@section('title', 'Community Settings - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Community Settings</h1>
            <p class="text-xs text-slate-500 mt-1">Manage details and view change audit history for <strong>{{ $group->name }}</strong>.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Community Details Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Edit Details</h3>
            <form action="{{ route('group_admin.settings.update', $group->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Name</label>
                    <input type="text" name="name" value="{{ old('name', $group->name) }}" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $group->tagline) }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
                    <textarea name="description" rows="4" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('description', $group->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $group->city) }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Region</label>
                        <input type="text" name="region" value="{{ old('region', $group->region) }}" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-sm">
                    Save Changes & Log Audit
                </button>
            </form>
        </div>

        <!-- Audit History Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Audit Trail / Update History</h3>
                    <p class="text-xs text-slate-500">Field changes logged by co-admins and super admins.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                            <th class="py-3.5 px-4">Admin</th>
                            <th class="py-3.5 px-4">Field Changed</th>
                            <th class="py-3.5 px-4">Old Value</th>
                            <th class="py-3.5 px-4">New Value</th>
                            <th class="py-3.5 px-4">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($auditLogs as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $log->user ? $log->user->name : 'System' }}</td>
                                <td class="py-3 px-4 uppercase font-bold text-sky-600 text-[11px]">{{ $log->field_name }}</td>
                                <td class="py-3 px-4 text-slate-500 max-w-[150px] truncate" title="{{ $log->old_value }}">{{ $log->old_value ?? '—' }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800 max-w-[150px] truncate" title="{{ $log->new_value }}">{{ $log->new_value ?? '—' }}</td>
                                <td class="py-3 px-4 text-slate-400 text-[11px]">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">No audit log entries recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $auditLogs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
