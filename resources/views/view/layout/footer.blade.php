<!-- footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <h5>About Us</h5>
                <p>{{ $headerFooter->footer_content ?? 'We are passionate about providing high-quality gardening products and solutions to help you create beautiful, thriving green spaces.' }}</p>
                <div class="social-links">
                    @if($headerFooter->facebook_link) <a href="{{ $headerFooter->facebook_link }}" target="_blank"><i class="fab fa-facebook-f"></i></a> @endif
                    @if($headerFooter->twitter_link) <a href="{{ $headerFooter->twitter_link }}" target="_blank"><i class="fab fa-twitter"></i></a> @endif
                    @if($headerFooter->insta_link) <a href="{{ $headerFooter->insta_link }}" target="_blank"><i class="fab fa-instagram"></i></a> @endif
                    @if($headerFooter->linkedin_link) <a href="{{ $headerFooter->linkedin_link }}" target="_blank"><i class="fab fa-linkedin-in"></i></a> @endif
                    @if($headerFooter->youtube_link) <a href="{{ $headerFooter->youtube_link }}" target="_blank"><i class="fab fa-youtube"></i></a> @endif
                </div>
            </div>

            <!-- Second Column: Quick Links -->
            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ url('about') }}">About Us</a></li>
                    <li><a href="{{ url('combo-packs') }}">Combo Packs</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('terms-conditions') }}">Terms & Conditions</a></li>
                    <li><a href="{{ url('refund-policy') }}">Refund Policy</a></li>

                </ul>
            </div>

            <div class="col-lg-3 col-md-6 col-6 mb-4">
                <h5>Categories</h5>
                <ul>
                    @foreach($headerCategories->take(6) as $category)
                    <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>{{ $headerFooter->footer_contact_title ?? 'Contact Info' }}</h5>

                <div class="contact-info">
                    @if($headerFooter->address)
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <div class="contact-text">
                            <strong>Address</strong>
                            <p>{{ $headerFooter->address }}</p>
                        </div>
                    </div>
                    @endif

                    @if($headerFooter->mobile_no)
                    <div class="contact-item">
                        <i class="fas fa-phone-alt mr-2"></i>
                        <div class="contact-text">
                            <strong>Phone</strong>
                            <p>{{ $headerFooter->mobile_no }}</p>
                        </div>
                    </div>
                    @endif

                    @if($headerFooter->email)
                    <div class="contact-item">
                        <i class="fas fa-envelope mr-2"></i>
                        <div class="contact-text">
                            <strong>Email</strong>
                            <p>{{ $headerFooter->email }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div> <!-- End row -->
    </div> <!-- End top container -->

        <!-- Footer Bottom -->
        <div class="footer-bottom py-3">
            <div class="container">
                @php
                    $servicesPage = \App\Models\Page::where('slug', 'services')->first();
                    $serviceHighlights = $servicesPage && isset($servicesPage->extra_content['features']) ? $servicesPage->extra_content['features'] : [];
                @endphp

                @if(!empty($serviceHighlights) && count($serviceHighlights) > 0)
                <!-- Footer Bottom Highlights (Creative UX) -->
                <div class="footer-highlights-bar py-3 mb-4">
                    <div class="row justify-content-center">
                        @foreach($serviceHighlights as $highlight)
                        <div class="col-lg-3 col-md-6 col-6 mb-3 mb-lg-0 highlight-item">
                            <div class="d-flex align-items-center justify-content-start">
                                <div class="highlight-icon-wrapper">
                                    <i class="{{ $highlight['icon'] ?? 'fas fa-check' }}"></i>
                                </div>
                                <div class="highlight-info">
                                    <h6 class="highlight-title">{{ $highlight['title'] ?? '' }}</h6>
                                    <span class="highlight-desc">{{ $highlight['description'] ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-3">
                            <!-- Left Side -->
                            <p class="m-0 text-center text-md-start mb-0" style="flex: 1;">
                                &copy; {{ date('Y') }} Plantsware. All rights reserved.
                            </p>

                            <!-- Middle: Payments -->
                            <div class="footer-bottom-payment d-flex justify-content-center flex-wrap gap-2" style="flex: 1;">
                                <div class="payment-link bg-white px-2 py-1  d-flex align-items-center">
                                    <img src="{{ asset('assets/images/visa.png') }}" alt="Visa" height="20" style="object-fit: contain; max-width: 40px;">
                                </div>
                                <div class="payment-link bg-white px-2 py-1  d-flex align-items-center">
                                    <img src="{{ asset('assets/images/rupay.png') }}" alt="RuPay" height="20" style="object-fit: contain; max-width: 40px;">
                                </div>
                                <div class="payment-link bg-white px-2 py-1  d-flex align-items-center">
                                    <img src="{{ asset('assets/images/gpay.png') }}" alt="GPay" height="20" style="object-fit: contain; max-width: 40px;">
                                </div>
                                <div class="payment-link bg-white px-2 py-1  d-flex align-items-center">
                                    <img src="{{ asset('assets/images/paytm.png') }}" alt="PayTM" height="20" style="object-fit: contain; max-width: 40px;">
                                </div>
                                <div class="payment-link bg-white px-2 py-1  d-flex align-items-center">
                                    <img src="{{ asset('assets/images/upi.png') }}" alt="UPI" height="20" style="object-fit: contain; max-width: 40px;">
                                </div>
                            </div>

                            <!-- Right Side -->
                            <p class="m-0 text-center text-md-end mb-0" style="flex: 1;">
                                <a href="https://jayamwebsolutions.com/web-design-company-in-chennai.php" class="developer-link">Developed by Jayam Web Solutions</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</footer>
<!-- footer -->
<!-- footer end -->
<!-- Floating WhatsApp Button -->
@if($headerFooter->whatsapp_no)
<a href="https://wa.me/{{ $headerFooter->whatsapp_no }}?text=Hello! I have a question about your products." class="whatsapp-float shadow-lg d-none d-md-flex" target="_blank">
    <div class="whatsapp-message-container">
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_1 ?? 'Chat with us' }}</div>
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_2 ?? 'Enquire and Order' }}</div>
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_3 ?? 'Green World' }}</div>
    </div>
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Mobile Sticky Bottom WhatsApp -->
<div class="mobile-whatsapp-bottom d-md-none">
    <a href="https://wa.me/{{ $headerFooter->whatsapp_no }}?text=Hello! I have a question about your products." target="_blank">
        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
    </a>
