<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupAdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $assignedGroups = Group::withCount(['members', 'events', 'notices'])->get();
        } else {
            $assignedGroups = $user->groups()->wherePivot('membership_role', 'group_admin')->withCount(['members', 'events', 'notices'])->get();
        }

        if ($assignedGroups->isEmpty()) {
            return view('group_admin.no_groups');
        }

        $activeGroup = $assignedGroups->first();

        $memberCount = $activeGroup->members()->count();
        $newMembersCount = $activeGroup->members()->where('group_user.created_at', '>=', now()->subDays(30))->count();
        $eventsCount = $activeGroup->events()->count();
        $noticesCount = $activeGroup->notices()->count();

        $recentMembers = $activeGroup->members()->latest()->take(5)->get();
        $upcomingEvents = $activeGroup->upcomingEvents()->take(5)->get();

        return view('group_admin.dashboard', compact(
            'assignedGroups',
            'activeGroup',
            'memberCount',
            'newMembersCount',
            'eventsCount',
            'noticesCount',
            'recentMembers',
            'upcomingEvents'
        ));
    }
}
