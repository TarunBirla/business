<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberWelcomeMail extends Mailable
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
        $firstName = $this->user->first_name ?: $this->user->name;
        return new Envelope(
            subject: "Welcome to Bizconn, {$firstName}! Account Registration Received",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.member_welcome',
        );
    }
}
