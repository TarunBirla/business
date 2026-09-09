<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function register(Request $request, Event $event)
    {
        $user = auth()->user();

        // Check capacity
        if ($event->available_seats <= 0) {
            return back()->with('error', 'Sorry, this event is fully booked.');
        }

        // Check if already registered
        if ($event->registrations()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'You are already registered for this event.');
        }

        if ($event->event_type === 'paid' && $event->price > 0) {
            // Process Stripe Event Payment
            StripePaymentService::processEventRegistration($user, $event);
            return redirect()->route('events.show', $event->slug)->with('success', 'Payment successful! You are now registered for this event.');
        }

        // Free event registration
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'amount' => 0.00,
            'payment_status' => 'completed',
            'registration_status' => 'confirmed',
            'registered_at' => now(),
        ]);

        return redirect()->route('events.show', $event->slug)->with('success', 'Registration confirmed! See you at the event.');
    }
}
