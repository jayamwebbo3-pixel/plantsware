@include('view.layout.header')

<div class="order-confirmation-container">
    <div class="container d-flex justify-content-center">
        <div class="confirmation-card" data-aos="zoom-in" data-aos-duration="800">
            
            <!-- Animated Checkmark -->
            

            <h1 class="conf-title" data-aos="fade-up" data-aos-delay="400">Order Confirmed!</h1>
            <p class="conf-subtitle" data-aos="fade-up" data-aos-delay="500">Thank you for letting Plantsware bring nature closer to you.</p>

            <div class="order-details-box" data-aos="fade-up" data-aos-delay="600">
                <div class="order-info-row">
                    <div class="info-label">Order Number</div>
                    <div class="info-value">#{{ $order->order_number }}</div>
                </div>
                

                <div class="order-info-row">
                    <div class="info-label">Product(s) Ordered</div>
                    <div class="info-value info-product">
                        @if($order->items && $order->items->count() > 0)
                            {{ $order->items->first()->product_name }}
                            @if($order->items->count() > 1)
                                <span class="text-muted" style="font-size: 0.85em; font-weight: 600;">+ {{ $order->items->count() - 1 }} more</span>
                            @endif
                        @else
                            Items securely placed
                        @endif
                    </div>
                </div>

                <div class="order-info-row">
                    <div class="info-label">Order Date</div>
                    <div class="info-value">{{ $order->created_at->format('d M, Y') }}</div>
                </div>

                <div class="order-info-row">
                    <div class="info-label">Total Amount</div>
                    <div class="info-value">₹{{ number_format($order->total ?? 0, 2) }}</div>
                </div>
            </div>

            <div class="btn-action-group" data-aos="fade-up" data-aos-delay="800">
                <a href="{{ route('user.dashboard') }}#order-history" class="btn-dashboard">
                    <i class="fas fa-boxes"></i>
                    <span class="d-none d-lg-inline">View Details</span>
                    <span class="d-inline d-lg-none">Details</span>
                </a>
                <a href="{{ url('/') }}" class="btn-shop">
                    <i class="fas fa-leaf"></i> Continue Shopping
                </a>
            </div>

        </div>
    </div>
</div>

@include('view.layout.footer')