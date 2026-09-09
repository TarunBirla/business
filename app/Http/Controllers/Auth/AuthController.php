<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->isSuperAdmin()) {
                return redirect()->intended(route('super_admin.dashboard'));
            }

            // Check if user was trying to join a group stored in session
            $groupId = session('join_group_id') ?? session('referral_group_id');
            if ($groupId) {
                $group = Group::find($groupId);
                if ($group) {
                    session()->forget(['join_group_id', 'referral_group_id']);
                    if (!$user->isMemberOf($group->id)) {
                        if ($group->community_type === 'paid') {
                            return redirect()->route('join.checkout', $group->id);
                        } else {
                            $user->groups()->attach($group->id, [
                                'membership_role' => 'member',
                                'status' => 'active',
                                'joined_at' => now(),
                            ]);
                            return redirect()->route('member.dashboard')->with('success', "Welcome back! You have joined {$group->name}.");
                        }
                    }
                }
            }

            return redirect()->intended(route('member.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        $groupId = $request->get('group_id') ?? session('join_group_id') ?? session('referral_group_id');
        $group = $groupId ? Group::find($groupId) : null;
        return view('auth.register', compact('group'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'profession' => ['nullable', 'string', 'max:255'],
            'group_id' => ['nullable', 'exists:groups,id'],
            'terms' => ['accepted'],
        ]);

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
            'status' => 'active',
        ]);

        Auth::login($user);

        // Handle joining group if specified or stored in session
        $groupId = $request->group_id ?? session('join_group_id') ?? session('referral_group_id');
        if ($groupId) {
            $group = Group::find($groupId);
            if ($group) {
                session()->forget(['join_group_id', 'referral_group_id']);
                if ($group->community_type === 'paid') {
                    // Redirect to payment review page
                    return redirect()->route('join.checkout', $group->id);
                } else {
                    // Free community join
                    $user->groups()->attach($group->id, [
                        'membership_role' => 'member',
                        'status' => 'active',
                        'joined_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('member.dashboard')->with('success', 'Welcome to the platform! Your account has been created.');
    }

    public function showCheckout(Group $group)
    {
        $plan = $group->activeSubscriptionPlan;
        return view('auth.checkout', compact('group', 'plan'));
    }

    public function processCheckout(Request $request, Group $group)
    {
        $user = Auth::user();
        $plan = $group->activeSubscriptionPlan;

        // Process subscription payment via StripePaymentService
        StripePaymentService::processGroupSubscription($user, $group, $plan);

        return redirect()->route('member.dashboard')->with('success', "Payment successful! You are now an active member of {$group->name}.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
