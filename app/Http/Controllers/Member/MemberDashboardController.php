<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\EventRegistration;
use App\Models\Notice;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $myGroups = $user->groups()->with(['events', 'notices'])->get();
        $groupIds = $myGroups->pluck('id');

        $pendingConnectionsCount = Connection::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $myConnectionsCount = Connection::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->count();

        $upcomingEvents = EventRegistration::where('user_id', $user->id)
            ->whereHas('event', function($q) {
                $q->where('start_at', '>=', now())->where('status', 'published');
            })
            ->with('event.group')
            ->take(3)
            ->get();

        $latestNotices = Notice::whereIn('group_id', $groupIds)
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->with('group')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $profileCompletion = $user->profile_completion_percentage;

        return view('member.dashboard', compact(
            'user',
            'myGroups',
            'pendingConnectionsCount',
            'myConnectionsCount',
            'upcomingEvents',
            'latestNotices',
            'profileCompletion'
        ));
    }
}
