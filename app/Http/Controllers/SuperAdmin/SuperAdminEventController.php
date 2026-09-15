<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperAdminEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('group')->withCount('registrations');

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $events = $query->latest()->paginate(15)->withQueryString();
        $groups = Group::orderBy('name')->get();

        return view('super_admin.events.index', compact('events', 'groups'));
    }

    public function create()
    {
        $groups = Group::orderBy('name')->get();
        return view('super_admin.events.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'agenda' => 'nullable|string',
            'banner_img' => 'nullable|image|max:5120',
            'event_img' => 'nullable|image|max:5120',
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

        $bannerPath = null;
        if ($request->hasFile('banner_img')) {
            $bannerPath = $request->file('banner_img')->store('events/banners', 'public');
        }

        $eventImgPath = null;
        if ($request->hasFile('event_img')) {
            $eventImgPath = $request->file('event_img')->store('events/images', 'public');
        }

        Event::create([
            'group_id' => $request->group_id,
            'created_by' => auth()->id(),
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'overview' => $request->overview,
            'agenda' => $request->agenda,
            'banner_img' => $bannerPath,
            'event_img' => $eventImgPath,
            'banner' => $bannerPath,
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

        return redirect()->route('super_admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $groups = Group::orderBy('name')->get();
        return view('super_admin.events.edit', compact('event', 'groups'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'agenda' => 'nullable|string',
            'banner_img' => 'nullable|image|max:5120',
            'event_img' => 'nullable|image|max:5120',
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

        $data = [
            'group_id' => $request->group_id,
            'title' => $request->title,
            'description' => $request->description,
            'overview' => $request->overview,
            'agenda' => $request->agenda,
            'event_type' => $request->event_type,
            'venue' => $request->venue,
            'address' => $request->address,
            'city' => $request->city,
            'meeting_url' => $request->meeting_url,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'capacity' => $request->capacity,
            'price' => $request->event_type === 'paid' ? ($request->price ?? 10.00) : 0.00,
        ];

        if ($request->hasFile('banner_img')) {
            $bannerPath = $request->file('banner_img')->store('events/banners', 'public');
            $data['banner_img'] = $bannerPath;
            $data['banner'] = $bannerPath;
        }

        if ($request->hasFile('event_img')) {
            $data['event_img'] = $request->file('event_img')->store('events/images', 'public');
        }

        $event->update($data);

        return redirect()->route('super_admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('super_admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
