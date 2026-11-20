<?php

namespace App\Mail;

use App\Models\CommunityRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommunityRequestReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public CommunityRequest $communityRequest;

    public function __construct(CommunityRequest $communityRequest)
    {
        $this->communityRequest = $communityRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We have received your Community Request: {$this->communityRequest->community_name} - Bizconn",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.community_request_received',
        );
    }
}
