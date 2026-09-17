<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupAdminPolicyController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $managedGroups = Group::orderBy('name')->get();
            $policies = CmsPage::whereNotNull('group_id')
                ->orWhereIn('page_type', ['terms', 'privacy'])
                ->with('group')
                ->latest()
                ->paginate(15);
        } else {
            $managedGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id');
            $managedGroups = Group::whereIn('id', $managedGroupIds)->orderBy('name')->get();
            $policies = CmsPage::whereIn('group_id', $managedGroupIds)
                ->with('group')
                ->latest()
                ->paginate(15);
        }

        return view('group_admin.policies.index', compact('policies', 'managedGroups'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $managedGroups = Group::orderBy('name')->get();
        } else {
            $managedGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id');
            $managedGroups = Group::whereIn('id', $managedGroupIds)->orderBy('name')->get();
        }

        return view('group_admin.policies.form', [
            'policy' => new CmsPage(),
            'isEdit' => false,
            'managedGroups' => $managedGroups,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'group_id' => 'nullable', // 'all' or specific group id
            'page_type' => 'required|in:terms,privacy',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($user->isSuperAdmin()) {
            $managedGroupIds = Group::pluck('id')->toArray();
        } else {
            $managedGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id')->toArray();
        }

        $targetGroupIds = [];
        if ($request->group_id === 'all' || empty($request->group_id)) {
            $targetGroupIds = $managedGroupIds;
        } else {
            if (!in_array($request->group_id, $managedGroupIds) && !$user->isSuperAdmin()) {
                abort(403, 'Unauthorized community selection.');
            }
            $targetGroupIds = [(int)$request->group_id];
        }

        foreach ($targetGroupIds as $gId) {
            $slug = "{$request->page_type}-group-{$gId}";
            CmsPage::updateOrCreate(
                [
                    'group_id' => $gId,
                    'page_type' => $request->page_type,
                ],
                [
                    'slug' => $slug,
                    'title' => $request->title,
                    'content' => $request->content,
                    'is_published' => true,
                ]
            );
        }

        return redirect()->route('group_admin.policies.index')
            ->with('success', 'Community policy published successfully.');
    }

    public function edit(CmsPage $policy)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            if ($policy->group_id) {
                if (!$user->isGroupAdmin($policy->group_id)) {
                    abort(403, 'Unauthorized access to this community policy.');
                }
            }
            $managedGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id');
            $managedGroups = Group::whereIn('id', $managedGroupIds)->orderBy('name')->get();
        } else {
            $managedGroups = Group::orderBy('name')->get();
        }

        return view('group_admin.policies.form', [
            'policy' => $policy,
            'isEdit' => true,
            'managedGroups' => $managedGroups,
        ]);
    }

    public function update(Request $request, CmsPage $policy)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            if ($policy->group_id && !$user->isGroupAdmin($policy->group_id)) {
                abort(403, 'Unauthorized access.');
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $policy->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('group_admin.policies.index')
            ->with('success', 'Community policy updated successfully.');
    }

    public function destroy(CmsPage $policy)
    {
        $user = auth()->user();

        if (!$user->isSuperAdmin()) {
            if ($policy->group_id && !$user->isGroupAdmin($policy->group_id)) {
                abort(403, 'Unauthorized access.');
            }
        }

        $policy->delete();

        return redirect()->route('group_admin.policies.index')
            ->with('success', 'Community policy deleted successfully.');
    }
}
