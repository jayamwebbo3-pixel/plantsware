@include('view.layout.header')

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

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

<main class="cart-section">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="gi-vendor-dashboard-card">
                    <div class="gi-vendor-card-header">
                        <h5>Shopping Cart</h5>
                        <div class="">
                            <a class="btn btn-outline-white" href="{{ route('home') }}">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                    <div class="gi-vendor-card-body">
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
                                                <div class="cart-item" id="cartItem_{{ $item->id }}">
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
                                                    <div class="item-image position-relative d-flex align-items-center justify-content-center" style="background: #fdfdfd; border-radius: 8px; overflow: hidden; width: 80px; height: 80px;">
                                                        @php
                                                        $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                                        @endphp

                                                        @if($isCombo && !$p->is_combo_only && is_array($imgData) && count($imgData) >= 2)
                                                        <div class="cart-dual-image d-flex align-items-center justify-content-center w-100 h-100 p-1">
                                                            <img src="{{ asset('storage/' . $imgData[0]) }}" alt="{{ $p->name }}" style="width: 40%; height: auto; object-fit: contain;">
                                                            <span style="font-size: 12px; font-weight: bold; color: #72a420; margin: 0 2px;">+</span>
                                                            <img src="{{ asset('storage/' . $imgData[1]) }}" alt="{{ $p->name }}" style="width: 40%; height: auto; object-fit: contain;">
                                                        </div>
                                                        @else
                                                        @php
                                                        $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                                        @endphp
                                                        <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}"
                                                            alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @endif

                                                        @if($isCombo)
                                                        <span class="badge badge-danger position-absolute" style="top:2px; left:2px; font-size: 8px; padding: 2px 4px;">COMBO</span>
                                                        @endif
                                                    </div>
                                                    <div class="item-details">
                                                        <h3 class="item-name mb-1">{{ $p->name }}</h3>
                                                        @if($item->options)
                                                        @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                                        @if(is_array($options) && isset($options['size']))
                                                        <div class="text-muted small mb-2">Size: {{ $options['size'] }}</div>
                                                        @elseif(is_string($options) && !empty($options))
                                                        <div class="text-muted small mb-2">Size: {{ $options }}</div>
                                                        @endif
                                                        @endif
                                                        @php
                                                        $priceToUse = $item->calculated_price;
                                                        @endphp
                                                        <div class="item-price">₹{{ number_format($priceToUse ?? 0, 2) }}</div>
                                                        <div class="item-meta small text-muted mb-1">
                                                            <span>Weight: {{ number_format($item->calculated_weight, 2) }} grams</span>
                                                            @php
                                                            $regularPrice = $isCombo ? $p->total_price : $p->price;
                                                            $savings = max(0, $regularPrice - $priceToUse);
                                                            @endphp
                                                        </div>
                                                        <div class="item-total" id="itemTotal_{{ $item->id }}">
                                                            ₹{{ number_format($priceToUse * $item->quantity, 2) }}
                                                        </div>
                                                        <div class="quantity-controls">
                                                            <span class="qty-label">Quantity:</span>
                                                            <div class="qty-pill-control">
                                                                <button type="button" class="qty-btn-inline decrement-btn {{ $item->quantity <= 1 ? 'd-none' : '' }}"
                                                                    id="minus_{{ $item->id }}" data-item-id="{{ $item->id }}">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                                <button type="button" class="qty-btn-inline delete-btn {{ $item->quantity > 1 ? 'd-none' : '' }}"
                                                                    id="trash_{{ $item->id }}" onclick="removeCartItem('{{ $item->id }}')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>

                                                                <input type="number" id="quantity_{{ $item->id }}"
                                                                    value="{{ $item->quantity }}" min="1"
                                                                    class="qty-number-input" readonly>

                                                                <button type="button" class="qty-btn-inline increment-btn"
                                                                    data-item-id="{{ $item->id }}"
                                                                    data-stock="{{ $stock }}">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="item-actions">
                                                            <button type="button" class="action-btn top-right-remove"
                                                                data-item-id="{{ $item->id }}" title="Remove from Cart">
                                                                <i class="fas fa-times"></i>
                                                            </button>
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
                                            <div class="custom-combo-block mb-4" id="cartItem_{{ $groupItems->first()->id }}" style="border: 1px solid #d2e1cd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
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
                                                        <div class="cart-item py-3" style="border-bottom: 1px solid #f2f2f2; margin-bottom: 0; padding-bottom: 1rem; {{ $loop->last ? 'border-bottom: none; padding-bottom: 0;' : '' }}">
                                                            <div class="item-image position-relative d-flex align-items-center justify-content-center" style="background: #fdfdfd; border-radius: 8px; overflow: hidden; width: 70px; height: 70px;">
                                                                @php
                                                                $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                                                $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                                                @endphp
                                                                <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                            </div>
                                                            <div class="item-details ms-3 flex-grow-1">
                                                                <h3 class="item-name mb-1" style="font-size: 15px; font-weight: 600; color: #333;">{{ $p->name }}</h3>
                                                                @if($item->options)
                                                                @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                                                @if(is_array($options) && isset($options['size']))
                                                                <div class="text-muted small mb-1" style="font-size: 12px;">Size: {{ $options['size'] }}</div>
                                                                @elseif(is_string($options) && !empty($options))
                                                                <div class="text-muted small mb-1" style="font-size: 12px;">Size: {{ $options }}</div>
                                                                @endif
                                                                @endif
                                                                <div class="item-price" style="font-size: 14px; font-weight: 500; color: #555;">₹{{ number_format($item->calculated_price, 2) }}</div>
                                                                <div class="item-meta small text-muted mb-1">
                                                                    <span>Weight: {{ number_format($item->calculated_weight, 2) }} grams</span>
                                                                </div>
                                                                <div class="quantity-controls mt-1">
                                                                    <span class="badge bg-light text-dark border" style="font-size: 11px; padding: 4px 8px; font-weight: 500;">Qty: 1 (custom combo pack)</span>
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
                                <div class="cart-summary">
                                    <h2 class="summary-title">Order Summary</h2>
                                    <div class="summary-row">
                                        <span>Subtotal:</span>
                                        <span class="summary-amount" id="cartSubtotal">₹{{ number_format($subtotal ?? 0, 2) }}</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Total Weight:</span>
                                        <span class="summary-amount" id="cartWeight">{{ number_format($totalWeight, 2) }} grams</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Shipping:</span>
                                        <span class="summary-amount" id="cartShipping">
                                            @if(($shipping ?? 0) > 0)
                                            ₹{{ number_format($shipping, 2) }}
                                            @else
                                            Free
                                            @endif
                                        </span>
                                    </div>
                                    <div class="summary-row" id="taxRow" style="{{ ($tax ?? 0) > 0 ? '' : 'display:none;' }}">
                                        <span id="cartTaxLabel">GST ({{ $taxPercentage ?? 0 }}%):</span>
                                        <span class="summary-amount" id="cartTax">₹{{ number_format($tax ?? 0, 2) }}</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Discount:</span>
                                        <span class="summary-amount" id="cartDiscount" style="color: var(--primary-color);">
                                            -₹{{ number_format($discount ?? 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="summary-row total">
                                        <span>Total:</span>
                                        <span class="summary-amount total" id="cartTotal">₹{{ number_format($total ?? $subtotal ?? 0, 2) }}</span>
                                    </div>
                                    <button type="button" class="checkout-btn" onclick="window.location='{{ route('checkout.address') }}'">Proceed to Checkout</button>
                                    <button type="button" class="continue-shopping-btn w-100" onclick="window.location='{{ route('home') }}'">Continue Shopping</button>
                                    <button type="button" class="clear-cart-btn btn-clear-red w-100 mt-2" onclick="clearCart()">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="empty-cart-container">
                            <div class="empty-icon-wrapper">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h3 class="empty-text">Your Cart Is Currently Empty</h3>
                            <p class="empty-subtext">Before You Proceed To Checkout, You Must Add Some Products To Your Shopping Cart.</p>
                            <a href="{{ route('home') }}" class="continue-shopping-btn">
                                Continue Shopping
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

        console.log('Cart initialization complete');
    });
</script>
<style>
    /* Modern Pill-Style Quantity Control */
    .qty-pill-control {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 2px solid #6ea820;
        /* Matched the yellow/gold border from screenshot */
        border-radius: 50px;
        padding: 2px 5px;
        height: 38px;
        min-width: 110px;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(255, 202, 44, 0.1);
    }

    .qty-btn-inline {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #333;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .qty-btn-inline:hover {
        background-color: #fff9e6;
        color: #6ea820;
    }

    .qty-btn-inline.delete-btn {
        color: #444;
        /* Darker for the trash icon */
    }

    .qty-btn-inline.delete-btn:hover {
        background-color: #fff0f0;
        color: #dc3545;
    }

    .qty-number-input {
        width: 35px;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 15px;
        color: #1e293b;
        background: transparent;
        outline: none !important;
    }

    /* Chrome, Safari, Edge, Opera: remove arrows */
    .qty-number-input::-webkit-outer-spin-button,
    .qty-number-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Simple Empty Cart Styles */
    .empty-cart-container {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        min-height: 450px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    /* Absolute Positioned Remove Button */
    .cart-item {
        position: relative;
        padding-top: 25px !important;
    }

    .action-btn.top-right-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #f1f5f9;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 5;
    }

    .action-btn.top-right-remove:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: rotate(90deg);
    }

    .empty-icon-wrapper {
        width: 120px;
        height: 120px;
        background-color: #f3f7ed;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        color: #72a420;
        font-size: 50px;
        transition: all 0.3s ease;
    }

    .empty-icon-wrapper:hover {
        transform: scale(1.1) rotate(-10deg);
    }

    .empty-text {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        font-family: var(--font-heading);
    }

    .empty-subtext {
        font-size: 16px;
        color: #777;
        margin-bottom: 35px;
        max-width: 400px;
    }

    .checkout-btn {
        display: block;
        width: 100%;
        background-color: var(--primary-color);
        color: #fff !important;
        padding: 14px 40px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.3s ease;
        border: none;
        text-transform: capitalize;
        letter-spacing: 0.5px;
        text-align: center;
    }

    .checkout-btn:hover {
        background-color: #5b8c19;
        box-shadow: 0 5px 15px rgba(110, 168, 32, 0.3);
    }

    .continue-shopping-btn {
        display: block;
        width: 30%;
        margin-top: 10px;
        background-color: #fff;
        color: var(--primary-color) !important;
        border: 1px solid var(--primary-color);
        padding: 14px 40px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.3s ease;
        text-transform: capitalize;
        letter-spacing: 0.5px;
        text-align: center;
    }

    .continue-shopping-btn:hover {
        background-color: var(--primary-color);
        color: #fff !important;
        box-shadow: 0 5px 15px rgba(110, 168, 32, 0.3);
    }

    .btn-clear-red {
        display: block;
        background-color: #fff;
        color: #dc3545 !important;
        border: 1px solid #dc3545;
        padding: 14px 40px;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: capitalize;
        letter-spacing: 0.5px;
    }

    .btn-clear-red:hover {
        background-color: #dc3545;
        color: #fff !important;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 991px) {
        .cart-summary {
            margin-top: 30px;
            position: static;
            width: 100%;
            padding: 20px;
        }
    }

    @media (max-width: 768px) {
        .empty-cart-container {
            padding: 50px 15px;
            min-height: 350px;
        }

        .empty-text {
            font-size: 20px;
        }

        .empty-icon-wrapper {
            width: 90px;
            height: 90px;
            font-size: 35px;
        }
    }

    @media (max-width: 576px) {
        .gi-vendor-card-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .gi-vendor-card-header h5 {
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .gi-vendor-card-header .btn {
            width: 100%;
        }

        .cart-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 15px;
        }

        .item-image {
            width: 100px !important;
            height: 100px !important;
            margin: 0 auto;
        }

        .item-details {
            width: 100%;
            padding: 0 10px;
        }

        .qty-input-group {
            justify-content: center;
        }

        .item-actions {
            justify-content: center;
            margin-top: 15px;
        }
    }
</style>

@include('view.layout.footer')