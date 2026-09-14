<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
use App\Mail\MemberApprovalMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GroupAdminMemberController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);
        $members = $group->members()->wherePivot('status', 'active')->paginate(15);
        $pendingCount = $group->members()->wherePivot('status', 'pending')->count();
        return view('group_admin.members.index', compact('group', 'members', 'pendingCount'));
    }

    public function pending(Group $group)
    {
        $this->authorizeAdmin($group);
        $pendingMembers = $group->members()->wherePivot('status', 'pending')->paginate(15);
        return view('group_admin.members.pending', compact('group', 'pendingMembers'));
    }

    public function approve(Group $group, User $user)
    {
        $this->authorizeAdmin($group);

        $group->members()->updateExistingPivot($user->id, [
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
        ]);

        if ($user->status === 'pending') {
            $user->update(['status' => 'active']);
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'approval',
            'title' => 'Membership Approved',
            'message' => "Your membership request for {$group->name} has been approved!",
            'link' => route('groups.show', $group->slug),
        ]);

        try {
            Mail::to($user->email)->send(new MemberApprovalMail($user, 'approved', $group->name));
        } catch (\Exception $e) {
            // Ignore email errors in local env
        }

        return back()->with('success', "Member {$user->name} has been approved successfully.");
    }

    public function reject(Group $group, User $user)
    {
        $this->authorizeAdmin($group);

        $group->members()->updateExistingPivot($user->id, [
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type' => 'approval',
            'title' => 'Membership Request Update',
            'message' => "Your membership request for {$group->name} was not approved.",
        ]);

        try {
            Mail::to($user->email)->send(new MemberApprovalMail($user, 'rejected', $group->name));
        } catch (\Exception $e) {
            // Ignore email errors in local env
        }

        return back()->with('success', "Member {$user->name}'s request has been rejected.");
    }

    public function create(Group $group)
    {
        $this->authorizeAdmin($group);
        return view('group_admin.members.create', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'profession' => $request->profession,
                'city' => $request->city,
                'password' => Hash::make(Str::random(12)),
                'global_role' => 'user',
                'status' => 'active',
            ]);
        }

        if (!$user->isMemberOf($group->id)) {
            $group->members()->attach($user->id, [
                'membership_role' => 'member',
                'status' => 'active',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'joined_at' => now(),
            ]);
        }

        return redirect()->route('group_admin.members.index', $group->id)->with('success', "Member {$user->name} added successfully to {$group->name}.");
    }

    public function exportCsv(Group $group)
    {
        $this->authorizeAdmin($group);
        $members = $group->members()->wherePivot('status', 'active')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=members_{$group->slug}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Profession', 'City', 'Joined At']);
            foreach ($members as $member) {
                fputcsv($file, [
                    $member->id,
                    $member->first_name,
                    $member->last_name,
                    $member->email,
                    $member->phone,
                    $member->profession,
                    $member->city,
                    $member->pivot->joined_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
