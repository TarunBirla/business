<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Bizconn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
        .header { background-color: #0A4744; color: #ffffff; padding: 28px 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.5px; }
        .body { padding: 30px; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-row { margin-bottom: 8px; font-size: 14px; }
        .details-row strong { color: #0f172a; display: inline-block; width: 130px; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; font-weight: bold; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 14px; margin-top: 15px; text-align: center; }
        .badge { display: inline-block; background-color: #dcfce7; color: #166534; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Bizconn</h1>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #a7f3d0;">Empowering Communities & Business Networking UK</p>
        </div>
        <div class="body">
            <span class="badge">REGISTRATION SUCCESSFUL</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">Hello, {{ $user->first_name ?: $user->name }}!</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                Thank you for joining <strong>Bizconn</strong>. Your account has been registered successfully.
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Registered Name:</strong> {{ $user->name }}</div>
                <div class="details-row"><strong>Login Email:</strong> {{ $user->email }}</div>
                @if(!empty($groupName))
                    <div class="details-row"><strong>Selected Community:</strong> {{ $groupName }}</div>
                @endif
                <div class="details-row"><strong>Status:</strong> <span style="color: #0284c7; font-weight: bold;">Account Active</span></div>
            </div>

            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                You can now log in, explore UK business communities, discover verified professionals, attend upcoming networking events, and create your digital business card.
            </p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ url('/login') }}" class="btn">Log In to Your Bizconn Account</a>
            </div>

            <p style="font-size: 13px; color: #64748b; line-height: 1.5; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                If you have any questions or need assistance getting started, reply directly to this email or reach our support team.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn by Nexteck Company. All rights reserved.
        </div>
    </div>
</body>
</html>
