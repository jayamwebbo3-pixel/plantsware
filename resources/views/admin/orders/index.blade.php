{{-- 
    Blade view file: Orders Management
    Note: Blade view files do not support PHP namespaces; 
    ensure that in your PHP controller or models, the namespace is declared as the very first statement or after any declare call in the script, as per PHP standards.
--}}
@extends('admin.layout')

@section('title', 'Orders Management')

@section('content')
<style>
    .order-status-btn {
        border: 1.5px solid transparent;
        background-color: #fff;
        color: #6c757d;
        font-weight: 500;
        border-radius: 6px;
        margin-right: 6px;
        margin-bottom: 6px;
        transition: all 0.2s;
        box-shadow: none;
    }
    .order-status-btn.active, .order-status-btn:focus {
        color: #fff !important;
    }
    .order-status-btn.status-all {
        border-color: #adb5bd;
        color: #495057;
        background: #adb5bd;
    }
    .order-status-btn.status-processing {
        border-color: #a48cff;
        color: #7e57c2;
        background: #f6f0ff;
    }
    .order-status-btn.status-processing.active,
    .order-status-btn.status-processing:focus {
        background: #a48cff !important;
        color: #fff !important;
        border-color: #a48cff !important;
    }
    .order-status-btn.status-confirmed {
        border-color: #46cbfbff;
        color: #5799c2ff;
        background: #e8f7ffff;
    }
    .order-status-btn.status-confirmed.active,
    .order-status-btn.status-confirmed:focus {
        background: #46cbfbff !important;
        color: #fff !important;
        border-color: #46cbfbff !important;
    }

    .order-status-btn.status-shipped {
        border-color: #42a5f5;
        color: #1976d2;
        background: #e3f2fd;
    }
    .order-status-btn.status-shipped.active,
    .order-status-btn.status-shipped:focus {
        background: #42a5f5 !important;
        color: #fff !important;
        border-color: #42a5f5 !important;
    }

    .order-status-btn.status-delivered {
        border-color: #66bb6a;
        color: #388e3c;
        background: #e8f5e9;
    }
    .order-status-btn.status-delivered.active,
    .order-status-btn.status-delivered:focus {
        background: #66bb6a !important;
        color: #fff !important;
        border-color: #66bb6a !important;
    }

    .order-status-btn.status-cancelled {
        border-color: #ef9a9a;
        color: #e53935;
        background: #ffebee;
    }
    .order-status-btn.status-cancelled.active,
    .order-status-btn.status-cancelled:focus {
        background: #e57373 !important;
        color: #fff !important;
        border-color: #e57373 !important;
    }

    .order-status-btn.status-returned {
        border-color: #ffb74d;
        color: #ffa000;
        background: #fff8e1;
    }
    .order-status-btn.status-returned.active,
    .order-status-btn.status-returned:focus {
        background: #ffb74d !important;
        color: #fff !important;
        border-color: #ffb74d !important;
    }
