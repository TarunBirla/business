<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $plainPassword;
    public string $communityName;

    public function __construct(User $user, string $plainPassword, string $communityName)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->communityName = $communityName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Welcome to {$this->communityName} - Account Credentials",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.member_created',
        );
    }
}
