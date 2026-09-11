<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperAdminGroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount(['members', 'events'])->latest()->paginate(10);
        return view('super_admin.groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::where('status', 'active')
            ->where(function($q) {
                $q->whereIn('global_role', ['group_admin', 'super_admin'])
                  ->orWhereHas('groups', function($gq) {
                      $gq->where('group_user.membership_role', 'group_admin');
                  });
            })
            ->orderBy('first_name')
            ->get();
        return view('super_admin.groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'required|string',
            'purpose' => 'nullable|string',
            'why_join' => 'nullable|string',
            'community_type' => 'required|in:free,paid',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'admin_id' => 'nullable|exists:users,id',
        ]);

        $slug = Str::slug($request->name);

        $group = Group::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'purpose' => $request->purpose,
            'why_join' => $request->why_join,
            'who_can_join' => ['UK Residents', 'Community Professionals', 'Families & Businesses'],
            'benefits' => ['Community Directory Access', 'Professional Networking', 'Exclusive Events', 'Business Services Exchange'],
            'community_type' => $request->community_type,
            'city' => $request->city ?? 'London',
            'country' => $request->country ?? 'United Kingdom',
            'status' => 'active',
        ]);

        if ($request->community_type === 'paid' && $request->filled('price')) {
            GroupSubscription::create([
                'group_id' => $group->id,
                'name' => 'Annual Membership',
                'price' => $request->price ?? 20.00,
                'currency' => 'GBP',
                'duration' => 1,
                'duration_type' => 'yearly',
                'status' => 'active',
            ]);
        }

        if ($request->filled('admin_id')) {
            $group->members()->attach($request->admin_id, [
                'membership_role' => 'group_admin',
                'status' => 'active',
                'joined_at' => now(),
            ]);
        }

        return redirect()->route('super_admin.groups.index')->with('success', "Community '{$group->name}' created successfully.");
    }

    public function edit(Group $group)
    {
        $users = User::where('status', 'active')
            ->where(function($q) {
                $q->whereIn('global_role', ['group_admin', 'super_admin'])
                  ->orWhereHas('groups', function($gq) {
                      $gq->where('group_user.membership_role', 'group_admin');
                  });
            })
            ->orderBy('first_name')
            ->get();
        $groupAdmins = $group->groupAdmins->pluck('id')->toArray();
        $subscription = $group->activeSubscriptionPlan;

        return view('super_admin.groups.edit', compact('group', 'users', 'groupAdmins', 'subscription'));
    }

    public function members(Group $group, Request $request)
    {
        $query = $group->members();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(15);

        return view('super_admin.groups.members', compact('group', 'members'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'required|string',
            'community_type' => 'required|in:free,paid',
            'status' => 'required|in:draft,active,suspended,archived',
            'price' => 'nullable|numeric|min:0',
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'purpose' => $request->purpose,
            'why_join' => $request->why_join,
            'community_type' => $request->community_type,
            'city' => $request->city,
            'status' => $request->status,
        ]);

        if ($request->community_type === 'paid' && $request->filled('price')) {
            GroupSubscription::updateOrCreate(
                ['group_id' => $group->id],
                [
                    'name' => 'Annual Membership',
                    'price' => $request->price,
                    'currency' => 'GBP',
                    'duration' => 1,
                    'duration_type' => 'yearly',
                    'status' => 'active',
                ]
            );
        }

        if ($request->has('group_admins')) {
            // Update group admin roles
            foreach ($request->group_admins as $adminId) {
                if (!$group->members()->where('user_id', $adminId)->exists()) {
                    $group->members()->attach($adminId, ['membership_role' => 'group_admin', 'status' => 'active', 'joined_at' => now()]);
                } else {
                    $group->members()->updateExistingPivot($adminId, ['membership_role' => 'group_admin']);
                }
            }
        }

        return redirect()->route('super_admin.groups.index')->with('success', 'Community updated successfully.');
    }
}
