<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupSubscription;
use App\Models\UserSubscription;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Str;

class StripePaymentService
{
    /**
     * Process a payment for Group Membership Subscription
     */
    public static function processGroupSubscription(User $user, Group $group, GroupSubscription $plan = null, string $provider = 'stripe'): Payment
    {
        $amount = $plan ? $plan->price : 20.00;
        $currency = $plan ? $plan->currency : 'GBP';
        $transactionId = 'STRIPE_' . strtoupper(Str::random(16));

        $payment = Payment::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'subscription_id' => $plan?->id,
            'amount' => $amount,
            'currency' => $currency,
            'provider' => $provider,
            'transaction_id' => $transactionId,
            'status' => 'completed',
            'metadata' => [
                'type' => 'group_subscription',
                'group_name' => $group->name,
                'user_email' => $user->email,
            ],
        ]);

        // Activate User Subscription
        $durationYears = ($plan && $plan->duration_type === 'yearly') ? $plan->duration : 1;
        
        UserSubscription::updateOrCreate(
            ['user_id' => $user->id, 'group_id' => $group->id],
            [
                'subscription_id' => $plan?->id,
                'payment_id' => $payment->id,
                'start_date' => now(),
                'expiry_date' => now()->addYears($durationYears),
                'status' => 'active',
            ]
        );

        // Ensure user is attached to group as member
        if (!$user->isMemberOf($group->id)) {
            $user->groups()->attach($group->id, [
                'membership_role' => 'member',
                'status' => 'active',
                'joined_at' => now(),
            ]);
        } else {
            $user->groups()->updateExistingPivot($group->id, ['status' => 'active']);
        }

        return $payment;
    }

    /**
     * Process a payment for Event Registration
     */
    public static function processEventRegistration(User $user, Event $event, string $provider = 'stripe'): EventRegistration
    {
        $amount = $event->price;
        $currency = $event->currency;
        $transactionId = 'STRIPE_EVT_' . strtoupper(Str::random(16));

        $payment = Payment::create([
            'user_id' => $user->id,
            'group_id' => $event->group_id,
            'event_id' => $event->id,
            'amount' => $amount,
            'currency' => $currency,
            'provider' => $provider,
            'transaction_id' => $transactionId,
            'status' => 'completed',
            'metadata' => [
                'type' => 'event_registration',
                'event_title' => $event->title,
                'user_email' => $user->email,
            ],
        ]);

        $registration = EventRegistration::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            [
                'payment_id' => $payment->id,
                'amount' => $amount,
                'payment_status' => 'completed',
                'registration_status' => 'confirmed',
                'registered_at' => now(),
            ]
        );

        return $registration;
    }
}
