<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminSecurityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $detectedWord;
    public array $info;

    public function __construct(string $detectedWord, array $info = [])
    {
        $this->detectedWord = $detectedWord;
        $this->info = $info;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "SECURITY ALERT: Inappropriate Content Attempt Flagged on Bizconn",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_security_alert',
        );
    }
}
