<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Plantsware - {{ $filter_label }} Sales Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 11px;
        color: #1a2332;
        background: #ffffff;
        padding: 30px 28px;
    }

    /* ── Top brand strip ── */
    .brand-strip {
        border-bottom: 3px solid #134e5e;
        padding-bottom: 14px;
        margin-bottom: 20px;
    }

    .brand-name {
        font-size: 20px;
        font-weight: 700;
        color: #134e5e;
        letter-spacing: -0.4px;
    }

    .report-title {
        font-size: 13px;
        font-weight: 700;
        color: #1a2332;
        margin-top: 4px;
    }

    .report-meta {
        font-size: 9px;
        color: #6b7a8d;
        margin-top: 3px;
    }

    .report-meta span { margin-right: 16px; }

    /* ── Table ── */
    table.sr-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
    }

    table.sr-table thead tr {
        background-color: #134e5e;
    }

    table.sr-table thead th {
        padding: 10px 14px;
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #ffffff;
        border: 1px solid #0f3d4a;
    }

    table.sr-table thead th.right  { text-align: right; }
    table.sr-table thead th.center { text-align: center; }

    table.sr-table tbody tr:nth-child(even) { background-color: #eef6f2; }
    table.sr-table tbody tr:nth-child(odd)  { background-color: #ffffff; }

    table.sr-table tbody td {
        padding: 9px 14px;
        font-size: 10px;
        color: #1a2332;
        border: 1px solid #dde3e8;
        vertical-align: middle;
    }

    table.sr-table tbody td.right  { text-align: right; }
    table.sr-table tbody td.center { text-align: center; }
    table.sr-table tbody td .sub-lbl { font-size: 8.5px; color: #6b7a8d; margin-top: 2px; }

    table.sr-table tfoot tr {
        background-color: #134e5e;
    }

    table.sr-table tfoot td {
        padding: 10px 14px;
        font-size: 10px;
        font-weight: 700;
        color: #ffffff;
        border: 1px solid #134e5e;
    }

    table.sr-table tfoot td.right  { text-align: right; }
    table.sr-table tfoot td.center { text-align: center; }

    /* ── Footer ── */
    .pdf-footer {
        margin-top: 18px;
        padding-top: 10px;
        border-top: 1px solid #dde3e8;
        font-size: 8.5px;
        color: #9aa6b2;
        display: table;
        width: 100%;
    }

    .pdf-footer .left  { display: table-cell; text-align: left; }
    .pdf-footer .right { display: table-cell; text-align: right; }

    .empty-msg {
        text-align: center;
        padding: 40px;
        color: #6b7a8d;
        font-style: italic;
        font-size: 11px;
    }
</style>
</head>
<body>

{{-- ── Brand + Title Header ── --}}
<div class="brand-strip">
    <div class="brand-name">Plantsware</div>
    <div class="report-title">
        {{ $filter === 'product_wise' ? 'Product Wise Sales Report' : $filter_label . ' Sales Report' }}
    </div>
    <div class="report-meta">
        <span>Filter: {{ $filter_label }}</span>
        @if($date_from && $date_to)
        <span>Period: {{ $date_from }} &ndash; {{ $date_to }}</span>
        @endif
        <span>Generated: {{ $generated_at }}</span>
    </div>
</div>

{{-- ── Table Only ── --}}
@if(count($table_rows) > 0)
@php
    $totalOrders = 0;
    $totalGross  = 0;
    $totalGst    = 0;
    $totalNet    = 0;
@endphp

<table class="sr-table">
    <thead>
        <tr>
            @if($filter === 'product_wise')
                <th>Product Name</th>
                <th class="center">Qty Sold</th>
            @else
                <th>Period</th>
                <th class="center">Orders</th>
            @endif
            <th class="right">Gross Sales (Rs.)</th>
            <th class="right">GST (Rs.)</th>
            <th class="right">Net Sales (Rs.)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($table_rows as $row)
        @php
            $totalOrders += $row['orders'];
            $totalGross  += $row['gross'];
            $totalGst    += $row['gst'];
            $totalNet    += $row['net'];
        @endphp
        <tr>
            <td>
                {{ $row['label'] }}
                @if($row['sub_label'])
                    <div class="sub-lbl">{{ $row['sub_label'] }}</div>
                @endif
            </td>
            <td class="center">{{ number_format($row['orders']) }}</td>
            <td class="right">{{ number_format($row['gross'], 2) }}</td>
            <td class="right">{{ number_format($row['gst'], 2) }}</td>
            <td class="right">{{ number_format($row['net'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="center"><strong>{{ number_format($totalOrders) }}</strong></td>
            <td class="right"><strong>{{ number_format($totalGross, 2) }}</strong></td>
            <td class="right"><strong>{{ number_format($totalGst, 2) }}</strong></td>
            <td class="right"><strong>{{ number_format($totalNet, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
@else
<div class="empty-msg">No data available for this period.</div>
@endif

{{-- ── Footer ── --}}
<div class="pdf-footer">
    <div class="left">Plantsware &copy; {{ date('Y') }} &nbsp;|&nbsp; All amounts in Indian Rupees (INR)</div>
    <div class="right">Generated on {{ $generated_at }}</div>
</div>

</body>
</html>
