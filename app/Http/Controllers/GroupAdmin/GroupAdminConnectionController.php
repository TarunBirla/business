<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupAdminConnectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // My accepted connections (where Group Admin is sender or receiver)
        $myConnections = Connection::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['sender', 'receiver', 'group'])
            ->paginate(15);

        // Pending Connection Requests RECEIVED by this Group Admin
        $receivedRequests = Connection::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with(['sender', 'group'])
            ->get();

        // Pending Connection Requests SENT by this Group Admin
        $sentRequests = Connection::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->with(['receiver', 'group'])
            ->get();

        $myAdminGroups = $user->isSuperAdmin()
            ? Group::all()
            : $user->groups()->wherePivot('membership_role', 'group_admin')->get();

        return view('group_admin.connections.index', compact('myConnections', 'receivedRequests', 'sentRequests', 'myAdminGroups'));
    }

    public function accept(Connection $connection)
    {
        $user = auth()->user();

        if ((int)$connection->receiver_id !== (int)$user->id && !$user->isSuperAdmin()) {
            return back()->with('error', 'You are not authorized to accept this connection request.');
        }

        $connection->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return back()->with('success', 'Connection request accepted! Email & phone number are now visible.');
    }

    public function reject(Connection $connection)
    {
        $user = auth()->user();

        if ((int)$connection->receiver_id !== (int)$user->id && (int)$connection->sender_id !== (int)$user->id && !$user->isSuperAdmin()) {
            return back()->with('error', 'You are not authorized to decline this connection request.');
        }

        $connection->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Connection request declined.');
    }
}
