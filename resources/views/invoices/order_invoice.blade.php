<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            font-size: 13px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .header td {
            vertical-align: top;
        }
        .logo {
            max-width: 180px;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            text-align: right;
        }
        .invoice-details {
            text-align: right;
            margin-top: 5px;
            color: #555;
            line-height: 1.5;
        }
        .invoice-details span {
            font-weight: bold;
            color: #333;
        }
        .divider {
            border-top: 2px solid #2ea25a; /* A professional green matching "plantsware" */
            margin-bottom: 25px;
        }
        .address-box {
            width: 100%;
            margin-bottom: 30px;
        }
        .address-box td {
            vertical-align: top;
            width: 50%;
            line-height: 1.5;
        }
        .address-title {
            font-size: 14px;
            font-weight: bold;
            color: #2ea25a;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            color: #333;
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .items-table th.center {
            text-align: center;
        }
        .items-table th.right {
            text-align: right;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        .items-table td.center {
            text-align: center;
        }
        .items-table td.right {
            text-align: right;
        }
        
        /* Totals section inside items-table */
        .total-row td {
            padding: 8px 10px;
            color: #555;
            border-bottom: none;
        }
        .total-row .label {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        .total-row .value {
            text-align: right;
        }
        .grand-total td {
            font-size: 16px;
            font-weight: bold;
            color: #2ea25a;
            padding: 15px 10px;
            border-top: 2px solid #eee;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 15px;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="container">
    <table class="header">
        <tr>
            <td style="width: 50%;">
                @php
                    $logoPath = public_path('assets/images/logo-1.png');
                    if (file_exists($logoPath)) {
                        $logoData = file_get_contents($logoPath);
                        $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                    } else {
                        $logoBase64 = ''; // Fallback if image is missing
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo" alt="Logo">
                @else
                    <h2>{{ $store_name }}</h2>
                @endif
            </td>
            <td style="width: 50%;" class="invoice-details">
                <div class="invoice-title">Invoice</div>
                <div>Invoice No: <span>{{ $invoice_number }}</span></div>
                <div>Order Date: <span>{{ $order_date }}</span></div>
                <div>Payment Status: <span>{{ ucfirst($payment_status) }}</span></div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="address-box">
        <tr>
            <td>
                <div class="address-title">Billed By:</div>
                <strong>{{ $store_name }}</strong><br>
                {{ $store_address }}<br>
                {{ $store_email }}<br>
                {{ $store_phone }}
            </td>
            <td>
                <div class="address-title">Billed To:</div>
                <strong>{{ $customer_name }}</strong><br>
                @if(!empty($customer_address['door_number'])){{ $customer_address['door_number'] }}, @endif @if(!empty($customer_address['street'])){{ $customer_address['street'] }}, @endif {{ $customer_address['address'] ?? 'N/A' }}<br>
                {{ $customer_address['city'] ?? '' }} - {{ $customer_address['pincode'] ?? '' }}<br>
                {{ $customer_phone }}<br>
                {{ $customer_email }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Item Description</th>
                <th class="right" style="width: 20%;">Price</th>
                <th class="center" style="width: 15%;">Qty</th>
                <th class="right" style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order_items as $item)
                <tr>
                    <td>
                        {{ $item->product_name ?? $item->name }}
                        @if($item->options)
                            @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                            @if(is_array($options) && isset($options['size']))
                                <br><span style="font-size: 11px; color: #777;">Size: {{ $options['size'] }}</span>
                            @elseif(is_string($options) && !empty($options))
                                <br><span style="font-size: 11px; color: #777;">Size: {{ $options }}</span>
                            @endif
                        @endif
                    </td>
                    <td class="right">₹{{ number_format($item->price, 2) }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
            
            <!-- Padding row to separate items from totals -->
            <tr>
                <td colspan="4" style="border-bottom: 2px solid #ddd; padding: 5px;"></td>
            </tr>

            <!-- Totals aligning exactly underneath the headers -->
            <tr class="total-row">
                <td colspan="2" style="border: none;"></td>
                <td class="label">Subtotal:</td>
                <td class="value right">₹{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="border: none;"></td>
                <td class="label">Discount:</td>
                <td class="value right">- ₹{{ number_format($discount_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="border: none;"></td>
                <td class="label">Shipping:</td>
                <td class="value right">₹{{ number_format($tax_amount ?? 0, 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td colspan="2" style="border: none;"></td>
                <td class="label">Grand Total:</td>
                <td class="value right">₹{{ number_format($grand_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <strong>Thank you for shopping with us!</strong><br>
        If you have any questions concerning this invoice, please contact us at {{ $store_email }} or {{ $store_phone }}.
    </div>
</div>

</body>
</html>
