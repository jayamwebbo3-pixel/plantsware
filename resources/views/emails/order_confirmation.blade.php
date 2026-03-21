<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: 'Rubik', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background-color: #72a420; padding: 30px; text-align: center; color: #ffffff; }
        .header img {background-color: #fff; border: 1px solid #bd1313ff; border-radius: 10px; max-width: 150px; margin-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 30px; }
        .order-summary { background: #f9f9f9; padding: 20px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #eee; }
        .order-summary h2 { margin-top: 0; font-size: 18px; color: #72a420; border-bottom: 2px solid #72a420; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #666; font-size: 14px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 15px; }
        .text-right { text-align: right; }
        .total-row td { font-weight: 700; font-size: 18px; color: #333; border-top: 2px solid #eee; border-bottom: none; }
        .footer { background: #333; color: #999; text-align: center; padding: 20px; font-size: 12px; }
        .footer a { color: #72a420; text-decoration: none; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #e8f5e9; color: #2e7d32; }
        .shipping-info { margin-top: 20px; display: grid; gap: 15px; }
        .info-card { background: #fff; padding: 15px; border: 1px solid #f0f0f0; border-radius: 6px; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #72a420; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: 600; margin-top: 20px; box-shadow: 0 4px 6px rgba(114,164,32,0.2); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php $headerFooter = \App\Models\HeaderFooter::first(); @endphp
            <img src="{{ $message->embed(public_path('assets/images/logo-1.png')) }}" alt="Plantly Logo">
            <h1>Thank You for Your Order!</h1>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $order->shipping_address['name'] ?? ($order->user->name ?? 'Valued Customer') }}</strong>,</p>
            <p>Your order has been successfully placed and is now being processed. We'll send you another email when it ships!</p>

            <div class="order-summary">
                <h2>Order #{{ $order->order_number }} <span class="badge" style="float: right;">Confirmed</span></h2>
                <p style="font-size: 14px; color: #888;">Placed on: {{ $order->created_at->format('M d, Y h:i A') }}</p>

                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @php 
                                    $itemOptions = $item->options;
                                    if(is_string($itemOptions)) $itemOptions = json_decode($itemOptions, true);
                                @endphp
                                @if(is_array($itemOptions) && count($itemOptions) > 0)
                                <div style="font-size: 12px; color: #777;">
                                    @foreach($itemOptions as $key => $val)
                                        {{ ucfirst($key) }}: {{ $val }}
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
                            <td colspan="2" class="text-right" style="padding-top: 20px; color: #999;">Subtotal:</td>
                            <td class="text-right" style="padding-top: 20px; color: #333;">₹{{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        @if($order->shipping > 0)
                        <tr>
                            <td colspan="2" class="text-right" style="color: #999;">Shipping:</td>
                            <td class="text-right" style="color: #333;">₹{{ number_format($order->shipping, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->tax > 0)
                        <tr>
                            <td colspan="2" class="text-right" style="color: #999;">Tax:</td>
                            <td class="text-right" style="color: #333;">₹{{ number_format($order->tax, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td colspan="2" class="text-right">Grand Total:</td>
                            <td class="text-right">₹{{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="shipping-info">
                <div class="info-card">
                    <h3 style="margin-top: 0; font-size: 16px; color: #333;">Shipping Address</h3>
                    <p style="margin: 0; font-size: 14px; color: #666;">
                        {{ $order->shipping_address['name'] }}<br>
                        {{ $order->shipping_address['address'] }}<br>
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
            <p>&copy; {{ date('Y') }} Plantly. All rights reserved.</p>
            <p>
                If you have any questions, contact us at: 
                <a href="mailto:{{ $headerFooter->email ?? 'support@plantly.com' }}">{{ $headerFooter->email ?? 'support@plantly.com' }}</a>
            </p>
        </div>
    </div>
</body>
</html>
