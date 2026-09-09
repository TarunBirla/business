<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\ContactRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myConnections = Connection::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['sender', 'receiver', 'group'])
            ->get();

        $sentRequests = Connection::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->with(['receiver', 'group'])
            ->get();

        $receivedRequests = Connection::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with(['sender', 'group'])
            ->get();

        // Suggested Connections based on shared group, profession, or city
        $myGroupIds = $user->groups->pluck('id');
        $connectedUserIds = $myConnections->pluck('sender_id')->merge($myConnections->pluck('receiver_id'))->unique()->toArray();
        $connectedUserIds[] = $user->id;

        $suggestedConnections = User::whereHas('groups', function($q) use ($myGroupIds) {
                $q->whereIn('groups.id', $myGroupIds);
            })
            ->whereNotIn('id', $connectedUserIds)
            ->where(function($q) use ($user) {
                $q->where('city', $user->city)->orWhere('profession', $user->profession);
            })
            ->take(6)
            ->get();

        return view('member.connections.index', compact('myConnections', 'sentRequests', 'receivedRequests', 'suggestedConnections'));
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        $sender = auth()->user();
        $receiverId = $request->receiver_id;
        $groupId = $request->group_id;

        // Ensure both users are members of the group
        if (!$sender->isMemberOf($groupId)) {
            return back()->with('error', 'You must be a member of this community to send connection requests.');
        }

        Connection::updateOrCreate(
            [
                'sender_id' => $sender->id,
                'receiver_id' => $receiverId,
                'group_id' => $groupId,
            ],
            [
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Connection request sent successfully.');
    }

    public function acceptRequest(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $connection = Connection::find($id);

        if (!$connection) {
            return redirect()->route('member.connections')->with('error', 'Connection request not found.');
        }

        if ($connection->receiver_id !== $user->id && !$user->isSuperAdmin()) {
            return redirect()->route('member.connections')->with('error', 'You are not authorized to accept this connection request.');
        }

        $connection->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()->route('member.connections')->with('success', 'Connection request accepted successfully!');
    }

    public function rejectRequest(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $connection = Connection::find($id);

        if (!$connection) {
            return redirect()->route('member.connections')->with('error', 'Connection request not found.');
        }

        if ($connection->receiver_id !== $user->id && !$user->isSuperAdmin()) {
            return redirect()->route('member.connections')->with('error', 'You are not authorized to decline this connection request.');
        }

        $connection->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('member.connections')->with('success', 'Connection request declined.');
    }

    public function requestContactDetails(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        $requester = auth()->user();

        ContactRequest::updateOrCreate(
            [
                'requester_id' => $requester->id,
                'receiver_id' => $request->receiver_id,
                'group_id' => $request->group_id,
            ],
            [
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Contact details request sent to member.');
    }
}
