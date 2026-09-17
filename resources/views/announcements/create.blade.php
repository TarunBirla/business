@extends('layouts.dashboard')

@section('title', 'Create New Announcement')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Create Announcement</h1>
            <p class="text-sm text-slate-600 mt-1">Publish a new broadcast announcement to your community members.</p>
        </div>
        <a href="{{ route('announcements.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Announcements
        </a>
    </div>

    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 space-y-6 shadow-xs">
        @csrf

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Community</label>
                    <select name="group_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
                        <option value="">All Communities (Global Broadcast)</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Community *</label>
                    <select name="group_id" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Audience</label>
                <select name="target_role" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
                    <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>All Members</option>
                    <option value="member" {{ old('target_role') == 'member' ? 'selected' : '' }}>Regular Members Only</option>
                    <option value="group_admin" {{ old('target_role') == 'group_admin' ? 'selected' : '' }}>Group Admins Only</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Announcement Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Annual Community Meetup Announced!" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Announcement Banner Image</label>
            <div class="flex items-center space-x-4 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                <div class="w-24 h-16 rounded-xl bg-white border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-400">
                    <img id="announcementImgPreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                    <i id="announcementImgFallback" class="fa-solid fa-image text-2xl"></i>
                </div>
                <div class="space-y-1.5">
                    <label class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl cursor-pointer shadow-2xs transition inline-flex items-center space-x-2">
                        <i class="fa-solid fa-upload text-sky-600"></i>
                        <span>Select Image</span>
                        <input type="file" name="image" accept="image/*" class="hidden" onchange="previewAnnouncementImg(this)">
                    </label>
                    <p class="text-[11px] text-slate-400">JPG, PNG, WEBP up to 5MB.</p>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Announcement Content *</label>
            <textarea name="content" rows="6" required placeholder="Write full details of the announcement here..." class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">{{ old('content') }}</textarea>
        </div>

        <div class="pt-2 border-t border-slate-100">
            <label class="flex items-center space-x-3 cursor-pointer p-3 bg-sky-50/50 border border-sky-100 rounded-xl">
                <input type="checkbox" name="send_email" value="1" {{ old('send_email') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                <div>
                    <span class="text-xs font-bold text-slate-900 block">Send HTML Email Notification</span>
                    <span class="text-[11px] text-slate-500 block">Also send an immediate email copy of this announcement to targeted users.</span>
                </div>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('announcements.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-md transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Publish Announcement</span>
            </button>
        </div>
    </form>
</div>

<script>
function previewAnnouncementImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('announcementImgPreview');
            const fallback = document.getElementById('announcementImgFallback');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            fallback.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
