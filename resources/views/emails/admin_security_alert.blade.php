<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Security Alert - Bizconn</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; }
        .header { background-color: #be123c; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .body { padding: 30px; }
        .details-box { background-color: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-row { margin-bottom: 10px; font-size: 14px; }
        .details-row strong { color: #9f1239; display: inline-block; width: 140px; }
        .badge { display: inline-block; background-color: #ffe4e6; color: #be123c; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Security Alert</h1>
        </div>
        <div class="body">
            <span class="badge">PROFANITY FILTER TRIGGERED</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #be123c;">Inappropriate Content Attempt Blocked</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                The automated profanity filter intercepted a form submission containing prohibited or abusive words:
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Detected Word:</strong> <code style="background: #e11d48; color: #ffffff; padding: 2px 8px; border-radius: 4px;">{{ $detectedWord }}</code></div>
                @if(!empty($info['user_name']))
                    <div class="details-row"><strong>User:</strong> {{ $info['user_name'] }} ({{ $info['user_email'] }})</div>
                @else
                    <div class="details-row"><strong>User:</strong> Guest / Unauthenticated</div>
                @endif
                <div class="details-row"><strong>IP Address:</strong> {{ $info['ip'] ?? 'Unknown' }}</div>
                <div class="details-row"><strong>Requested URL:</strong> {{ $info['url'] ?? 'Unknown' }}</div>
                <div class="details-row"><strong>Action Taken:</strong> <span style="color: #be123c; font-weight: bold;">Request Blocked with 422 Error</span></div>
                <div class="details-row"><strong>Timestamp:</strong> {{ now()->format('d M Y, h:i A') }} (UK)</div>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn Platform. Automated Security Alert.
        </div>
    </div>
</body>
</html>
