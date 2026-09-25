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
        $userModel->load([
            'servicesOffered',
            'servicesNeeded',
            'groups',
            'projects' => function ($q) {
                $q->where('status', 'active')->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
            }
        ]);

        // $showEmail = false;
        // $showPhone = false;

        // // Email and Phone number visible IF own card, super admin, or accepted connection
        // if (auth()->check()) {
        //     if (auth()->id() === $userModel->id || auth()->user()->isSuperAdmin()) {
        //         $showEmail = true;
        //         $showPhone = true;
        //     } else {
        //         $connection = \App\Models\Connection::where(function($q) use ($userModel) {
        //             $q->where('sender_id', auth()->id())->where('receiver_id', $userModel->id);
        //         })->orWhere(function($q) use ($userModel) {
        //             $q->where('sender_id', $userModel->id)->where('receiver_id', auth()->id());
        //         })->where('status', 'accepted')->first();

        //         if ($connection) {
        //             $showEmail = true;
        //             $showPhone = true;
        //         }
        //     }
        // }
        $showEmail = !empty($userModel->email);
        $showPhone = !empty($userModel->phone);

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

        $userAnnouncements = collect();
        $userServicesRequests = collect();
        $userConnectionRequests = collect();

        if (auth()->check() && (int) auth()->id() === (int) $userModel->id) {
            $currentUserId = auth()->id();
            $groupIds = $userModel->groups->pluck('id')->toArray();

            // Fetch IDs of announcements marked as read by the user
            $readAnnouncementIds = \App\Models\Notification::where('user_id', $currentUserId)
                ->where('type', 'announcement')
                ->where('is_read', true)
                ->pluck('announcement_id')
                ->filter()
                ->toArray();

            // Fetch ONLY UNREAD announcements for joined communities or global announcements
            $userAnnouncements = \App\Models\Announcement::whereNotIn('id', $readAnnouncementIds)
                ->where(function ($q) use ($groupIds) {
                    if (!empty($groupIds)) {
                        $q->whereIn('group_id', $groupIds)->orWhereNull('group_id');
                    } else {
                        $q->whereNull('group_id');
                    }
                })->latest()->take(5)->get();

            // Fetch ONLY pending service requests
            $userServicesRequests = \App\Models\ServiceRequest::where('status', 'pending')
                ->where(function ($q) use ($currentUserId) {
                    $q->where('provider_id', $currentUserId)
                        ->orWhere('requester_id', $currentUserId);
                })
                ->with(['service', 'requester', 'provider'])
                ->latest()
                ->take(5)
                ->get();

            // Fetch ONLY pending connection requests where user is receiver
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

    public function markAnnouncementRead(Request $request, \App\Models\Announcement $announcement)
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $notification = \App\Models\Notification::where('user_id', $userId)
            ->where('announcement_id', $announcement->id)
            ->first();

        if ($notification) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        } else {
            \App\Models\Notification::create([
                'user_id' => $userId,
                'announcement_id' => $announcement->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => \Illuminate\Support\Str::limit($announcement->content, 120),
                'link' => route('notifications.index'),
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Announcement marked as read.']);
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

        // FIXED: correct ADR field order -> POBox;Extended;Street;Locality;Region;PostalCode;Country
        if ($userModel->city) {
            $vcardLines[] = 'ADR;TYPE=WORK:;;;'
                . $this->escapeVcard($userModel->city) . ';;;'
                . $this->escapeVcard($userModel->country ?? 'UK');
        }

        // ---------- 1) PHOTO (embedded as base64) ----------
        if ($userModel->profile_photo_url) {
    $photoLine = $this->buildPhotoLine($userModel->profile_photo_url);
    if ($photoLine) {
        // PHOTO ko fold NAHI karna — Android/Samsung ke parsers folded
        // continuation lines ko sahi se unfold nahi karte aur unhe
        // pichle field (ADR) ke garbage text ki tarah dikha dete hain.
        // Ek single unfolded line most mobile apps ke saath reliably chalti hai.
        $vcardLines[] = $photoLine;
    }
}

        // ---------- 2) Richer NOTE (bio + custom notes + card link) ----------
        $noteParts = [];

        

        if ($userModel->notes) {
            $noteParts[] = $this->escapeVcard($userModel->notes);
        }

        $noteParts[] = 'Member of Community UK Professional Network. Card: ' . route('bizcard.show', $userModel->id);

        // NOTE: parts are already escaped above, so join with the literal
        // vCard newline token directly (do NOT re-escape here).
        $noteLine = 'NOTE:' . implode('\n', $noteParts);

        foreach ($this->foldVcardLine($noteLine) as $line) {
            $vcardLines[] = $line;
        }

        $vcardLines[] = 'END:VCARD';

        $vcardContent = implode("\r\n", $vcardLines) . "\r\n";
        $filename = preg_replace('/[^A-Za-z0-9_]/', '_', $userModel->name) . '_BusinessCard.vcf';

        return response($vcardContent, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Fetches the image, base64-encodes it, and returns the unfolded
     * PHOTO;ENCODING=b;TYPE=...:<base64data> line.
     * Returns null if the image can't be fetched or isn't a supported
     * type (fails silently so the vCard still generates without a photo
     * rather than throwing or producing a corrupt PHOTO block).
     */
   private function buildPhotoLine($photoUrl)
{
    try {
        $imageData = @file_get_contents($photoUrl);
        if ($imageData === false || empty($imageData)) {
            return null;
        }

        // Bahut badi image skip karo — single-line base64 bloat se kuch
        // apps timeout/reject kar sakte hain.
        if (strlen($imageData) > 150 * 1024) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($imageData);

        $type = match ($mime) {
            'image/jpeg' => 'JPEG',
            'image/png' => 'PNG',
            default => null,
        };

        if (!$type) {
            return null;
        }

        $base64 = base64_encode($imageData);
        return 'PHOTO;ENCODING=b;TYPE=' . $type . ':' . $base64;
    } catch (\Throwable $e) {
        return null;
    }
}

    /**
     * Folds a single vCard property line per RFC 2426 §2.6:
     * - Max 75 octets per line
     * - Continuation lines start with a single space
     * Byte-safe (won't split a multibyte UTF-8 character across lines)
     * and O(n) — safe even for large base64 PHOTO lines.
     * Returns an array of lines (first line is the property line itself,
     * subsequent lines are the folded continuations).
     */
    private function foldVcardLine(string $line): array
    {
        $maxLen = 75;
        $chars = mb_str_split($line, 1, 'UTF-8');
        $lines = [];
        $chunk = '';
        $isFirst = true;
        $limit = $maxLen;

        foreach ($chars as $char) {
            $charBytes = strlen($char);
            if (strlen($chunk) + $charBytes > $limit) {
                $lines[] = $isFirst ? $chunk : ' ' . $chunk;
                $isFirst = false;
                $chunk = '';
                $limit = $maxLen - 1; // continuation lines: 1 byte less (leading space)
            }
            $chunk .= $char;
        }

        if ($chunk !== '' || empty($lines)) {
            $lines[] = $isFirst ? $chunk : ' ' . $chunk;
        }

        return $lines;
    }

    private function escapeVcard($string)
    {
        $string = $string ?? '';
        $string = str_replace('\\', '\\\\', $string);
        $string = str_replace([';', ','], ['\;', '\,'], $string);
        $string = str_replace(["\r\n", "\r", "\n"], '\n', $string);
        return $string;
    }
}
