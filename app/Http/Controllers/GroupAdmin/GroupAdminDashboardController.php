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

                $memberCount = $activeGroup->members()
            ->where('users.id', '!=', $user->id)
            ->count();
        $newMembersCount = $activeGroup->members()
            ->where('users.id', '!=', $user->id)
            ->where('group_user.created_at', '>=', now()->subDays(30))
            ->count();
        $eventsCount = $activeGroup->events()->count();
        $noticesCount = $activeGroup->notices()->count();

        // 1. Online Members Count in Active Community
        $fiveMinutesAgo = now()->subMinutes(5);
        $onlineMembersCount = $activeGroup->members()
            ->where('users.id', '!=', $user->id)
            ->where('users.last_seen_at', '>=', $fiveMinutesAgo)
            ->where(function ($q) {
                $q->whereNull('users.last_logout_at')
                    ->orWhereColumn('users.last_seen_at', '>', 'users.last_logout_at');
            })
            ->count();

        // 2. Total Connections formed in Active Community
        $communityConnectionsCount = \App\Models\Connection::where('group_id', $activeGroup->id)
            ->where('status', 'accepted')
            ->count();

        // 3. Query Community Members with full Activity & Connection data
               // 3. Query Community Members with full Activity & Connection data
        $membersQuery = $activeGroup->members()
            ->select('users.*', 'group_user.membership_role', 'group_user.joined_at', 'group_user.status as member_status')
            ->where('users.id', '!=', $user->id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $membersQuery->where(function ($q) use ($s) {
                $q->where('users.first_name', 'like', "%{$s}%")
                    ->orWhere('users.last_name', 'like', "%{$s}%")
                    ->orWhere('users.email', 'like', "%{$s}%")
                    ->orWhere('users.profession', 'like', "%{$s}%")
                    ->orWhere('users.company', 'like', "%{$s}%")
                    ->orWhere('users.city', 'like', "%{$s}%");
            });
        }

        if ($request->get('status_filter') === 'online') {
            $membersQuery->where('users.last_seen_at', '>=', $fiveMinutesAgo)
                ->where(function ($q) {
                    $q->whereNull('users.last_logout_at')
                        ->orWhereColumn('users.last_seen_at', '>', 'users.last_logout_at');
                });
        } elseif ($request->get('status_filter') === 'offline') {
            $membersQuery->where(function ($q) use ($fiveMinutesAgo) {
                $q->whereNull('users.last_seen_at')
                    ->orWhere('users.last_seen_at', '<', $fiveMinutesAgo)
                    ->orWhereColumn('users.last_logout_at', '>=', 'users.last_seen_at');
            });
        }

        // Sort: Online users first, then by last_seen_at or last_login_at
        $communityMembers = $membersQuery
            ->orderByRaw("CASE 
                WHEN users.last_seen_at >= '{$fiveMinutesAgo->toDateTimeString()}' 
                     AND (users.last_logout_at IS NULL OR users.last_seen_at > users.last_logout_at) THEN 1 
                ELSE 2 END ASC")
            ->orderBy('users.last_seen_at', 'desc')
            ->orderBy('users.last_login_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // 4. Attach Connections Count and Recent Activities to each member
        $memberIds = $communityMembers->pluck('id')->toArray();

        $connectionsRaw = \App\Models\Connection::where('group_id', $activeGroup->id)
            ->where('status', 'accepted')
            ->where(function ($q) use ($memberIds) {
                $q->whereIn('sender_id', $memberIds)
                    ->orWhereIn('receiver_id', $memberIds);
            })
            ->get();

        $connectionsCountMap = [];
        foreach ($connectionsRaw as $conn) {
            $connectionsCountMap[$conn->sender_id] = ($connectionsCountMap[$conn->sender_id] ?? 0) + 1;
            $connectionsCountMap[$conn->receiver_id] = ($connectionsCountMap[$conn->receiver_id] ?? 0) + 1;
        }

        $activitiesGrouped = \App\Models\UserActivity::whereIn('user_id', $memberIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        foreach ($communityMembers as $m) {
            $m->group_connections_count = $connectionsCountMap[$m->id] ?? 0;
            $m->recent_activities = $activitiesGrouped->get($m->id, collect())->take(4);
        }

        $upcomingEvents = $activeGroup->upcomingEvents()->take(5)->get();

        return view('group_admin.dashboard', compact(
            'assignedGroups',
            'activeGroup',
            'memberCount',
            'onlineMembersCount',
            'newMembersCount',
            'communityConnectionsCount',
            'eventsCount',
            'noticesCount',
            'communityMembers',
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
            'promotional_message' => 'nullable|string|max:255',
            'description' => 'required|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'theme_id' => 'nullable|exists:themes,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'thumbnail_image' => 'nullable|string',
            'delete_images' => 'nullable|array',
            'expert_categories' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        try {
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('groups/logos', 'public');
                $validated['logo'] = $logoPath;
            }

            // Map tagline to promotional_message column
            if (array_key_exists('tagline', $validated)) {
                $validated['promotional_message'] = $validated['tagline'];
            }

            // Map region to state column
            if (array_key_exists('region', $validated)) {
                $validated['state'] = $validated['region'];
            }

            if ($request->has('expert_categories')) {
                if (is_array($request->expert_categories)) {
                    $categories = array_values(array_filter(array_map('trim', $request->expert_categories)));
                } else {
                    $rawText = (string) $request->expert_categories;
                    $split = preg_split('/[\n\r,]+/', $rawText);
                    $categories = array_values(array_filter(array_map('trim', $split)));
                }
                $validated['expert_categories'] = $categories;
            }

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

            // Log audit trail for changed fields
            $auditFieldMap = [
                'name' => 'name',
                'promotional_message' => 'tagline',
                'description' => 'description',
                'city' => 'city',
                'state' => 'region/state',
                'theme_id' => 'theme_id',
                'thumbnail_image' => 'thumbnail_image',
                'logo' => 'logo',
            ];

            foreach ($auditFieldMap as $dbCol => $label) {
                if (array_key_exists($dbCol, $validated)) {
                    $newValue = $validated[$dbCol];
                    $oldValue = $group->$dbCol;
                    if ($oldValue != $newValue) {
                        CommunityAuditLog::create([
                            'group_id' => $group->id,
                            'user_id' => auth()->id(),
                            'field_name' => $label,
                            'old_value' => (string) $oldValue,
                            'new_value' => (string) $newValue,
                        ]);
                    }
                }
            }

            $group->update($validated);

            return back()->with('success', 'Community details, images & theme updated successfully. Audit log entry recorded.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to update community settings: ' . $e->getMessage());
        }
    }

    public function memberActivityLog(Request $request, Group $group, \App\Models\User $user)
    {
        $this->authorizeAdmin($group);

        if (!$user->isMemberOf($group->id) && !$user->isGroupAdmin($group->id)) {
            abort(404, 'User is not a member of this community.');
        }

        $activities = \App\Models\UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $connectionsCount = $user->acceptedConnectionsCountInGroup($group->id);
        $totalConnectionsCount = $user->acceptedConnectionsCountInGroup();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profession' => $user->profession,
                    'photo' => $user->profile_photo_url,
                    'is_online' => $user->isOnline(),
                    'last_login_at' => $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'Never',
                    'last_logout_at' => $user->last_logout_at ? $user->last_logout_at->format('d M Y, h:i A') : 'Never',
                    'last_seen_at' => $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Never',
                    'group_connections_count' => $connectionsCount,
                    'total_connections_count' => $totalConnectionsCount,
                ],
                'activities' => $activities,
            ]);
        }

        return view('group_admin.members.activity', compact('group', 'user', 'activities', 'connectionsCount', 'totalConnectionsCount'));
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
