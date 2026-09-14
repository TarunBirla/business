<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $status; // 'approved' or 'rejected'
    public string $communityName;

    public function __construct(User $user, string $status, string $communityName = 'Platform')
    {
        $this->user = $user;
        $this->status = $status;
        $this->communityName = $communityName;
    }

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? "Your account/membership has been approved for {$this->communityName}"
            : "Update regarding your membership for {$this->communityName}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.member_approval',
        );
    }
}
