<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Notice;
use Illuminate\Http\Request;

class GroupAdminNoticeController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);
        $notices = $group->notices()->latest()->paginate(10);
        return view('group_admin.notices.index', compact('group', 'notices'));
    }

    public function create(Group $group)
    {
        $this->authorizeAdmin($group);
        return view('group_admin.notices.create', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $group->notices()->create([
            'created_by' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'published_at' => now(),
            'expires_at' => $request->expires_at,
            'status' => 'published',
        ]);

        return redirect()->route('group_admin.notices.index', $group->id)->with('success', 'Notice published successfully.');
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
