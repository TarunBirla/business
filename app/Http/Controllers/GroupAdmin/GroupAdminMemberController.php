<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
use App\Mail\MemberApprovalMail;
use App\Mail\MemberCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GroupAdminMemberController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);
        $user = auth()->user();
        $assignedGroups = $user->isSuperAdmin() ? Group::all() : $user->groups()->wherePivot('membership_role', 'group_admin')->get();
        $members = $group->members()->wherePivotIn('group_user.status', ['active', 'inactive', 'suspended'])->where('users.id', '!=', auth()->id())->paginate(15);
        $pendingCount = $group->members()->wherePivot('status', 'pending')->count();
        return view('group_admin.members.index', compact('group', 'members', 'pendingCount', 'assignedGroups'));
    }

    public function pending(Group $group)
    {
        $this->authorizeAdmin($group);
        $user = auth()->user();
        $assignedGroups = $user->isSuperAdmin() ? Group::all() : $user->groups()->wherePivot('membership_role', 'group_admin')->get();
        $pendingMembers = $group->members()->wherePivot('status', 'pending')->paginate(15);
        return view('group_admin.members.pending', compact('group', 'pendingMembers', 'assignedGroups'));
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
            'password' => 'required|string|min:6',
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
                'password' => Hash::make($request->password),
                'global_role' => 'user',
                'status' => 'active',
            ]);
        } else {
            $user->update([
                'password' => Hash::make($request->password),
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
        } else {
            $group->members()->updateExistingPivot($user->id, [
                'status' => 'active',
            ]);
        }

        try {
            Mail::to($user->email)->send(new MemberCreatedMail($user, $request->password, $group->name));
        } catch (\Exception $e) {
            // Ignore mail failures in dev environments
        }

        return redirect()->route('group_admin.members.index', $group->id)->with('success', "Member {$user->name} added successfully and credentials email sent.");
    }

    public function updateStatus(Request $request, Group $group, User $user)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $status = $request->status;

        $group->members()->updateExistingPivot($user->id, [
            'status' => $status,
        ]);

        $user->update([
            'status' => $status,
        ]);

        return back()->with('success', "Member {$user->name}'s status updated to " . strtoupper($status) . ".");
    }

    public function exportCsv(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $selectedIds = $request->input('selected_ids');
        if (is_string($selectedIds)) {
            $selectedIds = array_filter(explode(',', $selectedIds));
        }

        $query = $group->members()->wherePivotIn('group_user.status', ['active', 'inactive', 'suspended'])->where('users.id', '!=', auth()->id());

        if (!empty($selectedIds) && is_array($selectedIds)) {
            $query->whereIn('users.id', $selectedIds);
        }

        $members = $query->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=members_{$group->slug}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Profession', 'City', 'Role', 'Status', 'Joined At']);
            foreach ($members as $member) {
                fputcsv($file, [
                    $member->id,
                    $member->first_name,
                    $member->last_name,
                    $member->email,
                    $member->phone ?? '',
                    $member->profession ?? '',
                    $member->city ?? '',
                    str_replace('_', ' ', $member->pivot->membership_role ?? 'member'),
                    $member->pivot->status ?? 'active',
                    $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('Y-m-d H:i') : '',
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
