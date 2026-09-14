<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    <style>
        body { font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid #e2e8f0; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Request</h2>
        <p>Hello,</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <p>Click the button below to reset your password. This link is valid for 60 minutes.</p>
        <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        <p style="margin-top: 24px; font-size: 13px; color: #64748b;">If you did not request a password reset, no further action is required.</p>
        <div class="footer">
            &copy; {{ date('Y') }} Business Community Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
