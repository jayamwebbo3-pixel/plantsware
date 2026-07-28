@include('view.layout.header')

<!-- Professional Order Details Content -->
<section class="order-details-section">
    <div class="container">
        <!-- Top Navigation & Header -->
        <div class="order-details-header" data-aos="fade-down" data-aos-duration="800">
            <a href="{{ route('user.dashboard') }}" class="order-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span class="desktop-text">Back to Orders</span>
                <span class="mobile-text">Back</span>
            </a>
            <div class="order-title-box">
                <h2 class="order-main-title">Order #{{ $order->order_number }}</h2>
                @php
                    $statusColors = [
                        'pending' => 'order-status-pending',
                        'processing' => 'order-status-processing',
                        'shipped' => 'order-status-shipped',
                        'delivered' => 'order-status-delivered',
                        'completed' => 'order-status-completed',
                        'cancelled' => 'order-status-cancelled',
                        'return_rejected' => 'order-status-cancelled',
                        'return_requested' => 'order-status-pending',
                        'returned' => 'order-status-cancelled',
                    ];
                    $statusClass = $statusColors[strtolower($order->status)] ?? 'order-status-default';
                @endphp
                <span class="order-status-badge {{ $statusClass }}" style="{{ in_array(strtolower($order->status), ['return_rejected', 'cancelled', 'returned']) ? 'background-color: #dc3545; color: white;' : (strtolower($order->status) == 'return_requested' ? 'background-color: #ffc107; color: black;' : '') }}">
                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
        </div>

        <div class="order-details-layout">
            <!-- Left Info Column -->
            <div class="order-details-sidebar" data-aos="fade-right" data-aos-delay="100">
                <!-- Order Information Card -->
                <div class="order-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="order-card-header">
                        <h6 class="order-card-title"><i class="far fa-calendar-alt"></i> Order Information</h6>
                    </div>
                    <div class="order-card-body">
                        <div class="order-info-row">
                            <span class="order-info-label">Order ID:</span>
                            <span class="order-info-value">{{ $order->order_number }}</span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Order Date:</span>
                            <span class="order-info-value">{{ $order->created_at->format('n/j/Y') }}</span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Status:</span>
                            <span class="order-info-value">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Payment Method:</span>
                            <span class="order-info-value">{{ ucfirst($order->payment_method ?? 'Online') }}</span>
                        </div>
                        <div class="order-info-row">
                            <span class="order-info-label">Payment Status:</span>
                            @php
                                $isPaid = in_array(strtolower($order->payment_status), ['paid', 'success']);
                            @endphp
                            <span class="order-info-value {{ $isPaid ? 'payment-success' : 'payment-pending' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </div>
                        @if(!in_array(strtolower($order->status), ['returned', 'return_rejected', 'return_requested', 'cancelled']))
                        <div class="order-info-row border-top-row">
                            <span class="order-info-label">Arriving On:</span>
                            <span class="order-info-value">
                                {{ $order->created_at->addDays(2)->format('n/j/Y') }} <span class="order-info-note">(Estimated)</span>
                            </span>
                        </div>
                        @endif

                        @if(strtolower($order->status) === 'return_rejected')
                        <div class="order-info-row border-top-row" style="background-color: #f8d7da; padding: 10px; border-radius: 6px; margin-top: 15px; flex-direction: column; align-items: flex-start; border: 1px solid #f5c6cb;">
                            <span class="order-info-label" style="color: #721c24; font-weight: bold; margin-bottom: 5px;"><i class="fas fa-exclamation-circle"></i> Rejection Reason:</span>
                            <span class="order-info-value" style="color: #721c24; text-align: left; line-height: 1.4;">{{ $order->return_rejection_reason ?? 'No reason provided.' }}</span>
                        </div>
                        @elseif(in_array(strtolower($order->status), ['return_requested', 'returned']))
                        <div class="order-info-row border-top-row" style="background-color: #fff3cd; padding: 10px; border-radius: 6px; margin-top: 15px; flex-direction: column; align-items: flex-start; border: 1px solid #ffeeba;">
                            <span class="order-info-label" style="color: #856404; font-weight: bold; margin-bottom: 5px;"><i class="fas fa-undo"></i> Return Reason:</span>
                            <span class="order-info-value" style="color: #856404; text-align: left; line-height: 1.4;">{{ $order->return_reason ?? 'No reason provided.' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Address Card -->
                <div class="order-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="order-card-header">
                        <h6 class="order-card-title"><i class="fas fa-map-marker-alt"></i> Shipping Address</h6>
                    </div>
                    <div class="order-card-body">
                        <h6 class="order-address-name">{{ $order->shipping_address['name'] ?? ($order->user->name ?? 'Customer') }}</h6>
                        <p class="order-address-text">
                            @if(!empty($order->shipping_address['door_number'])){{ $order->shipping_address['door_number'] }}, @endif
                            @if(!empty($order->shipping_address['street'])){{ $order->shipping_address['street'] }}, @endif
                            {{ $order->shipping_address['address'] ?? '' }}<br>
                            {{ $order->shipping_address['city'] ?? '' }}@if(!empty($order->shipping_address['district'])), {{ $order->shipping_address['district'] }}@endif, 
                            {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}
                        </p>
                        <div class="order-address-phone">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{ $order->shipping_address['phone'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Billing Address Card -->
                <div class="order-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="order-card-header">
                        <h6 class="order-card-title"><i class="fas fa-file-invoice"></i> Billing Address</h6>
                    </div>
                    <div class="order-card-body">
                        @if(empty($order->billing_address) || (isset($order->billing_address['name']) && $order->billing_address == $order->shipping_address))
                            <p class="order-address-text">
                                <i class="fas fa-check-circle text-success"></i> Same as shipping address
                            </p>
                        @else
                            <h6 class="order-address-name">{{ $order->billing_address['name'] ?? '' }}</h6>
                            <p class="order-address-text">
                                @if(!empty($order->billing_address['door_number'])){{ $order->billing_address['door_number'] }}, @endif
                                @if(!empty($order->billing_address['street'])){{ $order->billing_address['street'] }}, @endif
                                {{ $order->billing_address['address'] ?? '' }}<br>
                                {{ $order->billing_address['city'] ?? '' }}@if(!empty($order->billing_address['district'])), {{ $order->billing_address['district'] }}@endif, 
                                {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['pincode'] ?? '' }}
                            </p>
                            <div class="order-address-phone">
                                <i class="fas fa-phone-alt"></i>
                                <span>{{ $order->billing_address['phone'] ?? 'N/A' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Items Column -->
            <div class="order-details-main" data-aos="fade-left" data-aos-delay="200">
                <!-- Items Header -->
                <div class="order-items-header">
                    <h5 class="order-items-title"><i class="fas fa-shopping-basket"></i> Items ({{ $order->items->count() }})</h5>
                </div>

                <!-- Items List -->
                <div class="order-items-list">
                    @foreach($order->items as $item)
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
                        
                        <div class="order-item-card">
                            <!-- Image Box -->
                            <a href="{{ $productUrl }}" class="order-item-image-wrapper">
                                <img src="{{ $imgSrc }}" class="order-item-img" alt="{{ $item->product_name }}" onerror="this.src='{{ asset('assets/images/product/product1.jpg') }}'">
                            </a>
                            
                            <!-- Details Box -->
                            <div class="order-item-content">
                                <div class="order-item-info">
                                    <div class="order-item-title-row">
                                        <a href="{{ $productUrl }}" class="order-item-name">{{ $item->product_name }}</a>
                                        @if($item->custom_combo_id)
                                            <span class="order-item-combo-badge">COMBO</span>
                                        @endif
                                    </div>
                                    
                                    @if($item->options)
                                        @php $options = is_string($item->options) ? json_decode($item->options, true) : $item->options; @endphp
                                        @if(is_array($options))
                                            <div class="order-item-meta">
                                                @php $displayed = []; @endphp
                                                @foreach($options as $k => $v)
                                                    @if(!is_array($v) && !is_object($v) && $k !== 'custom_combo_id' && $k !== 'combo_products')
                                                        @php $displayed[] = ucfirst($k) . ': <span>' . e($v) . '</span>'; @endphp
                                                    @endif
                                                @endforeach
                                                {!! implode(' &bull; ', $displayed) !!}
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <div class="order-item-meta">Weight: <span>{{ number_format($item->weight ?? 0, 2) }}g</span></div>
                                    
                                    <div class="order-item-qty">
                                        <span class="qty-label">Qty:</span>
                                        <span class="qty-value">{{ $item->quantity }}</span>
                                    </div>
                                </div>
                                
                                <!-- Price Box -->
                                <div class="order-item-price-box">
                                    <div class="order-item-price-each">₹{{ number_format($item->price, 2) }} each</div>
                                    <h5 class="order-item-price-total">₹{{ number_format($item->price * $item->quantity, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Card -->
                <div class="order-card summary-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="order-card-header">
                        <h6 class="order-card-title"><i class="fas fa-file-invoice-dollar"></i> Order Summary</h6>
                    </div>
                    <div class="order-card-body">
                        <div class="order-summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="order-summary-row">
                            <span class="summary-label">Shipping</span>
                            <span class="summary-value {{ $order->shipping == 0 ? 'text-success' : '' }}">
                                {{ $order->shipping > 0 ? '₹'.number_format($order->shipping, 2) : 'Free' }}
                            </span>
                        </div>
                        
                        @if($order->igst > 0)
                        <div class="order-summary-row border-bottom-row">
                            <span class="summary-label">IGST</span>
                            <span class="summary-value">₹{{ number_format($order->igst, 2) }}</span>
                        </div>
                        @elseif($order->cgst > 0 || $order->sgst > 0)
                        <div class="order-summary-row">
                            <span class="summary-label">CGST</span>
                            <span class="summary-value">₹{{ number_format($order->cgst, 2) }}</span>
                        </div>
                        <div class="order-summary-row border-bottom-row">
                            <span class="summary-label">SGST</span>
                            <span class="summary-value">₹{{ number_format($order->sgst, 2) }}</span>
                        </div>
                        @else
                        <div class="order-summary-row border-bottom-row">
                            <span class="summary-label">Tax (GST)</span>
                            <span class="summary-value">₹{{ number_format($order->tax ?? 0, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($order->discount > 0)
                        <div class="order-summary-row summary-discount">
                            <span class="summary-label text-success"><i class="fas fa-tag"></i> Discount</span>
                            <span class="summary-value text-success">
                                -₹{{ number_format($order->discount, 2) }}
                            </span>
                        </div>
                        @endif
                        
                        <div class="order-summary-total">
                            <span class="total-label">Total:</span>
                            <span class="total-value">₹{{ number_format($order->total, 2) }}</span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="order-actions-container" data-aos="fade-up" data-aos-delay="400">
                            <a href="{{ route('user.order.invoice', $order->id) }}" class="order-action-btn invoice-btn">
                                <i class="fas fa-file-download"></i>
                                <span class="desktop-text">Download Invoice</span>
                                <span class="mobile-text">Invoice</span>
                            </a>
                            <a href="{{ route('user.dashboard') }}" class="order-action-btn back-btn">
                                <i class="fas fa-arrow-left"></i>
                                <span class="desktop-text">Back to Orders</span>
                                <span class="mobile-text">Back</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('view.layout.footer')