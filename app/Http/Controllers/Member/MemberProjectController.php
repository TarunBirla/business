<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        return view('member.projects.index', compact('projects'));
    }

    public function create()
    {
        $project = new Project();
        return view('member.projects.form', compact('project'));
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

        return redirect()->route('member.projects.index')->with('success', 'Project added successfully to your portfolio!');
    }

    public function edit(Project $project)
    {
        $user = auth()->user();
        if (!$this->canManageProject($user, $project)) {
            abort(403, 'Unauthorized action.');
        }

        return view('member.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $user = auth()->user();
        if (!$this->canManageProject($user, $project)) {
            abort(403, 'Unauthorized action.');
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

        return redirect()->route('member.projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $user = auth()->user();
        if (!$this->canManageProject($user, $project)) {
            abort(403, 'Unauthorized action.');
        }

        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()->route('member.projects.index')->with('success', 'Project deleted successfully.');
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
