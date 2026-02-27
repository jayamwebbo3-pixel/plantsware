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
    <div class="container">
        <h1 class="cart-title">Shopping Cart</h1>

        @if(isset($cartItems) && $cartItems->count() > 0)
            <div class="row" id="cartContent">
                <!-- CART ITEMS -->
                <div class="col-lg-8">
                    <div class="cart-items-wrapper" id="cartItemsWrapper">
                        @foreach($cartItems as $item)
                        @if(isset($item->product))
                        <div class="cart-item" id="cartItem_{{ $item->id }}">
                            <div class="item-image">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('assets/images/product/product1.jpg') }}" 
                                     alt="{{ $item->product->name }}">
                            </div>
                            <div class="item-details">
                                <h3 class="item-name">{{ $item->product->name }}</h3>
                                <div class="item-price">₹{{ number_format($item->product->price ?? 0, 2) }}</div>
                                <div class="item-total" id="itemTotal_{{ $item->id }}">
                                    ₹{{ number_format($item->product->price * $item->quantity, 2) }}
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
                            <span>Shipping:</span>
                            <span class="summary-amount">Free</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span class="summary-amount">₹0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Discount:</span>
                            <span class="summary-amount" style="color: var(--primary-color);">-₹0.00</span>
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
            <div class="text-center py-5" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <h3 class="mb-4" style="color: #666;">Your cart is empty</h3>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3" style="background-color: var(--primary-color); border: none; padding: 12px 30px; font-size: 16px;">
                    Start Shopping
                </a>
            </div>
        @endif
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
            cartContent.innerHTML = `
                <div class="text-center py-5" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <h3 class="mb-4" style="color: #666;">Your cart is empty</h3>
                    <a href="${baseUrl}" class="btn btn-primary mt-3" style="background-color: var(--primary-color); border: none; padding: 12px 30px; font-size: 16px;">
                        Start Shopping
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
.toast-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #2ecc71;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
/* Additional cart styles can go here */
</style>

@include('view.layout.footer')