</style>
<div class="container-fluid">
    <!-- Top Stats Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row text-center">
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total Orders</small>
                        </div>
                        <!-- <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h4>
                            <small class="text-muted">Pending</small>
                        </div> -->
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-success">{{ $stats['confirmed'] ?? 0 }}</h4>
                            <small class="text-muted">Confirmed</small>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-primary">{{ $stats['shipped'] ?? 0 }}</h4>
                            <small class="text-muted">Shipped</small>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-info">{{ $stats['processing'] ?? 0 }}</h4>
                            <small class="text-muted">Processing</small>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-success">{{ $stats['delivered'] ?? 0 }}</h4>
                            <small class="text-muted">Delivered</small>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-danger">{{ $stats['cancelled'] ?? 0 }}</h4>
                            <small class="text-muted">Cancelled</small>
                        </div>
                    </div>
                    <div class="row text-center mt-2">
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-secondary">{{ $stats['returned'] ?? 0 }}</h4>
                            <small class="text-muted">Returned</small>
                        </div>
                        <div class="col-lg-10 col-md-8 col-6 mb-3 text-start small text-muted d-flex align-items-center">
                            <span>
                                Returned Requested: {{ $stats['return_requested'] ?? 0 }} |
                                Returned Approved: {{ $stats['return_approved'] ?? 0 }} |
                                Returned Rejected: {{ $stats['return_rejected'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <!-- <div class="card-header bg-white border-0 py-3">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('customized') ? '' : 'active' }}" href="{{ route('admin.orders.index', array_merge(request()->except('customized'))) }}">
                        All Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('customized') ? 'active' : '' }}" href="{{ route('admin.orders.index', array_merge(request()->all(), ['customized' => 1])) }}">
                        Customized Orders
                    </a>
                </li> 
            </ul>
        </div> -->

        <div class="card-body">
            <!-- Action Buttons & Filters -->
            <div class="row mb-3 align-items-center">
                <!-- <div class="col-md-3">
                    
                        In a real implementation, this button should submit a form to trigger a payment status update,
                        likely opening a modal to select the order(s) and new payment status.
                    
                    <button type="button" class="btn btn-warning btn-sm" disabled title="Select orders to change payment status">Change Payment Status</button>
                </div> -->
                <div class="col-md-9 text-center">
                    
                    <div class="d-inline-flex flex-nowrap" role="group">
                        @php
                            $statuses = [
                                '' => ['label' => 'All', 'class' => 'status-all'],
                                'confirmed' => ['label' => 'Confirmed', 'class' => 'status-confirmed'],
                                'processing' => ['label' => 'Processing', 'class' => 'status-processing'],
                                'shipped' => ['label' => 'Shipped', 'class' => 'status-shipped'],
                                'delivered' => ['label' => 'Delivered', 'class' => 'status-delivered'],
                                'cancelled' => ['label' => 'Cancelled', 'class' => 'status-cancelled'],
                                'returned' => ['label' => 'Returned', 'class' => 'status-returned'],
                            ];
                            $currentStatus = request('status') ?: '';
                        @endphp
                        @foreach($statuses as $code => $meta)
                            <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), $code ? ['status' => $code] : ['status' => null])) }}"
                               class="order-status-btn btn btn-sm {{ $meta['class'] }}{{ $currentStatus === $code ? ' active' : '' }}">
                                {{ $meta['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <form method="GET" class="d-inline">
                        @foreach(request()->except(['per_page', 'page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <small class="me-2">Show</small>
                        <select name="per_page" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Search -->
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    <form method="GET">
                        @foreach(request()->except(['search', 'page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th>SI.NO</th>
                            <th>IMAGE</th>
                            <th>ORDER ID</th>
                            <th>ORDER TIME</th>
                            <th>PRODUCT</th>
                            <th>QTY</th>
                            <th>PRICE</th>
                            <th>USER NAME</th>
                            <th>MOBILE NO</th>
                            <th>ADDRESS</th>
                            <th>PAYMENT STATUS</th>
                            <th>ORDER STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php $itemCount = count($order->items) ?: 1; @endphp
                        @foreach($order->items as $index => $item)
                        <tr>
                            @if($index === 0)
                            <td rowspan="{{ $itemCount }}" class="align-middle">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->parent->iteration }}</td>
                            @endif
                            <td class="align-middle text-center">
                                @php
                                    $img = $item->product_image;
                                    $imgData = is_string($img) ? json_decode($img, true) : $img;
                                    $firstImg = is_array($imgData) ? $imgData[0] : $img;
                                @endphp
                                @if($firstImg)
                                     <img src="{{ asset('storage/' . $firstImg) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                     <img src="{{ asset('assets/images/product/product1.jpg') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @endif
                            </td>
                            @if($index === 0)
                            <td rowspan="{{ $itemCount }}" class="align-middle">
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td rowspan="{{ $itemCount }}" class="align-middle small">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </td>
                            @endif
                            <td class="align-middle small">
                                @if($item->product && !$item->combo_pack_id)
                                    <a href="{{ route('product.show', $item->product->slug ?? '') }}" target="_blank" class="text-decoration-none fw-bold" style="color:#2ea25a;">{{ $item->product_name }}</a>
                                @elseif($item->comboPack)
                                    <a href="{{ route('combo_packs.frontend_show', $item->comboPack->slug ?? '') }}" target="_blank" class="text-decoration-none fw-bold" style="color:#2ea25a;">{{ $item->product_name }}</a> <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">COMBO</span>
                                @else
                                    <span class="fw-bold">{{ $item->product_name }}</span>
                                @endif
                                
                                @if($item->options)
                                    @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                    @if(is_array($options) && isset($options['size']))
                                        <br><small class="text-muted">Size: {{ $options['size'] }}</small>
                                    @elseif(is_string($options) && !empty($options))
                                        <br><small class="text-muted">Size: {{ $options }}</small>
                                    @endif
                                @endif
                            </td>
                            <td class="align-middle text-center">{{ $item->quantity }}</td>
                            <td class="align-middle">₹{{ number_format($item->price, 2) }}</td>
                            @if($index === 0)
                            <td rowspan="{{ $itemCount }}" class="align-middle">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td rowspan="{{ $itemCount }}" class="align-middle">
                                {{ $order->shipping_address['phone'] ?? ($order->user?->phone ?? 'N/A') }}
                            </td>
                            <td rowspan="{{ $itemCount }}" class="align-middle small">
                                {{ $order->shipping_address['address'] ?? 'N/A' }}
                                @if(!empty($order->shipping_address['address']))<br>@endif
                                {{ $order->shipping_address['city'] ?? '' }}
                                @if(!empty($order->shipping_address['city'])),@endif
                                {{ $order->shipping_address['state'] ?? '' }}
                                @if(!empty($order->shipping_address['pincode'])) - {{ $order->shipping_address['pincode'] }}@endif
                            </td>
                            <td rowspan="{{ $itemCount }}" class="align-middle">
                                @php
                                    $paymentStatus = $order->payment_status;
                                    $paymentBadge = [
                                        'paid' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'failed' => 'bg-danger',
                                        'refunded' => 'bg-info text-dark'
                                    ][$paymentStatus] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $paymentBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                </span>
                                <div class="mt-2 text-muted small fw-bold">Total: ₹{{ number_format($order->total, 2) }}</div>
                            </td>
                            <td rowspan="{{ $itemCount }}" class="align-middle">
                                @php
                                    if (method_exists($order, 'getStatusBadgeClass')) {
                                        $badgeClass = $order->getStatusBadgeClass();
                                    } else {
                                        $statusColor = [
                                            'pending' => 'bg-warning text-dark',
                                            'confirmed' => 'bg-success text-white',
                                            'processing' => 'bg-info text-dark',
                                            'shipped' => 'bg-primary',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            'returned' => 'bg-secondary'
                                        ][$order->status] ?? 'bg-secondary';
                                        $badgeClass = $statusColor;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td rowspan="{{ $itemCount }}" class="align-middle">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info mb-1" title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->payment_status === 'paid' || $order->payment_status === 'refunded')
                                    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-sm btn-success mb-1" target="_blank" title="View Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                @endif
                                <!-- @if($order->status == 'processing')
                                    <a href="#" class="btn btn-sm btn-primary mb-1" disabled title="Ship order (not implemented)"><i class="fas fa-shipping-fast"></i></a>
                                @endif -->
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        
                        {{-- Fallback for any orders somehow missing items entirely --}}
                        @if($itemCount === 0)
                        <tr>
                            <td class="align-middle">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td class="align-middle text-center">-</td>
                            <td class="align-middle"><strong>{{ $order->order_number }}</strong></td>
                            <td class="align-middle small">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td class="align-middle">-</td>
                            <td class="align-middle text-center">-</td>
                            <td class="align-middle">-</td>
                            <td class="align-middle">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td class="align-middle">
                                {{ $order->shipping_address['phone'] ?? ($order->user?->phone ?? 'N/A') }}
                            </td>
                            <td class="align-middle small">
                                {{ $order->shipping_address['address'] ?? 'N/A' }}
                                @if(!empty($order->shipping_address['address']))<br>@endif
                                {{ $order->shipping_address['city'] ?? '' }}
                                @if(!empty($order->shipping_address['city'])),@endif
                                {{ $order->shipping_address['state'] ?? '' }}
                                @if(!empty($order->shipping_address['pincode'])) - {{ $order->shipping_address['pincode'] }}@endif
                            </td>
                            <td class="align-middle">
                                @php
                                    $paymentStatus = $order->payment_status;
                                    $paymentBadge = [
                                        'paid' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'failed' => 'bg-danger',
                                        'refunded' => 'bg-info text-dark'
                                    ][$paymentStatus] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $paymentBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                </span>
                                <div class="mt-2 text-muted small fw-bold">Total: ₹{{ number_format($order->total, 2) }}</div>
                            </td>
                            <td class="align-middle">
                                @php
                                    if (method_exists($order, 'getStatusBadgeClass')) {
                                        $badgeClass = $order->getStatusBadgeClass();
                                    } else {
                                        $statusColor = [
                                            'pending' => 'bg-warning text-dark',
                                            'confirmed' => 'bg-success text-white',
                                            'processing' => 'bg-info text-dark',
                                            'shipped' => 'bg-primary',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            'returned' => 'bg-secondary'
                                        ][$order->status] ?? 'bg-secondary';
                                        $badgeClass = $statusColor;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info mb-1" title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->payment_status === 'paid' || $order->payment_status === 'refunded')
                                    <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-sm btn-success mb-1" target="_blank" title="View Invoice">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                @endif
                                <!-- @if($order->status == 'processing')
                                    <a href="#" class="btn btn-sm btn-primary mb-1" disabled title="Ship order (not implemented)"><i class="fas fa-shipping-fast"></i></a>
                                @endif -->
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="13" class="text-center py-5 text-muted">
                                No orders found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                </div>
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection