<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('status', 'published')->with(['group']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }

        $events = $query->orderBy('start_at', 'asc')->paginate(9);

        return view('events.index', compact('events'));
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)->with(['group', 'creator'])->firstOrFail();
        $isRegistered = false;

        if (auth()->check()) {
            $isRegistered = $event->registrations()->where('user_id', auth()->id())->exists();
        }

        return view('events.show', compact('event', 'isRegistered'));
    }
}
