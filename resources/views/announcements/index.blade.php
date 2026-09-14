@extends('layouts.dashboard')

@section('title', 'Manage Announcements')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Broadcast Announcements</h1>
            <p class="text-xs text-slate-500 mt-1">Send in-app notifications and emails to community members.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- New Announcement Form -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Create Announcement</h3>

            <form action="{{ route('announcements.store') }}" method="POST" class="space-y-4">
                @csrf
                @if(auth()->user()->isSuperAdmin())
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Community</label>
                        <select name="group_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                            <option value="">All Communities (Global)</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Community *</label>
                        <select name="group_id" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Title *</label>
                    <input type="text" name="title" required placeholder="Announcement Title" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Content *</label>
                    <textarea name="content" rows="5" required placeholder="Write your announcement content here..." class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Audience</label>
                    <select name="target_role" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        <option value="all">All Members</option>
                        <option value="member">Regular Members Only</option>
                        <option value="group_admin">Group Admins Only</option>
                    </select>
                </div>

                <div class="pt-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="send_email" value="1" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <span class="text-xs font-bold text-slate-700">Also send HTML Email Notification</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-sm">
                    <i class="fa-solid fa-paper-plane mr-1.5"></i> Publish Announcement
                </button>
            </form>
        </div>

        <!-- Recent Announcements History -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Broadcast History</h3>
            </div>
            @if($announcements->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <i class="fa-solid fa-bullhorn text-4xl mb-3 block"></i>
                    <p class="text-sm font-medium">No announcements published yet.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($announcements as $announcement)
                        <div class="p-6 space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <h4 class="text-base font-bold text-slate-900">{{ $announcement->title }}</h4>
                                <div class="flex items-center space-x-2 shrink-0">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg">
                                        {{ $announcement->group ? $announcement->group->name : 'Global' }}
                                    </span>
                                    <button type="button" onclick='openEditAnnouncementModal(@json($announcement))' class="px-2.5 py-1 bg-sky-50 text-sky-700 hover:bg-sky-100 text-xs font-bold rounded-lg transition border border-sky-200 flex items-center space-x-1" title="Edit Announcement">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        <span>Edit</span>
                                    </button>
                                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold rounded-lg transition border border-rose-200 flex items-center space-x-1" title="Delete Announcement">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{!! nl2br(e($announcement->content)) !!}</p>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-50">
                                <span>Posted by <strong>{{ $announcement->creator ? $announcement->creator->name : 'Admin' }}</strong></span>
                                <span>{{ $announcement->created_at->format('M d, Y - H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t border-slate-100">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div id="editAnnouncementModal" class="hidden fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 relative">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-pen-to-square text-sky-600"></i>
                <span>Edit Announcement</span>
            </h3>
            <button type="button" onclick="closeEditAnnouncementModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editAnnouncementForm" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Community</label>
                    <select name="group_id" id="edit_group_id" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        <option value="">All Communities (Global)</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Community *</label>
                    <select name="group_id" id="edit_group_id" required class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Title *</label>
                <input type="text" name="title" id="edit_title" required placeholder="Announcement Title" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Content *</label>
                <textarea name="content" id="edit_content" rows="5" required placeholder="Write your announcement content here..." class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Audience</label>
                <select name="target_role" id="edit_target_role" class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <option value="all">All Members</option>
                    <option value="member">Regular Members Only</option>
                    <option value="group_admin">Group Admins Only</option>
                </select>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-2">
                <button type="button" onclick="closeEditAnnouncementModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-sm transition">
                    Update Announcement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditAnnouncementModal(item) {
        const modal = document.getElementById('editAnnouncementModal');
        const form = document.getElementById('editAnnouncementForm');
        form.action = `/announcements/${item.id}`;
        document.getElementById('edit_title').value = item.title;
        document.getElementById('edit_content').value = item.content;
        if (document.getElementById('edit_group_id')) {
            document.getElementById('edit_group_id').value = item.group_id || '';
        }
        if (document.getElementById('edit_target_role')) {
            document.getElementById('edit_target_role').value = item.target_role || 'all';
        }
        modal.classList.remove('hidden');
    }

    function closeEditAnnouncementModal() {
        const modal = document.getElementById('editAnnouncementModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
