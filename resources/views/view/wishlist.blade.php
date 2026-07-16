@include('view.layout.header')

<!-- Ensure CSRF token is in header -->

{{-- 
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">My Wishlist</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
 --}}

<div class="container wishlist-page-container">
    <div class="row">
        <div class="col-md-12">
            <div class="gi-vendor-dashboard-card">
                <div class="gi-vendor-card-header">
                    <h5>My Wishlist</h5>
                    <div class="">
                        <a class="btn btn-outline-white" href="{{ route('home') }}">
                            <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
                <div class="gi-vendor-card-body">
                    @if(Auth::check() && $wishlistItems->count() > 0)
                    <!-- Wishlist Items List -->
                    <div class="wishlist-list-wrapper">
                        <!-- Wishlist Table Header (Desktop Only) -->
                        <div class="wishlist-table-header d-none d-md-flex">
                            <div class="wishlist-item-main">
                                <span class="fw-bold text-uppercase" style="font-size: 12px; color: #475569; letter-spacing: 0.5px;">Product</span>
                            </div>
                            <div class="wishlist-item-details">
                                <div class="wishlist-price-box">
                                    <span class="fw-bold text-uppercase" style="font-size: 12px; color: #475569; letter-spacing: 0.5px;">Price</span>
                                </div>
                                <div class="wishlist-stock-box">
                                    <span class="fw-bold text-uppercase" style="font-size: 12px; color: #475569; letter-spacing: 0.5px;">Stock Status</span>
                                </div>
                            </div>
                            <div class="wishlist-actions-box">
                                <span class="fw-bold text-uppercase" style="font-size: 12px; color: #475569; letter-spacing: 0.5px;">Actions</span>
                            </div>
                        </div>

                        @foreach($wishlistItems as $index => $item)
                        @php
                        $isCombo = (bool) $item->combo_pack_id;
                        $p = $isCombo ? $item->comboPack : $item->product;
                        @endphp
                        @if($p)
                        @php
                        if ($isCombo) {
                            $priceToUse = $p->offer_price;
                        } else {
                            $priceToUse = ($p->sale_price && $p->sale_price > 0 && $p->sale_price < $p->price)
                                ? $p->sale_price
                                : $p->price;
                        }
                        @endphp
                        <div class="wishlist-row-item" id="wishlistItem_{{ $item->id }}">
                            
                            <!-- Main info (Image & Name) -->
                            <div class="wishlist-item-main">
                                <div class="wishlist-img-box">
                                    @php
                                    $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                    @endphp

                                    @if($isCombo && !$p->is_combo_only && is_array($imgData) && count($imgData) >= 2)
                                    <div class="wishlist-dual-image-box">
                                        <img src="{{ asset('storage/' . $imgData[0]) }}" alt="{{ $p->name }}">
                                        <span>+</span>
                                        <img src="{{ asset('storage/' . $imgData[1]) }}" alt="{{ $p->name }}">
                                    </div>
                                    @else
                                    @php
                                    $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                    @endphp
                                    <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}">
                                    @endif
                                </div>
                                
                                <div class="wishlist-meta-box">
                                    @if($isCombo)
                                    <a href="{{ route('combo_packs.frontend_show', $p->slug) }}" class="wishlist-item-title">
                                        {{ $p->name }} <span class="badge-combo">COMBO</span>
                                    </a>
                                    @else
                                    <a href="{{ route('product.show', $p->slug) }}" class="wishlist-item-title">
                                        {{ $p->name }}
                                    </a>
                                    @endif
                                    
                                    <!-- Mobile Price & Stock -->
                                    <div class="wishlist-mobile-price-stock d-md-none">
                                        <span class="price-val">₹{{ number_format($priceToUse, 2) }}</span>
                                        @if($p->stock_quantity > 0)
                                        <span class="stock-status in-stock">In Stock</span>
                                        @else
                                        <span class="stock-status out-of-stock">Out of Stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Desktop Details (Price & Stock) -->
                            <div class="wishlist-item-details d-none d-md-flex">
                                <div class="wishlist-price-box">
                                    <span class="price-val">₹{{ number_format($priceToUse, 2) }}</span>
                                </div>
                                <div class="wishlist-stock-box">
                                    @if($p->stock_quantity > 0)
                                    <span class="stock-status in-stock">In Stock</span>
                                    @else
                                    <span class="stock-status out-of-stock">Out of Stock</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="wishlist-actions-box">
                                @if($p->stock_quantity > 0)
                                    @if($isCombo)
                                    <form action="{{ route('cart.add_combo', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-wishlist-cart" title="Add To Cart">
                                            <i class="fas fa-shopping-cart"></i> <span>Add to Cart</span>
                                        </button>
                                    </form>
                                    @else
                                        @php
                                        $hasAttributes = false;
                                        if (!empty($p->size)) {
                                            if (is_array($p->size)) {
                                                $hasAttributes = count($p->size) > 0;
                                            } else {
                                                $decoded = json_decode($p->size, true);
                                                if (is_array($decoded)) {
                                                    $hasAttributes = count($decoded) > 0;
                                                } else {
                                                    $parts = array_filter(array_map('trim', explode(',', $p->size)));
                                                    $hasAttributes = count($parts) > 0;
                                                }
                                            }
                                        }
                                        @endphp

                                        @if($hasAttributes)
                                        <a href="{{ route('product.show', $p->slug) }}" class="btn-wishlist-cart text-decoration-none" title="Select Options">
                                            <i class="fas fa-eye"></i> <span>Select Options</span>
                                        </a>
                                        @else
                                        <form action="{{ route('cart.add', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-wishlist-cart" title="Add To Cart">
                                                <i class="fas fa-shopping-cart"></i> <span>Add to Cart</span>
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                @endif

                                @if($isCombo)
                                <button type="button" class="btn-wishlist-delete" onclick="removeFromWishlistCombo({{ $p->id }})" title="Remove From List">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @else
                                <button type="button" class="btn-wishlist-delete" onclick="removeFromWishlist({{ $p->id }})" title="Remove From List">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </div>

                        </div>
                        @endif
                        @endforeach
                    </div>
                    @else
                    <!-- Empty Wishlist -->
                    <div class="empty-cart-container">
                        <div class="empty-icon-wrapper">
                            <i class="far fa-heart"></i>
                        </div>
                        @if(!Auth::check())
                        <h3 class="empty-text">Your Wishlist is Empty</h3>
                        <p class="empty-subtext">Please login to view and add items to your wishlist.</p>
                        <a href="{{ route('login') }}" class="continue-shopping-btn">
                            Login Now
                        </a>
                        @else
                        <h3 class="empty-text">Your Wishlist is Empty</h3>
                        <p class="empty-subtext">You haven't added any products to your wishlist yet. Explore our beautiful plants and save your favorites!</p>
                        <a href="{{ route('home') }}" class="continue-shopping-btn">
                            Continue Shopping
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($wishlistItems->count() > 0)
<section class="bg-white-section">
    <div class="container-fluid px-4">
        <div class="section-title">
            <h2>Related Products</h2>
            <div class="title-link">
                <a href="{{ route('products.index') }}">More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                <!-- You can add dynamic related products here -->
                <!-- For now, keeping the static ones -->
                @foreach($wishlistItems->take(10) as $item)
                @if(isset($item->product))
                <div class="swiper-slide">
                    @include('view.partials.product-card', ['product' => $item->product, 'isWishlistPage' => true])
                </div>
                @endif
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
@endif

