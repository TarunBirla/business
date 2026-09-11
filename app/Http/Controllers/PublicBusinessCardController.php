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
            $q->where('status', 'active')->orderBy('created_at', 'desc');
        }]);

        $privacy = $userModel->privacy_settings ?? [];
        $showEmail = $privacy['show_email'] ?? false;
        $showPhone = $privacy['show_phone'] ?? false;

        // If logged in as super admin or viewing own card, or if user is connected
        if (auth()->check()) {
            if (auth()->id() === $userModel->id || auth()->user()->isSuperAdmin()) {
                $showEmail = true;
                $showPhone = true;
            }
        }

        $cardUrl = route('bizcard.show', $userModel->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($cardUrl);

        // Fetch all admin-created upcoming community events across the platform
        $upcomingEvents = Event::with('group')
            ->where(function ($q) {
                $q->where('start_at', '>=', now())->orWhereNull('start_at');
            })
            ->orderBy('start_at', 'asc')
            ->limit(5)
            ->get();

        return view('business_card.show', compact('userModel', 'showEmail', 'showPhone', 'cardUrl', 'qrCodeUrl', 'upcomingEvents'));
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
