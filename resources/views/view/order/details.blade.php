@include('view.layout.header')

<!-- Professional Order Details Content -->
<div class="order-details-container py-5 bg-light-gray">
    <div class="container">
        <!-- Top Navigation & Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            
            <div class="text-end mt-3 mt-sm-0">
                <h2 class="h4 mb-1 fw-bold text-dark">Order #{{ $order->order_number }}</h2>
                @php
                    $statusColors = [
                        'pending' => 'bg-soft-warning text-warning',
                        'processing' => 'bg-soft-info text-info',
                        'shipped' => 'bg-soft-primary text-primary',
                        'delivered' => 'bg-soft-success text-success',
                        'completed' => 'bg-soft-success text-success',
                        'cancelled' => 'bg-soft-danger text-danger',
                    ];
                    $statusClass = $statusColors[strtolower($order->status)] ?? 'bg-soft-secondary text-secondary';
                @endphp
                <span class="badge {{ $statusClass }} text-uppercase px-3 py-2 rounded-pill" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Info Column -->
            <div class="col-lg-5 col-md-6">
                <!-- Order Information Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0 text-muted"><i class="far fa-calendar-alt me-2 text-custom"></i> Order Information</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-row d-flex justify-content-between mb-3">
                            <span class="text-muted small">Order ID:</span>
                            <span class="fw-bold small text-dark">{{ $order->order_number }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between mb-3">
                            <span class="text-muted small">Order Date:</span>
                            <span class="fw-bold small">{{ $order->created_at->format('n/j/Y') }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between mb-3">
                            <span class="text-muted small">Status:</span>
                            <span class="fw-bold small text-dark">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between mb-3">
                            <span class="text-muted small">Payment Method:</span>
                            <span class="fw-bold small text-dark">{{ ucfirst($order->payment_method ?? 'Online') }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between mb-0">
                            <span class="text-muted small">Payment Status:</span>
                            @php
                                $isPaid = in_array(strtolower($order->payment_status), ['paid', 'success']);
                            @endphp
                            <span class="fw-bold small {{ $isPaid ? 'text-success' : 'text-warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </div>
                        <div class="info-row d-flex justify-content-between mt-3 mb-0">
                            <span class="text-muted small">Arriving On:</span>
                            <span class="fw-bold small text-dark">
                                {{ $order->created_at->addDays(2)->format('n/j/Y') }} <span class="text-muted fw-normal">(Estimated)</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0 text-muted"><i class="fas fa-map-marker-alt me-2 text-custom"></i> Shipping Address</h6>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-2">{{ $order->shipping_address['name'] ?? ($order->user->name ?? 'Customer') }}</h6>
                        <p class="small text-muted mb-3 lh-base">
                            @if(!empty($order->shipping_address['door_number'])){{ $order->shipping_address['door_number'] }}, @endif
                            @if(!empty($order->shipping_address['street'])){{ $order->shipping_address['street'] }}, @endif
                            {{ $order->shipping_address['address'] ?? '' }}<br>
                            {{ $order->shipping_address['city'] ?? '' }}@if(!empty($order->shipping_address['district'])), {{ $order->shipping_address['district'] }}@endif, 
                            {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}
                        </p>
                        <div class="pt-3 border-top d-flex align-items-center">
                            <i class="fas fa-phone-alt me-2 text-custom small"></i>
                            <span class="small text-muted fw-bold">{{ $order->shipping_address['phone'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Items Column -->
            <div class="col-lg-7 col-md-6">
                <!-- Items Box -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold mb-0 text-muted"><i class="fas fa-shopping-basket me-2 text-custom"></i> Items ({{ $order->items->count() }})</h6>
                    </div>
                    <div class="card-body p-0">
                        @foreach($order->items as $item)
                        <div class="p-4 border-bottom item-row">
                            @php
                                $pImg = $item->product_image;
                                if (!$pImg && $item->product) $pImg = $item->product->image;
                                if (is_string($pImg) && str_starts_with($pImg, '[')) {
                                    $decoded = json_decode($pImg, true);
                                    $pImg = is_array($decoded) && count($decoded) > 0 ? $decoded[0] : '';
                                }
                                $imgSrc = $pImg ? asset('storage/' . $pImg) : asset('assets/images/product/product1.jpg');
                                if (is_string($pImg) && (str_starts_with($pImg, 'http') || str_starts_with($pImg, 'assets/'))) {
                                    $imgSrc = asset($pImg);
                                }
                                $productUrl = $item->product ? route('product.show', $item->product->slug) : '#';
                            @endphp
                            <div class="d-flex align-items-center">
                                <a href="{{ $productUrl }}" class="item-img-box rounded-3 overflow-hidden shadow-sm d-block" style="width: 70px; height: 70px; flex-shrink: 0;">
                                    <img src="{{ $imgSrc }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->product_name }}" onerror="this.src='{{ asset('assets/images/product/product1.jpg') }}'">
                                </a>
                                <div class="ms-3 flex-grow-1">
                                    <a href="{{ $productUrl }}" class="h6 mb-1 text-dark fw-bold text-decoration-none d-block hover-text-custom">{{ $item->product_name }}</a>
                                    @if($item->options)
                                        @php $options = is_string($item->options) ? json_decode($item->options, true) : $item->options; @endphp
                                        @if(is_array($options))
                                            <p class="small mb-0 text-muted">
                                                @foreach($options as $k => $v)
                                                    {{ ucfirst($k) }}: <span class="text-dark fw-medium">{{ $v }}</span> @if(!$loop->last) • @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    @endif
                                    <span class="small text-muted">Qty: <span class="text-dark fw-medium">{{ $item->quantity }}</span></span>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0 fw-bold text-dark">₹{{ number_format($item->price * $item->quantity, 2) }}</h6>
                                    <span class="small text-muted" style="font-size: 0.7rem;">₹{{ number_format($item->price, 2) }} each</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span class="small">Subtotal</span>
                            <span class="fw-bold small text-dark">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span class="small">Shipping</span>
                            <span class="fw-bold small {{ $order->shipping > 0 ? 'text-dark' : 'text-success' }}">
                                {{ $order->shipping > 0 ? '₹'.number_format($order->shipping, 2) : '₹0.00' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted border-bottom pb-3">
                            <span class="small">Tax (Included)</span>
                            <span class="fw-bold small text-dark">₹{{ number_format($order->tax ?? 0, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-3 text-danger border-bottom pb-3">
                            <span class="small fw-bold">Discount</span>
                            <span class="fw-bold small">- ₹{{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <h5 class="fw-bold mb-0 text-dark">Total</h5>
                            <h3 class="fw-bold mb-0 text-custom">₹{{ number_format($order->total, 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center text-md-end mb-4">
                    <a href="{{ route('user.order.invoice', $order->id) }}" class="btn btn-custom py-3 px-md-5 w-100 w-md-auto rounded-3 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center mb-2">
                        <i class="fas fa-file-download me-2"></i> Download Invoice
                            </a>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-dark-gray py-3 px-md-5 w-100 w-md-auto rounded-3 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-arrow-left me-2"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --custom-color: #72a420;
        --custom-soft: #f4f9ed;
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Inter', sans-serif;
    }

    .text-custom { color: var(--custom-color) !important; }
    .bg-custom { background-color: var(--custom-color) !important; }
    .bg-soft-custom { background-color: var(--custom-soft) !important; }

    .btn-custom {
        background-color: #557918;
        color: white;
        transition: 0.3s;
        border: none;
    }
    .btn-custom:hover {
        background-color: #446114;
        color: white;
        transform: translateY(-2px);
    }

    .btn-dark-gray {
        background-color: #3d3d3d;
        color: white;
        transition: 0.3s;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
    }
    .btn-dark-gray:hover {
        background-color: #2b2b2b;
        color: white;
    }

    .hover-text-custom:hover {
        color: var(--custom-color) !important;
    }

    @media (max-width: 767.98px) {
        .btn-custom, .btn-dark-gray {
            width: auto !important;
            margin-bottom: 15px;
            display: inline-flex !important;
            justify-content: center !important;
        }
        .text-end {
            text-align: center !important;
        }
        .d-flex.flex-wrap {
            justify-content: center !important;
        }
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    .rounded-4 { border-radius: 20px !important; }
    
    .bg-soft-warning { background: #fff8e1 !important; color: #fbc02d !important; }
    .bg-soft-success { background: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-soft-info { background: #e1f5fe !important; color: #0288d1 !important; }
    .bg-soft-primary { background: #e3f2fd !important; color: #1976d2 !important; }
    .bg-soft-danger { background: #ffebee !important; color: #d32f2f !important; }
    .bg-soft-secondary { background: #f5f5f5 !important; color: #757575 !important; }

    .item-row:last-child { border-bottom: none !important; }

    /* Custom Scrollbar for better aesthetic */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #bbb; }
</style>

@include('view.layout.footer')