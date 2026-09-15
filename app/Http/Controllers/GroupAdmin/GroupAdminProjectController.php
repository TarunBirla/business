<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupAdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'my_projects');
        $search = $request->get('search');
        $selectedGroupId = $request->get('group_id', 'all');

        if ($user->isSuperAdmin()) {
            $adminGroups = Group::orderBy('name')->get();
            $adminGroupIds = $adminGroups->pluck('id')->toArray();
        } else {
            $adminGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id')->toArray();
            $adminGroups = Group::whereIn('id', $adminGroupIds)->orderBy('name')->get();
        }

        // My Projects (Projects created by the logged-in Group Admin)
        $myProjectsQuery = Project::where('user_id', $user->id);
        if ($search && $tab === 'my_projects') {
            $myProjectsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technologies', 'like', "%{$search}%");
            });
        }
        $myProjects = $myProjectsQuery->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate(9, ['*'], 'my_page');

        // Member Projects (Projects created by members of communities where user is Group Admin)
        if ($selectedGroupId !== 'all' && in_array((int)$selectedGroupId, $adminGroupIds)) {
            $targetGroupIds = [(int)$selectedGroupId];
        } else {
            $targetGroupIds = $adminGroupIds;
        }

        $memberUserIds = User::whereHas('groups', function ($q) use ($targetGroupIds) {
            $q->whereIn('groups.id', $targetGroupIds);
        })->where('users.id', '!=', $user->id)->pluck('id')->toArray();

        $memberProjectsQuery = Project::whereIn('user_id', $memberUserIds)->with('user');

        if ($search && $tab === 'member_projects') {
            $memberProjectsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('technologies', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $memberProjects = $memberProjectsQuery->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate(9, ['*'], 'member_page');

        return view('group_admin.projects.index', compact(
            'tab',
            'myProjects',
            'memberProjects',
            'adminGroups',
            'selectedGroupId',
            'search'
        ));
    }

    public function create()
    {
        $project = new Project();
        return view('group_admin.projects.form', compact('project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:3000',
            'image' => 'nullable|image|max:5120',
            'project_url' => 'nullable|url|max:255',
            'technologies' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'status' => 'nullable|in:active,archived',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'title',
            'category',
            'description',
            'project_url',
            'technologies',
            'completion_date',
            'status',
            'sort_order',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = $request->status ?? 'active';
        $data['sort_order'] = $request->filled('sort_order') ? (int)$request->sort_order : 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        Project::create($data);

        return redirect()->route('group_admin.projects.index', ['tab' => 'my_projects'])
            ->with('success', 'Project created successfully in your portfolio!');
    }

    public function edit(Project $project)
    {
        $user = auth()->user();
        if (!$this->canManageProject($user, $project)) {
            abort(403, 'Unauthorized action. You can only edit projects in your assigned communities.');
        }

        return view('group_admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $user = auth()->user();
        if (!$this->canManageProject($user, $project)) {
            abort(403, 'Unauthorized action. You can only edit projects in your assigned communities.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:3000',
            'image' => 'nullable|image|max:5120',
            'project_url' => 'nullable|url|max:255',
            'technologies' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'status' => 'nullable|in:active,archived',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->only([
            'title',
            'category',
            'description',
            'project_url',
            'technologies',
            'completion_date',
            'status',
            'sort_order',
        ]);

        $data['sort_order'] = $request->filled('sort_order') ? (int)$request->sort_order : 0;

        if ($request->hasFile('image')) {
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        $project->update($data);

        return redirect()->route('group_admin.projects.index', ['tab' => 'my_projects'])
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $user = auth()->user();
        if (!$this->canManageProject($user, $project)) {
            abort(403, 'Unauthorized action. You can only delete projects in your assigned communities.');
        }

        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()->route('group_admin.projects.index', ['tab' => 'my_projects'])
            ->with('success', 'Project deleted successfully.');
    }

    private function canManageProject(User $user, Project $project): bool
    {
        if ($project->user_id === $user->id) {
            return true;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isGroupAdmin()) {
            $adminGroupIds = $user->groups()
                ->wherePivot('membership_role', 'group_admin')
                ->pluck('groups.id')
                ->toArray();

            if (!empty($adminGroupIds)) {
                $isMemberInAdminGroup = User::where('id', $project->user_id)
                    ->whereHas('groups', function ($q) use ($adminGroupIds) {
                        $q->whereIn('groups.id', $adminGroupIds);
                    })
                    ->exists();

                if ($isMemberInAdminGroup) {
                    return true;
                }
            }
        }

        return false;
    }
}
