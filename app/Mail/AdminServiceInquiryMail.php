<?php

namespace App\Mail;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminServiceInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public ServiceRequest $serviceRequest;
    public User $requester;
    public ?Service $service;

    public function __construct(ServiceRequest $serviceRequest, User $requester, ?Service $service = null)
    {
        $this->serviceRequest = $serviceRequest;
        $this->requester = $requester;
        $this->service = $service;
    }

    public function envelope(): Envelope
    {
        $serviceTitle = $this->service ? $this->service->title : 'Service';
        return new Envelope(
            subject: "New Service Inquiry: {$serviceTitle} by {$this->requester->name} | Bizconn Alert",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_service_inquiry',
        );
    }
}
