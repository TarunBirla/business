@extends('layouts.dashboard')

@section('title', 'Community Settings - ' . $group->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Community Settings</h1>
            <p class="text-xs text-slate-500 mt-1">Manage details and view change audit history for <strong>{{ $group->name }}</strong>.</p>
        </div>
        @if(isset($assignedGroups) && $assignedGroups->count() > 1)
            <div class="flex items-center space-x-2">
                <label class="text-xs font-bold text-slate-600 uppercase">Switch Community:</label>
                <select onchange="window.location.href=this.value" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 shadow-2xs">
                    @foreach($assignedGroups as $ag)
                        <option value="{{ route('group_admin.settings', $ag->id) }}" {{ $ag->id === $group->id ? 'selected' : '' }}>
                            {{ $ag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Community Details Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Edit Details</h3>
            <form action="{{ route('group_admin.settings.update', $group->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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

                <!-- Community Photos & Thumbnail Selection -->
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        <i class="fa-solid fa-images text-sky-600 mr-1"></i> Community Photos & Thumbnail Selection
                    </label>

                    @if(!empty($group->gallery_images) && count($group->gallery_images) > 0)
                        <div class="grid grid-cols-2 gap-3 my-2">
                            @foreach($group->gallery_images as $index => $imgPath)
                                @php
                                    $imgUrl = $group->formatImageUrl($imgPath);
                                    $isThumb = ($group->thumbnail_image === $imgPath) || (empty($group->thumbnail_image) && $index === 0);
                                @endphp
                                <div class="relative bg-white p-2 rounded-xl border {{ $isThumb ? 'border-sky-500 ring-2 ring-sky-200' : 'border-slate-200' }} flex flex-col items-center">
                                    @if($isThumb)
                                        <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-sky-600 text-white text-[9px] font-extrabold rounded-md shadow-xs z-10">
                                            <i class="fa-solid fa-star text-[8px]"></i> Thumbnail
                                        </span>
                                    @endif
                                    <img src="{{ $imgUrl }}" class="w-full h-20 object-cover rounded-lg">
                                    
                                    <div class="w-full mt-1.5 pt-1.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                        <label class="flex items-center space-x-1 font-bold text-sky-700 cursor-pointer">
                                            <input type="radio" name="thumbnail_image" value="{{ $imgPath }}" {{ $isThumb ? 'checked' : '' }} class="text-sky-600">
                                            <span>Set Main</span>
                                        </label>

                                        <label class="flex items-center space-x-1 font-bold text-rose-600 cursor-pointer">
                                            <input type="checkbox" name="delete_images[]" value="{{ $imgPath }}" class="text-rose-600 rounded">
                                            <span>Delete</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <label class="block text-[11px] font-semibold text-slate-600">Upload Additional Photos (Optional)</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl bg-white">
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

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        <i class="fa-solid fa-palette text-sky-600 mr-1"></i> Community Theme (Applies to Members)
                    </label>
                    <select name="theme_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium">
                        <option value="">Default Platform Theme (Original Teal)</option>
                        @if(isset($themes))
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}" {{ old('theme_id', $group->theme_id) == $theme->id ? 'selected' : '' }}>
                                    {{ $theme->name }} {{ $theme->is_default ? '(Global Default)' : '' }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Select a custom theme for members in {{ $group->name }}.</p>
                </div>

                <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-sm">
                    Save Changes & Log Audit
                </button>
            </form>
        </div>

        <!-- Audit History Table -->
        <div class=" bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
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
