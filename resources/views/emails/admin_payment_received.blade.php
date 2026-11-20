<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Received - Bizconn</title>
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
        .badge { display: inline-block; background-color: #dcfce7; color: #166534; font-size: 12px; font-weight: bold; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bizconn Super Admin Alert</h1>
        </div>
        <div class="body">
            <span class="badge">PAYMENT SUCCESSFUL</span>
            <h2 style="margin-top: 10px; font-size: 18px; color: #0f172a;">New Payment Received!</h2>
            <p style="font-size: 14px; color: #475569; line-height: 1.6;">
                A member has successfully completed a payment on Bizconn:
            </p>

            <div class="details-box">
                <div class="details-row"><strong>Amount:</strong> <span style="font-size: 18px; font-weight: bold; color: #16a34a;">{{ ($payment->currency === 'GBP' ? '£' : $payment->currency) . number_format($payment->amount, 2) }}</span></div>
                <div class="details-row"><strong>Paid By:</strong> {{ $user->name }}</div>
                <div class="details-row"><strong>Email:</strong> {{ $user->email }}</div>
                <div class="details-row"><strong>Transaction ID:</strong> <code>{{ $payment->transaction_id }}</code></div>
                <div class="details-row"><strong>Payment Type:</strong> {{ ucfirst($payment->type ?: 'Subscription') }}</div>
                @if(!empty($details['item_name']))
                    <div class="details-row"><strong>Item / Plan:</strong> {{ $details['item_name'] }}</div>
                @endif
                <div class="details-row"><strong>Provider:</strong> {{ ucfirst($payment->provider ?: 'Stripe') }}</div>
                <div class="details-row"><strong>Date:</strong> {{ now()->format('d M Y, h:i A') }} (UK)</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('super_admin.payments.index') }}" class="btn">View All Payments in Admin Panel</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bizconn Platform. Automated Super Admin Notification.
        </div>
    </div>
</body>
</html>
