@include('view.layout.header')

<div class="checkout-page-wrapper bg-light py-4 py-md-5">
    <div class="container">
        <!-- Modern Breadcrumb/Steps -->
        <nav aria-label="breadcrumb" class="mb-4 d-none d-md-block">
            <ol class="breadcrumb checkout-steps justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item"><a href="{{ route('checkout.address') }}">Address</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment & Review</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Left Column: Shipping & Payment -->
            <div class="col-lg-8">
                <div class="checkout-main-content">

                    <!-- Address Info Row -->
                    <div class="row g-4 mb-4">
                        <!-- Shipping Address Column -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                                        <i class="fas fa-shipping-fast text-success me-2"></i> Shipping Address
                                    </h5>
                                    <div>
                                        <a href="{{ route('checkout.address') }}" class="btn btn-success btn-xs-comp rounded-pill px-3 shadow-sm">
                                            <i class="fas fa-edit me-1"></i> Change
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="shipping-info-box p-3 rounded-3 bg-light border h-100">
                                        <div class="address-details">
                                            <h6 class="fw-bold mb-1">{{ $shippingAddress['name'] }}</h6>
                                            <p class="text-muted mb-0 small line-height-base">
                                                @if(!empty($shippingAddress['door_number'])){{ $shippingAddress['door_number'] }}, @endif{{ $shippingAddress['address'] }}<br>
                                                {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} - {{ $shippingAddress['pincode'] }}<br>
                                                <span class="text-dark fw-medium mt-1 d-block"><i class="fas fa-phone-alt me-1 small"></i> {{ $shippingAddress['phone'] }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Billing Address Column -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                                        <i class="fas fa-file-invoice text-success me-2"></i> Billing Address
                                    </h5>
                                    <div>
                                        <a href="{{ route('checkout.address') }}" class="btn btn-success btn-xs-comp rounded-pill px-3 shadow-sm">
                                            <i class="fas fa-edit me-1"></i> Change
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="shipping-info-box p-3 rounded-3 bg-light border h-100">
                                        <div class="address-details">
                                            @if($billingSame)
                                                <p class="text-muted mb-0 small py-3 text-center">
                                                    <i class="fas fa-check-circle text-success me-1"></i> Same as shipping address
                                                </p>
                                            @else
                                                <h6 class="fw-bold mb-1">{{ $billingAddress['name'] ?? '' }}</h6>
                                                <p class="text-muted mb-0 small line-height-base">
                                                    @if(!empty($billingAddress['door_number'])){{ $billingAddress['door_number'] }}, @endif{{ $billingAddress['address'] ?? '' }}<br>
                                                    {{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }} - {{ $billingAddress['pincode'] ?? '' }}<br>
                                                    <span class="text-dark fw-medium mt-1 d-block"><i class="fas fa-phone-alt me-1 small"></i> {{ $billingAddress['phone'] ?? '' }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Review Items Card (Step 2) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                            <h5 class="mb-2 mb-sm-0 fw-bold d-flex align-items-center">
                                <i class="fas fa-shopping-basket text-success me-2"></i> Review Items
                            </h5>
                            <div class="align-self-start align-self-sm-center">
                                <a href="{{ route('cart.index') }}" class="btn btn-success btn-xs-comp rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-edit me-1"></i> Edit Cart
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 py-3 text-muted small fw-bold border-0">PRODUCT</th>
                                            <th class="text-center py-3 text-muted small fw-bold border-0">QTY</th>
                                            <th class="text-end py-3 text-muted small fw-bold border-0">PRICE</th>
                                            <th class="pe-4 py-3 text-muted small fw-bold border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $groupedCheckoutItems = $cartItems->groupBy(function($item) {
                                            return $item->custom_combo_id ?? 'single';
                                        });
                                        @endphp
                                        @foreach($groupedCheckoutItems as $comboId => $groupItems)
                                            @if($comboId === 'single')
                                                @foreach($groupItems as $item)
                                                    @php
                                                    $isCombo = (bool) $item->combo_pack_id;
                                                    $p = $isCombo ? $item->comboPack : $item->product;
                                                    $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                                    $priceToUse = $item->calculated_price;
                                                    @endphp
                                                    <tr>
                                                        <td class="ps-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="product-thumb-sm me-4 bg-white border rounded">
                                                                    @if($isCombo && !$p->is_combo_only && is_array($imgData) && count($imgData) >= 2)
                                                                    <div class="dual-images">
                                                                        <img src="{{ asset('storage/' . $imgData[0]) }}" alt="">
                                                                        <img src="{{ asset('storage/' . $imgData[1]) }}" alt="">
                                                                    </div>
                                                                    @else
                                                                    @php
                                                                    $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                                                    @endphp
                                                                    <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}">
                                                                    @endif
                                                                </div>
                                                                <div>
                                                                    <div class="fw-bold text-dark fs-7 mb-1 line-clamp-1" title="{{ $p->name }}">{{ $p->name }}</div>
                                                                    @if($item->options)
                                                                    @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                                                    @if(is_array($options) && isset($options['size']))
                                                                    <div class="text-muted extra-small mb-1">Size: {{ $options['size'] }}</div>
                                                                    @elseif(is_string($options) && !empty($options))
                                                                    <div class="text-muted extra-small mb-1">Size: {{ $options }}</div>
                                                                    @endif
                                                                    @endif
                                                                    <div class="text-muted small">
                                                                        W: {{ number_format($item->calculated_weight, 2) }} grams
                                                                        @if($isCombo) <span class="badge bg-danger-soft text-danger ms-1">COMBO</span> @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center py-3">
                                                            <span class="badge bg-light text-dark border fw-medium px-3 py-2">× {{ $item->quantity }}</span>
                                                        </td>
                                                        <td class="text-end py-3">
                                                            <div class="fw-bold">₹{{ number_format($priceToUse * $item->quantity, 2) }}</div>
                                                            <div class="text-muted extra-small">₹{{ number_format($priceToUse, 2) }} / unit</div>
                                                        </td>
                                                        <td class="pe-4 py-3 text-end">
                                                            <button type="button" class="btn-close-style" 
                                                                    onclick="removeFromCartSummary('{{ $item->id }}')" title="Remove Item">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @php
                                                $comboSubtotal = $groupItems->sum(function($item) {
                                                    return $item->calculated_price * $item->quantity;
                                                });
                                                $slabs = \App\Models\ComboPackDiscountSlab::where('status', true)->orderBy('min_amount', 'asc')->get();
                                                $pct = 0;
                                                foreach($slabs as $slab) {
                                                    if ($comboSubtotal >= $slab->min_amount) {
                                                        $pct = (float)$slab->discount_percentage;
                                                    }
                                                }
                                                $comboDiscount = $comboSubtotal * ($pct / 100);
                                                @endphp
                                                <tr style="background-color: #f7f9f6; border-top: 2px solid #d2e1cd; border-bottom: 1px solid #d2e1cd;">
                                                    <td colspan="2" class="ps-4 py-2">
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge badge-success me-2" style="background-color: #2e7d32; font-size: 10px; font-weight: 600; padding: 3px 6px;">CUSTOM COMBO PACK</span>
                                                            @if($pct > 0)
                                                            <span class="badge bg-danger text-white font-weight-bold" style="font-size: 10px; padding: 3px 6px;">{{ $pct }}% OFF APPLIED</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-end py-2">
                                                        <span class="fw-bold text-success" style="font-size: 14px;">₹{{ number_format($comboSubtotal - $comboDiscount, 2) }}</span>
                                                    </td>
                                                    <td class="pe-4 py-2 text-end">
                                                    </td>
                                                </tr>
                                                @foreach($groupItems as $item)
                                                    @php
                                                    $p = $item->product;
                                                    $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                                    @endphp
                                                    <tr style="background-color: #fcfdfc; border-bottom: 1px solid #f2f2f2; {{ $loop->last ? 'border-bottom: 2px solid #d2e1cd;' : '' }}">
                                                        <td class="ps-5 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <div class="product-thumb-sm me-3 bg-white border rounded" style="width: 45px; height: 45px;">
                                                                    @php
                                                                    $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                                                    @endphp
                                                                    <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}">
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium text-dark small">{{ $p->name }}</div>
                                                                    @if($item->options)
                                                                    @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                                                    @if(is_array($options) && isset($options['size']))
                                                                    <div class="text-muted extra-small mb-1">Size: {{ $options['size'] }}</div>
                                                                    @elseif(is_string($options) && !empty($options))
                                                                    <div class="text-muted extra-small mb-1">Size: {{ $options }}</div>
                                                                    @endif
                                                                    @endif
                                                                    <div class="text-muted extra-small">W: {{ number_format($item->calculated_weight, 2) }} grams</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center py-2">
                                                            <span class="text-muted extra-small">1 (custom combo pack)</span>
                                                        </td>
                                                        <td class="text-end py-2 pe-3">
                                                            <span class="text-muted small">₹{{ number_format($item->calculated_price, 2) }}</span>
                                                        </td>
                                                        <td class="pe-4 py-2"></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Form -->
                    <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="payment_method" value="online">

                        <!-- Mobile Submit Button (Sticky Bottom) - Hidden on Desktop -->
                        <div class="d-lg-none sticky-bottom bg-white p-3 border-top shadow-lg-reverse">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-muted small">Total Payable</span>
                                <span class="fw-bold text-dark fs-5">₹{{ number_format($total, 2) }}</span>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-2-5 shadow-success">
                                <span class="d-none d-lg-inline">PAY & PLACE ORDER</span>
                                <span class="d-inline d-lg-none">PLACE ORDER</span>
                                <i class="fas fa-lock ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Sticky Summary -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header py-3" style="background-color: var(--secondary-color, #2b6139) !important; color: #ffffff !important;">
                            <h6 class="mb-0 fw-bold text-center text-white" style="color: #ffffff !important;">ORDER SUMMARY</h6>
                        </div>
                         <div class="card-body p-4">
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Products ({{ $itemCount }})</span>
                                <span class="fw-medium text-dark">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Weight</span>
                                <span class="fw-medium text-dark">{{ number_format($totalWeight, 2) }} grams</span>
                            </div>
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping Fee</span>
                                <span class="fw-bold text-success">@if($shipping > 0) +₹{{ number_format($shipping, 2) }} @else FREE @endif</span>
                            </div>

                            @if(isset($gstSettings) && $gstSettings->gst_status)
                                @if($igst > 0)
                                <div class="summary-line d-flex justify-content-between mb-2">
                                    <span class="text-muted">IGST ({{ number_format($gstSettings->gst_percentage, 1) }}%)</span>
                                    <span class="fw-medium text-danger">+₹{{ number_format($igst, 2) }}</span>
                                </div>
                                @elseif($cgst > 0 || $sgst > 0)
                                <div class="summary-line d-flex justify-content-between mb-1">
                                    <span class="text-muted">CGST ({{ number_format($gstSettings->gst_percentage/2, 1) }}%)</span>
                                    <span class="fw-medium text-danger">+₹{{ number_format($cgst, 2) }}</span>
                                </div>
                                <div class="summary-line d-flex justify-content-between mb-2">
                                    <span class="text-muted">SGST ({{ number_format($gstSettings->gst_percentage/2, 1) }}%)</span>
                                    <span class="fw-medium text-danger">+₹{{ number_format($sgst, 2) }}</span>
                                </div>
                                @else
                                <div class="summary-line d-flex justify-content-between mb-2">
                                    <span class="text-muted">GST ({{ number_format($gstSettings->gst_percentage, 1) }}%)</span>
                                    <span class="fw-medium text-danger">+₹{{ number_format($tax, 2) }}</span>
                                </div>
                                @endif
                            @else
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Estimated Tax</span>
                                <span class="fw-medium">₹{{ number_format($tax, 2) }}</span>
                            </div>
                            @endif

                            @if(($discount ?? 0) > 0)
                            <div class="summary-line d-flex justify-content-between mb-2 text-success">
                                <span>Discount</span>
                                <span class="fw-bold">-₹{{ number_format($discount, 2) }}</span>
                            </div>
                            @endif

                            @if(isset($couponDiscount) && $couponDiscount > 0)
                            <div class="summary-line d-flex justify-content-between mb-2 text-success">
                                <span>Coupon Discount ({{ $coupon->coupon_code ?? '' }})</span>
                                <span class="fw-bold">-₹{{ number_format($couponDiscount, 2) }}</span>
                            </div>
                            @endif


                            <hr class="my-4 border-2">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="h5 mb-0 fw-bold">Grand Total</span>
                                <span class="h4 mb-0 fw-bold text-success">₹{{ number_format($total, 2) }}</span>
                            </div>

                            <button type="submit" form="checkout-form" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-2-5 shadow-success-hover d-none d-lg-block" style="font-size: 15px;">
                                SECURE CHECKOUT <i class="fas fa-lock ms-2"></i>
                            </button>

                            <!-- <div class="mt-4 text-center">
                                <div class="text-muted extra-small mb-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-shield-alt text-success me-2"></i> 256-bit Secure Encryption
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #72a420;
        --secondary-color: #2b6139;
        --light-bg: #f8f9fa;
        --border-dashed: #dee2e6;
    }

    .checkout-page-wrapper {
        min-height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .checkout-steps .breadcrumb-item+.breadcrumb-item::before {
        content: "\f054";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 10px;
        color: #adb5bd;
    }

    .checkout-steps .breadcrumb-item {
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .checkout-steps .breadcrumb-item.active {
        color: var(--primary-color);
    }

    .checkout-steps .breadcrumb-item a {
        color: #adb5bd;
        text-decoration: none;
    }

    .btn-xs-comp {
        padding: 5px 15px;
        font-size: 13px;
        font-weight: 700;
        color: #fff !important;
        letter-spacing: 0.3px;
    }

    .card {
        transition: transform 0.2s ease;
    }

    .bg-danger-soft {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .fs-7 { font-size: 14px; }
    .extra-small { font-size: 11px; }

    .line-height-base {
        line-height: 1.6;
    }

    .border-dashed {
        border: 2px dashed var(--border-dashed) !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .payment-card-inner {
        border-width: 2px !important;
        background: #fff;
    }

    .payment-option-modern.active .payment-card-inner {
        border-color: var(--secondary-color) !important;
        background-color: #f4f8f5;
    }

    .payment-icon-circle {
        width: 45px;
        height: 45px;
        background: #eaf0eb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .check-icon {
        opacity: 0;
        transform: scale(0);
        transition: 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
    }

    .payment-option-modern.active .check-icon {
        opacity: 1;
        transform: scale(1);
    }

    .product-thumb-sm {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-right: 15px !important;
    }

    .product-thumb-sm img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .dual-images {
        display: flex;
        width: 100%;
        height: 100%;
    }

    .dual-images img {
        width: 50%;
        object-fit: cover;
    }

    .btn-success {
        background-color: var(--secondary-color, #4a7856) !important;
        border-color: var(--secondary-color, #4a7856) !important;
        border-radius: 10px !important;
        border: none !important;
    }

    .btn-success:hover {
        background-color: #1c3f24 !important;
        border-color: #1c3f24 !important;
    }

    .shadow-success {
        box-shadow: 0 8px 20px rgba(74, 120, 86, 0.25) !important;
    }

    .shadow-success-hover:hover {
        box-shadow: 0 8px 25px rgba(59, 98, 71, 0.35) !important;
        transform: translateY(-2px);
        transition: all 0.3s;
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .shadow-lg-reverse {
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.1);
        z-index: 1050;
    }
    .py-2-5 { padding-top: 0.8rem !important; padding-bottom: 0.8rem !important; }

    @media (max-width: 991.98px) {
        .checkout-page-wrapper {
            padding-bottom: 120px !important;
        }

        .sticky-top {
            position: static !important;
        }
    }

    @media (max-width: 576px) {
        .payment-brand-img {
            height: 20px;
            margin: 6px 10px !important;
        }

        .card-header h5 {
            font-size: 16px;
        }
    }

    .hover-opacity-100:hover { opacity: 1 !important; transform: scale(1.1); transition: all 0.2s; }

    .payment-brand-img {
        height: 28px;
        width: auto;
        object-fit: contain;
        
        transition: all 0.3s ease;
        margin: 8px 16px !important;
    }
    
    .payment-brand-img:hover {
        filter: grayscale(0);
        opacity: 1;
        transform: translateY(-2px);
    }
</style>

<script>
async function removeFromCartSummary(itemId) {
    const result = await Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#72a420',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`${window.APP_URL}/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            if (data.success) {
                // If cart is now empty, redirect to cart page
                if (data.cart_count === 0) {
                    window.location.href = "{{ route('cart.index') }}";
                    return;
                }

                // Fetch the updated checkout page in the background
                const pageResponse = await fetch(window.location.href);
                const pageHtml = await pageResponse.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(pageHtml, 'text/html');

                // Swap the items table
                const oldTable = document.querySelector('.table-responsive');
                const newTable = doc.querySelector('.table-responsive');
                if (oldTable && newTable) {
                    oldTable.innerHTML = newTable.innerHTML;
                }

                // Swap the order summary card body
                const oldSummary = document.querySelector('.sticky-top');
                const newSummary = doc.querySelector('.sticky-top');
                if (oldSummary && newSummary) {
                    oldSummary.innerHTML = newSummary.innerHTML;
                }

                // Swap the mobile sticky bottom bar
                const oldStickyBottom = document.querySelector('.sticky-bottom');
                const newStickyBottom = doc.querySelector('.sticky-bottom');
                if (oldStickyBottom && newStickyBottom) {
                    oldStickyBottom.innerHTML = newStickyBottom.innerHTML;
                }

                // Update cart count badge in header
                document.querySelectorAll('.cart-icon-link .price_cart').forEach(el => el.textContent = data.cart_count);
                document.querySelectorAll('.cart-icon-link .sticky-badge').forEach(el => el.textContent = data.cart_count);

                // Show success toast
                Swal.fire({
                    icon: 'success',
                    title: data.message || 'Item removed',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to remove item', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to communicate with server', 'error');
        }
    }
}
</script>
<style>
.btn-close-style {
    background: transparent;
    border: none;
    color: #adb5bd;
    font-size: 14px;
    padding: 5px;
    transition: all 0.2s;
    cursor: pointer;
}
.btn-close-style:hover {
    color: #dc3545;
}
/* Product Thumbnails in table */
.product-thumb-sm {
    width: 60px;
    height: 60px;
    padding: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.product-thumb-sm img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
/* Ensure Inter font */
body { font-family: 'Inter', sans-serif !important; }
</style>
@include('view.layout.footer')