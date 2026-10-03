<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Community Request Approved - Bizconn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
        .header { background-color: #0A4744; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .body { padding: 30px; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-row { margin-bottom: 10px; font-size: 14px; }
        .details-row strong { color: #0f172a; }
        .btn { display: inline-block; background-color: #0A4744; color: #ffffff !important; font-weight: bold; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 14px; margin-top: 20px; text-align: center; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
        .badge { display: inline-block; background-color: #dcfce7; color: #166534; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Community Network</h1>
        </div>
        <div class="body">
            <span class="badge">APPLICATION APPROVED</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">Congratulations, {{ $user->name }}!</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                Great news! Your request to create <strong>{{ $group->name }}</strong> on Bizconn has been reviewed and <strong>APPROVED</strong> by Super Admin.
            </p>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                You have been assigned as the official <strong>Group Admin</strong> for <strong>{{ $group->name }}</strong>. You can now log in and manage your community, invite members, host events, and customize branding.
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Community Name:</strong> {{ $group->name }}</div>
                <div class="details-row"><strong>Login URL:</strong> <a href="{{ url('/login') }}" style="color: #0A4744;">{{ url('/login') }}</a></div>
                <div class="details-row"><strong>Email:</strong> {{ $user->email }}</div>
                @if($isNewUser && !empty($temporaryPassword))
                    <div class="details-row" style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                        <strong>Temporary Password:</strong> <code style="background: #e2e8f0; padding: 4px 8px; border-radius: 6px; font-size: 15px; color: #0f172a;">{{ $temporaryPassword }}</code>
                    </div>
                    <p style="font-size: 12px; color: #e11d48; margin-top: 10px; font-weight: bold;">
                        Instruction: Pehli baar login karne ke baad aap Profile me jaakar apna Password change kar sakte hain.
                    </p>
                @else
                    <p style="font-size: 12px; color: #64748b; margin-top: 10px;">
                        Aap apne existing Bizconn account password se login kar sakte hain.
                    </p>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Log In to Group Admin Dashboard &rarr;</a>
            </div>
        </div>
        <div class="footer">
            Developed and Powered by Nexteck &copy; {{ date('Y') }} | Support: nasar@thenexteck.com
        </div>
    </div>
</body>
</html>
