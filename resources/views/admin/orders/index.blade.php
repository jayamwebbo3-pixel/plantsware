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
        border: 2px solid transparent;
        background-color: #fff;
        color: #6c757d;
        font-weight: 600;
        border-radius: 6px;
        margin-right: 4px;
        margin-bottom: 4px;
        transition: all 0.2s;
        box-shadow: none;
        padding: 6px 14px !important;
        font-size: 13px !important;
    }

    .order-status-btn.active {
        color: #fff !important;
    }

    /* All Status Button */
    .order-status-btn.status-all {
        border-color: #6c757d;
        color: #6c757d;
        background: #fff;
    }
    .order-status-btn.status-all:hover {
        background: #f8f9fa;
        color: #495057;
    }
    .order-status-btn.status-all.active {
        background: #6c757d !important;
        color: #fff !important;
        border-color: #6c757d !important;
    }

    /* Processing Status Button */
    .order-status-btn.status-processing {
        border-color: #a48cff;
        color: #7e57c2;
        background: #fff;
    }
    .order-status-btn.status-processing:hover {
        background: #f6f0ff;
    }
    .order-status-btn.status-processing.active {
        background: #a48cff !important;
        color: #fff !important;
        border-color: #a48cff !important;
    }

    /* Confirmed Status Button */
    .order-status-btn.status-confirmed {
        border-color: #46cbfb;
        color: #5799c2;
        background: #fff;
    }
    .order-status-btn.status-confirmed:hover {
        background: #e8f7ff;
    }
    .order-status-btn.status-confirmed.active {
        background: #46cbfb !important;
        color: #fff !important;
        border-color: #46cbfb !important;
    }

    /* Shipped Status Button */
    .order-status-btn.status-shipped {
        border-color: #42a5f5;
        color: #1976d2;
        background: #fff;
    }
    .order-status-btn.status-shipped:hover {
        background: #e3f2fd;
    }
    .order-status-btn.status-shipped.active {
        background: #42a5f5 !important;
        color: #fff !important;
        border-color: #42a5f5 !important;
    }

    /* Delivered Status Button */
    .order-status-btn.status-delivered {
        border-color: #66bb6a;
        color: #388e3c;
        background: #fff;
    }
    .order-status-btn.status-delivered:hover {
        background: #e8f5e9;
    }
    .order-status-btn.status-delivered.active {
        background: #66bb6a !important;
        color: #fff !important;
        border-color: #66bb6a !important;
    }

    /* Cancelled Status Button */
    .order-status-btn.status-cancelled {
        border-color: #ef9a9a;
        color: #e53935;
        background: #fff;
    }
    .order-status-btn.status-cancelled:hover {
        background: #ffebee;
    }
    .order-status-btn.status-cancelled.active {
        background: #e57373 !important;
        color: #fff !important;
        border-color: #e57373 !important;
    }

    /* Returned Status Button */
    .order-status-btn.status-returned {
        border-color: #ffb74d;
        color: #ffa000;
        background: #fff;
    }
    .order-status-btn.status-returned:hover {
        background: #fff8e1;
    }
    .order-status-btn.status-returned.active {
        background: #ffb74d !important;
        color: #fff !important;
        border-color: #ffb74d !important;
    }

    /* Return Requested Status Button */
    .order-status-btn.status-return-requested {
        border-color: #f6c23e;
        color: #f6c23e;
        background: #fff;
    }
    .order-status-btn.status-return-requested:hover {
        background: #fdf6e2;
    }
    .order-status-btn.status-return-requested.active {
        background: #f6c23e !important;
        color: #fff !important;
        border-color: #f6c23e !important;
    }

    .orders-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .orders-table {
        min-width: 1400px;
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th, 
    .orders-table td {
        padding: 16px 20px !important;
        font-size: 14.5px !important;
        vertical-align: middle;
    }

    .orders-table th {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 13.5px !important;
        letter-spacing: 0.5px;
    }

    .orders-table .btn {
        font-size: 13px !important;
        padding: 6px 12px !important;
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
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h4>
                            <small class="text-muted">Pending</small>
                        </div>
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

                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <h4 class="mb-0 text-secondary">{{ $stats['returned'] ?? 0 }}</h4>
                            <small class="text-muted">Returned</small>
                        </div>
                        <!-- <div class="col-lg-10 col-md-8 col-6 mb-3 text-start small text-muted d-flex align-items-center">
                            <span>
                                Returned Requested: {{ $stats['return_requested'] ?? 0 }} |
                                Returned Approved: {{ $stats['return_approved'] ?? 0 }} |
                                Returned Rejected: {{ $stats['return_rejected'] ?? 0 }}
                            </span>
                        </div> -->
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
                <div class="col-md-9 text-start">
                    <div class="d-inline-flex flex-wrap" role="group">
                        @php
                        $statuses = [
                        '' => ['label' => 'All', 'class' => 'status-all'],
                        'confirmed' => ['label' => 'Confirmed', 'class' => 'status-confirmed'],
                        'processing' => ['label' => 'Processing', 'class' => 'status-processing'],
                        'shipped' => ['label' => 'Shipped', 'class' => 'status-shipped'],
                        'delivered' => ['label' => 'Delivered', 'class' => 'status-delivered'],
                        'cancelled' => ['label' => 'Cancelled', 'class' => 'status-cancelled'],
                        'return_requested' => ['label' => 'Return Requested', 'class' => 'status-return-requested'],
                        'returned' => ['label' => 'Returned', 'class' => 'status-returned'],
                        ];
                        $currentStatus = request('status') ?: '';
                        @endphp
                        @foreach($statuses as $code => $meta)
                        <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), $code ? ['status' => $code] : ['status' => null])) }}"
                            class="order-status-btn btn {{ $meta['class'] }}{{ $currentStatus === $code ? ' active' : '' }}">
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
            <div class="table-responsive orders-table-wrapper">
                <table class="table table-bordered table-hover align-middle orders-table">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th>SI.NO</th>
                            <th>ORDER ID</th>
                            <th>ORDER TIME</th>
                            <th>PRODUCT</th>
                            <th>PRICE</th>
                            <th>MOBILE NO</th>
                            <th>ADDRESS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="align-middle">{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td class="align-middle">
                                <strong>{{ $order->order_number }}</strong>
                            </td>
                            <td class="align-middle text-nowrap">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="align-middle text-center text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 text-nowrap" data-bs-toggle="modal" data-bs-target="#productsModal{{ $order->id }}">
                                    <i class="fas fa-shopping-bag me-1"></i> View
                                </button>
                                
                                <!-- Modal for Order Items -->
                                <div class="modal fade text-start" id="productsModal{{ $order->id }}" tabindex="-1" aria-labelledby="productsModalLabel{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold" id="productsModalLabel{{ $order->id }}">
                                                    <i class="fas fa-receipt text-success me-2"></i>Order Items for #{{ $order->order_number }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover align-middle mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Product Name</th>
                                                                <th class="text-center">Quantity</th>
                                                                <th class="text-end">Price</th>
                                                                <th class="text-end">Total Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($order->items as $item)
                                                            <tr>
                                                                <td class="text-center" style="width: 80px;">
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
                                                                <td>
                                                                    @php
                                                                    $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options;
                                                                    $sizeParam = is_array($options) ? ($options['size'] ?? null) : (is_string($options) ? $options : null);
                                                                    @endphp

                                                                    @if($item->product && !$item->combo_pack_id)
                                                                    <a href="{{ route('product.show', ['slug' => $item->product->slug ?? '', 'size' => $sizeParam]) }}" target="_blank" style="color:#2ea25a; font-weight: bold;">{{ $item->product_name }}</a>
                                                                    @elseif($item->comboPack)
                                                                    <a href="{{ route('combo_packs.frontend_show', $item->comboPack->slug ?? '') }}" target="_blank" style="color:#2ea25a; font-weight: bold;">{{ $item->product_name }}</a> <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">COMBO</span>
                                                                    @else
                                                                    <span class="fw-bold">{{ $item->product_name }}</span>
                                                                    @endif

                                                                    @if($item->custom_combo_id)
                                                                    <br><span class="badge bg-danger" style="font-size: 0.6rem; padding: 3px 6px;">BUILD A COMBO</span>
                                                                    @endif

                                                                    @if($sizeParam)
                                                                    <br><small class="text-muted">Size: {{ $sizeParam }}</small>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                                                <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                                                <td class="text-end fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle fw-bold">₹{{ number_format($order->total, 2) }}</td>
                            <td class="align-middle">
                                {{ $order->shipping_address['phone'] ?? ($order->user?->phone ?? 'N/A') }}
                            </td>
                            <td class="align-middle">
                                @if(!empty($order->shipping_address['door_number'])){{ $order->shipping_address['door_number'] }}, @endif{{ $order->shipping_address['address'] ?? 'N/A' }}
                                @if(!empty($order->shipping_address['address']) || !empty($order->shipping_address['door_number']))<br>@endif
                                {{ $order->shipping_address['city'] ?? '' }}
                                @if(!empty($order->shipping_address['city'])),@endif
                                {{ $order->shipping_address['state'] ?? '' }}
                                @if(!empty($order->shipping_address['pincode'])) - {{ $order->shipping_address['pincode'] }}@endif
                            </td>
                            <td class="text-nowrap align-middle">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info mb-1" title="View Order">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->payment_status === 'paid' || $order->payment_status === 'refunded')
                                <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-sm btn-success mb-1" target="_blank" title="View Invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
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