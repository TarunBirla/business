<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    /**
     * Get list of active public communities for Flutter app.
     */
    public function getCommunities(Request $request)
    {
        $query = Group::where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('visibility_type')->orWhere('visibility_type', 'public');
            })
            ->withCount(['members', 'events'])
            ->orderBy('name', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $groups = $query->get()->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'slug' => $group->slug,
                'logo_url' => $group->logo_url,
                'thumbnail_image_url' => $group->thumbnail_image_url,
                'description' => $group->description,
                'city' => $group->city,
                'country' => $group->country,
                'community_type' => $group->community_type,
                'members_count' => $group->members_count ?? 0,
                'events_count' => $group->events_count ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $groups->count(),
            'data' => $groups,
        ]);
    }

    /**
     * Register a new member from Flutter app with community selection.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'profession' => ['nullable', 'string', 'max:255'],
            'group_id' => ['required', 'exists:groups,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'country' => $request->country ?? 'United Kingdom',
            'city' => $request->city,
            'profession' => $request->profession,
            'privacy_settings' => [
                'show_email' => false,
                'show_phone' => false,
                'allow_connections' => true,
                'allow_contact_requests' => true,
            ],
            'global_role' => 'user',
            'status' => 'pending',
        ]);

        // Attach selected community
        $group = Group::find($request->group_id);
        if ($group) {
            $user->groups()->attach($group->id, [
                'membership_role' => 'member',
                'status' => 'pending',
                'joined_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your account has been registered successfully! Your account is waiting for Community Admin approval.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'community' => [
                    'id' => $group->id,
                    'name' => $group->name,
                ],
            ],
        ], 201);
    }

    /**
     * Flutter native login API with One-Time Handshake Token generation for WebView.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide email and password.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        // Account status checks (Super Admin bypasses)
        if (!$user->isSuperAdmin()) {
            if ($user->status === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been deactivated by the administrator.',
                ], 403);
            }

            if ($user->status === 'suspended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been suspended by the administrator.',
                ], 403);
            }

            if ($user->status === 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is waiting for Community Admin approval.',
                ], 403);
            }

            if ($user->status === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account application has been rejected by the admin.',
                ], 403);
            }
        }

        // 1. Generate Single-Use SSO Token for WebView (Valid for 2 minutes)
        $ssoToken = Str::random(64);
        Cache::put('webview_sso_' . $ssoToken, $user->id, now()->addMinutes(2));

        // 2. Generate target WebView URL
        $webviewUrl = url('/auth/sso?token=' . $ssoToken);

        // 3. Optional Sanctum token if native Flutter calls are also made
        $apiToken = null;
        if (method_exists($user, 'createToken')) {
            try {
                $apiToken = $user->createToken('flutter_app')->plainTextToken;
            } catch (\Throwable $e) {
                // Ignore if personal access client is not configured
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'global_role' => $user->global_role,
                'profile_photo_url' => $user->profile_photo_url,
            ],
            'api_token' => $apiToken,
            'webview_url' => $webviewUrl,
        ]);
    }
}
