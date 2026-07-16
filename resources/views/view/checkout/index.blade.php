@include('view.layout.header')

<div class="checkout-page-wrapper bg-light py-4 py-md-5">
    <div class="container">
        <!-- Modern Step Header -->
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom d-md-none">
            <a href="{{ route('checkout.address') }}" class="text-dark text-decoration-none me-3">
                <i class="fas fa-arrow-left fs-4"></i>
            </a>
            <div>
                <div class="text-muted extra-small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Step 3 of 3</div>
                <h5 class="mb-0 fw-bold text-dark">Order Details & Payment</h5>
            </div>
        </div>

        <!-- Desktop Steps Indicator -->
        <div class="checkout-steps-container d-none d-md-flex justify-content-between align-items-center mb-4 mt-2" style="border-bottom: none; padding-bottom: 0;">
            <div class="d-flex align-items-center">
                <div class="step-item completed fw-semibold" style="font-size: 15px;">
                    <a href="{{ route('checkout.address') }}" class="text-decoration-none" style="color: #388e3c;">Address</a>
                </div>
                <div class="step-divider mx-3" style="color: #388e3c;"><i class="fas fa-chevron-right" style="font-size: 11px;"></i></div>
                
                <div class="step-item active fw-semibold" style="color: #388e3c; font-size: 15px;">
                    Payment & Review
                </div>
            </div>
            <div class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.85rem;">
                Step 2 of 2
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Shipping & Payment -->
            <div class="col-lg-8">
                <div class="checkout-main-content">

                    <!-- Shipping Address Bar -->
                    <div class="d-flex flex-column p-3 bg-white mb-3 rounded shadow-sm border" style="border-color: #e2e8f0 !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px; color: #64748b;"><i class="fas fa-truck me-2"></i>Shipping Address</div>
                            <a href="{{ route('checkout.address') }}#shipping-address-form" class="text-decoration-none fw-bold px-3 py-1 rounded shadow-sm transition-all d-flex align-items-center" style="font-size: 12px; color: #1f2937; background-color: #ffffff; border: 1px solid #4b5563;"><i class="fas fa-edit me-1" style="color: #4b5563;"></i> Edit</a>
                        </div>
                        <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                            <i class="fas fa-map-marker-alt fs-5 me-3" style="color: #1e293b;"></i>
                            <div class="text-truncate" style="font-size: 14.5px;">
                                <span class="fw-bold" style="color: #0f172a;">{{ explode(' ', trim($shippingAddress['name'] ?? ''))[0] ?? '' }}</span> 
                                <span class="text-muted mx-1">|</span>
                                <span style="color: #334155;">{{ $shippingAddress['address'] ?? '' }}, {{ $shippingAddress['city'] ?? '' }}, {{ $shippingAddress['state'] ?? '' }} - {{ $shippingAddress['pincode'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address Bar -->
                    <div class="d-flex flex-column p-3 bg-white mb-4 rounded shadow-sm border" style="border-color: #e2e8f0 !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px; color: #64748b;"><i class="fas fa-file-invoice-dollar me-2"></i>Billing Address</div>
                            <a href="{{ route('checkout.address') }}#shipping-address-form" class="text-decoration-none fw-bold px-3 py-1 rounded shadow-sm transition-all d-flex align-items-center" style="font-size: 12px; color: #1f2937; background-color: #ffffff; border: 1px solid #4b5563;"><i class="fas fa-edit me-1" style="color: #4b5563;"></i> Edit</a>
                        </div>
                        <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                            <i class="fas fa-map-marker-alt fs-5 me-3" style="color: #1e293b;"></i>
                            <div class="text-truncate" style="font-size: 14.5px;">
                                <span class="fw-bold" style="color: #0f172a;">{{ explode(' ', trim($billingAddress['name'] ?? ''))[0] ?? '' }}</span> 
                                <span class="text-muted mx-1">|</span>
                                <span style="color: #334155;">{{ $billingAddress['address'] ?? '' }}, {{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }} - {{ $billingAddress['pincode'] ?? '' }}</span>
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
                                                            <span class="badge text-white" style="background-color: #2e7d32; font-size: 10px; font-weight: 600; padding: 4px 8px; margin-right: 8px; border-radius: 4px;">CUSTOM COMBO PACK</span>
                                                            @if($pct > 0)
                                                            <span class="badge bg-danger text-white" style="font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 4px;">{{ $pct }}% OFF APPLIED</span>
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
                        <div class="fixed-bottom bg-white border-top p-3 d-flex justify-content-between align-items-center shadow-lg d-md-none" style="z-index: 1050;">
                            <div>
                                <div class="fw-bold text-dark fs-5">₹{{ number_format($total, 2) }}</div>
                                <div class="text-primary small fw-medium" data-bs-toggle="modal" data-bs-target="#orderSummaryModalPayment">View details</div>
                            </div>
                            <button type="submit" class="btn btn-dark px-4 py-2 fw-bold" style="border-radius: 6px;">
                                Pay Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mobile Order Summary Modal for Payment Step -->
            <div class="modal fade" id="orderSummaryModalPayment" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-bottom modal-fullscreen-sm-down">
                    <div class="modal-content border-0 rounded-top-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Products ({{ $itemCount ?? 0 }})</span>
                                <span class="text-dark">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if(isset($discount) && $discount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bag Savings</span>
                                <span class="text-success fw-bold">-₹{{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee</span>
                                <span class="text-dark">@if(isset($shipping) && $shipping > 0) ₹{{ number_format($shipping, 2) }} @else <span class="text-success">Free</span> @endif</span>
                            </div>
                            
                            @if(isset($gstSettings) && $gstSettings->gst_status)
                                @if($igst > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">IGST</span>
                                    <span class="text-dark">+₹{{ number_format($igst, 2) }}</span>
                                </div>
                                @elseif($cgst > 0 || $sgst > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">CGST & SGST</span>
                                    <span class="text-dark">+₹{{ number_format($cgst + $sgst, 2) }}</span>
                                </div>
                                @endif
                            @endif
                            <hr class="border-dashed">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Amount Payable</span>
                                <span>₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sticky Summary -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header py-3" style="background-color: #388e3c !important; border-bottom: none;">
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

@include('view.layout.footer')