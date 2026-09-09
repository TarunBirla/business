<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Get group ID from route parameter if available
        $groupId = $request->route('group')?->id ?? $request->route('groupId') ?? $request->input('group_id');
        
        if ($groupId) {
            if (!$user->isGroupAdmin((int)$groupId)) {
                abort(403, 'You do not have administrative privileges for this community.');
            }
        } else {
            if (!$user->isGroupAdmin()) {
                abort(403, 'You do not have any assigned community to manage.');
            }
        }

        return $next($request);
    }
}
