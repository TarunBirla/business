<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupAdminEventController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);
        $user = auth()->user();
        $assignedGroups = $user->isSuperAdmin() ? Group::all() : $user->groups()->wherePivot('membership_role', 'group_admin')->get();
        $events = $group->events()->withCount('registrations')->latest()->paginate(10);
        return view('group_admin.events.index', compact('group', 'events', 'assignedGroups'));
    }

    public function create(Group $group)
    {
        $this->authorizeAdmin($group);
        return view('group_admin.events.create', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:free,paid',
            'venue' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'meeting_url' => 'nullable|url|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'capacity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $slug = Str::slug($request->title) . '-' . Str::random(5);

        $group->events()->create([
            'created_by' => auth()->id(),
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'event_type' => $request->event_type,
            'venue' => $request->venue,
            'address' => $request->address,
            'city' => $request->city,
            'meeting_url' => $request->meeting_url,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'capacity' => $request->capacity,
            'price' => $request->event_type === 'paid' ? ($request->price ?? 10.00) : 0.00,
            'currency' => 'GBP',
            'status' => 'published',
        ]);

        return redirect()->route('group_admin.events.index', $group->id)->with('success', 'Event created successfully.');
    }

    public function edit(Group $group, Event $event)
    {
        $this->authorizeAdmin($group);
        return view('group_admin.events.edit', compact('group', 'event'));
    }

    public function update(Request $request, Group $group, Event $event)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:free,paid',
            'venue' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'meeting_url' => 'nullable|url|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'capacity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_type' => $request->event_type,
            'venue' => $request->venue,
            'address' => $request->address,
            'city' => $request->city,
            'meeting_url' => $request->meeting_url,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'capacity' => $request->capacity,
            'price' => $request->event_type === 'paid' ? ($request->price ?? 10.00) : 0.00,
        ]);

        return redirect()->route('group_admin.events.index', $group->id)->with('success', 'Event updated successfully.');
    }

    public function destroy(Group $group, Event $event)
    {
        $this->authorizeAdmin($group);
        $event->delete();
        return redirect()->route('group_admin.events.index', $group->id)->with('success', 'Event deleted successfully.');
    }

    public function attendees(Group $group, Event $event)
    {
        $this->authorizeAdmin($group);
        $attendees = $event->registrations()->with('user')->paginate(15);
        return view('group_admin.events.attendees', compact('group', 'event', 'attendees'));
    }

    public function approveRegistration(Group $group, Event $event, EventRegistration $registration)
    {
        $this->authorizeAdmin($group);

        if ((int)$registration->event_id !== (int)$event->id) {
            abort(404);
        }

        $registration->update([
            'registration_status' => 'confirmed',
        ]);

        \App\Models\Notification::create([
            'user_id' => $registration->user_id,
            'type' => 'approval',
            'title' => 'Event Registration Approved!',
            'message' => "Your registration request for {$event->title} has been approved by the Group Admin!",
            'link' => route('events.show', $event->slug),
        ]);

        return back()->with('success', "Attendee {$registration->user->name}'s event registration approved successfully.");
    }

    public function rejectRegistration(Group $group, Event $event, EventRegistration $registration)
    {
        $this->authorizeAdmin($group);

        if ((int)$registration->event_id !== (int)$event->id) {
            abort(404);
        }

        $registration->update([
            'registration_status' => 'rejected',
        ]);

        \App\Models\Notification::create([
            'user_id' => $registration->user_id,
            'type' => 'approval',
            'title' => 'Event Registration Update',
            'message' => "Your registration request for {$event->title} was not approved by the Group Admin.",
        ]);

        return back()->with('success', "Attendee {$registration->user->name}'s registration request was declined.");
    }

    public function exportAttendeesCsv(Group $group, Event $event)
    {
        $this->authorizeAdmin($group);
        $registrations = $event->registrations()->with('user')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=attendees_{$event->slug}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['User ID', 'First Name', 'Last Name', 'Email', 'Amount', 'Payment Status', 'Registered At']);
            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->user_id,
                    $reg->user->first_name,
                    $reg->user->last_name,
                    $reg->user->email,
                    $reg->amount,
                    $reg->payment_status,
                    $reg->registered_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
