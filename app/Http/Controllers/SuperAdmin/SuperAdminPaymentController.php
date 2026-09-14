<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Group;
use App\Models\Event;
use Illuminate\Http\Request;

class SuperAdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'group', 'event']);

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $groups = Group::orderBy('name')->get();
        $events = Event::orderBy('title')->get();

        return view('super_admin.payments.index', compact('payments', 'groups', 'events'));
    }
}
