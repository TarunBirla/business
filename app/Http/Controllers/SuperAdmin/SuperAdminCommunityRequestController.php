<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CommunityRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminCommunityRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = CommunityRequest::with('createdGroup')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('community_name', 'like', "%{$search}%")
                  ->orWhere('applicant_name', 'like', "%{$search}%")
                  ->orWhere('applicant_email', 'like', "%{$search}%");
            });
        }

        $communityRequests = $query->paginate(15)->withQueryString();

        $pendingCount = CommunityRequest::where('status', 'pending')->count();

        return view('super_admin.community_requests.index', compact('communityRequests', 'pendingCount', 'status'));
    }

    public function show(CommunityRequest $communityRequest)
    {
        return view('super_admin.community_requests.show', compact('communityRequest'));
    }

    public function approve(CommunityRequest $communityRequest)
    {
        if ($communityRequest->status === 'approved') {
            return back()->with('info', 'This community request has already been approved.');
        }

        try {
            // Generate unique slug
            $baseSlug = Str::slug($communityRequest->community_name);
            $slug = $baseSlug;
            $count = 1;
            while (Group::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $galleryImages = !empty($communityRequest->image) ? [$communityRequest->image] : [];
            $thumbnailImage = !empty($communityRequest->image) ? $communityRequest->image : null;

            // 1. Create Community
            $group = Group::create([
                'name' => $communityRequest->community_name,
                'slug' => $slug,
                'logo' => $communityRequest->image,
                'thumbnail_image' => $thumbnailImage,
                'gallery_images' => $galleryImages,
                'description' => $communityRequest->description,
                'purpose' => $communityRequest->purpose,
                'why_join' => $communityRequest->why_join,
                'community_type' => $communityRequest->community_type,
                'visibility_type' => 'public',
                'city' => $communityRequest->city ?? 'London',
                'country' => $communityRequest->country ?? 'United Kingdom',
                'status' => 'active',
            ]);

            // 2. Find or Create User for Applicant
            $nameParts = explode(' ', trim($communityRequest->applicant_name), 2);
            $firstName = $nameParts[0] ?? 'Group';
            $lastName = $nameParts[1] ?? 'Admin';

            $user = User::where('email', $communityRequest->applicant_email)->first();
            $isNewUser = false;
            $temporaryPassword = '';

            if (!$user) {
                $isNewUser = true;
                $temporaryPassword = 'Bz' . rand(100000, 999999);
                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $communityRequest->applicant_email,
                    'phone' => $communityRequest->applicant_phone,
                    'password' => Hash::make($temporaryPassword),
                    'global_role' => 'group_admin',
                    'status' => 'active',
                ]);
            } else {
                if ($user->global_role === 'user') {
                    $user->update(['global_role' => 'group_admin']);
                }
            }

            // 3. Attach User as Group Admin
            if (!$group->members()->where('users.id', $user->id)->exists()) {
                $group->members()->attach($user->id, [
                    'membership_role' => 'group_admin',
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            } else {
                $group->members()->updateExistingPivot($user->id, [
                    'membership_role' => 'group_admin',
                    'status' => 'active',
                ]);
            }

            // 4. Update Community Request Status
            $communityRequest->update([
                'status' => 'approved',
                'created_group_id' => $group->id,
            ]);

            // 5. Create In-App Notification
            try {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'type' => 'approval',
                    'title' => 'Community Creation Approved',
                    'message' => "Congratulations! Your request for {$group->name} has been approved by Super Admin.",
                    'link' => route('group_admin.dashboard', ['group_id' => $group->id]),
                ]);
            } catch (\Throwable $e) {
                // Ignore notification failure
            }

            // 6. Send Official Approval Email to Applicant
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(
                    new \App\Mail\CommunityRequestApprovedMail($user, $group, $temporaryPassword, $isNewUser)
                );
            } catch (\Throwable $mailException) {
                // Ignore email error if mail server is not configured in local environment
            }

            return redirect()->route('super_admin.community_requests.index')
                ->with('success', "Community Request for '{$group->name}' approved! Approval email sent to {$user->email}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, CommunityRequest $communityRequest)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $communityRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', "Community Request for '{$communityRequest->community_name}' has been rejected.");
    }
}
