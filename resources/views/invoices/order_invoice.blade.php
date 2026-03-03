<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ddd;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .invoice-box table td {
            padding: 8px;
            vertical-align: top;
        }
        .invoice-box table.top td {
            padding-bottom: 20px;
        }
        .invoice-box table.info td {
            padding-bottom: 40px;
        }
        .invoice-box table.items th {
            background: #f4f4f4;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table.items td {
            border-bottom: 1px solid #eee;
        }
        .total-row td {
            font-weight: bold;
        }
        .right {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <table class="top">
        <tr>
            <td>
                <h2>INVOICE</h2>
                Invoice #: <strong>{{ $invoice_number }}</strong><br>
                Date: <strong>{{ $order_date }}</strong><br>
                Payment Status: <strong>{{ ucfirst($payment_status) }}</strong>
            </td>
            <td class="right">
                <img src="{{ $store_logo }}" style="max-width:150px;">
            </td>
        </tr>
    </table>

    <table class="info">
        <tr>
            <td>
                <strong>From:</strong><br>
                {{ $store_name }}<br>
                {{ $store_address }}<br>
                {{ $store_email }}<br>
                {{ $store_phone }}
            </td>
            <td>
                <strong>To:</strong><br>
                {{ $customer_name }}<br>
                {{ $customer_email }}<br>
                {{ $customer_phone }}<br>
                {{ $customer_address['address'] ?? 'N/A' }}, 
                {{ $customer_address['city'] ?? '' }} - {{ $customer_address['pincode'] ?? '' }}
            </td>
        </tr>
    </table>

    <table class="items">
        <tr>
            <th>Product</th>
            <th class="right">Price</th>
            <th class="right">Qty</th>
            <th class="right">Total</th>
        </tr>

        @foreach($order_items as $item)
            <tr>
                <td>{{ $item->product_name ?? $item->name }}</td>
                <td class="right">₹{{ number_format($item->price, 2) }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <table style="margin-top: 20px;">
        <tr>
            <td colspan="3" class="right">Subtotal</td>
            <td class="right">₹{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td colspan="3" class="right">Discount</td>
            <td class="right">- ₹{{ number_format($discount_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="3" class="right">Shipping</td>
            <td class="right">₹{{ number_format($tax_amount ?? 0, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="3" class="right">Grand Total</td>
            <td class="right">₹{{ number_format($grand_total, 2) }}</td>
        </tr>
    </table>

    <br><br>
    <strong>Thank you for shopping with us!</strong><br>
    If you have any questions about this invoice, please contact us at {{ $store_email }}.
</div>

</body>
</html>
