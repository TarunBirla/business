<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Referral;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class PublicGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::where('status', 'active')->withCount(['members', 'events']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('type')) {
            $query->where('community_type', $request->type);
        }

        $groups = $query->paginate(9);

        return view('groups.index', compact('groups'));
    }

    public function show(Request $request, string $slug)
    {
        $group = Group::where('slug', $slug)->with(['activeSubscriptionPlan'])->firstOrFail();

        // Store group ID in session so registration or login auto-joins
        session(['join_group_id' => $group->id]);

        // Track referral if ?ref= parameter is present
        if ($request->has('ref')) {
            $refCode = $request->get('ref');
            $source = $request->get('source', 'social');
            
            session(['referral_group_id' => $group->id, 'referral_code' => $refCode, 'referral_source' => $source]);

            Referral::create([
                'group_id' => $group->id,
                'source' => $source,
                'referral_code' => $refCode,
            ]);
        }

        $memberCount = $group->members()->count();
        $professionalsCount = $group->members()->whereNotNull('profession')->count();
        $eventsCount = $group->events()->count();
        $noticesCount = $group->notices()->count();

        $upcomingEvents = $group->upcomingEvents()->take(3)->get();
        $activeNotices = $group->activeNotices()->take(3)->get();
        $activePlan = $group->activeSubscriptionPlan;

        $qrCodeSvg = QrCodeService::renderInlineSvg($group->join_url, 180);

        return view('groups.show', compact(
            'group',
            'memberCount',
            'professionalsCount',
            'eventsCount',
            'noticesCount',
            'upcomingEvents',
            'activeNotices',
            'activePlan',
            'qrCodeSvg'
        ));
    }

    public function joinCommunity(Request $request, Group $group)
    {
        $user = auth()->user();

        if ($user->isMemberOf($group->id)) {
            return redirect()->route('member.dashboard')->with('info', "You are already a member of {$group->name}.");
        }

        if ($group->community_type === 'paid') {
            return redirect()->route('join.checkout', $group->id);
        }

        // Free community join
        $user->groups()->attach($group->id, [
            'membership_role' => 'member',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return redirect()->route('member.dashboard')->with('success', "You have successfully joined {$group->name}!");
    }

    public function qr(string $slug)
    {
        $group = Group::where('slug', $slug)->firstOrFail();
        $qrCodeSvg = QrCodeService::renderInlineSvg($group->join_url, 300);
        return view('groups.qr', compact('group', 'qrCodeSvg'));
    }
}
