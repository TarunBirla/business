<?php

namespace App\Http\Controllers\GroupAdmin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Referral;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class GroupAdminPromotionController extends Controller
{
    public function index(Group $group)
    {
        $this->authorizeAdmin($group);

        $shareUrl = $group->join_url;
        $referralUrl = $shareUrl . '?ref=admin_' . auth()->id();

        $defaultShareText = $group->promotional_message ?: "Join {$group->name} and connect with people, professionals and businesses from our community. Discover services, events and new opportunities.";

        $qrCodeSvg = QrCodeService::renderInlineSvg($shareUrl, 260);

        $referralsCount = Referral::where('group_id', $group->id)->count();
        $sourcesCount = Referral::where('group_id', $group->id)
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source')
            ->toArray();

        $user = auth()->user();
        $assignedGroups = $user->isSuperAdmin() ? Group::all() : $user->groups()->wherePivot('membership_role', 'group_admin')->get();

        return view('group_admin.promotion.index', compact(
            'group',
            'shareUrl',
            'referralUrl',
            'defaultShareText',
            'qrCodeSvg',
            'referralsCount',
            'sourcesCount',
            'assignedGroups'
        ));
    }

    public function updateMessage(Request $request, Group $group)
    {
        $this->authorizeAdmin($group);

        $request->validate([
            'promotional_message' => 'required|string|max:1000',
        ]);

        $group->update([
            'promotional_message' => $request->promotional_message,
        ]);

        return back()->with('success', 'Promotional message saved successfully!');
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
