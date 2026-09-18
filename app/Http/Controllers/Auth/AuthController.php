<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Mail\PasswordResetMail;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
            $user = Auth::user();

            if ($user->status === 'inactive' && !$user->isSuperAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated by the administrator. You cannot log in.',
                ])->onlyInput('email');
            }

            if ($user->status === 'suspended' && !$user->isSuperAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account has been suspended by the administrator.',
                ])->onlyInput('email');
            }

            if ($user->status === 'pending' && !$user->isSuperAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account is waiting for Community Admin approval.',
                ])->onlyInput('email');
            }

            if ($user->status === 'rejected' && !$user->isSuperAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Your account application has been rejected by the admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($request->wantsJson() || $request->ajax()) {
                $targetUrl = $request->input('redirect_to');
                if (!$targetUrl) {
                    if ($user->isSuperAdmin()) $targetUrl = route('super_admin.dashboard');
                    elseif ($user->isGroupAdmin()) $targetUrl = route('group_admin.dashboard');
                    else $targetUrl = route('member.dashboard');
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully!',
                    'redirect' => $targetUrl,
                ]);
            }
            
            if ($user->isSuperAdmin()) {
                return redirect()->intended(route('super_admin.dashboard'));
            }

            if ($user->isGroupAdmin()) {
                return redirect()->intended(route('group_admin.dashboard'));
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
                                'status' => 'pending',
                                'joined_at' => now(),
                            ]);
                            return redirect()->route('member.dashboard')->with('success', "Your request to join {$group->name} has been submitted for admin approval.");
                        }
                    }
                }
            }

            if ($request->filled('redirect_to')) {
                return redirect($request->input('redirect_to'))->with('success', 'Logged in successfully!');
            }

            return redirect()->intended(route('member.dashboard'));
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records or account is not active.',
            ], 422);
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

    public function generateCaptcha()
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }

        session(['captcha_code' => $code]);

        $width = 170;
        $height = 48;

        $lines = '';
        for ($i = 0; $i < 5; $i++) {
            $x1 = rand(5, 40);
            $y1 = rand(5, $height - 5);
            $x2 = rand($width - 40, $width - 5);
            $y2 = rand(5, $height - 5);
            $color = ['#0284c7', '#0284c7', '#64748b', '#94a3b8', '#0369a1'][rand(0, 4)];
            $lines .= "<line x1='{$x1}' y1='{$y1}' x2='{$x2}' y2='{$y2}' stroke='{$color}' stroke-width='1.5' stroke-dasharray='4,2' opacity='0.5'/>";
        }

        $circles = '';
        for ($i = 0; $i < 12; $i++) {
            $cx = rand(5, $width - 5);
            $cy = rand(5, $height - 5);
            $r = rand(1, 3);
            $circles .= "<circle cx='{$cx}' cy='{$cy}' r='{$r}' fill='#cbd5e1' opacity='0.6'/>";
        }

        $textNodes = '';
        $charArray = str_split($code);
        $spacing = ($width - 24) / count($charArray);

        foreach ($charArray as $index => $char) {
            $x = 14 + ($index * $spacing);
            $y = rand(31, 36);
            $rotate = rand(-18, 18);
            $fontSize = rand(22, 26);
            $fill = ['#0f172a', '#0369a1', '#1e293b', '#0284c7', '#334155'][$index % 5];
            $textNodes .= "<text x='{$x}' y='{$y}' fill='{$fill}' font-size='{$fontSize}' font-family='Courier, monospace' font-weight='bold' transform='rotate({$rotate}, {$x}, {$y})'>{$char}</text>";
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect width="100%" height="100%" fill="#f1f5f9" rx="10" stroke="#cbd5e1" stroke-width="1.5"/>
    {$lines}
    {$circles}
    {$textNodes}
</svg>
SVG;

        return response($svg)->header('Content-Type', 'image/svg+xml')->header('Cache-Control', 'no-cache, no-store, must-revalidate');
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
            'captcha' => ['required', 'string'],
            'terms' => ['accepted'],
        ]);

        $expectedCaptcha = session('captcha_code');
        if (!$expectedCaptcha || strtolower(trim($request->captcha)) !== strtolower(trim($expectedCaptcha))) {
            return back()->withErrors(['captcha' => 'Incorrect CAPTCHA code. Please try again.'])->withInput();
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

        // Handle joining group if specified or stored in session
        $groupId = $request->group_id ?? session('join_group_id') ?? session('referral_group_id');
        if ($groupId) {
            $group = Group::find($groupId);
            if ($group) {
                session()->forget(['join_group_id', 'referral_group_id']);
                $user->groups()->attach($group->id, [
                    'membership_role' => 'member',
                    'status' => 'pending',
                    'joined_at' => now(),
                ]);
            }
        }

        return redirect()->route('login')->with('success', 'Your account has been registered! Your account is waiting for Community Admin approval.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        try {
            Mail::to($request->email)->send(new PasswordResetMail($token, $request->email));
        } catch (\Exception $e) {
            // Ignore mail transport errors in local env
        }

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetPassword($token, Request $request)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please log in with your new password.');
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
