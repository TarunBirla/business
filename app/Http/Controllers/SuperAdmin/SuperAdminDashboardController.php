<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Payment;
use App\Models\UserSubscription;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalGroups = Group::count();
        $activeGroups = Group::where('status', 'active')->count();

        $groupAdminsCount = User::whereHas('groups', function($q) {
            $q->where('group_user.membership_role', 'group_admin');
        })->count();

        $activeSubscriptions = UserSubscription::where('status', 'active')->count();
        $expiredSubscriptions = UserSubscription::where('status', 'expired')->count();

        $totalEvents = Event::count();
        $totalRegistrations = EventRegistration::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        $recentPayments = Payment::with(['user', 'group', 'event'])->latest()->take(6)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('super_admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalGroups',
            'activeGroups',
            'groupAdminsCount',
            'activeSubscriptions',
            'expiredSubscriptions',
            'totalEvents',
            'totalRegistrations',
            'totalRevenue',
            'recentPayments',
            'recentUsers'
        ));
    }
}
