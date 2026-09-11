<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;
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
        ]);

        $data = $request->only([
            'title',
            'category',
            'description',
            'project_url',
            'technologies',
            'completion_date',
            'status',
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = $request->status ?? 'active';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        Project::create($data);

        return redirect()->route('member.projects.index')->with('success', 'Project added successfully to your portfolio!');
    }

    public function edit(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('member.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== auth()->id()) {
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
        ]);

        $data = $request->only([
            'title',
            'category',
            'description',
            'project_url',
            'technologies',
            'completion_date',
            'status',
        ]);

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
        if ($project->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()->route('member.projects.index')->with('success', 'Project deleted successfully.');
    }
}
