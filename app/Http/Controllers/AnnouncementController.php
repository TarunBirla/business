<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Group;
use App\Models\User;
use App\Mail\AnnouncementMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $announcements = Announcement::with('group', 'creator')->latest()->paginate(15);
            $groups = Group::orderBy('name')->get();
        } else {
            $adminGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id');
            $announcements = Announcement::whereIn('group_id', $adminGroupIds)->with('group', 'creator')->latest()->paginate(15);
            $groups = Group::whereIn('id', $adminGroupIds)->orderBy('name')->get();
        }

        return view('announcements.index', compact('announcements', 'groups'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:all,member,group_admin',
            'send_email' => 'nullable|boolean',
        ]);

        if ($request->filled('group_id')) {
            if (!$user->isSuperAdmin() && !$user->isGroupAdmin($request->group_id)) {
                abort(403, 'Unauthorized community target.');
            }
        } else {
            if (!$user->isSuperAdmin()) {
                abort(403, 'Only Super Admins can post global announcements.');
            }
        }

        $announcement = Announcement::create([
            'group_id' => $request->group_id,
            'created_by' => $user->id,
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
            'is_email_sent' => $request->boolean('send_email'),
        ]);

        // Target users for notification
        if ($request->filled('group_id')) {
            $group = Group::find($request->group_id);
            $targetQuery = $group->members();
            if ($request->target_role === 'group_admin') {
                $targetQuery->wherePivot('membership_role', 'group_admin');
            } elseif ($request->target_role === 'member') {
                $targetQuery->wherePivot('membership_role', 'member');
            }
            $targetUsers = $targetQuery->get();
        } else {
            $targetQuery = User::query();
            if ($request->target_role !== 'all') {
                $targetQuery->where('global_role', $request->target_role);
            }
            $targetUsers = $targetQuery->get();
        }

        foreach ($targetUsers as $targetUser) {
            Notification::create([
                'user_id' => $targetUser->id,
                'announcement_id' => $announcement->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => Str::limit($announcement->content, 120),
                'link' => route('notifications.index'),
            ]);

            if ($request->boolean('send_email')) {
                try {
                    Mail::to($targetUser->email)->send(new AnnouncementMail($announcement, $targetUser));
                } catch (\Exception $e) {
                    // Ignore email error
                }
            }
        }

        return back()->with('success', 'Announcement published successfully to target members.');
    }
}
