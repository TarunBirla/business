<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Community Request - Bizconn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
        .header { background-color: #0A4744; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .body { padding: 30px; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-row { margin-bottom: 10px; font-size: 14px; }
        .details-row strong { color: #0f172a; display: inline-block; width: 145px; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; font-weight: bold; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-size: 14px; margin-top: 15px; }
        .badge { display: inline-block; background-color: #fef3c7; color: #b45309; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Super Admin Alert</h1>
        </div>
        <div class="body">
            <span class="badge">ACTION REQUIRED</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">New Community Creation Request Submitted!</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                An applicant has submitted a request to launch a new community on Bizconn. Please review the details below:
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Community Name:</strong> <span style="font-size: 15px; font-weight: bold; color: #0A4744;">{{ $communityRequest->community_name }}</span></div>
                <div class="details-row"><strong>Applicant Name:</strong> {{ $communityRequest->applicant_name }}</div>
                <div class="details-row"><strong>Applicant Email:</strong> <a href="mailto:{{ $communityRequest->applicant_email }}" style="color: #0A4744;">{{ $communityRequest->applicant_email }}</a></div>
                <div class="details-row"><strong>Applicant Phone:</strong> {{ $communityRequest->applicant_phone ?: 'Not provided' }}</div>
                <div class="details-row"><strong>Community Type:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ $communityRequest->community_type }}</span></div>
                <div class="details-row"><strong>Location:</strong> {{ $communityRequest->city ? $communityRequest->city . ', ' : '' }}{{ $communityRequest->country ?: 'United Kingdom' }}</div>
                <div class="details-row" style="margin-top: 12px; border-top: 1px dashed #cbd5e1; padding-top: 10px;">
                    <strong>Description:</strong>
                    <p style="margin: 6px 0 0 0; color: #334155; line-height: 1.5; font-size: 13px;">{{ $communityRequest->description }}</p>
                </div>
                @if(!empty($communityRequest->purpose))
                    <div class="details-row" style="margin-top: 10px;">
                        <strong>Purpose:</strong>
                        <p style="margin: 4px 0 0 0; color: #334155; line-height: 1.5; font-size: 13px;">{{ $communityRequest->purpose }}</p>
                    </div>
                @endif
                <div class="details-row" style="margin-top: 12px; border-top: 1px dashed #cbd5e1; padding-top: 10px;">
                    <strong>Submitted At:</strong> {{ now()->format('d M Y, h:i A') }} (UK)
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('super_admin.community_requests.index') }}" class="btn">Review & Approve in Admin Panel</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn Platform. Automated Super Admin Notification.
        </div>
    </div>
</body>
</html>