</div>
@endif




<!-- Cart Drawer Overlay -->
<div id="cartDrawerOverlay" class="cart-drawer-overlay"></div>

<!-- Cart Drawer -->
<div id="cartDrawer" class="cart-drawer">
    <div class="cart-drawer-header">
        <h5 class="cart-drawer-title">Shopping Cart</h5>
        <button type="button" id="closeCartDrawerBtn" class="cart-drawer-close-btn">&times;</button>
    </div>
    
    <div class="cart-drawer-body" id="cartDrawerBody">
        <div class="cart-drawer-loading">
            <!-- Blank state while loading -->
        </div>
    </div>
</div>

<!-- Wishlist Drawer -->
<div id="wishlistDrawer" class="cart-drawer">
    <div class="cart-drawer-header">
        <h5 class="cart-drawer-title">My Wishlist</h5>
        <button type="button" id="closeWishlistDrawerBtn" class="cart-drawer-close-btn">&times;</button>
    </div>
    
    <div class="cart-drawer-body" id="wishlistDrawerBody">
        <div class="cart-drawer-loading">
            <!-- Blank state while loading -->
        </div>
    </div>
</div>

<!-- jquery-3.4.1 -->
<script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}" defer></script>
<script src="{{ asset('assets/js/popper.min.js') }}" defer></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('assets/js/wow.min.js') }}" defer></script>

<script src="{{ asset('assets/js/custom.min.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";

        if (successMessage) {
            Toast.fire({
                icon: 'success',
                title: successMessage
            });
        }

        if (errorMessage) {
            Toast.fire({
                icon: 'error',
                title: errorMessage
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperElements = document.querySelectorAll('.product-swiper');

        swiperElements.forEach(function(element) {
            new Swiper(element, {
                loop: true,
                slidesPerView: 2,
                spaceBetween: 8,

                // ⭐ AUTOPLAY SETTINGS
                autoplay: {
                    delay: 99000, // Time between slides
                    disableOnInteraction: false, // Keep autoplay after user swipes
                },

                pagination: {
                    el: element.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: element.querySelector('.swiper-button-next'),
                    prevEl: element.querySelector('.swiper-button-prev'),
                },

                breakpoints: {
                    480: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 15,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 15,
                    },
                    1180: {
                        slidesPerView: 5,
                        spaceBetween: 16,
                    },
                },
            });
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            speed: 600,
            grabCursor: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next1',
                prevEl: '.swiper-button-prev1',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySwiper = new Swiper('.plant-categories-carousel', {
            slidesPerView: 2,
            spaceBetween: 10,
            loop: true,
            speed: 800,
            grabCursor: true,
            freeMode: false,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            breakpoints: {
                375: {
                    slidesPerView: 3,
                    spaceBetween: 10,
                },
                575: {
                    slidesPerView: 4,
                    spaceBetween: 15,
                },
                768: {
                    slidesPerView: 5,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 6,
                    spaceBetween: 20,
                },
            },
        });
    });
</script>


<!-- blog sharing section -->
<script>
    function copyBlogLink() {
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                alert('Blog link copied! You can paste it on Instagram.');
            });
    }
