<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $announcement->title }}</title>
    <style>
        body { font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid #e2e8f0; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .footer { margin-top: 32px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color: #0A4744;">📢 {{ $announcement->title }}</h2>
        <p>Hello {{ $user->first_name }},</p>
        <div style="background-color: #f1f5f9; padding: 20px; border-radius: 12px; margin: 16px 0;">
            {!! nl2br(e($announcement->content)) !!}
        </div>
        <a href="{{ route('notifications.index') }}" class="btn">View in App</a>
        <div class="footer">
            &copy; {{ date('Y') }} Business Community Platform. All rights reserved.
        </div>
    </div>
</body>
</html>
