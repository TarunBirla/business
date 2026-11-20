<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Event Created - Bizconn</title>
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
        .badge { display: inline-block; background-color: #ede9fe; color: #6d28d9; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Super Admin Alert</h1>
        </div>
        <div class="body">
            <span class="badge">NEW EVENT</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">New Community Event Published!</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                A new event has been scheduled on Bizconn. Details:
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Event Title:</strong> <span style="font-weight: bold; color: #0A4744;">{{ $event->title }}</span></div>
                @if($group)
                    <div class="details-row"><strong>Organizing Group:</strong> {{ $group->name }}</div>
                @endif
                @if($creator)
                    <div class="details-row"><strong>Created By:</strong> {{ $creator->name }} ({{ $creator->email }})</div>
                @endif
                <div class="details-row"><strong>Event Type:</strong> {{ ucfirst($event->event_type ?: 'In-Person') }}</div>
                <div class="details-row"><strong>Date & Time:</strong> {{ $event->start_at ? $event->start_at->format('d M Y, h:i A') : 'TBD' }}</div>
                <div class="details-row"><strong>Venue / URL:</strong> {{ $event->venue ?: ($event->meeting_url ?: 'Not specified') }}</div>
                <div class="details-row"><strong>Price:</strong> {{ $event->price > 0 ? '£' . number_format($event->price, 2) : 'Free' }}</div>
                <div class="details-row"><strong>Status:</strong> <span style="color: #16a34a; font-weight: bold;">{{ ucfirst($event->status ?: 'Published') }}</span></div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('events.show', $event->slug) }}" class="btn">View Live Event Page</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn Platform. Automated Super Admin Notification.
        </div>
    </div>
</body>
</html>