</script>

<!-- blog sharing section end  -->

<!-- Mini Cart Drawer Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartDrawer = document.getElementById('cartDrawer');
    const cartDrawerOverlay = document.getElementById('cartDrawerOverlay');
    const closeBtn = document.getElementById('closeCartDrawerBtn');
    
    function openCartDrawer() {
        cartDrawer.classList.add('active');
        cartDrawerOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        refreshCartDrawer();
    }
    
    function closeCartDrawer() {
        cartDrawer.classList.remove('active');
        cartDrawerOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function showDrawerLoading() {
        const body = document.getElementById('cartDrawerBody');
        if (body) {
            body.style.opacity = '0.5';
            body.style.pointerEvents = 'none';
            body.style.transition = 'opacity 0.2s ease';
        }
    }
    
    function refreshCartDrawer() {
        fetch("{{ route('cart.drawer') }}")
            .then(res => res.text())
            .then(html => {
                const body = document.getElementById('cartDrawerBody');
                if (body) {
                    body.innerHTML = html;
                    body.style.opacity = '';
                    body.style.pointerEvents = '';
                }
            })
            .catch(err => {
                console.error('Error fetching cart drawer:', err);
                const body = document.getElementById('cartDrawerBody');
                if (body) {
                    body.style.opacity = '';
                    body.style.pointerEvents = '';
                }
            });
    }
    
    // --- Wishlist Drawer JS ---
    const wishlistDrawer = document.getElementById('wishlistDrawer');
    const closeWishlistBtn = document.getElementById('closeWishlistDrawerBtn');
    
    function openWishlistDrawer() {
        wishlistDrawer.classList.add('active');
        cartDrawerOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        refreshWishlistDrawer();
    }
    
    function closeWishlistDrawer() {
        wishlistDrawer.classList.remove('active');
        cartDrawerOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function refreshWishlistDrawer() {
        fetch("{{ route('wishlist.drawer') }}")
            .then(res => res.text())
            .then(html => {
                const body = document.getElementById('wishlistDrawerBody');
                if (body) {
                    body.innerHTML = html;
                }
            })
            .catch(err => console.error('Error fetching wishlist drawer:', err));
    }
    // --------------------------

    function updateCartCountBadges(count) {
        document.querySelectorAll('.cart-icon-link .price_cart, .cart-icon-link .badge-count').forEach(el => {
            el.textContent = count;
        });
    }
    
    function updateWishlistCountBadges(count) {
        document.querySelectorAll('.wishlist-icon-link .price_cart').forEach(el => {
            el.textContent = count;
        });
    }
    
    window.openCartDrawer = openCartDrawer;
    window.closeCartDrawer = closeCartDrawer;
    window.refreshCartDrawer = refreshCartDrawer;
    window.openWishlistDrawer = openWishlistDrawer;
    window.closeWishlistDrawer = closeWishlistDrawer;
    window.refreshWishlistDrawer = refreshWishlistDrawer;
    window.updateCartCountBadges = updateCartCountBadges;
    window.updateWishlistCountBadges = updateWishlistCountBadges;
    
    document.addEventListener('click', function(e) {
        const cartLink = e.target.closest('.cart-icon-link');
        if (cartLink) {
            e.preventDefault();
            openCartDrawer();
        }
        const wishlistLink = e.target.closest('.wishlist-icon-link');
        if (wishlistLink) {
            e.preventDefault();
            openWishlistDrawer();
        }
    });
    
    // Close Drawer events
    if (closeBtn) closeBtn.addEventListener('click', closeCartDrawer);
    if (closeWishlistBtn) closeWishlistBtn.addEventListener('click', closeWishlistDrawer);
    if (cartDrawerOverlay) {
        cartDrawerOverlay.addEventListener('click', function() {
            closeCartDrawer();
            closeWishlistDrawer();
        });
    }
    
    // Continue Shopping button inside drawer (dynamic delegate)
    document.addEventListener('click', function(e) {
        if (e.target.id === 'drawerContinueShoppingBtn') {
            closeCartDrawer();
        }
    });
    
    // Intercept Form Submissions for Add to Cart
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const action = form.getAttribute('action') || '';
        
        // Handle normal product and combo pack forms (excluding custom combo redirects)
        if (action.includes('cart/add/') || action.includes('cart/add-combo/')) {
            const submitter = e.submitter;
            
            // Bypass Buy Now submits (must have explicitly name="buy_now" input/button or be product page buy now btn)
            const hasBuyNowInput = form.querySelector('input[name="buy_now"]') || form.querySelector('input[value="buy_now"]');
            const isBuyNowSubmitter = submitter && (submitter.name === 'buy_now' || submitter.value === 'buy_now' || submitter.classList.contains('product-page-btn-buy-now'));
            
            if (hasBuyNowInput || isBuyNowSubmitter) {
                return; // Let it submit naturally
            }
            
            e.preventDefault();
            
            const formData = new FormData(form);
            if (submitter && submitter.name) {
                formData.append(submitter.name, submitter.value);
            }
            
            openCartDrawer();
            showDrawerLoading();
            
            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;
                
                return {
                    status: response.status,
                    ok: response.ok,
                    body: data,
                    text: isJson ? null : await response.text()
                };
            })
            .then(({ status, ok, body, text }) => {
                if (ok && body && body.success) {
                    updateCartCountBadges(body.cart_count);
                    refreshCartDrawer();
                } else {
                    closeCartDrawer();
                    const errMsg = body ? body.message : 'Error adding to cart (Server returned ' + status + ')';
                    console.error('Add to cart failed:', errMsg, text);
                    if (window.Swal) {
                        Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        }).fire({
                            icon: 'error',
                            title: errMsg
                        });
                    }
                }
            })
            .catch(err => {
                console.error('Error adding to cart:', err);
                closeCartDrawer();
                if (window.Swal) {
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    }).fire({
                        icon: 'error',
                        title: 'Network error or request failed.'
                    });
                }
            });
        }
    });

    // Make global functions so they can be called inline (e.g. from onclick="removeDrawerItem(...)")
    window.removeDrawerItem = function(itemId) {
        const itemEl = document.getElementById(`drawerItem_${itemId}`);
        if (itemEl) {
            itemEl.style.transition = 'all 0.3s ease';
            itemEl.style.opacity = '0';
            itemEl.style.transform = 'scale(0.9)';
            itemEl.style.pointerEvents = 'none';
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch("{{ url('cart/remove') }}/" + itemId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status >= 200 && status < 300 && body.success) {
                updateCartCountBadges(body.cart_count);
                refreshCartDrawer();
                                // If we are currently on the /cart page, reload to sync
                if (window.location.pathname.includes('/cart')) {
                    window.location.reload();
                }
            } else {
                if (itemEl) {
                    itemEl.style.opacity = '';
                    itemEl.style.transform = '';
                    itemEl.style.pointerEvents = '';
                }
                refreshCartDrawer();
            }
        })
        .catch(err => {
            console.error('Error removing item:', err);
            if (itemEl) {
                itemEl.style.opacity = '';
                itemEl.style.transform = '';
                itemEl.style.pointerEvents = '';
            }
            refreshCartDrawer();
        });
    };

    // Inline qty increment/decrement delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.drawer-qty-dec')) {
            const btn = e.target.closest('.drawer-qty-dec');
            const itemId = btn.getAttribute('data-item-id');
            updateDrawerItemQty(itemId, -1);
        }
        if (e.target.closest('.drawer-qty-inc')) {
            const btn = e.target.closest('.drawer-qty-inc');
            const itemId = btn.getAttribute('data-item-id');
            updateDrawerItemQty(itemId, 1);
        }
    });

    function updateDrawerItemQty(itemId, change) {
        const itemEl = document.getElementById(`drawerItem_${itemId}`);
        if (!itemEl) return;
        const input = itemEl.querySelector('.drawer-qty-input');
        if (!input) return;
        
        let currentQty = parseInt(input.value) || 1;
        let newQty = currentQty + change;
        if (newQty < 1) return;
        
        // Hide any previous inline error
        const errorEl = itemEl.querySelector('.drawer-item-error');
        if (errorEl) {
            errorEl.style.display = 'none';
        }
        
        // Stock Limit Check for Side Cart
        if (change > 0) {
            const incBtn = itemEl.querySelector('.drawer-qty-inc');
            if (incBtn) {
                const stock = parseInt(incBtn.getAttribute('data-stock') || 0);
                if (newQty > stock) {
                    if (errorEl) {
                        errorEl.style.display = 'block';
                    }
                    return;
                }
            }
        }
        
        itemEl.style.transition = 'opacity 0.2s ease';
        itemEl.style.opacity = '0.6';
        itemEl.style.pointerEvents = 'none';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch("{{ url('cart/update') }}/" + itemId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                quantity: newQty
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status >= 200 && status < 300 && body.success) {
                updateCartCountBadges(body.cart_count);
                refreshCartDrawer();
                // If we are currently on the /cart page, reload to sync
                if (window.location.pathname.includes('/cart')) {
                    window.location.reload();
                }
            } else {
                itemEl.style.opacity = '';
                itemEl.style.pointerEvents = '';
                refreshCartDrawer();
                if (window.Swal) {
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    }).fire({
                        icon: 'error',
                        title: body.message || 'Unable to update quantity.'
                    });
                }
            }
        })
        .catch(err => {
            console.error('Error updating qty:', err);
            itemEl.style.opacity = '';
            itemEl.style.pointerEvents = '';
            refreshCartDrawer();
        });
    }

    window.applyDrawerCoupon = function(e) {
        e.preventDefault();
        const input = document.getElementById('drawerCouponCode');
        const code = input ? input.value.trim() : '';
        const errorEl = document.getElementById('drawerCouponError');
        if (errorEl) {
            errorEl.style.display = 'none';
            errorEl.textContent = '';
        }
        
        if (!code) {
            if (errorEl) {
                errorEl.textContent = 'Please enter a coupon code.';
                errorEl.style.display = 'block';
            }
            return;
        }
        
        const submitBtn = e.target.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch("{{ route('cart.apply_coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                refreshCartDrawer();
                if (window.location.pathname.includes('/cart')) {
                    window.location.reload();
                }
            } else {
                if (errorEl) {
                    errorEl.textContent = data.message;
                    errorEl.style.display = 'block';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Apply';
                }
            }
        })
        .catch(err => {
            console.error('Error applying coupon:', err);
            if (errorEl) {
                errorEl.textContent = 'Something went wrong. Please try again.';
                errorEl.style.display = 'block';
            }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Apply';
            }
        });
    };

    window.removeDrawerCoupon = function() {
        const removeBtn = document.querySelector('.drawer-coupon-section button[onclick="removeDrawerCoupon()"]');
        if (removeBtn) {
            removeBtn.disabled = true;
            removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch("{{ route('cart.remove_coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                refreshCartDrawer();
                if (window.location.pathname.includes('/cart')) {
                    window.location.reload();
                }
            }
        })
        .catch(err => {
            console.error('Error removing coupon:', err);
            if (removeBtn) {
                removeBtn.disabled = false;
                removeBtn.innerText = 'Remove';
            }
        });
    };

    // --- AJAX Wishlist Toggle ---
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.ajax-wishlist-btn');
        if (btn) {
            e.preventDefault();
            const icon = btn.querySelector('i.fa-heart');
            const inWishlist = btn.getAttribute('data-in-wishlist') === 'true';
            
            // Determine URL and Method
            const url = inWishlist ? btn.getAttribute('data-remove-url') : btn.getAttribute('data-add-url');
            const method = inWishlist ? 'DELETE' : 'POST';
            
            // Optimistic UI Update
            if (inWishlist) {
                // Removing
                icon.classList.remove('fas', 'text-danger');
                icon.classList.add('far');
                btn.setAttribute('data-in-wishlist', 'false');
            } else {
                // Adding
                icon.classList.remove('far');
                icon.classList.add('fas', 'text-danger');
                btn.setAttribute('data-in-wishlist', 'true');
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update badge count
                    if (data.wishlist_count !== undefined) {
                        document.querySelectorAll('.wishlist-icon-link .badge-count').forEach(el => {
                            el.innerText = data.wishlist_count;
                        });
                    }
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            })
            .catch(err => {
                console.error('Wishlist error:', err);
                // Revert on error
                if (inWishlist) {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-danger');
                    btn.setAttribute('data-in-wishlist', 'true');
                } else {
                    icon.classList.remove('fas', 'text-danger');
                    icon.classList.add('far');
                    btn.setAttribute('data-in-wishlist', 'false');
                }
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({ icon: 'error', title: 'Failed to update wishlist.' });
            });
        }
    });

    // --- Collapsible Filter Sections ---
    document.addEventListener('click', function(e) {
        const title = e.target.closest('.filter-title');
        if (title) {
            const section = title.closest('.filter-section');
            if (section && section.querySelector('.filter-options')) {
                section.classList.toggle('active');
            }
        }
    });
});
</script>
</body>

</html>