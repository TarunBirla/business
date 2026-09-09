<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('groups');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('global_role', $request->role);
        }

        $users = $query->latest()->paginate(15);

        return view('super_admin.users.index', compact('users'));
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
