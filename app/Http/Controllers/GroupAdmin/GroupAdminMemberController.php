<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupAdminMemberController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);
        $members = $group->members()->paginate(15);
        return view('group_admin.members.index', compact('group', 'members'));
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
                'joined_at' => now(),
            ]);
        }

        return redirect()->route('group_admin.members.index', $group->id)->with('success', "Member {$user->name} added successfully to {$group->name}.");
    }

    public function exportCsv(Group $group)
    {
        $this->authorizeAdmin($group);
        $members = $group->members()->get();

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
