<?php

namespace App\Services;

use App\Mail\AdminAbuseReportMail;
use App\Mail\AdminCommunityRequestMail;
use App\Mail\AdminNewEventMail;
use App\Mail\AdminNewMemberMail;
use App\Mail\AdminPaymentReceivedMail;
use App\Mail\AdminSecurityAlertMail;
use App\Mail\AdminServiceInquiryMail;
use App\Mail\CommunityRequestReceivedMail;
use App\Mail\MemberWelcomeMail;
use App\Models\CommunityRequest;
use App\Models\Event;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminAlertService
{
    /**
     * Get the configured Super Admin notification email.
     */
    public static function getAdminEmail(): string
    {
        return config('mail.admin_email') ?: env('ADMIN_EMAIL', 'nasar@thenexteck.com');
    }

    /**
     * Alert 1: New Member Registration
     */
    public static function sendNewMemberAlert(User $user, ?string $groupName = null): void
    {
        // 1. Send alert to Super Admin
        try {
            $adminEmail = self::getAdminEmail();
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new AdminNewMemberMail($user, $groupName));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Super Admin new member alert: ' . $e->getMessage());
        }

        // 2. Send welcome email to Member
        try {
            if (!empty($user->email)) {
                Mail::to($user->email)->send(new MemberWelcomeMail($user, $groupName));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Member welcome email: ' . $e->getMessage());
        }
    }

    /**
     * Alert 2: New Community Request (/request-community)
     */
    public static function sendCommunityRequestAlert(CommunityRequest $communityRequest): void
    {
        // 1. Send alert to Super Admin
        try {
            $adminEmail = self::getAdminEmail();
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new AdminCommunityRequestMail($communityRequest));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Super Admin community request alert: ' . $e->getMessage());
        }

        // 2. Send acknowledgement email to Applicant
        try {
            if (!empty($communityRequest->applicant_email)) {
                Mail::to($communityRequest->applicant_email)->send(new CommunityRequestReceivedMail($communityRequest));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Applicant community request acknowledgement: ' . $e->getMessage());
        }
    }

    /**
     * Alert 3: New Event Created
     */
    public static function sendNewEventAlert(Event $event, ?Group $group = null, ?User $creator = null): void
    {
        try {
            $adminEmail = self::getAdminEmail();
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new AdminNewEventMail($event, $group, $creator));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Super Admin new event alert: ' . $e->getMessage());
        }
    }

    /**
     * Alert 4: Payment Received / Subscription Completed
     */
    public static function sendPaymentAlert(Payment $payment, User $user, array $details = []): void
    {
        try {
            $adminEmail = self::getAdminEmail();
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new AdminPaymentReceivedMail($payment, $user, $details));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Super Admin payment alert: ' . $e->getMessage());
        }
    }

    /**
     * Alert 5: New Service Inquiry / Request
     */
    public static function sendServiceInquiryAlert(ServiceRequest $serviceRequest, User $requester, ?Service $service = null): void
    {
        try {
            $adminEmail = self::getAdminEmail();
            if (!empty($adminEmail)) {
                Mail::to($adminEmail)->send(new AdminServiceInquiryMail($serviceRequest, $requester, $service));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Super Admin service inquiry alert: ' . $e->getMessage());
        }
    }

    /**
     * Alert 6: Security / Profanity Filter Flag
     */
    public static function sendSecurityAlert(string $detectedWord, Request $request): void
    {
        try {
            $adminEmail = self::getAdminEmail();
            if (!empty($adminEmail)) {
                $user = auth()->user();
                $info = [
                    'user_name' => $user ? $user->name : null,
                    'user_email' => $user ? $user->email : null,
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                ];
                Mail::to($adminEmail)->send(new AdminSecurityAlertMail($detectedWord, $info));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send Super Admin security alert: ' . $e->getMessage());
        }
    }
}
