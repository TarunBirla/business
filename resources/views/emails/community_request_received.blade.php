<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Community Request Received - Bizconn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
        .header { background-color: #0A4744; color: #ffffff; padding: 26px 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .body { padding: 30px; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-row { margin-bottom: 8px; font-size: 14px; }
        .details-row strong { color: #0f172a; display: inline-block; width: 140px; }
        .badge { display: inline-block; background-color: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Community Network</h1>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #a7f3d0;">Application Under Review</p>
        </div>
        <div class="body">
            <span class="badge">REQUEST RECEIVED</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">Dear {{ $communityRequest->applicant_name }},</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                Thank you for applying to launch <strong>{{ $communityRequest->community_name }}</strong> on Bizconn! We have received your application.
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Community Name:</strong> {{ $communityRequest->community_name }}</div>
                <div class="details-row"><strong>Community Type:</strong> {{ ucfirst($communityRequest->community_type) }}</div>
                <div class="details-row"><strong>Target Region:</strong> {{ $communityRequest->city ? $communityRequest->city . ', ' : '' }}{{ $communityRequest->country ?: 'United Kingdom' }}</div>
                <div class="details-row"><strong>Status:</strong> <span style="color: #b45309; font-weight: bold;">Under Super Admin Review</span></div>
            </div>

            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                Our Super Admin team reviews each community proposal to ensure safety, relevance, and alignment with UK business standards. Once approved, you will receive an official approval email with your Group Admin login credentials.
            </p>

            <p style="font-size: 13px; color: #64748b; line-height: 1.5; border-top: 1px solid #e2e8f0; padding-top: 15px; margin-top: 20px;">
                Review typically takes 24 to 48 hours. If you need any immediate assistance, feel free to contact our support team.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
