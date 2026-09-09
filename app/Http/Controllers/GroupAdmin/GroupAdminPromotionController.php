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

        $defaultShareText = "Join {$group->name} and connect with people, professionals and businesses from our community. Discover services, events and new opportunities.";

        $qrCodeSvg = QrCodeService::renderInlineSvg($shareUrl, 260);

        $referralsCount = Referral::where('group_id', $group->id)->count();
        $sourcesCount = Referral::where('group_id', $group->id)
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source')
            ->toArray();

        return view('group_admin.promotion.index', compact(
            'group',
            'shareUrl',
            'referralUrl',
            'defaultShareText',
            'qrCodeSvg',
            'referralsCount',
            'sourcesCount'
        ));
    }

    private function authorizeAdmin(Group $group)
    {
        $user = auth()->user();
        if (!$user->isGroupAdmin($group->id)) {
            abort(403, 'Unauthorized access.');
        }
    }
}
