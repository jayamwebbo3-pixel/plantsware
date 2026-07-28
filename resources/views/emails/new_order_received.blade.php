<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Received</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #2d3748; margin: 0; padding: 0; background-color: #f7fafc; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #134e5e 0%, #117864 100%); padding: 40px 30px; text-align: center; color: #ffffff; }
        .header img { background-color: #ffffff; padding: 6px; border-radius: 8px; max-width: 150px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
        .content { padding: 35px 30px; }
        
        .alert-banner { background-color: #ebf8ff; border-left: 4px solid #3182ce; padding: 15px; border-radius: 6px; margin-bottom: 25px; font-size: 15px; color: #2b6cb0; }
        
        .section-title { font-size: 18px; color: #134e5e; font-weight: 700; margin-top: 25px; margin-bottom: 15px; border-bottom: 2px solid #edf2f7; padding-bottom: 8px; }
        
        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-row { display: table-row; }
        .info-cell-label { display: table-cell; padding: 8px 10px 8px 0; font-weight: 600; color: #4a5568; width: 35%; border-bottom: 1px solid #edf2f7; }
        .info-cell-value { display: table-cell; padding: 8px 0; color: #2d3748; border-bottom: 1px solid #edf2f7; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; color: #718096; font-size: 13px; text-transform: uppercase; font-weight: 600; }
        td { padding: 12px; border-bottom: 1px solid #edf2f7; font-size: 15px; color: #4a5568; }
        .text-right { text-align: right; }
        .total-row td { font-weight: 700; font-size: 18px; color: #1a202c; border-top: 2px solid #e2e8f0; border-bottom: none; }
        
        .footer { background: #1a202c; color: #a0aec0; text-align: center; padding: 30px 20px; font-size: 13px; }
        .footer p { margin: 0; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #e6fffa; color: #008080; text-transform: uppercase; }
        .badge-payment { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #ebf8ff; color: #2b6cb0; text-transform: uppercase; }
        
        .btn { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #134e5e 0%, #117864 100%); color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 25px; box-shadow: 0 4px 6px rgba(19, 78, 94, 0.15); text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php $headerFooter = \App\Models\HeaderFooter::first(); @endphp
            <img src="{{ $message->embed(public_path('assets/images/logo-1.png')) }}" alt="Plantsware Logo">
            <h1>New Order Received!</h1>
        </div>

        <div class="content">
            <div class="alert-banner">
                A new order has been successfully placed on Plantsware and is ready for fulfillment.
            </div>

            <div class="section-title">Order Overview</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell-label">Order Number</div>
                    <div class="info-cell-value"><strong>#{{ $order->order_number }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Date & Time</div>
                    <div class="info-cell-value">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Order Status</div>
                    <div class="info-cell-value"><span class="badge">Confirmed</span></div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Payment Status</div>
                    <div class="info-cell-value"><span class="badge-payment">Paid</span></div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Payment Method</div>
                    <div class="info-cell-value">{{ ucfirst($order->payment_method) }}</div>
                </div>
            </div>

            <div class="section-title">Customer Details</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-cell-label">Customer Name</div>
                    <div class="info-cell-value">{{ $order->shipping_address['name'] ?? ($order->user->name ?? 'Guest') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Customer Email</div>
                    <div class="info-cell-value">{{ $order->user->email ?? 'Guest' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Customer Phone</div>
                    <div class="info-cell-value">{{ $order->shipping_address['phone'] ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-cell-label">Shipping Address</div>
                    <div class="info-cell-value" style="line-height: 1.5;">
                        @if(!empty($order->shipping_address['door_number'])){{ $order->shipping_address['door_number'] }}, @endif{{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} - {{ $order->shipping_address['pincode'] }}
                    </div>
                </div>
            </div>

            <div class="section-title">Order Items</div>
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
                        <td colspan="3" class="text-right" style="padding-top: 20px; color: #718096; border-bottom: none;">Subtotal:</td>
                        <td class="text-right" style="padding-top: 20px; color: #2d3748; border-bottom: none;">₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->couponUsage)
                    <tr>
                        <td colspan="3" class="text-right" style="color: #718096; border-bottom: none;">Coupon Discount ({{ $order->couponUsage->coupon->coupon_code ?? '' }}):</td>
                        <td class="text-right" style="color: #2d3748; border-bottom: none;">-₹{{ number_format($order->couponUsage->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping > 0)
                    <tr>
                        <td colspan="3" class="text-right" style="color: #718096; border-bottom: none;">Shipping:</td>
                        <td class="text-right" style="color: #2d3748; border-bottom: none;">₹{{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->tax > 0)
                    <tr>
                        <td colspan="3" class="text-right" style="color: #718096; border-bottom: none;">Tax:</td>
                        <td class="text-right" style="color: #2d3748; border-bottom: none;">₹{{ number_format($order->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="text-right" style="padding-top: 15px;">Grand Total:</td>
                        <td class="text-right" style="padding-top: 15px;">₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div style="text-align: center;">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">View Order in Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Plantsware Admin. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
