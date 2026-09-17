<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Group;
use App\Models\User;
use App\Mail\AnnouncementMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    public function create()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $groups = Group::orderBy('name')->get();
        } else {
            $adminGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id');
            $groups = Group::whereIn('id', $adminGroupIds)->orderBy('name')->get();
        }

        return view('announcements.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        $announcement = Announcement::create([
            'group_id' => $request->group_id,
            'created_by' => $user->id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
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

        return redirect()->route('announcements.index')->with('success', 'Announcement published successfully to target members.');
    }

    public function edit(Announcement $announcement)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            if ($announcement->group_id) {
                if (!$user->isGroupAdmin($announcement->group_id)) {
                    abort(403, 'Unauthorized to edit this announcement.');
                }
            } else {
                if ($announcement->created_by !== $user->id) {
                    abort(403, 'Unauthorized to edit this announcement.');
                }
            }
            $adminGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id');
            $groups = Group::whereIn('id', $adminGroupIds)->orderBy('name')->get();
        } else {
            $groups = Group::orderBy('name')->get();
        }

        return view('announcements.edit', compact('announcement', 'groups'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            if ($announcement->group_id) {
                if (!$user->isGroupAdmin($announcement->group_id)) {
                    abort(403, 'Unauthorized to edit this announcement.');
                }
            } else {
                if ($announcement->created_by !== $user->id) {
                    abort(403, 'Unauthorized to edit this announcement.');
                }
            }
        }

        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'target_role' => 'required|in:all,member,group_admin',
        ]);

        if ($request->filled('group_id') && $request->group_id != $announcement->group_id) {
            if (!$user->isSuperAdmin() && !$user->isGroupAdmin($request->group_id)) {
                abort(403, 'Unauthorized community target.');
            }
        }

        $imagePath = $announcement->image;
        if ($request->hasFile('image')) {
            if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update([
            'group_id' => $request->filled('group_id') ? $request->group_id : null,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'target_role' => $request->target_role,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            if ($announcement->group_id) {
                if (!$user->isGroupAdmin($announcement->group_id)) {
                    abort(403, 'Unauthorized to delete this announcement.');
                }
            } else {
                if ($announcement->created_by !== $user->id) {
                    abort(403, 'Unauthorized to delete this announcement.');
                }
            }
        }

        if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
            Storage::disk('public')->delete($announcement->image);
        }

        // Delete associated notifications
        Notification::where('announcement_id', $announcement->id)->delete();

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
