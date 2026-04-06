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

                    <!-- Shipping Address Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="fas fa-shipping-fast text-success me-2"></i> Shipping Address
                            </h5>
                            <div class="align-self-start align-self-sm-center">
                                <a href="{{ route('checkout.address') }}" class="btn btn-success btn-xs-comp rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-edit me-1"></i> Change
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="shipping-info-box p-3 rounded-3 bg-light border">
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
                                        @foreach($cartItems as $item)
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
                                                        <div class="text-muted small">
                                                            W: {{ $p->weight ?? 0 }} KG
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card (Step 3) -->
                    <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white py-3 border-bottom-0">
                                <h5 class="mb-0 fw-bold d-flex align-items-center">
                                    <i class="fas fa-credit-card text-success me-2"></i> Payment Method
                                </h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="payment-selection-modern">
                                    <div class="payment-option-modern active">
                                        <input type="radio" name="payment_method" value="online" id="pay_online" checked class="d-none">
                                        <label for="pay_online" class="w-100 cursor-pointer">
                                            <div class="d-flex align-items-center p-3 border rounded-3 position-relative transition-all payment-card-inner">
                                                <div class="payment-icon-circle me-3">
                                                    <i class="fas fa-shield-alt text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark">Secure Online Payment</div>
                                                    <div class="small text-success fw-medium">Zero Transaction fee • Instant Confirmation</div>
                                                </div>
                                                <div class="check-icon">
                                                    <i class="fas fa-check-circle text-primary fs-4"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="accepted-cards-container mt-4 p-3 rounded-3 bg-light border-dashed">
                                        <div class="text-center mb-3">
                                            <span class="text-muted small fw-medium">We accept all major payment modes</span>
                                        </div>
                                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 payment-icons-row">
                                            <img src="/assets/images/visa.png" alt="Visa" class="payment-brand-img" title="Visa">
                                            <img src="/assets/images/rupay.png" alt="RuPay" class="payment-brand-img" title="RuPay">
                                            <img src="/assets/images/gpay.png" alt="Google Pay" class="payment-brand-img" title="Google Pay">
                                            <img src="/assets/images/paytm.png" alt="Paytm" class="payment-brand-img" title="Paytm">
                                            <img src="/assets/images/upi.png" alt="UPI" class="payment-brand-img" title="UPI">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Submit Button (Sticky Bottom) - Hidden on Desktop -->
                        <div class="d-lg-none sticky-bottom bg-white p-3 border-top shadow-lg-reverse">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-muted small">Total Payable</span>
                                <span class="fw-bold text-dark fs-5">₹{{ number_format($total, 2) }}</span>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-2-5 shadow-success">
                                PAY & PLACE ORDER <i class="fas fa-lock ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Sticky Summary -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-light text-dark py-3">
                            <h6 class="mb-0 fw-bold text-center">ORDER SUMMARY</h6>
                        </div>
                         <div class="card-body p-4">
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Products ({{ $itemCount }})</span>
                                <span class="fw-medium text-dark">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Weight</span>
                                <span class="fw-medium text-dark">{{ number_format($totalWeight, 2) }} KG</span>
                            </div>
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping Fee</span>
                                <span class="fw-bold text-success">@if($shipping > 0) +₹{{ number_format($shipping, 2) }} @else FREE @endif</span>
                            </div>

                            @if(isset($gstSettings) && $gstSettings->gst_status)
                            <div class="summary-line d-flex justify-content-between mb-2">
                                <span class="text-muted">GST ({{ number_format($gstSettings->gst_percentage, 1) }}%)</span>
                                <span class="fw-medium text-danger">+₹{{ number_format($tax, 2) }}</span>
                            </div>
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

                            <hr class="my-4 border-2">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="h5 mb-0 fw-bold">Grand Total</span>
                                <span class="h4 mb-0 fw-bold text-success">₹{{ number_format($total, 2) }}</span>
                            </div>

                            <button type="submit" form="checkout-form" class="btn btn-success btn-lg w-100 rounded-pill fw-bold py-2-5 shadow-success-hover d-none d-lg-block">
                                SECURE CHECKOUT <i class="fas fa-lock ms-2"></i>
                            </button>

                            <div class="mt-4 text-center">
                                <div class="text-muted extra-small mb-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-shield-alt text-success me-2"></i> 256-bit Secure Encryption
                                </div>
                            </div>
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
        --secondary-color: #0d6efd;
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
        background-color: #f8fbff;
    }

    .payment-icon-circle {
        width: 45px;
        height: 45px;
        background: #eef4ff;
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
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-success:hover {
        background-color: #5a821a;
        border-color: #5a821a;
    }

    .shadow-success {
        box-shadow: 0 8px 20px rgba(114, 164, 32, 0.3);
    }

    .shadow-success-hover:hover {
        box-shadow: 0 8px 25px rgba(114, 164, 32, 0.4);
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
            height: 18px;
        }

        .card-header h5 {
            font-size: 16px;
        }
    }

    .hover-opacity-100:hover { opacity: 1 !important; transform: scale(1.1); transition: all 0.2s; }

    .payment-brand-img {
        height: 22px;
        width: auto;
        object-fit: contain;
        filter: grayscale(100%);
        opacity: 0.7;
        transition: all 0.3s ease;
    }
    
    .payment-brand-img:hover {
        filter: grayscale(0);
        opacity: 1;
        transform: translateY(-2px);
    }
</style>

<script>
    function removeFromCartSummary(itemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to remove this item from your order?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#72a420",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "Keep it"
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a temporary form to send DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('cart/remove') }}/" + itemId + "?redirect=checkout";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<script>
async function removeFromCartSummary(itemId) {
    const { value: confirmed } = await Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#72a420',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    });

    if (confirmed) {
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
                window.location.reload();
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