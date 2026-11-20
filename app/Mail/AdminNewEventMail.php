<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public ?Group $group;
    public ?User $creator;

    public function __construct(Event $event, ?Group $group = null, ?User $creator = null)
    {
        $this->event = $event;
        $this->group = $group;
        $this->creator = $creator;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Event Created: {$this->event->title} | Bizconn Alert",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_new_event',
        );
    }
}
