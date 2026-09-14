<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Membership Update</title>
    <style>
        body { font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid #e2e8f0; }
        .btn { display: inline-block; background-color: #0284c7; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $user->first_name }},</h2>
        @if($status === 'approved')
            <p style="color: #16a34a; font-weight: bold; font-size: 18px;">Your membership has been APPROVED!</p>
            <p>Congratulations! You are now an active member of <strong>{{ $communityName }}</strong>.</p>
            <a href="{{ route('login') }}" class="btn">Log In to Your Account</a>
        @else
            <p style="color: #dc2626; font-weight: bold; font-size: 18px;">Membership Status Update</p>
            <p>We regret to inform you that your request to join <strong>{{ $communityName }}</strong> was not approved at this time.</p>
        @endif
        <div class="footer">
            &copy; {{ date('Y') }} Business Community Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
