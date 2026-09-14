@extends(auth()->user()->isSuperAdmin() ? 'layouts.dashboard' : (auth()->user()->isGroupAdmin() ? 'layouts.dashboard' : 'layouts.dashboard'))

@section('title', 'Notifications & Announcements')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Notifications & Announcements</h1>
            <p class="text-xs text-slate-500 mt-1">Stay updated with community announcements, approvals, and events.</p>
        </div>
        <div class="flex items-center space-x-3">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.mark_all_read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-sky-50 text-sky-600 hover:bg-sky-100 text-xs font-bold rounded-xl transition">
                        <i class="fa-solid fa-check-double mr-1.5"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-bell-slash text-4xl mb-3 block"></i>
                <p class="text-sm font-medium">No notifications found.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($notifications as $notification)
                    <div class="p-4 md:p-6 transition flex items-start justify-between gap-4 {{ $notification->is_read ? 'bg-white' : 'bg-sky-50/40 font-medium' }}">
                        <div class="flex items-start space-x-4 min-w-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $notification->is_read ? 'bg-slate-100 text-slate-500' : 'bg-sky-100 text-sky-600' }}">
                                @if($notification->type === 'announcement')
                                    <i class="fa-solid fa-bullhorn text-sm"></i>
                                @elseif($notification->type === 'approval')
                                    <i class="fa-solid fa-user-check text-sm"></i>
                                @else
                                    <i class="fa-solid fa-bell text-sm"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $notification->title }}</h4>
                                    @if(!$notification->is_read)
                                        <span class="px-2 py-0.5 bg-sky-600 text-white font-bold text-[10px] uppercase rounded-full">New</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $notification->message }}</p>
                                <span class="text-[11px] text-slate-400 mt-2 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0">
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.mark_read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-lg transition" title="Mark as Read">
                                        Read
                                    </button>
                                </form>
                            @endif
                            @if($notification->link)
                                <a href="{{ route('notifications.mark_read', $notification->id) }}" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-lg transition">
                                    View <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
