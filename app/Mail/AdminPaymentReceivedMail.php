<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public User $user;
    public array $details;

    public function __construct(Payment $payment, User $user, array $details = [])
    {
        $this->payment = $payment;
        $this->user = $user;
        $this->details = $details;
    }

    public function envelope(): Envelope
    {
        $currencySymbol = ($this->payment->currency === 'GBP') ? '£' : $this->payment->currency;
        return new Envelope(
            subject: "Payment Received: {$currencySymbol}{$this->payment->amount} from {$this->user->name} | Bizconn Alert",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_payment_received',
        );
    }
}
