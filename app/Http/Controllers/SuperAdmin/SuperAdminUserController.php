<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'users');

        $query = User::with('groups');

        if ($tab === 'group_admins') {
            $query->where(function($q) {
                $q->where('global_role', 'group_admin')
                  ->orWhereHas('groups', function($gq) {
                      $gq->where('group_user.membership_role', 'group_admin');
                  });
            });
        } else {
            $query->where('global_role', 'user');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('super_admin.users.index', compact('users', 'tab'));
    }

    public function groupAdmins(Request $request)
    {
        $query = User::with('groups')->where(function($q) {
            $q->where('global_role', 'group_admin')
              ->orWhereHas('groups', function($gq) {
                  $gq->where('group_user.membership_role', 'group_admin');
              });
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $groupAdmins = $query->latest()->paginate(15)->withQueryString();

        return view('super_admin.group_admins.index', compact('groupAdmins'));
    }

    public function createGroupAdmin()
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        return view('super_admin.group_admins.create', compact('groups'));
    }

    public function storeGroupAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'profession' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:groups,id',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'global_role' => 'group_admin',
            'profession' => $request->profession,
            'company' => $request->company,
            'city' => $request->city,
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        if ($request->filled('group_ids')) {
            foreach ($request->group_ids as $groupId) {
                $user->groups()->attach($groupId, [
                    'membership_role' => 'group_admin',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }
        }

        return redirect()->route('super_admin.group_admins.index')->with('success', "Group Administrator '{$user->name}' created successfully.");
    }

    public function editGroupAdmin(User $user)
    {
        $groups = Group::where('status', 'active')->orderBy('name')->get();
        $assignedGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id')->toArray();
        return view('super_admin.group_admins.edit', compact('user', 'groups', 'assignedGroupIds'));
    }

    public function updateGroupAdmin(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$user->id}",
            'phone' => 'nullable|string|max:50',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:groups,id',
            'status' => 'required|in:active,suspended,pending,rejected',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        // Sync group admin assignments
        if ($request->has('group_ids')) {
            $user->groups()->wherePivot('membership_role', 'group_admin')->detach();
            foreach ($request->group_ids as $groupId) {
                $user->groups()->attach($groupId, [
                    'membership_role' => 'group_admin',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }
        }

        return redirect()->route('super_admin.group_admins.index')->with('success', "Group Administrator '{$user->name}' updated successfully.");
    }

    public function create()
    {
        return view('super_admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'global_role' => 'required|in:user,group_admin,super_admin',
            'profession' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'global_role' => $request->global_role,
            'profession' => $request->profession,
            'company' => $request->company,
            'city' => $request->city,
            'phone' => $request->phone,
            'status' => 'active',
        ]);

        return redirect()->route('super_admin.users.index')->with('success', 'New user created successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot suspend a Super Admin.');
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', "User {$user->name} status changed to {$newStatus}.");
    }
}
