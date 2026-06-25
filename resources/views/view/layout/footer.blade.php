<!-- footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
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
            <div class="col-lg-3 col-md-6">
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

            <div class="col-lg-3 col-md-6 mb-4">
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

        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom py-3">
            <div class="container">
                <!-- Footer Bottom Highlights (Creative UX) -->
                <div class="footer-highlights-bar py-3 mb-4">
                    <div class="row">
                        <!-- Highlight 1 -->
                        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0 highlight-item">
                            <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                <div class="highlight-icon-wrapper">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="highlight-info">
                                    <h6 class="highlight-title">Fast Delivery</h6>
                                    <span class="highlight-desc">Fast Shipping On All Orders</span>
                                </div>
                            </div>
                        </div>
                        <!-- Highlight 2 -->
                        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0 highlight-item">
                            <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                <div class="highlight-icon-wrapper">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="highlight-info">
                                    <h6 class="highlight-title">Secure Payment</h6>
                                    <span class="highlight-desc">100% Secure Payment</span>
                                </div>
                            </div>
                        </div>
                        <!-- Highlight 3 -->
                        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0 highlight-item">
                            <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                <div class="highlight-icon-wrapper">
                                    <i class="fas fa-undo"></i>
                                </div>
                                <div class="highlight-info">
                                    <h6 class="highlight-title">Easy Returns</h6>
                                    <span class="highlight-desc">30-Day Return Policy</span>
                                </div>
                            </div>
                        </div>
                        <!-- Highlight 4 -->
                        <div class="col-lg-3 col-md-6 highlight-item">
                            <div class="d-flex align-items-center justify-content-center justify-content-lg-start">
                                <div class="highlight-icon-wrapper">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="highlight-info">
                                    <h6 class="highlight-title">Quality Guarantee</h6>
                                    <span class="highlight-desc">Premium Quality Products</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">

                        <!-- Left Side -->
                        <p class="mb-2 mb-md-0">
                            &copy; 2026 Plantsware. All rights reserved.
                        </p>

                        <!-- <div class="footer-bottom-payment d-flex justify-content-center">
                            <div class="payment-link">
                                <img src="/assets/images/visa.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/rupay.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/gpay.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/paytm.png" alt="payment">
                            </div>
                            <div class="payment-link">
                                <img src="/assets/images/upi.png" alt="payment">
                            </div>
                        </div> -->

                        <p class="mb-0">
                            <a href="https://jayamwebsolutions.com/web-design-company-in-chennai.php" class="text-dark"> Developed by Jayam Web Solutions</a>
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
<a href="https://wa.me/{{ $headerFooter->whatsapp_no }}?text=Hello! I have a question about your products." class="whatsapp-float shadow-lg" target="_blank">
    <div class="whatsapp-message-container">
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_1 ?? 'Chat with us' }}</div>
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_2 ?? 'Enquire and Order' }}</div>
        <div class="whatsapp-message">{{ $headerFooter->whatsapp_msg_3 ?? 'Green World' }}</div>
    </div>
    <i class="fab fa-whatsapp"></i>
</a>
@endif

<style>
    .whatsapp-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        text-decoration: none !important;
        transition: all 0.3s ease;
        animation: pulse-whatsapp 2s infinite;
    }

    .whatsapp-float:hover {
        background-color: #128c7e;
        transform: scale(1.1);
        color: #FFF;
    }

    .whatsapp-message-container {
        position: absolute;
        right: 80px;
        background: transparent;
        color: white;
        border-radius: 8px;
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr;
        place-items: center;
        opacity: 1;
        visibility: visible;
        pointer-events: none;
    }

    .whatsapp-message {
        grid-area: 1 / 1 / 2 / 2;
        position: relative;
        opacity: 0;
        text-align: left;
        width: max-content;
        background: #333;
        padding: 8px 15px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
        animation: fade-sequence 15s infinite ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
    }

    /* Sequential timing: 5s per message in a 15s total cycle */
    .whatsapp-message:nth-child(1) {
        animation-delay: 0s;
    }

    .whatsapp-message:nth-child(2) {
        animation-delay: 5s;
    }

    .whatsapp-message:nth-child(3) {
        animation-delay: 10s;
    }

    @keyframes fade-sequence {
        0% {
            opacity: 0;
            transform: translateX(10px);
        }

        1.33% { /* 0.2s fade-in */
            opacity: 1;
            transform: translateX(0);
        }

        6.67% { /* Stay visible until 1.0s */
            opacity: 1;
            transform: translateX(0);
        }

        8% { /* 0.2s fade-out (done at 1.2s) */
            opacity: 0;
            transform: translateX(-10px);
        }

        100% {
            opacity: 0;
            transform: translateX(-10px);
        }
    }

    @keyframes pulse-whatsapp {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    @media (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            font-size: 28px;
            bottom: 20px;
            right: 20px;
        }

        .whatsapp-message-container {
            display: none;
        }
    }
</style>


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
            <div class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>
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
                    1300: {
                        slidesPerView: 5,
                        spaceBetween: 20,
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
        // Initialize the carousel dynamically based on slide count
        var catSlideCount = $('#plantCategoriesCarousel .plant-category-item').length;
        $('#plantCategoriesCarousel').slick({
            dots: false,
            arrows: false,
            infinite: catSlideCount > 1,
            speed: 300,
            slidesToShow: Math.min(6, catSlideCount),
            slidesToScroll: 1,
            autoplay: catSlideCount > 1,
            autoplaySpeed: 3000,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: Math.min(5, catSlideCount),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: Math.min(4, catSlideCount),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: Math.min(3, catSlideCount),
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 375,
                    settings: {
                        slidesToShow: Math.min(2, catSlideCount),
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // Custom navigation buttons
        $('#prevBtn').click(function() {
            $('#plantCategoriesCarousel').slick('slickPrev');
        });

        $('#nextBtn').click(function() {
            $('#plantCategoriesCarousel').slick('slickNext');
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
    
    function updateCartCountBadges(count) {
        document.querySelectorAll('.cart-icon-link .price_cart').forEach(el => {
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
    window.updateCartCountBadges = updateCartCountBadges;
    window.updateWishlistCountBadges = updateWishlistCountBadges;
    
    // Intercept header cart icon clicks to open drawer instead of full redirect
    document.addEventListener('click', function(e) {
        const cartLink = e.target.closest('.cart-icon-link');
        if (cartLink) {
            if (!window.location.pathname.includes('/checkout')) {
                e.preventDefault();
                openCartDrawer();
            }
        }
    });
    
    // Close Drawer events
    if (closeBtn) closeBtn.addEventListener('click', closeCartDrawer);
    if (cartDrawerOverlay) cartDrawerOverlay.addEventListener('click', closeCartDrawer);
    
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
                    
                    if (window.Swal) {
                        Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).fire({
                            icon: 'success',
                            title: body.message
                        });
                    }
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