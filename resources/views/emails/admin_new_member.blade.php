<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Member Registered - Bizconn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
        .header { background-color: #0A4744; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .body { padding: 30px; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-row { margin-bottom: 10px; font-size: 14px; }
        .details-row strong { color: #0f172a; display: inline-block; width: 140px; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; font-weight: bold; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-size: 14px; margin-top: 15px; }
        .badge { display: inline-block; background-color: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Super Admin Alert</h1>
        </div>
        <div class="body">
            <span class="badge">NEW REGISTRATION</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">New Member Signed Up!</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                A new member has just registered an account on <strong>Bizconn</strong>. Here are their registration details:
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Full Name:</strong> {{ $user->name }}</div>
                <div class="details-row"><strong>Email:</strong> <a href="mailto:{{ $user->email }}" style="color: #0A4744;">{{ $user->email }}</a></div>
                <div class="details-row"><strong>Phone:</strong> {{ $user->phone ?: 'Not provided' }}</div>
                <div class="details-row"><strong>Profession:</strong> {{ $user->profession ?: 'Not provided' }}</div>
                <div class="details-row"><strong>Location:</strong> {{ $user->city ? $user->city . ', ' : '' }}{{ $user->country ?: 'United Kingdom' }}</div>
                @if(!empty($groupName))
                    <div class="details-row"><strong>Target Community:</strong> {{ $groupName }}</div>
                @endif
                <div class="details-row"><strong>Status:</strong> <span style="color: #d97706; font-weight: bold;">{{ ucfirst($user->status) }}</span></div>
                <div class="details-row"><strong>Registered At:</strong> {{ now()->format('d M Y, h:i A') }} (UK)</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('super_admin.users.index') }}" class="btn">View Users in Admin Panel</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn Platform. Automated Super Admin Notification.
        </div>
    </div>
</body>
</html>
