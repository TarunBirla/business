<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommunityRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Group $group;
    public string $temporaryPassword;
    public bool $isNewUser;

    public function __construct(User $user, Group $group, string $temporaryPassword = '', bool $isNewUser = true)
    {
        $this->user = $user;
        $this->group = $group;
        $this->temporaryPassword = $temporaryPassword;
        $this->isNewUser = $isNewUser;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Congratulations! Your Community Request for {$this->group->name} Has Been Approved!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.community_request_approved',
        );
    }
}
