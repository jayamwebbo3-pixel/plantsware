<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Order Has Been Shipped!</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1 { color: #2ea25a; font-size: 24px; margin-bottom: 20px; }
        p { margin-bottom: 15px; font-size: 16px; }
        .tracking-info { background: #f9f9f9; padding: 15px; border-left: 4px solid #2ea25a; margin: 20px 0; }
        .tracking-info strong { display: inline-block; width: 120px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #2ea25a; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
        .footer { margin-top: 30px; font-size: 14px; color: #777; text-align: center; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Order is on its way! 🚚</h1>
        
        <p>Hi {{ $order->user->name ?? 'Customer' }},</p>
        
        <p>Great news! We have shipped your order <strong>#{{ $order->order_number }}</strong>.</p>
        
        @if($order->tracking_number)
            <div class="tracking-info">
                <p style="margin-top: 0;"><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
                @if($order->tracking_link)
                    <p style="margin-bottom: 0;">
                        <strong>Tracking Link:</strong> 
                        <a href="{{ $order->tracking_link }}" target="_blank" style="color: #2ea25a; word-break: break-all;">{{ $order->tracking_link }}</a>
                    </p>
                @endif
            </div>
        @endif
        
        <p>You can also view the details and track your order from your dashboard.</p>
        
        <a href="{{ route('user.dashboard') }}#order-history" class="btn">Track Order / View Details</a>
        
        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>&copy; {{ date('Y') }} Plantsware. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
