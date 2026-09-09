<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredGroups = Group::where('status', 'active')->withCount(['members', 'events'])->take(6)->get();
        $upcomingEvents = Event::where('status', 'published')
            ->where('start_at', '>=', now())
            ->with(['group'])
            ->orderBy('start_at', 'asc')
            ->take(4)
            ->get();

        return view('home', compact('featuredGroups', 'upcomingEvents'));
    }
}
