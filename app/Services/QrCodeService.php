<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generates a crisp scannable QR code API URL.
     */
    public static function generateSvg(string $text, int $size = 250): string
    {
        $encodedText = urlencode($text);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedText}&margin=10";
    }

    /**
     * Returns an HTML img tag containing the 100% scannable real QR Code image
     */
    public static function renderInlineSvg(string $text, int $size = 200): string
    {
        $encodedText = urlencode($text);
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedText}&margin=10";
        $altText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return '<img src="' . $qrApiUrl . '" alt="QR Code: ' . $altText . '" class="mx-auto rounded-xl shadow-sm border-2 border-slate-100 bg-white p-2 object-contain" style="width:' . $size . 'px; height:' . $size . 'px;">';
    }
}
