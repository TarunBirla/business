<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\CommunityService;
use App\Models\ServiceRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupAdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'community_services');
        $search = $request->get('search');
        $category = $request->get('category');

        if ($user->isSuperAdmin()) {
            $adminGroups = Group::orderBy('name')->get();
            $adminGroupIds = $adminGroups->pluck('id')->toArray();
        } else {
            $adminGroupIds = $user->groups()->wherePivot('membership_role', 'group_admin')->pluck('groups.id')->toArray();
            $adminGroups = Group::whereIn('id', $adminGroupIds)->orderBy('name')->get();
        }

        $activeGroupId = $request->has('group_id') && $request->get('group_id') !== '' ? $request->get('group_id') : 'all';

        // Community services in admin's groups or created by members in admin's groups
        $communityServicesQuery = CommunityService::where('user_id', '!=', $user->id)
            ->where('status', 'active')
            ->with(['user', 'group', 'requests' => fn($q) => $q->where('requester_id', $user->id)]);

        if ($activeGroupId !== 'all') {
            $communityServicesQuery->where(function($q) use ($activeGroupId) {
                $q->where('group_id', $activeGroupId)
                  ->orWhere(function($sub) use ($activeGroupId) {
                      $sub->whereNull('group_id')
                          ->whereHas('user.groups', function($gq) use ($activeGroupId) {
                              $gq->where('groups.id', $activeGroupId);
                          });
                  });
            });
        } else {
            $communityServicesQuery->where(function($q) use ($adminGroupIds) {
                $q->whereIn('group_id', $adminGroupIds)
                  ->orWhere(function($sub) use ($adminGroupIds) {
                      $sub->whereNull('group_id')
                          ->whereHas('user.groups', function($gq) use ($adminGroupIds) {
                              $gq->whereIn('groups.id', $adminGroupIds);
                          });
                  });
            });
        }

        if ($search) {
            $communityServicesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($category) {
            $communityServicesQuery->where('category', $category);
        }

        $communityServices = $communityServicesQuery->latest()->get();

        // Admin's own services
        $myServices = CommunityService::where('user_id', $user->id)->with('group', 'requests')->latest()->get();

        // Service requests
        $receivedRequests = ServiceRequest::where('provider_id', $user->id)->with(['service', 'requester'])->latest()->get();
        $sentRequests = ServiceRequest::where('requester_id', $user->id)->with(['service', 'provider'])->latest()->get();

        $categories = [
            'IT & Software',
            'Legal & Accounting',
            'Marketing & Design',
            'Healthcare & Wellness',
            'Real Estate & Housing',
            'Business Coaching',
            'Event & Catering',
            'Trade & Crafts',
            'General Services'
        ];

        return view('group_admin.services.index', compact(
            'tab',
            'communityServices',
            'myServices',
            'receivedRequests',
            'sentRequests',
            'adminGroups',
            'activeGroupId',
            'categories',
            'search',
            'category'
        ));
    }
}
