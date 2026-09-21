@extends('layouts.dashboard')

@section('title', 'Edit Community - ' . $group->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-black">Edit Community</h1>
        <p class="text-sm text-black mt-1">Update details, status, pricing, and assigned Group Admins.</p>
    </div>

    <form method="POST" action="{{ route('super_admin.groups.update', $group->id) }}" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Name *</label>
            <input type="text" name="name" value="{{ old('name', $group->name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Description *</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('description', $group->description) }}</textarea>
        </div>

        <!-- Community Photos & Thumbnail Selection -->
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
            <label class="block text-xs font-bold text-slate-700 uppercase">
                <i class="fa-solid fa-images text-sky-600 mr-1"></i> Community Photos & Thumbnail Selection
            </label>

            @if(!empty($group->gallery_images) && count($group->gallery_images) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 my-3">
                    @foreach($group->gallery_images as $index => $imgPath)
                        @php
                            $imgUrl = $group->formatImageUrl($imgPath);
                            $isThumb = ($group->thumbnail_image === $imgPath) || (empty($group->thumbnail_image) && $index === 0);
                        @endphp
                        <div class="relative bg-white p-2 rounded-xl border {{ $isThumb ? 'border-sky-500 ring-2 ring-sky-200' : 'border-slate-200' }} flex flex-col items-center">
                            @if($isThumb)
                                <span class="absolute top-1.5 left-1.5 px-2 py-0.5 bg-sky-600 text-white text-[10px] font-extrabold rounded-md shadow-xs z-10">
                                    <i class="fa-solid fa-star text-[9px] mr-1"></i> Thumbnail
                                </span>
                            @endif
                            <img src="{{ $imgUrl }}" class="w-full h-24 object-cover rounded-lg">
                            
                            <div class="w-full mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <label class="flex items-center space-x-1 font-bold text-sky-700 cursor-pointer text-[11px]">
                                    <input type="radio" name="thumbnail_image" value="{{ $imgPath }}" {{ $isThumb ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500">
                                    <span>Set Main</span>
                                </label>

                                <label class="flex items-center space-x-1 font-bold text-rose-600 cursor-pointer text-[11px]">
                                    <input type="checkbox" name="delete_images[]" value="{{ $imgPath }}" class="text-rose-600 rounded">
                                    <span>Delete</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <label class="block text-xs font-semibold text-slate-600 mt-2">Upload Additional Photos (Optional)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 border border-slate-200 rounded-xl bg-white">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Community Type *</label>
                <select name="community_type" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="free" {{ $group->community_type === 'free' ? 'selected' : '' }}>Free Community</option>
                    <option value="paid" {{ $group->community_type === 'paid' ? 'selected' : '' }}>Paid Subscription Community</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Annual Subscription Fee (£ GBP)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $subscription->price ?? 20.00) }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
            <select name="status" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                <option value="active" {{ $group->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="draft" {{ $group->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="suspended" {{ $group->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="archived" {{ $group->status === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Assigned Group Admins</label>
            <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3 space-y-2">
                @foreach($users as $user)
                    <label class="flex items-center space-x-2 text-xs font-semibold cursor-pointer">
                        <input type="checkbox" name="group_admins[]" value="{{ $user->id }}" {{ in_array($user->id, $groupAdmins) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600">
                        <span>{{ $user->name }} ({{ $user->email }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow transition">
            Update Community
        </button>
    </form>
</div>
@endsection
