<?php

namespace App\Mail;

use App\Models\CommunityRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCommunityRequestMail extends Mailable
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
            subject: "New Community Creation Request: {$this->communityRequest->community_name} | Bizconn Alert",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_community_request',
        );
    }
}
