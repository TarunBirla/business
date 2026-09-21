<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\CommunityAuditLog;
use Illuminate\Http\Request;

class GroupAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $assignedGroups = Group::withCount(['members', 'events', 'notices'])->with(['auditLogs.user'])->get();
        } else {
            $assignedGroups = $user->groups()
                ->wherePivot('membership_role', 'group_admin')
                ->withCount(['members', 'events', 'notices'])
                ->with(['auditLogs.user'])
                ->get();
        }

        if ($assignedGroups->isEmpty()) {
            return view('group_admin.no_groups');
        }

        $activeGroupId = $request->get('group_id');
        $activeGroup = $activeGroupId ? $assignedGroups->firstWhere('id', $activeGroupId) : null;
        if (!$activeGroup) {
            $activeGroup = $assignedGroups->first();
        }

        $memberCount = $activeGroup->members()->count();
        $newMembersCount = $activeGroup->members()->where('group_user.created_at', '>=', now()->subDays(30))->count();
        $eventsCount = $activeGroup->events()->count();
        $noticesCount = $activeGroup->notices()->count();

        $recentMembers = $activeGroup->members()->where('users.id', '!=', auth()->id())->latest()->take(5)->get();
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

    public function communities()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $assignedGroups = Group::withCount(['members', 'events', 'notices'])->with(['auditLogs.user'])->get();
        } else {
            $assignedGroups = $user->groups()
                ->wherePivot('membership_role', 'group_admin')
                ->withCount(['members', 'events', 'notices'])
                ->with(['auditLogs.user'])
                ->get();
        }

        return view('group_admin.communities.index', compact('assignedGroups'));
    }

    public function settings(Group $group)
    {
        $this->authorizeAdmin($group);
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $assignedGroups = Group::all();
        } else {
            $assignedGroups = $user->groups()->wherePivot('membership_role', 'group_admin')->get();
        }

        $auditLogs = CommunityAuditLog::where('group_id', $group->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $themes = \App\Models\Theme::where('is_active', true)->orderBy('is_default', 'desc')->get();

        return view('group_admin.settings', compact('group', 'auditLogs', 'assignedGroups', 'themes'));
    }

    public function updateSettings(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'theme_id' => 'nullable|exists:themes,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'thumbnail_image' => 'nullable|string',
            'delete_images' => 'nullable|array',
        ]);

        $currentGallery = $group->gallery_images ?? [];

        // Handle image deletions
        if ($request->filled('delete_images')) {
            $deleteList = $request->delete_images;
            $currentGallery = array_values(array_filter($currentGallery, function ($img) use ($deleteList) {
                return !in_array($img, $deleteList);
            }));
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('groups/gallery', 'public');
                $currentGallery[] = $path;
            }
        }

        // Handle thumbnail selection
        $selectedThumbnail = $group->thumbnail_image;
        if ($request->filled('thumbnail_image')) {
            $selectedThumbnail = $request->thumbnail_image;
        } elseif (!empty($currentGallery) && !in_array($selectedThumbnail, $currentGallery)) {
            $selectedThumbnail = $currentGallery[0];
        }

        if (empty($currentGallery)) {
            $selectedThumbnail = null;
        }

        $validated['gallery_images'] = $currentGallery;
        $validated['thumbnail_image'] = $selectedThumbnail;

        foreach (['name', 'tagline', 'description', 'city', 'region', 'theme_id', 'thumbnail_image'] as $field) {
            if (array_key_exists($field, $validated)) {
                $newValue = $validated[$field];
                $oldValue = $group->$field;
                if ($oldValue != $newValue) {
                    CommunityAuditLog::create([
                        'group_id' => $group->id,
                        'user_id' => auth()->id(),
                        'field_name' => $field,
                        'old_value' => (string) $oldValue,
                        'new_value' => (string) $newValue,
                    ]);
                }
            }
        }

        $group->update($validated);

        return back()->with('success', 'Community details, images & theme updated successfully. Audit log entry recorded.');
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
