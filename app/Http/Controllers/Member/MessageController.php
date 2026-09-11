<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request, $groupId = null, $receiverId = null)
    {
        $authUser = auth()->user();
        $myGroups = $authUser->groups;

        if ($myGroups->isEmpty()) {
            return view('member.chat.no_groups');
        }

        // Select target group
        $activeGroup = $groupId ? Group::find($groupId) : $myGroups->first();
        if (!$activeGroup || !$authUser->isMemberOf($activeGroup->id)) {
            $activeGroup = $myGroups->first();
        }

        // Get all contacts in active group (excluding auth user)
        $contacts = $activeGroup->members()
            ->where('users.id', '!=', $authUser->id)
            ->where('group_user.status', 'active')
            ->get();

        // Select target chat contact
        $activeContact = null;
        if ($receiverId) {
            $activeContact = $contacts->firstWhere('id', $receiverId);
        }
        if (!$activeContact && $contacts->isNotEmpty()) {
            $activeContact = $contacts->first();
        }

        // Fetch conversation history & mark read
        $messages = collect();
        if ($activeContact) {
            // Mark unread messages from active contact as read immediately upon opening thread
            Message::where('group_id', $activeGroup->id)
                ->where('sender_id', $activeContact->id)
                ->where('receiver_id', $authUser->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $messages = Message::where('group_id', $activeGroup->id)
                ->where(function ($q) use ($authUser, $activeContact) {
                    $q->where(function ($q2) use ($authUser, $activeContact) {
                        $q2->where('sender_id', $authUser->id)->where('receiver_id', $activeContact->id);
                    })->orWhere(function ($q2) use ($authUser, $activeContact) {
                        $q2->where('sender_id', $activeContact->id)->where('receiver_id', $authUser->id);
                    });
                })
                ->with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('member.chat.index', compact(
            'myGroups',
            'activeGroup',
            'contacts',
            'activeContact',
            'messages'
        ));
    }

    public function fetchMessages(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'receiver_id' => 'required|exists:users,id',
            'last_id' => 'nullable|integer',
        ]);

        $authUser = auth()->user();
        $groupId = (int)$request->group_id;
        $receiverId = (int)$request->receiver_id;
        $lastId = (int)($request->input('last_id', 0));

        if (!$authUser->isMemberOf($groupId)) {
            return response()->json(['error' => 'Not a group member'], 403);
        }

        // Mark incoming unread messages as read
        Message::where('group_id', $groupId)
            ->where('sender_id', $receiverId)
            ->where('receiver_id', $authUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Query new messages after last_id
        $query = Message::where('group_id', $groupId)
            ->where(function ($q) use ($authUser, $receiverId) {
                $q->where(function ($q2) use ($authUser, $receiverId) {
                    $q2->where('sender_id', $authUser->id)->where('receiver_id', $receiverId);
                })->orWhere(function ($q2) use ($authUser, $receiverId) {
                    $q2->where('sender_id', $receiverId)->where('receiver_id', $authUser->id);
                });
            });

        if ($lastId > 0) {
            $query->where('id', '>', $lastId);
        }

        $messages = $query->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($authUser) {
                $isMe = (int)$msg->sender_id === (int)$authUser->id;
                $sender = $msg->sender;
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,
                    'message' => $msg->message,
                    'is_me' => $isMe,
                    'sender_name' => $sender ? $sender->first_name : 'Member',
                    'sender_initials' => $sender ? strtoupper(substr($sender->first_name, 0, 1) . substr($sender->last_name, 0, 1)) : 'M',
                    'is_read' => (bool)$msg->is_read,
                    'time' => $msg->created_at->format('h:i A'),
                ];
            });

        // Get updated unread counts for all contacts in this group
        $unreadCounts = Message::where('group_id', $groupId)
            ->where('receiver_id', $authUser->id)
            ->where('is_read', false)
            ->selectRaw('sender_id, count(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'unread_counts' => $unreadCounts,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $sender = auth()->user();
        $groupId = (int)$request->group_id;
        $receiverId = (int)$request->receiver_id;

        if (!$sender->isMemberOf($groupId)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'You are not a member of this community.'], 403);
            }
            return back()->with('error', 'You are not a member of this community.');
        }

        $receiver = User::find($receiverId);
        if (!$receiver || !$receiver->isMemberOf($groupId)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'The contact is not a member of this community.'], 404);
            }
            return back()->with('error', 'The contact is not a member of this community.');
        }

        $msg = Message::create([
            'group_id' => $groupId,
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,
                    'message' => $msg->message,
                    'is_me' => true,
                    'sender_name' => $sender->first_name,
                    'sender_initials' => strtoupper(substr($sender->first_name, 0, 1) . substr($sender->last_name, 0, 1)),
                    'is_read' => false,
                    'time' => $msg->created_at->format('h:i A'),
                ]
            ]);
        }

        return redirect()->route('member.chat', ['groupId' => $groupId, 'receiverId' => $receiverId]);
    }
}
