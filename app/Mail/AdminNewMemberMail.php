<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewMemberMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?string $groupName;

    public function __construct(User $user, ?string $groupName = null)
    {
        $this->user = $user;
        $this->groupName = $groupName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Member Registered: {$this->user->name} | Bizconn Alert",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_new_member',
        );
    }
}
