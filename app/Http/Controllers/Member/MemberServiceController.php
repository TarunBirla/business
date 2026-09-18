<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CommunityService;
use App\Models\ServiceRequest;
use App\Models\Group;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberServiceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'my_services');
        $search = $request->get('search');
        $category = $request->get('category');

        $groups = $user->groups;
        $userGroupIds = $groups->pluck('id')->toArray();

        // 1. My Services
        $myServicesQuery = CommunityService::where('user_id', $user->id)->with('group', 'requests.requester');
        if ($search) {
            $myServicesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($category) {
            $myServicesQuery->where('category', $category);
        }
        $myServices = $myServicesQuery->latest()->get();

        // 2. Others Services (scoped strictly to communities the member belongs to)
        $othersServicesQuery = CommunityService::where('user_id', '!=', $user->id)
            ->where('status', 'active')
            ->where(function($q) use ($userGroupIds) {
                if (empty($userGroupIds)) {
                    $q->whereNull('group_id');
                } else {
                    $q->whereIn('group_id', $userGroupIds)
                      ->orWhere(function($sub) use ($userGroupIds) {
                          $sub->whereNull('group_id')
                              ->whereHas('user.groups', function($gq) use ($userGroupIds) {
                                  $gq->whereIn('groups.id', $userGroupIds);
                              });
                      });
                }
            })
            ->with(['user', 'group', 'requests' => fn($q) => $q->where('requester_id', $user->id)]);

        if ($search) {
            $othersServicesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($category) {
            $othersServicesQuery->where('category', $category);
        }
        $othersServices = $othersServicesQuery->latest()->get();

        // 3. Requests Received & Sent
        $receivedRequests = ServiceRequest::where('provider_id', $user->id)
            ->with(['service', 'requester'])
            ->latest()
            ->get();

        $sentRequests = ServiceRequest::where('requester_id', $user->id)
            ->with(['service', 'provider'])
            ->latest()
            ->get();

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

        return view('member.services.index', compact(
            'tab',
            'myServices',
            'othersServices',
            'receivedRequests',
            'sentRequests',
            'groups',
            'categories',
            'search',
            'category'
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'website_url' => 'nullable|url|max:255',
            'video_url' => 'nullable|url|max:255',
            'price_type' => 'nullable|in:fixed,hourly,free,quote',
            'price' => 'nullable|numeric|min:0',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        CommunityService::create([
            'user_id' => $user->id,
            'group_id' => $request->group_id ?: null,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'image' => $imagePath,
            'website_url' => $request->website_url,
            'video_url' => $request->video_url,
            'price_type' => $request->price_type ?? 'quote',
            'price' => $request->price ?? 0,
            'status' => 'active',
        ]);

        return redirect()->route('member.services.index', ['tab' => 'my_services'])
            ->with('success', 'Your service has been published successfully!');
    }

    public function update(Request $request, CommunityService $service)
    {
        $user = auth()->user();

        if ($service->user_id !== $user->id && !$user->isSuperAdmin() && !$user->isGroupAdmin($service->group_id)) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'website_url' => 'nullable|url|max:255',
            'video_url' => 'nullable|url|max:255',
            'price_type' => 'nullable|in:fixed,hourly,free,quote',
            'price' => 'nullable|numeric|min:0',
            'group_id' => 'nullable|exists:groups,id',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $service->image;
        if ($request->hasFile('image')) {
            if ($service->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image);
            }
            $imagePath = $request->file('image')->store('services', 'public');
        }

        $service->update([
            'group_id' => $request->group_id ?: null,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'image' => $imagePath,
            'website_url' => $request->website_url,
            'video_url' => $request->video_url,
            'price_type' => $request->price_type ?? $service->price_type,
            'price' => $request->price ?? $service->price,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Service updated successfully.');
    }

    public function destroy(CommunityService $service)
    {
        $user = auth()->user();

        if ($service->user_id !== $user->id && !$user->isSuperAdmin() && !$user->isGroupAdmin($service->group_id)) {
            abort(403, 'Unauthorized.');
        }

        if ($service->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return back()->with('success', 'Service removed successfully.');
    }

    public function sendRequest(Request $request, CommunityService $service)
    {
        $user = auth()->user();

        if ($service->user_id === $user->id) {
            return back()->with('error', 'You cannot request your own service.');
        }

        $existing = ServiceRequest::where('service_id', $service->id)
            ->where('requester_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already sent a request for this service.');
        }

        $req = ServiceRequest::create([
            'service_id' => $service->id,
            'requester_id' => $user->id,
            'provider_id' => $service->user_id,
            'status' => 'pending',
            'message' => $request->message ?? 'Hi, I am interested in your service: ' . $service->title,
        ]);

        // Send notification to service owner
        Notification::create([
            'user_id' => $service->user_id,
            'type' => 'service_request',
            'title' => 'New Service Request',
            'message' => "{$user->name} requested your service: {$service->title}",
            'link' => route('member.services.index', ['tab' => 'requests']),
        ]);

        return redirect()->route('member.services.index', ['tab' => 'others_services'])
            ->with('success', 'Service request sent successfully! You will be notified when accepted.');
    }

    public function approveRequest(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        $isProvider = (int)$serviceRequest->provider_id === (int)$user->id;
        $isServiceOwner = $serviceRequest->service && (int)$serviceRequest->service->user_id === (int)$user->id;
        $isRequester = (int)$serviceRequest->requester_id === (int)$user->id;
        $isGroupAdmin = $user->isGroupAdmin() && ($serviceRequest->service && $serviceRequest->service->group_id ? $user->isGroupAdmin($serviceRequest->service->group_id) : true);
        $isSuperAdmin = $user->isSuperAdmin();

        if (!$isProvider && !$isServiceOwner && !$isRequester && !$isGroupAdmin && !$isSuperAdmin) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        $serviceRequest->update(['status' => 'accepted']);

        // Notify requester
        Notification::create([
            'user_id' => $serviceRequest->requester_id,
            'type' => 'service_request_accepted',
            'title' => 'Service Request Accepted!',
            'message' => "{$user->name} accepted your request for '" . ($serviceRequest->service->title ?? 'Service') . "'. You can now chat directly!",
            'link' => route('member.chat', [
                'groupId' => $serviceRequest->service->group_id ?? 1,
                'receiverId' => $serviceRequest->provider_id
            ]),
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Request accepted! You can now start chatting.']);
        }

        return back()->with('success', 'Request accepted! You can now start chatting.');
    }

    public function rejectRequest(ServiceRequest $serviceRequest)
    {
        $user = auth()->user();

        $isProvider = (int)$serviceRequest->provider_id === (int)$user->id;
        $isServiceOwner = $serviceRequest->service && (int)$serviceRequest->service->user_id === (int)$user->id;
        $isRequester = (int)$serviceRequest->requester_id === (int)$user->id;
        $isGroupAdmin = $user->isGroupAdmin() && ($serviceRequest->service && $serviceRequest->service->group_id ? $user->isGroupAdmin($serviceRequest->service->group_id) : true);
        $isSuperAdmin = $user->isSuperAdmin();

        if (!$isProvider && !$isServiceOwner && !$isRequester && !$isGroupAdmin && !$isSuperAdmin) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        $serviceRequest->update(['status' => 'rejected']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Request rejected.']);
        }

        return back()->with('success', 'Request rejected.');
    }
}
