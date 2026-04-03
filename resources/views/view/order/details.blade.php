@include('view.layout.header')

<!-- Page Path / Header Space -->
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none" style="text-transform: capitalize;">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('user.dashboard') }}" class="text-decoration-none" style="text-transform: capitalize;">dashboard</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder text-muted" style="text-transform: capitalize;">order details</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<style>
    .order-details-container {
        background: #fff;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .info-section {
        margin-bottom: 25px;
    }
    .info-section h4 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    .detail-row {
        margin-bottom: 8px;
        color: #555;
        font-size: 0.95rem;
    }
    .detail-row strong {
        color: #333;
        font-weight: 500;
        display: inline-block;
        width: 140px;
    }
    .shipping-box {
        background-color: #f9f9f9;
        border-left: 4px solid #007bff;
        padding: 15px 20px;
        border-radius: 4px;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .item-row {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    .item-image {
        width: 60px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 15px;
    }
    .item-details h5 {
        font-size: 0.95rem;
        margin: 0 0 5px 0;
        color: #333;
    }
    .item-details p {
        margin: 0 0 3px 0;
        color: #666;
        font-size: 0.85rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: #555;
    }
    .summary-row.total {
        font-weight: bold;
        color: #333;
        font-size: 1.1rem;
        border-top: 1px solid #ddd;
        padding-top: 15px;
        margin-top: 5px;
    }
</style>

<div class="container py-4">
    <div class="order-details-container">
        
        <div class="row mb-5">
            <!-- Order Info -->
            <div class="col-md-6 info-section">
                <h4>Order Details</h4>
                <div class="detail-row">
                    <strong>Order ID:</strong> {{ $order->order_number }}
                </div>
                <div class="detail-row">
                    <strong>Order Date:</strong> {{ $order->created_at->format('n/j/Y') }}
                </div>
                <div class="detail-row">
                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </div>
                <div class="detail-row">
                    <strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'credit Card') }}
                </div>
                <div class="detail-row">
                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'Paid') }}
                </div>
                <div class="detail-row" style="margin-top: 15px;">
                    @if(in_array(strtolower($order->status), ['delivered', 'completed']))
                        <strong>Delivered On:</strong> {{ $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('n/j/Y') : $order->updated_at->format('n/j/Y') }}
                    @else
                        <strong>Arriving On:</strong> {{ $order->created_at->addDays(2)->format('n/j/Y') }} <span class="text-muted" style="font-size: 0.8rem;">(Estimated)</span>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="col-md-6 info-section">
                <h4>Shipping Address</h4>
                <div class="shipping-box">
                    <div class="detail-row" style="margin-bottom: 5px;">
                        <strong>Name:</strong> {{ $order->shipping_address['name'] ?? ($order->user->name ?? 'Guest') }}
                    </div>
                    <div class="detail-row" style="margin-bottom: 5px;">
                        <strong>Address:</strong> @if(!empty($order->shipping_address['door_number'])){{ $order->shipping_address['door_number'] }}, @endif @if(!empty($order->shipping_address['street'])){{ $order->shipping_address['street'] }}, @endif {{ $order->shipping_address['address'] ?? '' }}
                    </div>
                    <div class="detail-row" style="margin-bottom: 5px;">
                        <strong>City:</strong> {{ $order->shipping_address['city'] ?? '' }}, @if(!empty($order->shipping_address['district'])){{ $order->shipping_address['district'] }}, @endif {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}
                    </div>
                    <div class="detail-row" style="margin-bottom: 5px;">
                        <strong>Country:</strong> {{ $order->shipping_address['country'] ?? 'India' }}
                    </div>
                    <div class="detail-row" style="margin-bottom: 0;">
                        <strong>Phone:</strong> {{ $order->shipping_address['phone'] ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="info-section">
            <h4>Items ({{ $order->items->count() }})</h4>
            <div style="border-top: 1px solid #eee;">
                @foreach($order->items as $item)
                <div class="item-row">
                    @php
                        $pImg = $item->product_image;
                        if (!$pImg && $item->product) {
                            $pImg = $item->product->image;
                        }
                        if (is_string($pImg) && str_starts_with($pImg, '[')) {
                            $decoded = json_decode($pImg, true);
                            $pImg = is_array($decoded) && count($decoded) > 0 ? $decoded[0] : '';
                        }
                        $imgSrc = $pImg ? asset('storage/' . $pImg) : asset('assets/images/product/product1.jpg');
                        // Fallback check
                        if (is_string($pImg) && (str_starts_with($pImg, 'http') || str_starts_with($pImg, 'assets/'))) {
                            $imgSrc = asset($pImg);
                        }
                    @endphp
                    @if($item->product && $item->product->slug)
                        <a href="{{ route('product.show', $item->product->slug) }}">
                            <img src="{{ $imgSrc }}" alt="{{ $item->product_name }}" class="item-image" onerror="this.src='{{ asset('assets/images/product/product1.jpg') }}'">
                        </a>
                    @else
                        <img src="{{ $imgSrc }}" alt="{{ $item->product_name }}" class="item-image" onerror="this.src='{{ asset('assets/images/product/product1.jpg') }}'">
                    @endif
                    
                    <div class="item-details flex-grow-1">
                        <h5>
                            @if($item->product && $item->product->slug)
                                <a href="{{ route('product.show', $item->product->slug) }}" style="color: inherit; text-decoration: none;">
                                    {{ $item->product_name ?? $item->product->name ?? 'Product' }}
                                </a>
                            @else
                                {{ $item->product_name ?? $item->product->name ?? 'Product' }}
                            @endif
                            (x{{ $item->quantity }})
                        </h5>
                        @if($item->options)
                            @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                            @if(is_array($options))
                                <p>
                                    @foreach($options as $key => $val)
                                        {{ ucfirst($key) }}: {{ $val }}@if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            @elseif(is_string($options) && !empty($options))
                                <p>Option: {{ $options }}</p>
                            @endif
                        @endif
                        <p>₹{{ number_format($item->price, 2) }} each = ₹{{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="row mt-4">
            <div class="col-md-6"></div>
            <div class="col-md-6 info-section">
                <h4>Order Summary</h4>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>₹{{ number_format($order->shipping ?? 0, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Tax</span>
                    <span>₹{{ number_format($order->tax ?? 0, 2) }}</span>
                </div>
                @if($order->discount > 0)
                <div class="summary-row text-success">
                    <span>Discount</span>
                    <span>- ₹{{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex align-items-center mt-4 pt-3" style="border-top: 1px solid #eee;">
            <a href="{{ route('user.order.invoice', $order->id) }}" class="btn" style="background-color: #81dd50ff; color: white; padding: 10px 20px; border-radius: 4px; font-weight: 500; text-decoration: none; margin-right: 15px;">
                <i class="fas fa-download me-2" style="margin-right: 8px;"></i> Download PDF
            </a>
            <a href="{{ route('user.dashboard') }}" class="btn btn-light" style="border: 1px solid #ccc; background-color: #f8f9fa; padding: 10px 20px; border-radius: 4px; color: #555; text-decoration: none;">
                <i class="fas fa-arrow-left me-2" style="margin-right: 8px;"></i> Back
            </a>
        </div>

    </div>
</div>
@include('view.layout.footer')
