<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #fff;
            padding: 24px 28px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #134e5e;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .brand-name   { font-size: 22px; font-weight: 700; color: #134e5e; }
        .brand-sub    { font-size: 12px; color: #64748b; margin-top: 2px; }
        .report-title { font-size: 17px; font-weight: 700; color: #1e293b; }
        .report-meta  { font-size: 12px; color: #64748b; margin-top: 3px; }

        /* Summary cards */
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 18px; }
        .summary-cell  {
            width: 33%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
        }
        .summary-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.07em; color: #64748b; font-weight: 700; }
        .summary-value { font-size: 20px; font-weight: 800; color: #134e5e; margin-top: 3px; }

        /* Data table */
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table thead tr { background-color: #134e5e; color: #ffffff; }
        table.data-table thead th {
            padding: 10px 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            text-align: left;
        }
        table.data-table thead th.right { text-align: right; }
        table.data-table tbody tr { border-bottom: 1px solid #f1f5f9; }
        table.data-table tbody tr.alt { background-color: #f8fafc; }
        table.data-table tbody td { padding: 9px 10px; vertical-align: top; }
        table.data-table tbody td.s-no   { color: #94a3b8; font-weight: 600; vertical-align: middle; }
        table.data-table tbody td.name   { font-weight: 700; color: #1e293b; vertical-align: middle; }
        table.data-table tbody td.email  { color: #475569; vertical-align: middle; }
        table.data-table tbody td.phone  { color: #475569; vertical-align: middle; }
        table.data-table tbody td.address { color: #475569; font-size: 12px; line-height: 1.5; }
        table.data-table tbody td.amount { font-weight: 700; color: #134e5e; text-align: right; vertical-align: middle; }
        table.data-table tbody td.joined { color: #64748b; vertical-align: middle; }
        table.data-table tbody td.empty  { text-align: center; color: #94a3b8; padding: 20px; }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: table;
            width: 100%;
            font-size: 11px;
            color: #94a3b8;
        }
        .footer-left  { display: table-cell; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <div class="brand-name">Plantsware</div>
            <div class="brand-sub">Customer Export Report</div>
        </div>
        <div class="header-right">
            <div class="report-title">Customer List</div>
            <div class="report-meta">Generated: {{ now()->format('d M Y, h:i A') }}</div>
            @if($search)
                <div class="report-meta">Filter: "{{ $search }}"</div>
            @endif
        </div>
    </div>

    {{-- Summary Bar --}}
    @php $totalCustomers = count($rows); @endphp
    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <div class="summary-label">Total Customers</div>
                <div class="summary-value">{{ $totalCustomers }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Active Buyers</div>
                <div class="summary-value">{{ $activeCustomers }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">Rs {{ number_format($totalRevenue, 2) }}</div>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:35px;">S.No</th>
                <th style="width:13%;">Name</th>
                <th style="width:20%;">Email</th>
                <th style="width:11%;">Phone</th>
                <th style="width:28%;">Default Address</th>
                <th class="right" style="width:12%;">Purchases</th>
                <th style="width:10%;">Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                @php $addr = $addrData[$index] ?? null; @endphp
                <tr class="{{ $index % 2 === 1 ? 'alt' : '' }}">
                    <td class="s-no">{{ $row['sno'] }}</td>
                    <td class="name">{{ $row['name'] }}</td>
                    <td class="email">{{ $row['email'] }}</td>
                    <td class="phone">{{ $row['phone'] }}</td>
                    <td class="address">
                        @if($addr)
                            <strong>{{ $addr['name'] }}</strong><br>
                            @if($addr['door_number']) {{ $addr['door_number'] }}, @endif
                            @if($addr['street']) {{ $addr['street'] }}, @endif
                            @if($addr['city']) {{ $addr['city'] }}, @endif
                            @if($addr['district']) {{ $addr['district'] }}, @endif
                            @if($addr['state']) {{ $addr['state'] }} @endif
                            @if($addr['post_code']) - {{ $addr['post_code'] }} @endif
                            @if($addr['phone_number'])<br>Ph: {{ $addr['phone_number'] }}@endif
                        @else
                            <span style="color:#94a3b8;">N/A</span>
                        @endif
                    </td>
                    <td class="amount">Rs {{ $row['purchases'] }}</td>
                    <td class="joined">{{ $row['joined'] }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="7">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-left">Plantsware Admin Panel — Confidential</div>
        <div class="footer-right">Total: {{ $totalCustomers }} customer(s) | {{ now()->format('d M Y') }}</div>
    </div>

</body>
</html>
