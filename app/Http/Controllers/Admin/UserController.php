<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 10);
        
        $users = User::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());
            
        return view('admin.users.index', compact('users'));
    }

    public function report(User $user)
    {
        $orders = $user->orders()->with('items')->latest()->get();

        $totalOrders = $orders->count();

        $completedOrders = $orders->filter(function ($order) {
            return in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered', 'completed'])
                && $order->payment_status === 'paid';
        });
        
        $totalCompletedOrders = $completedOrders->count();
        $totalPurchaseValue = $user->total_purchase_value;

        $formattedOrders = $orders->map(function ($order) {
            return [
                'date' => $order->created_at->format('d M Y h:i A'),
                'order_number' => $order->order_number,
                'items_summary' => $order->items->map(function ($item) {
                    return $item->quantity . ' x ' . $item->product_name;
                })->implode(', '),
                'total' => number_format($order->total, 2),
                'status' => ucfirst($order->status),
                'payment_status' => ucfirst($order->payment_status),
                'badge_class' => $order->getStatusBadgeClass()
            ];
        });

        // Resolve addresses
        $addresses = [];

        // 1. Last Ordered Address
        $latestOrder = $user->orders()->latest()->first();
        if ($latestOrder && !empty($latestOrder->shipping_address)) {
            $addr = $latestOrder->shipping_address;
            $addresses[] = [
                'type' => 'Last Ordered Address',
                'name' => $addr['name'] ?? $user->name,
                'door_number' => $addr['door_number'] ?? $addr['door_no'] ?? '',
                'street' => $addr['street'] ?? '',
                'city' => $addr['city'] ?? '',
                'state' => $addr['state'] ?? '',
                'pincode' => $addr['pincode'] ?? $addr['post_code'] ?? '',
                'phone' => $addr['phone'] ?? $addr['phone_number'] ?? '',
                'is_default' => false,
            ];
        }

        // 2. Default Address (from Saved Addresses)
        foreach ($user->addresses as $address) {
            if ($address->is_default) {
                $addresses[] = [
                    'type' => 'Default Address',
                    'name' => $address->first_name . ' ' . $address->last_name,
                    'door_number' => $address->door_number,
                    'street' => $address->street,
                    'city' => $address->city,
                    'state' => $address->state,
                    'pincode' => $address->post_code,
                    'phone' => $address->phone_number,
                    'is_default' => true,
                ];
            }
        }

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? 'N/A',
                'joined_at' => $user->created_at ? $user->created_at->format('d M Y') : 'N/A',
            ],
            'summary' => [
                'total_orders' => $totalOrders,
                'total_completed_orders' => $totalCompletedOrders,
                'total_purchase_value' => number_format($totalPurchaseValue, 2),
            ],
            'addresses' => $addresses,
            'orders' => $formattedOrders
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->get('search');
        $format = $request->get('format', 'excel');

        // Eager-load all addresses, filter to default in PHP
        $users = User::with('addresses')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        // Pre-build plain scalar rows (no Eloquent objects) + separate addr detail for PDF
        $rows     = [];
        $addrData = []; // plain arrays for PDF multi-line address rendering

        foreach ($users as $index => $user) {
            $addr = $user->addresses->firstWhere('is_default', true)
                    ?? $user->addresses->first();

            // Build flat address string for Excel
            $addressStr = 'N/A';
            if ($addr) {
                $addressStr = collect([
                    trim($addr->first_name . ' ' . $addr->last_name),
                    $addr->door_number,
                    $addr->street,
                    $addr->city,
                    $addr->district,
                    $addr->state,
                    $addr->post_code,
                ])->filter()->implode(', ');
            }

            // Store plain scalars only — safe for stream closure capture
            $rows[] = [
                'sno'       => $index + 1,
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone ?? 'N/A',
                'address'   => $addressStr,
                'purchases' => number_format($user->total_purchase_value, 2),
                'joined'    => $user->created_at ? $user->created_at->format('d M Y') : 'N/A',
            ];

            // Separate plain array for PDF (multi-line address fields)
            $addrData[] = $addr ? [
                'name'         => trim($addr->first_name . ' ' . $addr->last_name),
                'door_number'  => $addr->door_number,
                'street'       => $addr->street,
                'city'         => $addr->city,
                'district'     => $addr->district,
                'state'        => $addr->state,
                'post_code'    => $addr->post_code,
                'phone_number' => $addr->phone_number,
            ] : null;
        }

        if ($format === 'excel') {
            // Build CSV content as a string directly — avoids closure capture issues
            $csv  = "\xEF\xBB\xBF"; // UTF-8 BOM
            $csv .= implode(',', array_map(fn($v) => '"' . $v . '"',
                ['S.No', 'Name', 'Email', 'Phone', 'Default Address', 'Total Purchases (Rs)', 'Registered On']
            )) . "\r\n";

            foreach ($rows as $row) {
                $csv .= implode(',', array_map(
                    fn($v) => '"' . str_replace('"', '""', (string) $v) . '"',
                    [$row['sno'], $row['name'], $row['email'], $row['phone'], $row['address'], $row['purchases'], $row['joined']]
                )) . "\r\n";
            }

            $filename = 'customers_' . now()->format('Ymd_His') . '.csv';
            return response()->make($csv, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ]);
        }

        // PDF via DomPDF
        $totalRevenue    = $users->sum('total_purchase_value');
        $activeCustomers = $users->filter(fn($u) => $u->total_purchase_value > 0)->count();
        $filename        = 'customers_' . now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('admin.users.export_pdf',
                    compact('rows', 'addrData', 'search', 'totalRevenue', 'activeCustomers'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}


