<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Service;
use App\Models\Connection;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class MemberDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $myGroupIds = $authUser->groups->pluck('id');

        $query = User::whereHas('groups', function($q) use ($myGroupIds) {
            $q->whereIn('groups.id', $myGroupIds)
              ->where('group_user.status', 'active');
        })->where('users.id', '!=', $authUser->id)
          ->where('users.status', 'active')
          ->where('users.global_role', '!=', 'super_admin');

        if ($request->filled('group_id')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('groups.id', $request->group_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('profession', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('profession')) {
            $query->where('profession', 'like', "%{$request->profession}%");
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('service_offered')) {
            $query->whereHas('servicesOffered', function($q) use ($request) {
                $q->where('services.id', $request->service_offered);
            });
        }

        if ($request->filled('service_needed')) {
            $query->whereHas('servicesNeeded', function($q) use ($request) {
                $q->where('services.id', $request->service_needed);
            });
        }

        $members = $query->with(['servicesOffered', 'servicesNeeded', 'groups'])->paginate(12);
        $myGroups = $authUser->groups;
        $allServices = Service::where('status', 'active')->orderBy('name')->get();

        return view('member.directory.index', compact('members', 'myGroups', 'allServices'));
    }

    public function show(User $user)
    {
        $authUser = auth()->user();
        
        // Find shared groups between auth user and target member
        $sharedGroupIds = array_intersect(
            $authUser->groups->pluck('id')->toArray(),
            $user->groups->pluck('id')->toArray()
        );

        if (empty($sharedGroupIds) && !$authUser->isSuperAdmin()) {
            $targetUserGroupIds = $user->groups->pluck('id')->toArray();
            $authUserAdminGroupIds = $authUser->isGroupAdmin() ? $authUser->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id')->toArray() : [];
            $hasGroupAdminRelation = !empty(array_intersect($targetUserGroupIds, $authUserAdminGroupIds));

            if (!$hasGroupAdminRelation) {
                abort(403, 'You must share a community to view this profile.');
            }
        }

        $activeGroupId = !empty($sharedGroupIds) ? reset($sharedGroupIds) : ($user->groups->first()?->id ?? 1);

        // Check connection status
        $connection = Connection::where(function($q) use ($authUser, $user) {
            $q->where('sender_id', $authUser->id)->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($authUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $authUser->id);
        })->first();

        // Check contact request status
        $contactRequest = ContactRequest::where('requester_id', $authUser->id)
            ->where('receiver_id', $user->id)
            ->first();

        $isConnected = $connection && $connection->status === 'accepted';
        $isContactAccepted = $contactRequest && $contactRequest->status === 'accepted';
        $isOwnProfile = $authUser->id === $user->id;
        $isSuperAdmin = $authUser->isSuperAdmin();

        // Email and phone visible ONLY IF connected (accepted) or own profile or super admin
        $canSeeEmail = $isOwnProfile || $isSuperAdmin || $isConnected || $isContactAccepted;
        $canSeePhone = $isOwnProfile || $isSuperAdmin || $isConnected || $isContactAccepted;

        return view('member.directory.show', compact('user', 'connection', 'contactRequest', 'canSeeEmail', 'canSeePhone', 'activeGroupId'));
    }
}
