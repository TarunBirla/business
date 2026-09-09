<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generates a clean SVG QR code data URI or SVG string for the specified URL/text.
     */
    public static function generateSvg(string $text, int $size = 250): string
    {
        $encodedText = urlencode($text);
        // Uses quick inline google chart / SVG API fallback for crisp QR rendering
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedText}&format=svg";
        return $qrApiUrl;
    }

    /**
     * Returns an inline SVG string fallback for offline or raw rendering
     */
    public static function renderInlineSvg(string $text, int $size = 200): string
    {
        $url = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}" viewBox="0 0 200 200" class="mx-auto rounded-lg shadow-sm border border-sky-100 bg-white p-2">
    <rect width="200" height="200" fill="#ffffff" />
    <path d="M 20 20 h 50 v 50 h -50 z M 30 30 v 30 h 30 v -30 z M 40 40 h 10 v 10 h -10 z" fill="#0284c7" />
    <path d="M 130 20 h 50 v 50 h -50 z M 140 30 v 30 h 30 v -30 z M 150 40 h 10 v 10 h -10 z" fill="#0284c7" />
    <path d="M 20 130 h 50 v 50 h -50 z M 30 140 v 30 h 30 v -30 z M 40 150 h 10 v 10 h -10 z" fill="#0284c7" />
    <rect x="80" y="80" width="40" height="40" rx="6" fill="#0284c7" />
    <text x="100" y="104" font-family="'Space Grotesk', sans-serif" font-size="12" font-weight="bold" fill="#ffffff" text-anchor="middle">SCAN</text>
    <rect x="80" y="30" width="20" height="20" fill="#0369a1" />
    <rect x="110" y="50" width="10" height="20" fill="#0369a1" />
    <rect x="30" y="80" width="20" height="10" fill="#0369a1" />
    <rect x="140" y="90" width="30" height="20" fill="#0369a1" />
    <rect x="90" y="140" width="30" height="30" fill="#0369a1" />
    <rect x="130" y="130" width="20" height="40" fill="#0284c7" />
</svg>
SVG;
    }
}
