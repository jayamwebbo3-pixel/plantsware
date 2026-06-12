<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Coupon for You!</title>
    <style>
        body {
            font-family: 'Outfit', 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #134e5e, #71b280);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
            line-height: 1.6;
        }
        .content p {
            font-size: 16px;
            margin-bottom: 25px;
            color: #555555;
        }
        .coupon-card {
            background-color: #f8faf9;
            border: 2px dashed #71b280;
            border-radius: 8px;
            padding: 25px;
            margin: 30px auto;
            max-width: 80%;
        }
        .coupon-code {
            font-size: 32px;
            font-weight: 800;
            color: #134e5e;
            letter-spacing: 2px;
            margin: 0 0 10px 0;
        }
        .discount-details {
            font-size: 18px;
            font-weight: 600;
            color: #2e7d32;
            margin: 10px 0;
        }
        .terms {
            font-size: 12px;
            color: #888888;
            margin-top: 15px;
            line-height: 1.4;
        }
        .btn {
            display: inline-block;
            background-color: #134e5e;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 10px;
            box-shadow: 0 3px 6px rgba(19, 78, 94, 0.2);
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Exclusive Reward For You!</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Thank you for shopping with Plantsware! We appreciate your support and have generated a special coupon code assigned exclusively to your account.</p>
            
            <div class="coupon-card">
                <div class="coupon-code">{{ $coupon->coupon_code }}</div>
                <div class="discount-details">
                    @if($coupon->discount_type === 'percentage')
                        Get {{ number_format($coupon->discount_value, 0) }}% Off
                        @if($coupon->max_discount)
                            (Up to ₹{{ number_format($coupon->max_discount, 2) }})
                        @endif
                    @else
                        Get Flat ₹{{ number_format($coupon->discount_value, 2) }} Off
                    @endif
                </div>
                <div class="terms">
                    @if($coupon->minimum_order_amount > 0)
                        • Valid on orders of ₹{{ number_format($coupon->minimum_order_amount, 2) }} and above<br>
                    @endif
                    @if($coupon->valid_to)
                        • Valid until {{ $coupon->valid_to->format('d M Y') }}<br>
                    @endif
                    • Can be used only once per account
                </div>
            </div>
            
            <a href="{{ url('/') }}" class="btn">Shop Now</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Plantsware. All rights reserved.</p>
            <p>You received this email because you are a registered user of Plantsware.</p>
        </div>
    </div>
</body>
</html>
