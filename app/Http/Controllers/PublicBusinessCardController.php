<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PublicBusinessCardController extends Controller
{
    public function show($user)
    {
        $userModel = User::where('id', $user)->firstOrFail();
        $userModel->load(['servicesOffered', 'servicesNeeded', 'groups', 'projects' => function ($q) {
            $q->where('status', 'active')->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
        }]);

        $showEmail = false;
        $showPhone = false;

        // Email and Phone number visible IF own card, super admin, or accepted connection
        if (auth()->check()) {
            if (auth()->id() === $userModel->id || auth()->user()->isSuperAdmin()) {
                $showEmail = true;
                $showPhone = true;
            } else {
                $connection = \App\Models\Connection::where(function($q) use ($userModel) {
                    $q->where('sender_id', auth()->id())->where('receiver_id', $userModel->id);
                })->orWhere(function($q) use ($userModel) {
                    $q->where('sender_id', $userModel->id)->where('receiver_id', auth()->id());
                })->where('status', 'accepted')->first();

                if ($connection) {
                    $showEmail = true;
                    $showPhone = true;
                }
            }
        }

        $cardUrl = route('bizcard.show', $userModel->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($cardUrl);

        $userAnnouncements = collect();
        $userServicesRequests = collect();
        $userConnectionRequests = collect();

        if (auth()->check()) {
            $currentUserId = auth()->id();
            $groupIds = $userModel->groups->pluck('id')->toArray();

            // Fetch announcements for joined communities or global announcements
            $userAnnouncements = \App\Models\Announcement::where(function($q) use ($groupIds) {
                if (!empty($groupIds)) {
                    $q->whereIn('group_id', $groupIds)->orWhereNull('group_id');
                } else {
                    $q->whereNull('group_id');
                }
            })->latest()->take(5)->get();

            // Fetch service requests
            $userServicesRequests = \App\Models\CommunityServiceRequest::where('provider_id', $currentUserId)
                ->orWhere('requester_id', $currentUserId)
                ->with(['service', 'requester', 'provider'])
                ->latest()
                ->take(5)
                ->get();

            // Fetch pending connection requests
            $userConnectionRequests = \App\Models\Connection::where('receiver_id', $currentUserId)
                ->where('status', 'pending')
                ->with('sender')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('business_card.show', compact(
            'userModel',
            'showEmail',
            'showPhone',
            'cardUrl',
            'qrCodeUrl',
            'upcomingEvents',
            'userAnnouncements',
            'userServicesRequests',
            'userConnectionRequests'
        ));
    }

    public function manifest($user)
    {
        $userModel = User::where('id', $user)->firstOrFail();
        $iconUrl = $userModel->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($userModel->name) . '&background=0A4744&color=fff&size=512';

        $cardUrl = route('bizcard.show', $userModel->id);

        $manifest = [
            'name' => $userModel->name . ' - Digital Card',
            'short_name' => $userModel->first_name ?: 'Biz Card',
            'description' => $userModel->name . ($userModel->profession ? ' - ' . $userModel->profession : '') . ' Digital Business Card',
            'start_url' => $cardUrl,
            'scope' => $cardUrl,
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => '#ffffff',
            'theme_color' => '#0A4744',
            'icons' => [
                [
                    'src' => $iconUrl,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any'
                ],
                [
                    'src' => $iconUrl,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any'
                ]
            ]
        ];

        return response()->json($manifest)->header('Content-Type', 'application/manifest+json');
    }

    public function downloadVcard($user)
    {
        $userModel = User::where('id', $user)->firstOrFail();

        $privacy = $userModel->privacy_settings ?? [];
        $showEmail = $privacy['show_email'] ?? false;
        $showPhone = $privacy['show_phone'] ?? false;

        if (auth()->check() && (auth()->id() === $userModel->id || auth()->user()->isSuperAdmin())) {
            $showEmail = true;
            $showPhone = true;
        }

        $vcardLines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'N:' . $this->escapeVcard($userModel->last_name) . ';' . $this->escapeVcard($userModel->first_name) . ';;;',
            'FN:' . $this->escapeVcard($userModel->name),
        ];

        if ($userModel->profession) {
            $vcardLines[] = 'TITLE:' . $this->escapeVcard($userModel->profession);
        }

        if ($userModel->company) {
            $vcardLines[] = 'ORG:' . $this->escapeVcard($userModel->company);
        }

        if ($showPhone && $userModel->phone) {
            $vcardLines[] = 'TEL;TYPE=CELL,VOICE:' . $this->escapeVcard($userModel->phone);
        }

        if ($showEmail && $userModel->email) {
            $vcardLines[] = 'EMAIL;TYPE=INTERNET,PREF:' . $this->escapeVcard($userModel->email);
        }

        if ($userModel->linkedin) {
            $vcardLines[] = 'URL;TYPE=LinkedIn:' . $userModel->linkedin;
        } elseif ($userModel->website) {
            $vcardLines[] = 'URL:' . $userModel->website;
        }

        if ($userModel->city) {
            $vcardLines[] = 'ADR;TYPE=WORK:;;;' . $this->escapeVcard($userModel->city) . ';;' . $this->escapeVcard($userModel->country ?? 'UK') . ';';
        }

        $vcardLines[] = 'NOTE:Member of Community UK Professional Network. Card: ' . route('bizcard.show', $userModel->id);
        $vcardLines[] = 'END:VCARD';

        $vcardContent = implode("\r\n", $vcardLines);
        $filename = preg_replace('/[^A-Za-z0-9_]/', '_', $userModel->name) . '_BusinessCard.vcf';

        return response($vcardContent, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function escapeVcard($string)
    {
        return str_replace(['\\', ';', ','], ['\\\\', '\;', '\,'], $string ?? '');
    }
}
