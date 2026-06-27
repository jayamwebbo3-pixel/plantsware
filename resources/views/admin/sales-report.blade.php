@extends('admin.layout')

@section('title', 'Sales Report')

@push('styles')
<style>
    /* ── Root tokens — Project palette ──────────── */
    :root {
        --sr-primary:   #134e5e;   /* sidebar dark teal */
        --sr-accent:    #71b280;   /* sidebar light green */
        --sr-forest:    #1a5c2a;   /* forest green — Final Income card */
        --sr-bg:        #f4f7f6;
        --sr-white:     #ffffff;
        --sr-border:    #dde3e8;
        --sr-muted:     #6b7a8d;
        --sr-dark:      #1a2332;
    }

    body { background: var(--sr-bg); }

    /* ── Page header ─────────────────────────────── */
    .sr-page-header {
        background: linear-gradient(135deg, var(--sr-primary) 0%, var(--sr-accent) 100%);
        border-radius: 18px;
        padding: 28px 32px;
        margin-bottom: 28px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 8px 24px rgba(19,78,94,.25);
    }

    .sr-page-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -.3px;
    }

    .sr-page-header p {
        font-size: .88rem;
        opacity: .8;
        margin: 4px 0 0;
    }

    /* ── Filter bar ──────────────────────────────── */
    .sr-filter-bar {
        background: var(--sr-white);
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .sr-filter-label {
        font-size: .78rem;
        font-weight: 600;
        color: var(--sr-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
    }

    .sr-filter-select {
        appearance: none;
        -webkit-appearance: none;
        background: #eef6f2 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23134e5e' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
        border: 1.5px solid #71b280;
        border-radius: 10px;
        padding: 9px 36px 9px 14px;
        font-size: .88rem;
        font-weight: 500;
        color: var(--sr-dark);
        cursor: pointer;
        transition: border-color .2s;
        min-width: 160px;
    }

    .sr-filter-select:focus {
        border-color: var(--sr-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(113,178,128,.2);
    }

    .sr-date-input {
        border: 1.5px solid #71b280;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: .88rem;
        color: var(--sr-dark);
        background: #eef6f2;
        transition: border-color .2s;
    }

    .sr-date-input:focus { border-color: var(--sr-primary); outline: none; box-shadow: 0 0 0 3px rgba(113,178,128,.2); }

    .sr-custom-range {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .sr-custom-range span {
        font-size: .82rem;
        color: var(--sr-muted);
    }

    /* Download buttons — project teal/green */
    .btn-excel {
        background: var(--sr-accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: .85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 3px 10px rgba(113,178,128,.35);
    }
    .btn-excel:hover { background: #5ea06d; transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(113,178,128,.45); }

    .btn-pdf {
        background: var(--sr-primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: .85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 3px 10px rgba(19,78,94,.3);
    }
    .btn-pdf:hover { background: #0d3d4d; transform: translateY(-1px); color: #fff; box-shadow: 0 6px 16px rgba(19,78,94,.4); }

    /* ── Summary cards ───────────────────────────── */
    .sr-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .sr-card {
        background: var(--sr-white);
        border-radius: 16px;
        padding: 22px 24px;
        border: 1.5px solid var(--sr-border);
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
        transition: transform .2s, box-shadow .2s;
    }

    .sr-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,.07);
    }

    .sr-card-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--sr-muted);
        margin-bottom: 10px;
    }

    .sr-card-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--sr-dark);
        line-height: 1;
    }

    .sr-card-value.text-primary  { color: var(--sr-primary) !important; }
    .sr-card-value.text-danger   { color: #c0392b !important; }
    .sr-card-value.text-success  { color: var(--sr-accent) !important; }

    /* Final income special card — forest green */
    .sr-card-final {
        background: linear-gradient(135deg, var(--sr-forest) 0%, #2d8a45 100%);
        border-color: transparent;
        grid-column: span 2;
        box-shadow: 0 8px 28px rgba(26,92,42,.35);
    }

    @media (max-width: 768px) {
        .sr-card-final { grid-column: span 1; }
    }

    .sr-card-final .sr-card-label { color: rgba(255,255,255,.85); font-size: .78rem; letter-spacing: .1em; }
    .sr-card-final .sr-card-value { color: #ffffff; font-size: 2.4rem; text-shadow: 0 2px 12px rgba(0,0,0,.2); }

    /* ── Table ───────────────────────────────────── */
    .sr-table-wrapper {
        background: var(--sr-white);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
    }

    .sr-table-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--sr-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .sr-table-title-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sr-table-title span {
        background: var(--sr-primary);
        color: #fff;
        font-size: .7rem;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .sr-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .sr-table thead tr {
        background: var(--sr-primary);
    }

    .sr-table thead th {
        padding: 12px 16px;
        font-size: .73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: rgba(255,255,255,.9);
        border-bottom: 2px solid rgba(255,255,255,.1);
        white-space: nowrap;
    }

    .sr-table tbody tr {
        border-bottom: 1px solid #f0f3f5;
        transition: background .15s;
    }

    .sr-table tbody tr:hover {
        background: #eef6f2;
    }

    .sr-table tbody tr:last-child {
        border-bottom: none;
    }

    .sr-table td {
        padding: 14px 16px;
        font-size: .88rem;
        color: var(--sr-dark);
        vertical-align: middle;
    }

    .sr-table td .sub-label {
        font-size: .75rem;
        color: var(--sr-muted);
        margin-top: 2px;
    }

    .sr-table .col-right { text-align: right; }
    .sr-table .col-center { text-align: center; }

    .sr-table tfoot tr {
        background: linear-gradient(135deg, var(--sr-primary) 0%, #1c6b5a 100%);
    }

    .sr-table tfoot td {
        padding: 14px 16px;
        font-size: .88rem;
        font-weight: 700;
        color: #fff;
    }

    /* Empty state */
    .sr-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--sr-muted);
    }

    .sr-empty i {
        font-size: 3rem;
        margin-bottom: 16px;
        display: block;
        opacity: .4;
    }

    /* Badge for filter */
    .filter-badge {
        background: rgba(255,255,255,.2);
        color: #fff;
        font-size: .78rem;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,.35);
    }

    /* Apply btn */
    .btn-apply {
        background: var(--sr-primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 22px;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s;
        white-space: nowrap;
    }
    .btn-apply:hover { background: #0d3d4d; transform: translateY(-1px); }

    /* ── Pagination ───────────────────────────── */
    .sr-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1.5px solid var(--sr-border);
    }

    .sr-pagination-info {
        font-size: .82rem;
        color: var(--sr-muted);
        font-weight: 500;
    }

    .sr-pagination-info strong {
        color: var(--sr-primary);
    }

    .sr-page-controls {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .sr-page-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1.5px solid var(--sr-border);
        background: var(--sr-white);
        color: var(--sr-dark);
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .18s;
        text-decoration: none;
    }

    .sr-page-btn:hover {
        border-color: var(--sr-accent);
        background: #eef6f2;
        color: var(--sr-primary);
    }

    .sr-page-btn.active {
        background: var(--sr-primary);
        border-color: var(--sr-primary);
        color: #fff;
        box-shadow: 0 3px 10px rgba(19,78,94,.3);
    }

    .sr-page-btn:disabled,
    .sr-page-btn.disabled {
        opacity: .4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .sr-page-btn.nav-btn {
        width: auto;
        padding: 0 12px;
        gap: 5px;
        font-size: .8rem;
    }

    .sr-perpage {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .82rem;
        color: var(--sr-muted);
    }

    .sr-perpage select {
        border: 1.5px solid #71b280;
        border-radius: 8px;
        padding: 5px 26px 5px 10px;
        font-size: .82rem;
        background: #eef6f2 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%23134e5e' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 8px center;
        appearance: none;
        -webkit-appearance: none;
        color: var(--sr-dark);
        cursor: pointer;
    }

    .sr-perpage select:focus { outline: none; border-color: var(--sr-primary); }
</style>
@endpush

@section('content')

{{-- ── Page Header ─────────────────────────────────── --}}
<div class="sr-page-header">
    <div>
        <h1><i class="fas fa-chart-bar me-2"></i> Sales Analytics</h1>
        <p>Monitor sales performance, taxes, and product metrics.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="filter-badge"><i class="fas fa-calendar-alt me-1"></i>{{ $filter_label }}</span>
    </div>
</div>

{{-- ── Filter Bar ───────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.sales-report.index') }}" id="filterForm">
<div class="sr-filter-bar">
    <span class="sr-filter-label"><i class="fas fa-filter me-1"></i> Filter</span>

    <select name="filter" id="filterSelect" class="sr-filter-select" onchange="toggleCustomRange(this.value); this.form.submit();">
        <option value="today"        {{ $filter==='today'        ? 'selected' : '' }}>Today</option>
        <option value="weekly"       {{ $filter==='weekly'       ? 'selected' : '' }}>Weekly</option>
        <option value="monthly"      {{ $filter==='monthly'      ? 'selected' : '' }}>Monthly</option>
        <option value="yearly"       {{ $filter==='yearly'       ? 'selected' : '' }}>Yearly</option>
        <option value="custom"       {{ $filter==='custom'       ? 'selected' : '' }}>Custom Date Range</option>
        <option value="product_wise" {{ $filter==='product_wise' ? 'selected' : '' }}>Product Wise</option>
    </select>

    <div class="sr-custom-range" id="customRangeDiv" style="{{ $filter==='custom' ? '' : 'display:none;' }}">
        <input type="date" name="date_from" id="dateFrom" class="sr-date-input"
               value="{{ $date_from }}" max="{{ date('Y-m-d') }}">
        <span>to</span>
        <input type="date" name="date_to" id="dateTo" class="sr-date-input"
               value="{{ $date_to }}" max="{{ date('Y-m-d') }}">
        <button type="submit" class="btn-apply">Apply</button>
    </div>

    <div class="ms-auto d-flex gap-2 flex-wrap">
        <button type="button" class="btn-excel" onclick="downloadExcel()">
            <i class="fas fa-file-excel"></i> Excel
        </button>
        <button type="button" class="btn-pdf" onclick="downloadPdf()">
            <i class="fas fa-file-pdf"></i> PDF
        </button>
    </div>
</div>
</form>

{{-- ── Summary Cards ────────────────────────────────── --}}
<div class="sr-cards-grid" id="summaryCards">
    {{-- Total Orders --}}
    <div class="sr-card">
        <div class="sr-card-label">Total Orders</div>
        <div class="sr-card-value">{{ number_format($total_orders) }}</div>
    </div>

    {{-- Gross Sales --}}
    <div class="sr-card">
        <div class="sr-card-label">Gross Sales</div>
        <div class="sr-card-value text-primary">₹{{ number_format($gross_sales, 2) }}</div>
    </div>

    {{-- GST Collected --}}
    <div class="sr-card">
        <div class="sr-card-label">GST Collected</div>
        <div class="sr-card-value" style="color:var(--sr-accent);">₹{{ number_format($gst_collected, 2) }}</div>
    </div>

    {{-- Net Sales --}}
    <div class="sr-card">
        <div class="sr-card-label">Net Sales</div>
        <div class="sr-card-value text-primary">₹{{ number_format($net_sales, 2) }}</div>
    </div>

    {{-- Cancelled Orders --}}
    <div class="sr-card">
        <div class="sr-card-label">Cancelled Orders</div>
        <div class="sr-card-value text-danger">{{ number_format($cancelled_orders) }}</div>
    </div>

    {{-- Cancelled Value --}}
    <div class="sr-card">
        <div class="sr-card-label">Cancelled Value</div>
        <div class="sr-card-value text-danger">₹{{ number_format($cancelled_value, 2) }}</div>
    </div>

    {{-- Final Income (wide card) --}}
    <div class="sr-card sr-card-final">
        <div class="sr-card-label">FINAL INCOME</div>
        <div class="sr-card-value">₹{{ number_format($final_income, 2) }}</div>
    </div>
</div>

{{-- ── Detailed Table ───────────────────────────────── --}}
<div class="sr-table-wrapper" id="salesTable">
    <div class="sr-table-title">
        <div class="sr-table-title-left">
            {{ $filter === 'product_wise' ? 'Product Wise Sales Report' : ($filter_label . ' Sales Report') }}
            <span>{{ count($table_rows) }} rows</span>
        </div>
        @if(count($table_rows) > 0)
        <div class="sr-perpage">
            <span>Rows per page:</span>
            <select id="perPageSelect" onchange="changePerPage(this.value)">
                <option value="10" selected>10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="9999">All</option>
            </select>
        </div>
        @endif
    </div>

    @if(count($table_rows) > 0)
    <div class="table-responsive">
        <table class="sr-table" id="reportTable">
            <thead>
                <tr>
                    @if($filter === 'product_wise')
                        <th>Product Name</th>
                        <th class="col-center">Qty Sold</th>
                    @else
                        <th>Period</th>
                        <th class="col-center">Orders</th>
                    @endif
                    <th class="col-right">Gross Sales</th>
                    <th class="col-right">GST</th>
                    <th class="col-right">Net Sales</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalOrders = 0;
                    $totalGross  = 0;
                    $totalGst    = 0;
                    $totalNet    = 0;
                @endphp
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
                            <div class="sub-label">{{ $row['sub_label'] }}</div>
                        @endif
                    </td>
                    <td class="col-center">{{ number_format($row['orders']) }}</td>
                    <td class="col-right">₹{{ number_format($row['gross'], 2) }}</td>
                    <td class="col-right">₹{{ number_format($row['gst'], 2) }}</td>
                    <td class="col-right">₹{{ number_format($row['net'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>TOTAL</strong></td>
                    <td class="col-center"><strong>{{ number_format($totalOrders) }}</strong></td>
                    <td class="col-right"><strong>₹{{ number_format($totalGross, 2) }}</strong></td>
                    <td class="col-right"><strong>₹{{ number_format($totalGst, 2) }}</strong></td>
                    <td class="col-right"><strong>₹{{ number_format($totalNet, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    {{-- ── Pagination bar (bottom: info + page numbers only) ── --}}
    <div class="sr-pagination" id="paginationBar">
        <div class="sr-pagination-info" id="paginationInfo">Showing all rows</div>
        <div class="sr-page-controls" id="pageControls"></div>
    </div>
    @else
    <div class="sr-empty">
        <i class="fas fa-inbox"></i>
        <h5>No data for this period</h5>
        <p class="text-muted">Try changing the filter or date range.</p>
    </div>
    @endif
</div>

{{-- Hidden form for PDF --}}
<form id="pdfForm" method="GET" action="{{ route('admin.sales-report.pdf') }}" style="display:none;">
    <input type="hidden" name="filter"    value="{{ $filter }}">
    <input type="hidden" name="date_from" value="{{ $date_from }}">
    <input type="hidden" name="date_to"   value="{{ $date_to }}">
</form>

@push('scripts')
{{-- SheetJS for Excel export --}}
<script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>
<script>
    // ── Toggle custom date range inputs ──
    function toggleCustomRange(val) {
        document.getElementById('customRangeDiv').style.display = (val === 'custom') ? 'flex' : 'none';
    }

    // ── Download Excel ──  table-only, dynamic filename ──
    function downloadExcel() {
        const table = document.getElementById('reportTable');
        if (!table) { alert('No data to export.'); return; }

        const wb  = XLSX.utils.book_new();

        // Build header row manually (clean, no ₹)
        const filterType = '{{ $filter }}';
        const col1Header = (filterType === 'product_wise') ? 'Product Name' : 'Period';
        const col2Header = (filterType === 'product_wise') ? 'Qty Sold'     : 'Orders';

        // Title rows at top
        const titleRows = [
            ['Plantsware - {{ $filter_label }} Sales Report'],
            ['Generated: ' + new Date().toLocaleString()],
            [], // blank row
            [col1Header, col2Header, 'Gross Sales (Rs.)', 'GST (Rs.)', 'Net Sales (Rs.)'],
        ];

        // Data rows from PHP (clean numbers, no ₹ sign)
        @php
            $excelRows = [];
            foreach ($table_rows as $r) {
                $label = $r['label'];
                if ($r['sub_label']) $label .= ' (' . $r['sub_label'] . ')';
                $excelRows[] = [
                    $label,
                    (int)$r['orders'],
                    round((float)$r['gross'], 2),
                    round((float)$r['gst'],   2),
                    round((float)$r['net'],    2),
                ];
            }
            // Totals row
            $tOrders = array_sum(array_column($table_rows, 'orders'));
            $tGross  = array_sum(array_column($table_rows, 'gross'));
            $tGst    = array_sum(array_column($table_rows, 'gst'));
            $tNet    = array_sum(array_column($table_rows, 'net'));
        @endphp

        const dataRows = {!! json_encode($excelRows) !!};
        const totalRow = ['TOTAL', {{ $tOrders }}, {{ round($tGross, 2) }}, {{ round($tGst, 2) }}, {{ round($tNet, 2) }}];

        // Combine all rows
        const allRows = [...titleRows, ...dataRows, totalRow];

        const ws = XLSX.utils.aoa_to_sheet(allRows);

        // Set column widths
        ws['!cols'] = [
            { wch: 30 },  // Period/Product
            { wch: 12 },  // Orders/Qty
            { wch: 18 },  // Gross Sales
            { wch: 14 },  // GST
            { wch: 18 },  // Net Sales
        ];

        XLSX.utils.book_append_sheet(wb, ws, '{{ $filter_label }}');

        // Dynamic filename: Plantsware_Monthly_Sales_Report.xlsx
        const labelSlug = '{{ str_replace(" ", "_", $filter_label) }}';
        XLSX.writeFile(wb, 'Plantsware_' + labelSlug + '_Sales_Report.xlsx');
    }

    // ── Download PDF (server-side) ──
    function downloadPdf() {
        document.getElementById('pdfForm').submit();
    }

    // ══════════════════════════════════════════════════
    // ── Table Pagination ──────────────────────────────
    // ══════════════════════════════════════════════════
    (function () {
        const tbody      = document.querySelector('#reportTable tbody');
        const infoEl     = document.getElementById('paginationInfo');
        const controlsEl = document.getElementById('pageControls');

        if (!tbody || !infoEl || !controlsEl) return; // no table (empty state)

        let allRows  = Array.from(tbody.querySelectorAll('tr'));
        let perPage  = 10;
        let curPage  = 1;

        function totalPages() {
            return Math.max(1, Math.ceil(allRows.length / perPage));
        }

        function renderRows() {
            const start = (curPage - 1) * perPage;
            const end   = Math.min(start + perPage, allRows.length);

            allRows.forEach(function (row, idx) {
                row.style.display = (idx >= start && idx < end) ? '' : 'none';
            });

            // Info label
            if (perPage >= 9999 || allRows.length <= perPage) {
                infoEl.innerHTML = 'Showing <strong>all ' + allRows.length + '</strong> rows';
            } else {
                infoEl.innerHTML =
                    'Showing <strong>' + (start + 1) + '–' + end + '</strong> of <strong>' + allRows.length + '</strong> rows';
            }

            renderControls();
        }

        function renderControls() {
            controlsEl.innerHTML = '';
            const tp = totalPages();

            if (tp <= 1) return; // no controls needed for 1 page

            // ← Prev button
            const prevBtn = makeBtn('&#8592; Prev', curPage === 1);
            prevBtn.classList.add('nav-btn');
            prevBtn.addEventListener('click', function () { goTo(curPage - 1); });
            controlsEl.appendChild(prevBtn);

            // Page number buttons — smart window around current page
            const window_size = 5;
            let startP = Math.max(1, curPage - Math.floor(window_size / 2));
            let endP   = Math.min(tp, startP + window_size - 1);
            if (endP - startP < window_size - 1) startP = Math.max(1, endP - window_size + 1);

            if (startP > 1) {
                controlsEl.appendChild(makePageBtn(1));
                if (startP > 2) controlsEl.appendChild(makeEllipsis());
            }

            for (let p = startP; p <= endP; p++) {
                controlsEl.appendChild(makePageBtn(p));
            }

            if (endP < tp) {
                if (endP < tp - 1) controlsEl.appendChild(makeEllipsis());
                controlsEl.appendChild(makePageBtn(tp));
            }

            // Next → button
            const nextBtn = makeBtn('Next &#8594;', curPage === tp);
            nextBtn.classList.add('nav-btn');
            nextBtn.addEventListener('click', function () { goTo(curPage + 1); });
            controlsEl.appendChild(nextBtn);
        }

        function makePageBtn(p) {
            const btn = document.createElement('button');
            btn.className = 'sr-page-btn' + (p === curPage ? ' active' : '');
            btn.innerHTML = p;
            btn.addEventListener('click', function () { goTo(p); });
            return btn;
        }

        function makeBtn(html, disabled) {
            const btn = document.createElement('button');
            btn.className = 'sr-page-btn' + (disabled ? ' disabled' : '');
            btn.innerHTML = html;
            if (disabled) btn.setAttribute('disabled', 'disabled');
            return btn;
        }

        function makeEllipsis() {
            const span = document.createElement('span');
            span.style.cssText = 'padding:0 4px;color:var(--sr-muted);font-size:.82rem;';
            span.textContent = '…';
            return span;
        }

        function goTo(p) {
            const tp = totalPages();
            curPage  = Math.max(1, Math.min(tp, p));
            renderRows();
            // Smooth scroll to table
            document.getElementById('salesTable').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Rows-per-page change
        window.changePerPage = function (val) {
            perPage = parseInt(val);
            curPage = 1;
            renderRows();
        };

        // Initial render
        renderRows();
    })();
</script>
@endpush
@endsection
