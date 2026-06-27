<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    /**
     * Show the Sales Report page.
     */
    public function index(Request $request)
    {
        $filter   = $request->get('filter', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        [$from, $to] = $this->resolveDateRange($filter, $dateFrom, $dateTo);

        $reportData = $this->buildReportData($filter, $from, $to);

        return view('admin.sales-report', array_merge($reportData, [
            'filter'    => $filter,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]));
    }

    /**
     * Download PDF sales report.
     */
    public function downloadPdf(Request $request)
    {
        $filter   = $request->get('filter', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        [$from, $to] = $this->resolveDateRange($filter, $dateFrom, $dateTo);

        $reportData = $this->buildReportData($filter, $from, $to);

        // Dynamic filename: e.g. "Plantsware_Monthly_Sales_Report.pdf"
        $labelSlug = str_replace(' ', '_', $reportData['filter_label']);
        $filename  = 'Plantsware_' . $labelSlug . '_Sales_Report.pdf';

        $pdf = Pdf::loadView('admin.sales-report-pdf', array_merge($reportData, [
            'filter'       => $filter,
            'date_from'    => $from ? $from->format('d M Y') : ($dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : null),
            'date_to'      => $to   ? $to->format('d M Y')   : ($dateTo   ? \Carbon\Carbon::parse($dateTo)->format('d M Y')   : null),
            'generated_at' => now()->format('d M Y, h:i A'),
        ]))->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Return JSON data for AJAX/Excel export.
     */
    public function exportData(Request $request)
    {
        $filter   = $request->get('filter', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        [$from, $to] = $this->resolveDateRange($filter, $dateFrom, $dateTo);

        $reportData = $this->buildReportData($filter, $from, $to);

        return response()->json($reportData);
    }

    // -------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------

    private function resolveDateRange(string $filter, ?string $dateFrom, ?string $dateTo): array
    {
        switch ($filter) {
            case 'today':
                return [Carbon::today(), Carbon::today()->endOfDay()];

            case 'weekly':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];

            case 'yearly':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];

            case 'custom':
                $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : null;
                $to   = $dateTo   ? Carbon::parse($dateTo)->endOfDay()     : null;
                return [$from, $to];

            case 'product_wise':
                return [null, null]; // No date filter needed

            case 'monthly':
            default:
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        }
    }

    private function buildReportData(string $filter, $from, $to): array
    {
        // Base query builder
        $baseQuery = function () use ($from, $to, $filter) {
            $q = Order::query();
            if ($from && $to && $filter !== 'product_wise') {
                $q->whereBetween('created_at', [$from, $to]);
            }
            return $q;
        };

        // All orders in period
        $allOrders      = $baseQuery()->get();
        $cancelledOrders = $allOrders->where('status', 'cancelled');
        $activeOrders    = $allOrders->where('status', '!=', 'cancelled');

        // Gross sales (active orders total)
        $grossSales = $activeOrders->sum('total');

        // GST collected from per-order GST fields
        $gstCollected = $activeOrders->sum(function ($order) {
            return (float)($order->cgst ?? 0) + (float)($order->sgst ?? 0) + (float)($order->igst ?? 0);
        });

        // Net sales = Gross - GST
        $netSales = $grossSales - $gstCollected;

        // Cancelled value
        $cancelledValue = $cancelledOrders->sum('total');

        // Final income = net sales - cancelled value
        $finalIncome = $netSales - $cancelledValue;

        // Table rows depend on filter type
        $tableRows = $this->buildTableRows($filter, $from, $to, $allOrders);

        return [
            'total_orders'     => $allOrders->count(),
            'gross_sales'      => $grossSales,
            'gst_collected'    => $gstCollected,
            'net_sales'        => $netSales,
            'cancelled_orders' => $cancelledOrders->count(),
            'cancelled_value'  => $cancelledValue,
            'final_income'     => $finalIncome,
            'table_rows'       => $tableRows,
            'filter_label'     => $this->filterLabel($filter),
        ];
    }

    private function buildTableRows(string $filter, $from, $to, $allOrders): array
    {
        if ($filter === 'product_wise') {
            // Product-wise breakdown
            $rows = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'cancelled')
                ->select(
                    'products.name as product_name',
                    DB::raw('SUM(order_items.quantity) as total_qty'),
                    DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sales')
                ->get()
                ->map(function ($row) {
                    // For product-wise, GST is estimated at 18% included in total
                    $gst     = round($row->total_sales * 18 / 118, 2);
                    $netSale = $row->total_sales - $gst;
                    return [
                        'label'      => $row->product_name,
                        'sub_label'  => 'Qty: ' . $row->total_qty,
                        'orders'     => $row->total_qty,
                        'gross'      => $row->total_sales,
                        'gst'        => $gst,
                        'net'        => $netSale,
                    ];
                })->toArray();

            return $rows;
        }

        // Time-based breakdown
        $groupFormat = match ($filter) {
            'today'   => '%H:00',     // Hourly
            'weekly'  => '%W-%Y',     // Week
            'yearly'  => '%m-%Y',     // Monthly
            'custom'  => '%d-%m-%Y',  // Daily
            default   => '%d-%m-%Y',  // Monthly → daily
        };

        $labelFormat = match ($filter) {
            'today'   => 'H:00',
            'weekly'  => 'l (d M)',
            'yearly'  => 'M Y',
            'custom'  => 'd M Y',
            default   => 'd M Y',
        };

        // Build rows from orders collection grouped by period
        $grouped = $allOrders->where('status', '!=', 'cancelled')
            ->groupBy(function ($order) use ($filter) {
                return match ($filter) {
                    'today'  => Carbon::parse($order->created_at)->format('H'),
                    'weekly' => Carbon::parse($order->created_at)->format('Y-m-d'),
                    'yearly' => Carbon::parse($order->created_at)->format('Y-m'),
                    default  => Carbon::parse($order->created_at)->format('Y-m-d'),
                };
            });

        $rows = [];
        foreach ($grouped->sortKeys() as $key => $orders) {
            $dt = match ($filter) {
                'today'  => Carbon::today()->setHour((int)$key),
                'yearly' => Carbon::createFromFormat('Y-m', $key)->startOfMonth(),
                default  => Carbon::createFromFormat('Y-m-d', $key),
            };

            $gross    = $orders->sum('total');
            $gst      = $orders->sum(fn($o) => ($o->cgst ?? 0) + ($o->sgst ?? 0) + ($o->igst ?? 0));
            $net      = $gross - $gst;

            $rows[] = [
                'label'     => $dt->format($labelFormat),
                'sub_label' => null,
                'orders'    => $orders->count(),
                'gross'     => $gross,
                'gst'       => $gst,
                'net'       => $net,
            ];
        }

        // Monthly summary (default monthly view — show each day + a total)
        return $rows;
    }

    private function filterLabel(string $filter): string
    {
        return match ($filter) {
            'today'        => 'Today',
            'weekly'       => 'This Week',
            'monthly'      => 'This Month',
            'yearly'       => 'This Year',
            'custom'       => 'Custom Range',
            'product_wise' => 'Product Wise',
            default        => 'This Month',
        };
    }
}
