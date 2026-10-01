@extends('layouts.dashboard')

@section('title', 'Community Settings - ' . $group->name)

@section('content')
<div class="space-y-6">
    <!-- Top Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-sliders text-sky-600"></i>
                <span>Community Settings</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Manage branding, photos, services, and view change audit logs for <strong>{{ $group->name }}</strong>.</p>
        </div>
        @if(isset($assignedGroups) && $assignedGroups->count() > 1)
            <div class="flex items-center space-x-2 bg-slate-50 p-2 rounded-xl border border-slate-200">
                <label class="text-xs font-bold text-slate-600 uppercase">Switch Community:</label>
                <select onchange="window.location.href=this.value" class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 focus:outline-none focus:border-sky-500 shadow-2xs">
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
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-semibold flex items-center space-x-2 shadow-2xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm rounded-xl font-semibold flex items-center space-x-2 shadow-2xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm rounded-xl space-y-1">
            <div class="font-bold flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>Please fix the following validation errors:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-0.5 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <!-- Community Details Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-pen-to-square text-sky-600"></i>
                    <span>Edit Community Details</span>
                </h3>
                <span class="text-xs font-semibold text-slate-400">ID: #{{ $group->id }}</span>
            </div>

            <form action="{{ route('group_admin.settings.update', $group->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <!-- Section 1: Basic Information -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Name *</label>
                        <input type="text" name="name" value="{{ old('name', $group->name) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $group->tagline) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description *</label>
                        <textarea name="description" rows="4" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition">{{ old('description', $group->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city', $group->city) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Region / State</label>
                            <input type="text" name="region" value="{{ old('region', $group->region) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Brand Logo Upload -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block text-xs font-bold text-slate-800 uppercase flex items-center space-x-1.5">
                        <i class="fa-solid fa-shield-halved text-sky-600"></i>
                        <span>Community Brand Logo (Displays in Sidebar Header)</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shrink-0 overflow-hidden shadow-xs">
                            @if($group->logo_url)
                                <img src="{{ $group->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <span class="font-bold text-sky-700 text-base uppercase">{{ substr($group->name, 0, 2) }}</span>
                            @endif
                        </div>
                        <div class="flex-grow space-y-1">
                            <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-600 file:text-white hover:file:bg-sky-700 border border-slate-200 rounded-xl bg-white cursor-pointer">
                            <p class="text-[11px] text-slate-500">Upload custom logo image for {{ $group->name }}. Displays in the main dashboard sidebar header.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Community Photos & Thumbnail Selection -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                    <label class="block text-xs font-bold text-slate-800 uppercase flex items-center space-x-1.5">
                        <i class="fa-solid fa-images text-sky-600"></i>
                        <span>Community Gallery & Cover Thumbnail</span>
                    </label>

                    @if(!empty($group->gallery_images) && count($group->gallery_images) > 0)
                        <div class="grid grid-cols-2 gap-3 my-2">
                            @foreach($group->gallery_images as $index => $imgPath)
                                @php
                                    $imgUrl = $group->formatImageUrl($imgPath);
                                    $isThumb = ($group->thumbnail_image === $imgPath) || (empty($group->thumbnail_image) && $index === 0);
                                @endphp
                                <div class="relative bg-white p-2 rounded-xl border {{ $isThumb ? 'border-sky-500 ring-2 ring-sky-200' : 'border-slate-200' }} flex flex-col items-center shadow-2xs">
                                    @if($isThumb)
                                        <span class="absolute top-1.5 left-1.5 px-2 py-0.5 bg-sky-600 text-white text-[9px] font-extrabold rounded-md shadow-xs z-10">
                                            <i class="fa-solid fa-star text-[8px] mr-0.5"></i> Thumbnail
                                        </span>
                                    @endif
                                    <img src="{{ $imgUrl }}" class="w-full h-24 object-cover rounded-lg">
                                    
                                    <div class="w-full mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                        <label class="flex items-center space-x-1 font-bold text-sky-700 cursor-pointer">
                                            <input type="radio" name="thumbnail_image" value="{{ $imgPath }}" {{ $isThumb ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                                            <span>Set Main</span>
                                        </label>

                                        <label class="flex items-center space-x-1 font-bold text-rose-600 cursor-pointer">
                                            <input type="checkbox" name="delete_images[]" value="{{ $imgPath }}" class="text-rose-600 rounded focus:ring-rose-500">
                                            <span>Delete</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase">Upload Additional Photos (Multiple)</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl bg-white cursor-pointer">
                    </div>
                </div>

                <!-- Section 4: Theme & Expert Categories -->
                <div class="space-y-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1 flex items-center space-x-1">
                            <i class="fa-solid fa-palette text-sky-600"></i>
                            <span>Community Theme (Applies to Members)</span>
                        </label>
                        <select name="theme_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition">
                            <option value="">Default Platform Theme (Original Teal)</option>
                            @if(isset($themes))
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}" {{ old('theme_id', $group->theme_id) == $theme->id ? 'selected' : '' }}>
                                        {{ $theme->name }} {{ $theme->is_default ? '(Global Default)' : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Select a custom theme styling for members in {{ $group->name }}.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1 flex items-center space-x-1">
                            <i class="fa-solid fa-briefcase text-sky-600"></i>
                            <span>Available Expert Categories in Community</span>
                        </label>
                        <textarea name="expert_categories" rows="4" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition" placeholder="Enter service categories separated by commas or new lines (e.g. Accounting & Tax Advice, Digital Marketing & SEO, IT & Web Development, Legal & Solicitor Services)">{{ old('expert_categories', is_array($group->expert_categories) ? implode("\n", $group->expert_categories) : $group->expert_categories) }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Enter categories separated by commas or new lines. These will display on the Public Community page and in Member Profile options.</p>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-md flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Changes & Log Audit History</span>
                </button>
            </form>
        </div>

        <!-- Audit History Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-clock-rotate-left text-sky-600"></i>
                    <span>Audit Trail / Update History</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Field changes logged by co-admins and super admins.</p>
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
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-history text-2xl mb-2 text-slate-300"></i>
                                    <p>No audit log entries recorded yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($auditLogs->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
