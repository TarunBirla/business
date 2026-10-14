<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SsoController extends Controller
{
    /**
     * Authenticate user into web session from Flutter WebView using one-time SSO token.
     */
    public function handle(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Invalid login request. Token missing.');
        }

        $cacheKey = 'webview_sso_' . $token;
        $userId = Cache::get($cacheKey);

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Login session expired or invalid token. Please log in again.');
        }

        // Single-use token: Immediately delete from cache
        Cache::forget($cacheKey);

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Log the user into the standard web session
        Auth::loginUsingId($user->id, true);
        $request->session()->regenerate();

        // Redirect to intended dashboard based on role
        if ($user->isSuperAdmin()) {
            return redirect()->route('super_admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        if ($user->isGroupAdmin()) {
            return redirect()->route('group_admin.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return redirect()->route('member.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
    }
}
