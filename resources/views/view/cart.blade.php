@include('view.layout.header')

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- 
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">Cart</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
 --}}

<main class="cart-section">
<div class="container wishlist-page-container">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up" data-aos-duration="600">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-cart me-2 text-custom"></i> Shopping Cart</h4>
                        <div>
                            <a class="btn btn-outline-dark rounded-pill btn-sm px-3" href="{{ route('home') }}">
                                <i class="fas fa-arrow-left me-1"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-3">
                        @if(isset($cartItems) && $cartItems->count() > 0)
                        <div class="row" id="cartContent">
                            <!-- CART ITEMS -->
                            <div class="col-lg-8">
                                <div class="cart-items-wrapper" id="cartItemsWrapper">
                                    @php
                                    $groupedCartItems = $cartItems->groupBy(function($item) {
                                        return $item->custom_combo_id ?? 'single';
                                    });
                                    @endphp
                                    @foreach($groupedCartItems as $comboId => $groupItems)
                                        @if($comboId === 'single')
                                            @foreach($groupItems as $item)
                                                @php
                                                $isCombo = (bool) $item->combo_pack_id;
                                                $p = $isCombo ? $item->comboPack : $item->product;
                                                @endphp
                                                @if($p)
                                                <div class="cart-item d-flex align-items-center p-3 mb-4 bg-white rounded-4 shadow-sm border position-relative" id="cartItem_{{ $item->id }}" data-aos="fade-up" data-aos-delay="100" style="border-color: #f1f5f9 !important;">
                                                    @php
                                                        $stock = 0;
                                                        if ($p) {
                                                            if ($isCombo) {
                                                                $stock = $p->stock_quantity ?? 0;
                                                            } else {
                                                                $stock = $p->stock_quantity ?? 0;
                                                                if ($item->options) {
                                                                    $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                                                                    if (is_array($options) && isset($options['size']) && $p->size) {
                                                                        $sizes = is_string($p->size) ? json_decode($p->size, true) : $p->size;
                                                                        if (is_array($sizes) && isset($sizes[$options['size']])) {
                                                                            $sizeData = $sizes[$options['size']];
                                                                            $sizeStock = is_array($sizeData) ? ($sizeData['stock'] ?? null) : null;
                                                                            if ($sizeStock !== null) {
                                                                                $stock = $sizeStock;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    <!-- Image Box -->
                                                    <div class="item-img-box rounded-3 overflow-hidden shadow-sm flex-shrink-0 bg-light d-flex justify-content-center align-items-center" style="width: 85px; height: 85px; border: 1px solid #e2e8f0;">
                                                        @php
                                                        $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                                        @endphp

                                                        @if($isCombo && !$p->is_combo_only && is_array($imgData) && count($imgData) >= 2)
                                                        <div class="cart-dual-image d-flex align-items-center justify-content-center w-100 h-100 p-1">
                                                            <img src="{{ asset('storage/' . $imgData[0]) }}" alt="{{ $p->name }}" style="width: 45%; height: auto; object-fit: contain;">
                                                            <span style="font-size: 14px; font-weight: bold; color: var(--primary-color); margin: 0 2px;">+</span>
                                                            <img src="{{ asset('storage/' . $imgData[1]) }}" alt="{{ $p->name }}" style="width: 45%; height: auto; object-fit: contain;">
                                                        </div>
                                                        @else
                                                        @php
                                                        $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                                        @endphp
                                                        <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                                        @endif

                                                        @if($isCombo)
                                                        <span class="badge position-absolute text-white" style="top:5px; left:5px; font-size: 9px; padding: 3px 6px; background-color: var(--primary-color);">COMBO</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Details Box -->
                                                    <div class="mx-3 flex-grow-1 d-flex flex-column justify-content-center">
                                                        <h5 class="item-name mb-1 fw-bold text-dark">{{ $p->name }}</h5>
                                                        
                                                        @if($item->options)
                                                            @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                                            @if(is_array($options) && isset($options['size']))
                                                                <div class="text-muted small mb-1">Size: <span class="fw-medium text-dark">{{ $options['size'] }}</span></div>
                                                            @elseif(is_string($options) && !empty($options))
                                                                <div class="text-muted small mb-1">Size: <span class="fw-medium text-dark">{{ $options }}</span></div>
                                                            @endif
                                                        @endif
                                                        
                                                        <div class="small text-muted mb-2">Weight: <span class="fw-medium">{{ number_format($item->calculated_weight, 2) }}g</span></div>
                                                        
                                                        <!-- Quantity -->
                                                        <div class="d-flex align-items-center mt-1">
                                                            <span class="small text-muted me-2 fw-medium">Qty:</span>
                                                            <div class="d-flex align-items-center border rounded-pill px-2 bg-light shadow-sm" style="height: 32px;">
                                                                <button type="button" class="btn btn-sm btn-link text-dark text-decoration-none p-0 px-2 decrement-btn {{ $item->quantity <= 1 ? 'd-none' : '' }}" id="minus_{{ $item->id }}" data-item-id="{{ $item->id }}"><i class="fas fa-minus" style="font-size: 10px;"></i></button>
                                                                <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none p-0 px-2 delete-btn {{ $item->quantity > 1 ? 'd-none' : '' }}" id="trash_{{ $item->id }}" onclick="removeCartItem('{{ $item->id }}')"><i class="fas fa-trash-alt" style="font-size: 10px;"></i></button>
                                                                
                                                                <input type="number" id="quantity_{{ $item->id }}" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold p-0 mx-1" style="width: 30px; box-shadow: none;" readonly>
                                                                
                                                                <button type="button" class="btn btn-sm btn-link text-dark text-decoration-none p-0 px-2 increment-btn" data-item-id="{{ $item->id }}" data-stock="{{ $stock }}"><i class="fas fa-plus" style="font-size: 10px;"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Price & Action Box -->
                                                    <div class="ms-3 text-end d-flex flex-column align-items-end justify-content-between h-100" style="min-height: 85px;">
                                                        <button type="button" class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center action-btn top-right-remove border mb-auto" data-item-id="{{ $item->id }}" title="Remove" style="width: 32px; height: 32px;">
                                                            <i class="fas fa-times text-danger fs-6"></i>
                                                        </button>
                                                        
                                                        <div class="mt-auto pt-3">
                                                            @php
                                                                $priceToUse = $item->calculated_price;
                                                            @endphp
                                                            <div class="text-muted text-decoration-line-through small mb-1" style="font-size: 0.8rem;">₹{{ number_format($priceToUse ?? 0, 2) }} each</div>
                                                            <h5 class="mb-0 fw-bold text-dark" id="itemTotal_{{ $item->id }}">₹{{ number_format($priceToUse * $item->quantity, 2) }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @php
                                             $comboSubtotal = $groupItems->sum(function($item) {
                                                 return $item->calculated_price * $item->quantity;
                                             });
                                             $comboOriginalSubtotal = $groupItems->sum(function($item) {
                                                 return $item->original_price * $item->quantity;
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
                                            <div class="custom-combo-block mb-4" id="cartItem_{{ $groupItems->first()->id }}" style="border: 1px solid #d2e1cd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);" data-aos="fade-up" data-aos-delay="150">
                                                <div class="custom-combo-group-header d-flex justify-content-between align-items-center p-3" style="background: linear-gradient(135deg, #f7f9f6 0%, #eef3eb 100%); border-bottom: 1px solid #d2e1cd;">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge badge-success me-2" style="background-color: #2e7d32; font-size: 11px; font-weight: 600; padding: 4px 8px;">CUSTOM COMBO PACK</span>
                                                        @if($pct > 0)
                                                        <span class="badge bg-danger text-white font-weight-bold" style="font-size: 11px; padding: 4px 8px;">{{ $pct }}% OFF</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('combo-builder.index', ['edit_combo' => $comboId]) }}" class="btn btn-sm btn-outline-success" title="Edit this combo pack" style="font-size: 12px; border-radius: 4px; padding: 4px 8px; margin-right: 8px; border-color: #72a420; color: #72a420; text-decoration: none;">
                                                            <i class="fas fa-edit me-1"></i> Edit Bundle
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCartItem('{{ $groupItems->first()->id }}')" title="Remove entire combo pack" style="font-size: 12px; border-radius: 4px; padding: 4px 8px;">
                                                            <i class="fas fa-trash-alt me-1"></i> Remove Bundle
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="custom-combo-group-body p-3" style="background-color: #ffffff;">
                                                    @foreach($groupItems as $item)
                                                        @php
                                                        $p = $item->product;
                                                        @endphp
                                                        @if($p)
                                                        <div class="cart-item py-3 d-flex align-items-center position-relative" style="border-bottom: 1px solid #f2f2f2; margin-bottom: 0; {{ $loop->last ? 'border-bottom: none;' : '' }}">
                                                            <div class="item-img-box rounded-3 overflow-hidden shadow-sm flex-shrink-0 bg-light d-flex justify-content-center align-items-center" style="width: 70px; height: 70px; border: 1px solid #e2e8f0;">
                                                                @php
                                                                $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                                                $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                                                @endphp
                                                                <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                                                            </div>
                                                            <div class="item-details ms-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center flex-grow-1 w-100">
                                                                <div class="mb-2 mb-md-0">
                                                                    <h5 class="item-name mb-1 h6 fw-bold text-dark" style="font-size: 15px;">{{ $p->name }}</h5>
                                                                    @if($item->options)
                                                                    @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                                                    @if(is_array($options) && isset($options['size']))
                                                                    <div class="text-muted small mb-1" style="font-size: 12px;">Size: <span class="fw-medium text-dark">{{ $options['size'] }}</span></div>
                                                                    @elseif(is_string($options) && !empty($options))
                                                                    <div class="text-muted small mb-1" style="font-size: 12px;">Size: <span class="fw-medium text-dark">{{ $options }}</span></div>
                                                                    @endif
                                                                    @endif
                                                                    <div class="small text-muted mb-1">Weight: <span class="fw-medium">{{ number_format($item->calculated_weight, 2) }}g</span></div>
                                                                    <div class="mt-1">
                                                                        <span class="badge bg-light text-dark border" style="font-size: 11px; padding: 4px 8px; font-weight: 500;">Qty: 1 (in bundle)</span>
                                                                    </div>
                                                                </div>
                                                                <div class="text-start text-md-end mt-2 mt-md-0 ms-md-auto d-flex flex-row flex-md-column align-items-center align-items-md-end justify-content-between">
                                                                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 15px;">₹{{ number_format($item->calculated_price, 2) }}</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach

                                                    @if($pct > 0 || $comboOriginalSubtotal > $comboSubtotal)
                                                     <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center" style="background-color: #fcfdfc; margin: 0 -15px -15px -15px; padding: 15px;">
                                                         <div class="small text-success font-weight-bold">
                                                             <i class="fas fa-tags me-1"></i> 
                                                             @if($pct > 0)
                                                             Combo Discount Applied ({{ $pct }}%)
                                                             @else
                                                             Bundle Discount Applied
                                                             @endif
                                                         </div>
                                                         <div class="text-right">
                                                             @if($comboOriginalSubtotal > ($comboSubtotal - $comboDiscount))
                                                             <span class="text-muted small" style="text-decoration: line-through; margin-right: 8px;">₹{{ number_format($comboOriginalSubtotal, 2) }}</span>
                                                             @endif
                                                             <span class="font-weight-bold text-success" style="font-size: 16px;">₹{{ number_format($comboSubtotal - $comboDiscount, 2) }}</span>
                                                         </div>
                                                     </div>
                                                     @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- CART SUMMARY -->
                            <div class="col-lg-4">
                                <div class="cart-summary card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-left" data-aos-delay="200" style="position: sticky; top: 120px;">
                                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2">
                                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-file-invoice-dollar me-2 text-custom"></i> Order Summary</h5>
                                    </div>
                                    <div class="card-body p-4 pt-3">
                                        <div class="summary-row d-flex justify-content-between mb-3">
                                            <span class="text-muted small">Subtotal:</span>
                                            <span class="summary-amount fw-bold text-dark small" id="cartSubtotal">₹{{ number_format($subtotal ?? 0, 2) }}</span>
                                        </div>
                                        <div class="summary-row d-flex justify-content-between mb-3">
                                            <span class="text-muted small">Total Weight:</span>
                                            <span class="summary-amount fw-bold text-dark small" id="cartWeight">{{ number_format($totalWeight, 2) }}g</span>
                                        </div>
                                        <div class="summary-row d-flex justify-content-between mb-3 border-bottom pb-3">
                                            <span class="text-muted small">Shipping:</span>
                                            <span class="summary-amount fw-bold text-success small" id="cartShipping">
                                                @if(($shipping ?? 0) > 0)
                                                ₹{{ number_format($shipping, 2) }}
                                                @else
                                                Free
                                                @endif
                                            </span>
                                        </div>
                                        <div class="summary-row d-flex justify-content-between mb-3" id="taxRow" style="{{ ($tax ?? 0) > 0 ? '' : 'display:none;' }}">
                                            <span class="text-muted small" id="cartTaxLabel">GST ({{ $taxPercentage ?? 0 }}%):</span>
                                            <span class="summary-amount fw-bold text-dark small" id="cartTax">₹{{ number_format($tax ?? 0, 2) }}</span>
                                        </div>
                                        <div class="summary-row d-flex justify-content-between mb-3" id="couponDiscountRow" style="{{ ($couponDiscount ?? 0) > 0 ? '' : 'display:none;' }}">
                                            <span class="text-muted small text-success"><i class="fas fa-tag me-1"></i> Coupon:</span>
                                            <span class="summary-amount text-success fw-bold small" id="cartCouponDiscount">
                                                -₹{{ number_format($couponDiscount ?? 0, 2) }}
                                            </span>
                                        </div>
                                        
                                        <!-- Coupon Section -->
                                        <div class="coupon-section border-top pt-3 mt-2 mb-3">
                                            <label class="form-label fw-bold small mb-2 text-dark d-block"><i class="fas fa-ticket-alt me-1 text-custom"></i> Have a Coupon?</label>
                                            <div class="input-group input-group-sm mb-2 shadow-sm rounded-pill overflow-hidden border">
                                                <input type="text" id="coupon_code_input" class="form-control text-uppercase border-0 shadow-none px-3 bg-light" placeholder="ENTER CODE" value="{{ session('coupon_code') }}" {{ session('coupon_code') ? 'disabled' : '' }}>
                                                <div class="input-group-append">
                                                    @if(session('coupon_code'))
                                                        <button class="btn btn-danger text-white rounded-pill ms-1 px-3" type="button" id="removeCouponBtn">Remove</button>
                                                    @else
                                                        <button class="btn btn-custom text-white rounded-pill px-4" type="button" id="applyCouponBtn" style="background: var(--primary-color);">Apply</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="summary-row total d-flex justify-content-between border-top pt-3 mb-4">
                                            <span class="h6 fw-bold text-dark mb-0">Total:</span>
                                            <span class="summary-amount total h5 fw-bold text-custom mb-0" id="cartTotal">₹{{ number_format($total ?? $subtotal ?? 0, 2) }}</span>
                                        </div>
                                        
                                        <div class="d-flex flex-column gap-2 mt-4">
                                            <button type="button" class="btn btn-custom w-100 rounded-pill py-2 shadow-sm text-white fw-bold mb-2" onclick="window.location='{{ route('checkout.address') }}'" style="background: var(--primary-color);">
                                                <i class="fas fa-lock me-2"></i> Proceed to Checkout
                                            </button>
                                            <button type="button" class="btn btn-outline-dark w-100 rounded-pill py-2 mb-2 bg-light border-0" onclick="window.location='{{ route('home') }}'">
                                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                                            </button>
                                            <button type="button" class="btn btn-outline-danger w-100 rounded-pill py-2" onclick="clearCart()">
                                                <i class="fas fa-trash-alt me-2"></i> Clear Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="empty-cart-container text-center py-5" data-aos="zoom-in" data-aos-duration="600">
                            <div class="empty-icon-wrapper mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 100px; height: 100px;">
                                    <i class="fas fa-shopping-cart text-muted" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <h3 class="empty-text fw-bold text-dark mb-3">Your Cart Is Currently Empty</h3>
                            <p class="empty-subtext text-muted mb-4 mx-auto" style="max-width: 400px;">Before you proceed to checkout, you must add some products to your shopping cart.</p>
                            <a href="{{ route('home') }}" class="btn btn-custom rounded-pill px-5 py-2 text-white fw-bold shadow-sm" style="background: var(--primary-color);">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Cart page JavaScript loading
    console.log('Cart page JavaScript loading...');

    // Get the correct base URL from either window.APP_URL or fallback to document location
    let baseUrl = '';
    if (window.APP_URL) {
        baseUrl = window.APP_URL;
    } else if (document.querySelector('meta[name="base-url"]')) {
        baseUrl = document.querySelector('meta[name="base-url"]').content;
    } else {
        // Remove trailing slash from origin
        baseUrl = window.location.origin.replace(/\/$/, '');
    }
    console.log('Base URL:', baseUrl);

    // Get CSRF token
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    // Update quantity function
    function updateQuantity(itemId, change) {
        console.log('Updating quantity for item:', itemId, 'change:', change);
        const input = document.getElementById(`quantity_${itemId}`);
        if (!input) {
            alert('Error: Input field not found');
            return;
        }
        let newQuantity = parseInt(input.value) + change;
        if (isNaN(newQuantity) || newQuantity < 1) newQuantity = 1;

        if (change > 0) {
            const btn = document.querySelector(`.increment-btn[data-item-id="${itemId}"]`);
            if (btn) {
                const stock = parseInt(btn.getAttribute('data-stock') || 0);
                if (newQuantity > stock) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stock Limit',
                        text: `Only ${stock} of stock only available`,
                        confirmButtonColor: '#72a420'
                    });
                    return;
                }
            }
        }

        console.log('Current:', input.value, 'New:', newQuantity);
        input.disabled = true;

        fetch(`${baseUrl}/cart/update/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    input.value = data.quantity;

                    // Dynamic Trash/Minus Toggle Logic
                    const minusBtn = document.getElementById(`minus_${itemId}`);
                    const trashBtn = document.getElementById(`trash_${itemId}`);
                    if (minusBtn && trashBtn) {
                        if (data.quantity <= 1) {
                            minusBtn.classList.add('d-none');
                            trashBtn.classList.remove('d-none');
                        } else {
                            minusBtn.classList.remove('d-none');
                            trashBtn.classList.add('d-none');
                        }
                    }

                    // Update item total
                    const itemTotalEl = document.getElementById(`itemTotal_${itemId}`);
                    if (itemTotalEl && data.item_total) {
                        itemTotalEl.textContent = `₹${parseFloat(data.item_total).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }
                    // Update cart totals
                    if (data.subtotal) {
                        document.getElementById('cartSubtotal').textContent =
                            `₹${parseFloat(data.subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }
                    if (data.total) {
                        document.getElementById('cartTotal').textContent =
                            `₹${parseFloat(data.total).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }
                    if (data.discount !== undefined) {
                        document.getElementById('cartDiscount').textContent =
                            `-₹${parseFloat(data.discount).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }
                    if (data.totalWeight !== undefined) {
                        document.getElementById('cartWeight').textContent = `${data.totalWeight} grams`;
                    }
                    if (data.tax !== undefined) {
                        const taxEl = document.getElementById('cartTax');
                        const taxRow = document.getElementById('taxRow');
                        const taxLabelEl = document.getElementById('cartTaxLabel');

                        if (taxEl) {
                            taxEl.textContent = `₹${parseFloat(data.tax).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                        }
                        if (taxLabelEl && data.taxPercentage !== undefined) {
                            taxLabelEl.textContent = `GST (${data.taxPercentage}%):`;
                        }
                        if (taxRow) {
                            taxRow.style.display = data.tax > 0 ? 'flex' : 'none';
                        }
                    }
                    if (data.shipping !== undefined) {
                        const shipEl = document.getElementById('cartShipping');
                        shipEl.textContent = data.shipping > 0 ?
                            `₹${parseFloat(data.shipping).toLocaleString('en-IN', {minimumFractionDigits: 2})}` :
                            'Free';
                    }
                    // Update cart count if present
                    const cartCountEls = document.querySelectorAll('.cart-count, .cart-count-badge');
                    cartCountEls.forEach(el => {
                        if (data.cart_count !== undefined) el.textContent = data.cart_count;
                    });
                    showMessage(data.message || 'Quantity updated!', 'success');
                } else {
                    showMessage(data.message || 'Update failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage(error.message || 'Failed to update quantity. Please try again.', 'error');
            })
            .finally(() => {
                input.disabled = false;
            });
    }

    // Remove item function
    function removeCartItem(itemId) {
        Swal.fire({
            title: 'Remove Item?',
            text: "Are you sure you want to remove this item from your cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#72a420',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Removing item:', itemId);
                fetch(`${baseUrl}/cart/remove/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(async response => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            const idsToDelete = data.deleted_ids || [itemId];
                            let transitionCount = 0;
                            idsToDelete.forEach(id => {
                                const cartItem = document.getElementById(`cartItem_${id}`);
                                if (cartItem) {
                                    transitionCount++;
                                    cartItem.style.transition = 'opacity 0.3s';
                                    cartItem.style.opacity = '0';
                                    setTimeout(() => {
                                        cartItem.remove();
                                        
                                        transitionCount--;
                                        if (transitionCount === 0) {
                                            updateTotals(data);
                                            // Check if cart is empty
                                            const cartItemsWrapper = document.getElementById('cartItemsWrapper');
                                            if ((cartItemsWrapper && cartItemsWrapper.children.length === 0) || (!document.querySelector('.cart-item') && !document.querySelector('.custom-combo-block'))) {
                                                showEmptyCart();
                                            }
                                            showMessage(data.message || 'Item removed!', 'success');
                                        }
                                    }, 300);
                                }
                            });
                            if (transitionCount === 0) {
                                updateTotals(data);
                                showEmptyCart();
                                showMessage(data.message || 'Item removed!', 'success');
                            }
                        } else {
                            showMessage(data.message || 'Failed to remove item', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage(error.message || 'Failed to remove item. Please try again.', 'error');
                    });
            }
        });
    }

    // Helper for updating totals
    function updateTotals(data) {
        if (data.subtotal) {
            document.getElementById('cartSubtotal').textContent = `₹${parseFloat(data.subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        }
        if (data.total) {
            document.getElementById('cartTotal').textContent = `₹${parseFloat(data.total).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        }
        if (data.discount !== undefined) {
            document.getElementById('cartDiscount').textContent = `-₹${parseFloat(data.discount).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        }
        if (data.totalWeight !== undefined) {
            document.getElementById('cartWeight').textContent = `${data.totalWeight} grams`;
        }
        if (data.shipping !== undefined) {
            const shipEl = document.getElementById('cartShipping');
            shipEl.textContent = data.shipping > 0 ? `₹${parseFloat(data.shipping).toLocaleString('en-IN', {minimumFractionDigits: 2})}` : 'Free';
        }
        if (data.tax !== undefined) {
            const taxEl = document.getElementById('cartTax');
            const taxRow = document.getElementById('taxRow');
            const taxLabelEl = document.getElementById('cartTaxLabel');
            if (taxEl) {
                taxEl.textContent = `₹${parseFloat(data.tax).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
            }
            if (taxLabelEl && data.taxPercentage !== undefined) {
                taxLabelEl.textContent = `GST (${data.taxPercentage}%):`;
            }
            if (taxRow) {
                taxRow.style.display = data.tax > 0 ? 'flex' : 'none';
            }
        }
        const cartCountEls = document.querySelectorAll('.cart-count, .cart-count-badge');
        cartCountEls.forEach(el => {
            if (data.cart_count !== undefined) el.textContent = data.cart_count;
        });
    }

    // Clear cart function
    function clearCart() {
        Swal.fire({
            title: 'Clear Cart?',
            text: "Are you sure you want to clear your entire cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, clear it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Clearing cart...');
                fetch(`${baseUrl}/cart/clear`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(async response => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            // Animate and remove all items
                            const cartItems = document.querySelectorAll('.cart-item');
                            cartItems.forEach((item, index) => {
                                item.style.transition = 'opacity 0.3s';
                                item.style.opacity = '0';
                                setTimeout(() => {
                                    item.remove();
                                }, index * 100);
                            });
                            setTimeout(() => {
                                updateTotals(data);
                                showEmptyCart();
                                showMessage(data.message || 'Cart cleared!', 'success');
                            }, cartItems.length * 100);
                        } else {
                            showMessage(data.message || 'Failed to clear cart', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage(error.message || 'Failed to clear cart. Please try again.', 'error');
                    });
            }
        });
    }

    // Show empty cart message
    function showEmptyCart() {
        const cartContent = document.getElementById('cartContent');
        if (cartContent) {
            const cardBody = cartContent.parentElement;
            cardBody.innerHTML = `
                <div class="empty-cart-container" style="animation: fadeInUp 0.5s ease-out;">
                    <div class="empty-icon-wrapper">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="empty-text">Your Cart Is Currently Empty</h3>
                    <p class="empty-subtext">Before You Proceed To Checkout, You Must Add Some Products To Your Shopping Cart.</p>
                    <a href="${baseUrl}" class="continue-shopping-btn">
                        Continue Shopping
                    </a>
                </div>
            `;
        }
    }

    // Show message popup using SweetAlert2 Toast
    function showMessage(message, type = 'info') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    // Add CSS animations
    (function() {
        if (!document.getElementById('cartAnimStyle')) {
            const style = document.createElement('style');
            style.id = 'cartAnimStyle';
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @keyframes fadeOut {
                    from { opacity: 1; transform: translateY(0); }
                    to { opacity: 0; transform: translateY(-20px); }
                }
            `;
            document.head.appendChild(style);
        }
    })();

    // Event listeners setup
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing cart...');

        // Remove any default onclicks
        document.querySelectorAll('.increment, .decrement, .action-btn.remove').forEach(button => {
            button.removeAttribute('onclick');
        });

        // Setup increment buttons
        document.querySelectorAll('.increment-btn, .qty-btn.increment').forEach(button => {
            if (!button.hasAttribute('data-item-id')) {
                const cartItem = button.closest('.cart-item');
                if (cartItem && cartItem.id) {
                    const itemId = cartItem.id.replace('cartItem_', '');
                    button.setAttribute('data-item-id', itemId);
                }
            }
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const itemId = this.getAttribute('data-item-id');
                if (itemId) updateQuantity(itemId, 1);
            });
        });

        // Setup decrement buttons
        document.querySelectorAll('.decrement-btn, .qty-btn.decrement').forEach(button => {
            if (!button.hasAttribute('data-item-id')) {
                const cartItem = button.closest('.cart-item');
                if (cartItem && cartItem.id) {
                    const itemId = cartItem.id.replace('cartItem_', '');
                    button.setAttribute('data-item-id', itemId);
                }
            }
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const itemId = this.getAttribute('data-item-id');
                if (itemId) updateQuantity(itemId, -1);
            });
        });

        // Setup remove buttons
        document.querySelectorAll('.action-btn.top-right-remove').forEach(button => {
            if (!button.hasAttribute('data-item-id')) {
                const cartItem = button.closest('.cart-item');
                if (cartItem && cartItem.id) {
                    const itemId = cartItem.id.replace('cartItem_', '');
                    button.setAttribute('data-item-id', itemId);
                }
            }
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const itemId = this.getAttribute('data-item-id');
                if (itemId) removeCartItem(itemId);
            });
        });

        // Setup Coupon Events
        const applyCouponBtn = document.getElementById('applyCouponBtn');
        const removeCouponBtn = document.getElementById('removeCouponBtn');
        const couponCodeInput = document.getElementById('coupon_code_input');

        if (applyCouponBtn) {
            applyCouponBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const code = couponCodeInput.value.trim();
                if (!code) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Please enter a coupon code.',
                        confirmButtonColor: '#72a420'
                    });
                    return;
                }

                applyCouponBtn.disabled = true;
                applyCouponBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

                fetch(`${baseUrl}/cart/apply-coupon`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ coupon_code: code })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: data.message,
                            confirmButtonColor: '#72a420'
                        });
                        applyCouponBtn.disabled = false;
                        applyCouponBtn.innerText = 'Apply';
                    }
                })
                .catch(err => {
                    console.error(err);
                    applyCouponBtn.disabled = false;
                    applyCouponBtn.innerText = 'Apply';
                });
            });
        }

        if (removeCouponBtn) {
            removeCouponBtn.addEventListener('click', function(e) {
                e.preventDefault();
                removeCouponBtn.disabled = true;
                removeCouponBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

                fetch(`${baseUrl}/cart/remove-coupon`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    removeCouponBtn.disabled = false;
                    removeCouponBtn.innerText = 'Remove';
                });
            });
        }

        console.log('Cart initialization complete');
    });
</script>


@include('view.layout.footer')