<!-- Wishlist JavaScript -->
<script>
    // Base URL for your application
    let baseUrl = '';
    if (window.APP_URL) {
        baseUrl = window.APP_URL;
    } else if (document.querySelector('meta[name="base-url"]')) {
        baseUrl = document.querySelector('meta[name="base-url"]').content;
    } else {
        // Remove trailing slash from origin
        baseUrl = window.location.origin.replace(/\/$/, '');
    }

    // Get CSRF token
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    // Remove from wishlist with AJAX
    async function removeFromWishlist(productId) {
        handleRemoveFromWishlist(`${baseUrl}/wishlist/remove/${productId}`);
    }

    async function removeFromWishlistCombo(comboId) {
        handleRemoveFromWishlist(`${baseUrl}/wishlist/remove-combo/${comboId}`);
    }

    async function handleRemoveFromWishlist(url) {
        Swal.fire({
            title: 'Remove from Wishlist?',
            text: "Are you sure you want to remove this item from your wishlist?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#72a420',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, keep it'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.reload();
                    } else {
                        showToast(data.message || 'Failed to remove from wishlist', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Failed to remove item. Please try again.', 'error');
                }
            }
        });
    }

    // Add to cart from wishlist
    async function addToCartFromWishlist(productId, button) {
        try {
            // Add loading animation to button
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            const response = await fetch(`${baseUrl}/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    quantity: 1
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update cart count in header
                if (data.cart_count !== undefined) {
                    updateCartCount(data.cart_count);
                    if (typeof window.updateCartCountBadges === 'function') {
                        window.updateCartCountBadges(data.cart_count);
                    }
                }

                if (typeof window.openCartDrawer === 'function') window.openCartDrawer();
                if (typeof window.refreshCartDrawer === 'function') window.refreshCartDrawer();

                showToast(data.message || 'Added to cart!', 'success');

                // Optional: Remove from wishlist after adding to cart
                // removeFromWishlist(productId);

            } else {
                showToast(data.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Failed to add to cart. Please try again.', 'error');
        } finally {
            // Restore button
            button.innerHTML = originalHtml;
            button.disabled = false;
        }
    }

    // Update serial numbers after removal
    function updateSerialNumbers() {
        const rows = document.querySelectorAll('.pro-gl-content');
        rows.forEach((row, index) => {
            const serialSpan = row.querySelector('td:first-child span');
            if (serialSpan) {
                serialSpan.textContent = index + 1;
            }
        });
    }

    // Update cart count in header
    function updateCartCount(count) {
        document.querySelectorAll('.cart-icon-link .price_cart').forEach(element => {
            element.textContent = count;
        });
    }

    // Update wishlist count in header
    function updateWishlistCount(count) {
        document.querySelectorAll('.wishlist-icon-link .price_cart').forEach(element => {
            element.textContent = count;
        });
    }

    // Show empty wishlist message
    function showEmptyWishlist() {
        const wishlistBody = document.querySelector('.gi-vendor-card-body');
        if (wishlistBody) {
            wishlistBody.innerHTML = `
            <div class="empty-cart-container" style="animation: fadeInUp 0.5s ease-out;">
                <div class="empty-icon-wrapper">
                    <i class="far fa-heart"></i>
                </div>
                <h3 class="empty-text">Your Wishlist is Empty</h3>
                <p class="empty-subtext">You haven't added any products to your wishlist yet. Explore our beautiful plants and save your favorites!</p>
                <a href="${baseUrl}" class="continue-shopping-btn">
                    Continue Shopping
                </a>
            </div>
        `;

            // Hide related products section
            const relatedSection = document.querySelector('.bg-white-section');
            if (relatedSection) {
                relatedSection.style.display = 'none';
            }
        }
    }

    // Update related product wishlist buttons
    function updateRelatedProductWishlistButtons(productId) {
        // Find all wishlist buttons in related products
        const wishlistButtons = document.querySelectorAll('.btn-wishlist');
        wishlistButtons.forEach(button => {
            if (button.getAttribute('onclick')?.includes(productId)) {
                // Change to add to wishlist button
                button.innerHTML = '<i class="far fa-heart"></i>';
                button.setAttribute('onclick', `addToWishlistFromRelated(${productId})`);
                button.setAttribute('data-tooltip', 'Add to Wishlist');
            }
        });
    }

    // Add to wishlist from related products
    async function addToWishlistFromRelated(productId) {
        try {
            const response = await fetch(`${baseUrl}/wishlist/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update button to remove from wishlist
                const buttons = document.querySelectorAll('.btn-wishlist');
                buttons.forEach(button => {
                    if (button.getAttribute('onclick')?.includes(`addToWishlistFromRelated(${productId})`)) {
                        button.innerHTML = '<i class="fas fa-heart text-danger"></i>';
                        button.setAttribute('onclick', `removeFromWishlist(${productId})`);
                        button.setAttribute('data-tooltip', 'Remove from Wishlist');
                    }
                });

                // Update wishlist count
                if (data.wishlist_count !== undefined) {
                    updateWishlistCount(data.wishlist_count);
                }

                showToast(data.message || 'Added to wishlist!', 'success');
            } else if (data.login_url) {
                window.location.href = data.login_url;
            } else {
                showToast(data.message || 'Failed to add to wishlist', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Failed to add to wishlist. Please try again.', 'error');
        }
    }

    // Show toast notification using SweetAlert2
    function showToast(message, type = 'success') {
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




    // Initialize event listeners for add to cart buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Add to cart buttons in wishlist table
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                if (form) {
                    // Submit the form
                    form.submit();
                }
            });
        });

        // Add event listeners to wishlist buttons in related products
        document.querySelectorAll('.btn-wishlist').forEach(button => {
            button.addEventListener('click', function() {
                const onclickAttr = this.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes('removeFromWishlist')) {
                    const productId = onclickAttr.match(/\d+/)[0];
                    removeFromWishlist(productId);
                }
            });
        });

        console.log('Wishlist page initialized');
    });
</script>

@include('view.layout.footer')