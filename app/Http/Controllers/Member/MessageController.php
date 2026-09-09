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

        // Fetch conversation history
        $messages = collect();
        if ($activeContact) {
            $messages = Message::where('group_id', $activeGroup->id)
                ->where(function ($q) use ($authUser, $activeContact) {
                    $q->where(function ($q2) use ($authUser, $activeContact) {
                        $q2->where('sender_id', $authUser->id)->where('receiver_id', $activeContact->id);
                    })->orWhere(function ($q2) use ($authUser, $activeContact) {
                        $q2->where('sender_id', $activeContact->id)->where('receiver_id', $authUser->id);
                    });
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark unread messages from active contact as read
            Message::where('group_id', $activeGroup->id)
                ->where('sender_id', $activeContact->id)
                ->where('receiver_id', $authUser->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('member.chat.index', compact(
            'myGroups',
            'activeGroup',
            'contacts',
            'activeContact',
            'messages'
        ));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $sender = auth()->user();
        $groupId = $request->group_id;
        $receiverId = $request->receiver_id;

        // Verify both users are members of the group
        if (!$sender->isMemberOf($groupId)) {
            return back()->with('error', 'You are not a member of this community.');
        }

        $receiver = User::find($receiverId);
        if (!$receiver || !$receiver->isMemberOf($groupId)) {
            return back()->with('error', 'The contact is not a member of this community.');
        }

        $message = Message::create([
            'group_id' => $groupId,
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message->load('sender')]);
        }

        return redirect()->route('member.chat', ['groupId' => $groupId, 'receiverId' => $receiverId]);
    }
}
