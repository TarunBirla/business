<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ $communityName }}</title>
    <style>
        body { font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid #e2e8f0; }
        .box { background: #f0f7f6; padding: 20px; border-radius: 12px; margin: 20px 0; border: 1px solid #cce5e3; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $user->first_name }},</h2>
        <p>You have been registered as a member of <strong>{{ $communityName }}</strong> by your Group Administrator.</p>
        
        <div class="box">
            <h3 style="margin-top:0; color: #0A4744;">Your Account Credentials:</h3>
            <p><strong>Email Address:</strong> {{ $user->email }}</p>
            <p><strong>Initial Password:</strong> {{ $plainPassword }}</p>
        </div>

        <p>You can now log in to access your member dashboard, connect with community members, and view digital business cards.</p>
        <a href="{{ route('login') }}" class="btn">Log In to Your Account</a>

        <div class="footer">
            &copy; {{ date('Y') }} Community UK Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
