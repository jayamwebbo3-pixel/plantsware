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
                                        @foreach($cartItems as $item)
                                            @php
                                                $isCombo = (bool) $item->combo_pack_id;
                                                $p = $isCombo ? $item->comboPack : $item->product;
                                            @endphp
                                            @if($p)
                                                <div class="cart-item" id="cartItem_{{ $item->id }}">
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
                                                            <span>Weight: {{ $p->weight ?? 0 }} KG</span>
                                                            @php
                                                                $regularPrice = $isCombo ? $p->total_price : $p->price;
                                                                $savings = max(0, $regularPrice - $priceToUse);
                                                            @endphp
                                                            @if($savings > 0)
                                                                <span class="ms-2 text-success">Saved: ₹{{ number_format($savings, 2) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="item-total" id="itemTotal_{{ $item->id }}">
                                                            ₹{{ number_format($priceToUse * $item->quantity, 2) }}
                                                        </div>
                                                        <div class="quantity-controls">
                                                            <span class="qty-label">Quantity:</span>
                                                            <div class="qty-input-group">
                                                                <button type="button" class="qty-btn decrement" 
                                                                        data-item-id="{{ $item->id }}">−</button>
                                                                <input type="number" id="quantity_{{ $item->id }}" 
                                                                       value="{{ $item->quantity }}" min="1" 
                                                                       class="qty-input" readonly>
                                                                <button type="button" class="qty-btn increment" 
                                                                        data-item-id="{{ $item->id }}">+</button>
                                                            </div>
                                                        </div>
                                                        <div class="item-actions">
                                                            <button type="button" class="action-btn remove" 
                                                                    data-item-id="{{ $item->id }}">
                                                                <i class="fas fa-trash-alt"></i> Remove
                                                            </button>
                                                        </div>
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
                                            <span class="summary-amount" id="cartWeight">{{ $totalWeight ?? 0 }} KG</span>
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
                                        <div class="summary-row">
                                            <span>Tax:</span>
                                            <span class="summary-amount">₹0.00</span>
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
                                        <button type="button" class="continue-shopping-btn" onclick="window.location='{{ route('home') }}'">Continue Shopping</button>
                                        <button type="button" class="clear-cart-btn btn btn-outline-danger w-100 mt-3" 
                                                onclick="clearCart()">
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
                    document.getElementById('cartWeight').textContent = `${data.totalWeight} KG`;
                }
                if (data.shipping !== undefined) {
                    const shipEl = document.getElementById('cartShipping');
                    shipEl.textContent = data.shipping > 0 
                        ? `₹${parseFloat(data.shipping).toLocaleString('en-IN', {minimumFractionDigits: 2})}`
                        : 'Free';
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
        if (!confirm('Are you sure you want to remove this item from cart?')) {
            return;
        }
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
                const cartItem = document.getElementById(`cartItem_${itemId}`);
                if (cartItem) {
                    cartItem.style.transition = 'opacity 0.3s';
                    cartItem.style.opacity = '0';
                    setTimeout(() => {
                        cartItem.remove();
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
                            document.getElementById('cartWeight').textContent = `${data.totalWeight} KG`;
                        }
                        if (data.shipping !== undefined) {
                            const shipEl = document.getElementById('cartShipping');
                            shipEl.textContent = data.shipping > 0 
                                ? `₹${parseFloat(data.shipping).toLocaleString('en-IN', {minimumFractionDigits: 2})}`
                                : 'Free';
                        }
                        const cartCountEls = document.querySelectorAll('.cart-count, .cart-count-badge');
                        cartCountEls.forEach(el => {
                            if (data.cart_count !== undefined) el.textContent = data.cart_count;
                        });
                        // Check if cart is empty
                        const cartItemsWrapper = document.getElementById('cartItemsWrapper');
                        // Or fallback: count remaining .cart-item
                        if (
                            (cartItemsWrapper && cartItemsWrapper.children.length === 0)
                            || !document.querySelector('.cart-item')
                        ) {
                            showEmptyCart();
                        }
                        showMessage(data.message || 'Item removed!', 'success');
                    }, 300);
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

    // Clear cart function
    function clearCart() {
        if (!confirm('Are you sure you want to clear your entire cart?')) {
            return;
        }
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
                    // Update totals
                    if (data.subtotal) {
                        document.getElementById('cartSubtotal').textContent =
                            `₹${parseFloat(data.subtotal).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }
                    if (data.total) {
                        document.getElementById('cartTotal').textContent =
                            `₹${parseFloat(data.total).toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
                    }
                    // Update cart count
                    const cartCountEls = document.querySelectorAll('.cart-count, .cart-count-badge');
                    cartCountEls.forEach(el => {
                        if (data.cart_count !== undefined) el.textContent = data.cart_count;
                    });
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

    // Show message popup
    function showMessage(message, type = 'info') {
        // Remove existing messages
        const existingMsg = document.querySelector('.cart-message');
        if (existingMsg) {
            existingMsg.remove();
        }

        // Create message element
        const msgEl = document.createElement('div');
        msgEl.className = `cart-message alert alert-${type === 'success' ? 'success' : 'danger'}`;
        msgEl.textContent = message;

        // Style it
        msgEl.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: fadeIn 0.3s;
        `;

        document.body.appendChild(msgEl);

        setTimeout(() => {
            msgEl.style.animation = 'fadeOut 0.3s';
            setTimeout(() => msgEl.remove(), 300);
        }, 3000);
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
        document.querySelectorAll('.increment').forEach(button => {
            if (!button.hasAttribute('data-item-id')) {
                const cartItem = button.closest('.cart-item');
                if (cartItem && cartItem.id) {
                    const itemId = cartItem.id.replace('cartItem_', '');
                    button.setAttribute('data-item-id', itemId);
                }
            }
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                if (itemId) {
                    updateQuantity(itemId, 1);
                } else {
                    alert('Error: Button configuration issue');
                }
            });
        });

        // Setup decrement buttons
        document.querySelectorAll('.decrement').forEach(button => {
            if (!button.hasAttribute('data-item-id')) {
                const cartItem = button.closest('.cart-item');
                if (cartItem && cartItem.id) {
                    const itemId = cartItem.id.replace('cartItem_', '');
                    button.setAttribute('data-item-id', itemId);
                }
            }
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                if (itemId) {
                    updateQuantity(itemId, -1);
                } else {
                    alert('Error: Button configuration issue');
                }
            });
        });

        // Setup remove buttons
        document.querySelectorAll('.action-btn.remove').forEach(button => {
            if (!button.hasAttribute('data-item-id')) {
                const cartItem = button.closest('.cart-item');
                if (cartItem && cartItem.id) {
                    const itemId = cartItem.id.replace('cartItem_', '');
                    button.setAttribute('data-item-id', itemId);
                }
            }
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                if (itemId) {
                    removeCartItem(itemId);
                } else {
                    alert('Error: Button configuration issue');
                }
            });
        });

        console.log('Cart initialization complete');
    });

    // Debug/test function: window.testCart()
    window.testCart = function() {
        console.log('Testing cart functions...');
        console.log('Base URL:', baseUrl);
        console.log('CSRF Token:', getCsrfToken());
        console.log('Cart items:', document.querySelectorAll('.cart-item').length);

        // Test with first item
        const firstItem = document.querySelector('.cart-item');
        if (firstItem) {
            const itemId = firstItem.id.replace('cartItem_', '');
            console.log('First item ID:', itemId);
            console.log('Testing URL:', `${baseUrl}/cart/update/${itemId}`);
            updateQuantity(itemId, 1);
        } else {
            console.log('No cart items found');
        }
    };
</script>
<style>
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

.empty-icon-wrapper {
    width: 120px;
    height: 120px;
    background-color: var(--light-bg-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 30px;
    color: var(--primary-color);
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

.continue-shopping-btn {
    display: inline-block;
    background-color: var(--primary-color);
    color: #fff !important;
    padding: 14px 40px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.continue-shopping-btn:hover {
    background-color: #5b8c19;
    box-shadow: 0 5px 15px rgba(110, 168, 32, 0.3);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
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