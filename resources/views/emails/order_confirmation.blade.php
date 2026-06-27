<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #2d3748; margin: 0; padding: 0; background-color: #f7fafc; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #134e5e 0%, #71b280 100%); padding: 40px 30px; text-align: center; color: #ffffff; }
        .header img { background-color: #ffffff; padding: 6px; border-radius: 8px; max-width: 150px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
        .content { padding: 35px 30px; }
        .order-summary { background: #f8fafc; padding: 25px; border-radius: 10px; margin-bottom: 25px; border: 1px solid #edf2f7; }
        .order-summary h2 { margin-top: 0; font-size: 20px; color: #134e5e; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; color: #718096; font-size: 13px; text-transform: uppercase; font-weight: 600; }
        td { padding: 12px; border-bottom: 1px solid #edf2f7; font-size: 15px; color: #4a5568; }
        .text-right { text-align: right; }
        .total-row td { font-weight: 700; font-size: 18px; color: #1a202c; border-top: 2px solid #e2e8f0; border-bottom: none; }
        .footer { background: #1a202c; color: #a0aec0; text-align: center; padding: 30px 20px; font-size: 13px; }
        .footer p { margin: 0 0 10px 0; }
        .footer a { color: #71b280; text-decoration: none; font-weight: 600; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #e6fffa; color: #008080; }
        .shipping-info { margin-top: 20px; }
        .info-card { background: #ffffff; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .info-card h3 { margin-top: 0; font-size: 16px; color: #134e5e; font-weight: 700; margin-bottom: 10px; }
        .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #134e5e 0%, #71b280 100%); color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 25px; box-shadow: 0 4px 6px rgba(19, 78, 94, 0.15); transition: transform 0.2s ease; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php $headerFooter = \App\Models\HeaderFooter::first(); @endphp
            <img src="{{ $message->embed(public_path('assets/images/logo-1.png')) }}" alt="Plantsware Logo">
            <h1>Thank You for Your Order!</h1>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $order->shipping_address['name'] ?? ($order->user->name ?? 'Valued Customer') }}</strong>,</p>
            <p>Your order has been successfully placed and is now being processed. We'll send you another email when it ships!</p>

            <div class="order-summary">
                <h2>Order #{{ $order->order_number }} <span class="badge" style="float: right;">Confirmed</span></h2>
                <p style="font-size: 14px; color: #718096; margin-top: -5px; margin-bottom: 20px;">Placed on: {{ $order->created_at->format('M d, Y h:i A') }}</p>

                <table>
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Product</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @php 
                                    $itemOptions = $item->options;
                                    if(is_string($itemOptions)) $itemOptions = json_decode($itemOptions, true);
                                @endphp
                                @if(is_array($itemOptions) && count($itemOptions) > 0)
                                <div style="font-size: 12px; color: #718096; margin-top: 4px;">
                                    @foreach($itemOptions as $key => $val)
                                        {{ ucfirst($key) }}: {{ $val }}@if(!$loop->last), @endif
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right" style="padding-top: 20px; color: #718096; border-bottom: none;">Subtotal:</td>
                            <td class="text-right" style="padding-top: 20px; color: #2d3748; border-bottom: none;">₹{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        @if($order->couponUsage)
                        <tr>
                            <td colspan="2" class="text-right" style="color: #718096; border-bottom: none;">Coupon Discount ({{ $order->couponUsage->coupon->coupon_code ?? '' }}):</td>
                            <td class="text-right" style="color: #2d3748; border-bottom: none;">-₹{{ number_format($order->couponUsage->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->shipping > 0)
                        <tr>
                            <td colspan="2" class="text-right" style="color: #718096; border-bottom: none;">Shipping:</td>
                            <td class="text-right" style="color: #2d3748; border-bottom: none;">₹{{ number_format($order->shipping, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->tax > 0)
                        <tr>
                            <td colspan="2" class="text-right" style="color: #718096; border-bottom: none;">Tax:</td>
                            <td class="text-right" style="color: #2d3748; border-bottom: none;">₹{{ number_format($order->tax, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td colspan="2" class="text-right" style="padding-top: 15px;">Grand Total:</td>
                            <td class="text-right" style="padding-top: 15px;">₹{{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="shipping-info">
                <div class="info-card">
                    <h3>Shipping Address</h3>
                    <p style="margin: 0; font-size: 14px; color: #4a5568; line-height: 1.6;">
                        <strong>{{ $order->shipping_address['name'] }}</strong><br>
                        @if(!empty($order->shipping_address['door_number'])){{ $order->shipping_address['door_number'] }}, @endif{{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} - {{ $order->shipping_address['pincode'] }}<br>
                        Phone: {{ $order->shipping_address['phone'] }}
                    </p>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('user.order.invoice', $order->id) }}" class="btn">Download Invoice</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Plantsware. All rights reserved.</p>
            <p>
                If you have any questions, contact us at: 
                <a href="mailto:{{ $headerFooter->email ?? 'support@plantsware.com' }}">{{ $headerFooter->email ?? 'support@plantsware.com' }}</a>
            </p>
        </div>
    </div>
</body>
</html>
