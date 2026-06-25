@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('combo_packs.frontend_index') }}" class="text-decoration-none">Combo Packs</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder text-muted">{{ $comboPack->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@php
    $constituentProducts = [];
    if ($comboPack->comboProduct && $comboPack->comboProduct->product_ids) {
        foreach ($comboPack->comboProduct->product_ids as $pid) {
            if (str_starts_with($pid, 'p_')) {
                $realId = str_replace('p_', '', $pid);
                $p = \App\Models\Product::find($realId);
                if ($p) {
                    $constituentProducts[] = $p;
                }
            } elseif (str_starts_with($pid, 'co_')) {
                $realId = str_replace('co_', '', $pid);
                $co = \App\Models\ComboOnlyProduct::find($realId);
                if ($co) {
                    $constituentProducts[] = $co;
                }
            }
        }
    }
    
    // Fallback if constituentProducts is empty
    if (empty($constituentProducts)) {
        $images = $comboPack->images;
        if (!empty($images)) {
            foreach ($images as $img) {
                $constituentProducts[] = (object)[
                    'name' => $comboPack->name,
                    'image' => $img,
                    'slug' => null
                ];
            }
        }
    }
@endphp

<div class="container">
    <div class="container-fluid product-page-container mt-4 mb-5">
        <div class="product-page-section">
            <div class="row">
                <!-- Left Column: Combo Gallery -->
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="combo-gallery-wrapper" style="position: relative; width: 100%; overflow: visible !important;">
                        
                        @if(count($constituentProducts) >= 2)
                            @php
                                $productCount = count($constituentProducts);
                                $maxIndex = max($productCount - 1, 1);
                                $stackOffset = $maxIndex * 15;
                            @endphp
                            <div class="combo-right-stack-gallery w-100" data-aos="zoom-in" style="position: relative; overflow: visible !important; --stack-offset: {{ $stackOffset }}px;">
                                <div class="right-pages-stack" style="position: relative; width: 100%; height: 450px; overflow: visible !important;">
                                    
                                    @foreach($constituentProducts as $index => $prod)
                                        @php
                                            $prodImg = ($prod && !empty($prod->image) && file_exists(public_path('storage/' . $prod->image))) ? asset('storage/' . $prod->image) : asset('assets/images/product/product1.jpg');
                                            $prodUrl = ($prod instanceof \App\Models\Product) ? route('product.show', $prod->slug) : '#';
                                            $prodName = $prod->name ?? $comboPack->name;
                                        @endphp
                                        
                                        <div class="combo-stack-card card-index-{{ $index }} {{ $comboPack->stock_quantity <= 0 ? 'out-of-stock' : '' }}" 
                                             id="stackCard-{{ $index }}" 
                                             data-index="{{ $index }}"
                                             data-name="{{ $prodName }}"
                                             data-url="{{ $prodUrl }}">
                                            
                                            <div class="combo-stack-img-wrap" style="position: relative; overflow: hidden !important;">
                                                <img src="{{ $prodImg }}" alt="{{ $prodName }}" class="combo-stack-img">
                                                @if($comboPack->stock_quantity <= 0)
                                                    <div class="out-of-stock-overlay">
                                                        <span class="out-of-stock-badge">Out Of Stock</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <!-- Navigation Arrows on the sides of the stack -->
                                    <button type="button" class="combo-stack-nav-btn prev-btn" id="comboStackPrev">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button type="button" class="combo-stack-nav-btn next-btn" id="comboStackNext">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                
                                <!-- Active Product Details Below the gallery stack -->
                                <div class="combo-active-product-info text-center mt-4">
                                    <h5 class="fw-bold mb-1" id="activeProdName" style="color: #0f172a; font-size: 16px;">
                                        {{ $constituentProducts[0]->name ?? $comboPack->name }}
                                    </h5>
                                    <a href="{{ ($constituentProducts[0] instanceof \App\Models\Product) ? route('product.show', $constituentProducts[0]->slug) : '#' }}" 
                                       id="activeProdLink" 
                                       class="btn btn-sm btn-link text-success p-0 fw-bold" 
                                       style="color: #4a7856 !important; text-decoration: none; font-size: 13px;">
                                        View Detail <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                                    </a>
                                </div>
                            </div>
                        @elseif(count($constituentProducts) == 1)
                            @php
                                $prod = $constituentProducts[0];
                                $prodImg = ($prod && !empty($prod->image) && file_exists(public_path('storage/' . $prod->image))) ? asset('storage/' . $prod->image) : asset('assets/images/product/product1.jpg');
                                $prodUrl = ($prod instanceof \App\Models\Product) ? route('product.show', $prod->slug) : '#';
                            @endphp
                            <div class="combo-images-container d-flex align-items-center justify-content-center w-100">
                                <div class="combo-image-card single {{ $comboPack->stock_quantity <= 0 ? 'out-of-stock' : '' }}">
                                    <div class="combo-image-wrap" style="position: relative; overflow: hidden !important;">
                                        <img src="{{ $prodImg }}" alt="{{ $prod->name ?? $comboPack->name }}" class="combo-img single-img">
                                        @if($comboPack->stock_quantity <= 0)
                                            <div class="out-of-stock-overlay">
                                                <span class="out-of-stock-badge">Out Of Stock</span>
                                            </div>
                                        @endif
                                        <div class="combo-product-overlay">
                                            <div class="combo-product-info-glass">
                                                <span class="combo-prod-name">{{ $prod->name ?? $comboPack->name }}</span>
                                                @if($prodUrl !== '#')
                                                    <a href="{{ $prodUrl }}" class="combo-prod-link">View Detail <i class="fas fa-arrow-right"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="combo-images-container d-flex align-items-center justify-content-center w-100">
                                <div class="combo-image-card single {{ $comboPack->stock_quantity <= 0 ? 'out-of-stock' : '' }}">
                                    <div class="combo-image-wrap" style="position: relative; overflow: hidden !important;">
                                        <img src="{{ asset('assets/images/product/default.jpg') }}" alt="{{ $comboPack->name }}" class="combo-img single-img">
                                        @if($comboPack->stock_quantity <= 0)
                                            <div class="out-of-stock-overlay">
                                                <span class="out-of-stock-badge">Out Of Stock</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Right Column: Combo Info -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="product-page-info ps-lg-4">
                        <h1 class="product-detail-title mb-2">{{ $comboPack->name }}</h1>
                        
                        <!-- Dynamic Rating -->
                        @if(($comboPack->total_reviews ?? 0) > 0 && ($comboPack->avg_rating ?? 0) > 0)
                            @php $avg = $comboPack->avg_rating ?? 0; @endphp
                            <div class="product-page-rating mb-3 d-flex align-items-center gap-2">
                                <div class="product-page-rating-badge">
                                    <span>{{ number_format($avg, 1) }}</span>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="product-page-reviews-count text-secondary" style="font-size: 0.85rem; font-weight: 500;">({{ $comboPack->total_reviews ?? 0 }} Reviews)</span>
                            </div>
                        @endif

                        <!-- Price Section -->
                        <div class="product-page-price">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                @if($comboPack->stock_quantity > 0)
                                    <span class="product-page-current-price">₹{{ number_format($comboPack->offer_price, 2) }}</span>
                                    @if($comboPack->total_price > $comboPack->offer_price)
                                        <span class="product-page-original-price">₹{{ number_format($comboPack->total_price, 2) }}</span>
                                    @endif
                                @else
                                    <span class="product-page-current-price">₹{{ number_format($comboPack->offer_price, 2) }}</span>
                                    <span class="badge-out-of-stock">Out of Stock</span>
                                @endif

                                @php
                                    $discount = 0;
                                    if ($comboPack->total_price > 0) {
                                        $discount = round((($comboPack->total_price - $comboPack->offer_price) / $comboPack->total_price) * 100);
                                    }
                                @endphp
                                @if($discount > 0 && $comboPack->stock_quantity > 0)
                                    <span class="badge-discount-save">
                                        Save ₹{{ number_format($comboPack->total_price - $comboPack->offer_price, 2) }} ({{ $discount }}% OFF)
                                    </span>
                                @endif
                            </div>
                            <div class="product-page-tax-info mt-1">Inclusive of all taxes</div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-2" style="color: #333; font-size: 1rem;">Description:</h5>
                            <div class="product-page-description text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                                {!! $comboPack->description !!}
                            </div>
                        </div>

                        <!-- Main Purchase Form -->
                        <form action="{{ route('cart.add_combo', $comboPack->id) }}" method="POST" id="mainCartForm">
                            @csrf

                            @if($comboPack->stock_quantity > 0)
                                <!-- Quantity Selector and Wishlist Button Row -->
                                <div class="d-flex align-items-center mb-4 flex-nowrap" style="gap: 15px;">
                                    <div class="product-page-quantity-selector d-flex align-items-center mb-0 pe-2">
                                        <label class="product-page-qty-label me-3 fw-bold text-nowrap" for="quantityInput" style="font-size: 0.95rem;">Quantity:</label>
                                        <div class="product-page-qty-control qty-pill-control d-flex align-items-center">
                                            <button type="button" class="qty-btn-inline border-0 bg-transparent" onclick="updateQty(-1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="quantityInput" name="quantity" class="product-page-qty-input border-0 text-center" value="1" min="1" readonly style="width: 40px; font-weight: 700;">
                                            <button type="button" class="qty-btn-inline border-0 bg-transparent" onclick="updateQty(1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="button" class="wishlist-btn wishlist-btn-combo flex-shrink-0" data-id="{{ $comboPack->id }}">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>

                                <!-- Action Buttons -->
                                <div class="product-page-action-buttons">
                                    <button type="submit" class="product-page-btn-add-cart btn btn-lg d-flex align-items-center justify-content-center gap-2 flex-grow-1">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span class="d-none d-md-inline">Add to Cart</span>
                                        <span class="d-inline d-md-none">Cart</span>
                                    </button>
                                    <button type="submit" name="buy_now" value="1" class="product-page-btn-buy-now btn btn-lg d-flex align-items-center justify-content-center flex-grow-1">
                                        <span class="d-none d-md-inline">Buy Now</span>
                                        <span class="d-inline d-md-none">Buy</span>
                                    </button>
                                </div>
                            @else
                                <div class="d-flex align-items-center mb-4 flex-nowrap" style="gap: 15px;">
                                    <button type="button" class="wishlist-btn wishlist-btn-combo flex-shrink-0" data-id="{{ $comboPack->id }}">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="product-page-action-buttons">
                                    <button class="product-page-btn-add-cart btn btn-lg d-flex align-items-center justify-content-center gap-2 w-100" disabled>
                                        Out of Stock
                                    </button>
                                </div>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products Section -->
<section class="related-products py-5 bg-light border-top">
    <div class="container-fluid px-4">
        <div class="section-title d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0" style="font-size: 1.8rem; color: var(--secondary-color);">Related Products</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-success">View All Products &rarr;</a>
        </div>
        
        <div class="row g-4">
            @php
                $relatedProducts = \App\Models\Product::where('is_active', 1)->inRandomOrder()->limit(4)->get();
            @endphp
            @foreach($relatedProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    @include('view.partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity Update
    window.updateQty = function(change) {
        var input = document.getElementById('quantityInput');
        if (!input) return;
        var value = parseInt(input.value) || 1;
        var newVal = value + change;
        if (newVal < 1) newVal = 1;
        
        // Check max stock if available
        var maxStock = {{ $comboPack->stock_quantity ?? 0 }};
        if (newVal > maxStock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Limit',
                text: `Only ${maxStock} items available in stock`,
                confirmButtonColor: '#6EA820'
            });
            return;
        }
        
        input.value = newVal;
        
        // Hide/Show minus button
        var minusBtn = document.querySelector('.qty-btn-inline:first-child');
        if (minusBtn) {
            if (newVal <= 1) {
                minusBtn.style.visibility = 'hidden';
            } else {
                minusBtn.style.visibility = 'visible';
            }
        }
    };

    // Initial quantity buttons check
    var minusBtn = document.querySelector('.qty-btn-inline:first-child');
    if (minusBtn) {
        minusBtn.style.visibility = 'hidden';
    }

    // AJAX Wishlist
    document.querySelectorAll('.wishlist-btn-combo').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const currentBtn = this;
            
            fetch("{{ url('/wishlist/add-combo') }}/" + id, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.message || "Something went wrong");
                    }
                    return data;
                });
            })
            .then(data => {
                if (data && data.success) {
                    alert(data.message);
                    document.querySelectorAll('.wishlist-icon-link .price_cart').forEach(el => {
                        el.textContent = data.wishlist_count;
                    });
                    // Update UI to show it's added
                    const icon = currentBtn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('far', 'fa-heart');
                        icon.classList.add('fas', 'fa-heart', 'text-danger');
                    }
                    currentBtn.style.backgroundColor = '#fff5f5';
                }
            })
            .catch(error => {
                if (error && error.message) {
                    alert(error.message);
                }
            });
        });
    });

    // Combo Stack Deck Switcher
    const stackCards = document.querySelectorAll('.combo-stack-card');
    const prevBtn = document.getElementById('comboStackPrev');
    const nextBtn = document.getElementById('comboStackNext');
    const activeProdName = document.getElementById('activeProdName');
    const activeProdLink = document.getElementById('activeProdLink');

    if (stackCards.length > 0) {
        let cardStates = [];
        // Initialize cardStates array with initial indices
        stackCards.forEach((card, idx) => {
            cardStates.push(idx);
        });

        function updateCardDisplay() {
            stackCards.forEach((card, idx) => {
                // Clear old index classes
                card.className.split(' ').forEach(cls => {
                    if (cls.startsWith('card-index-')) {
                        card.classList.remove(cls);
                    }
                });
                // Add new index class
                const stateIdx = cardStates[idx];
                card.classList.add(`card-index-${stateIdx}`);

                // Update active info if this card is now front-most (index 0)
                if (stateIdx === 0) {
                    if (activeProdName) activeProdName.textContent = card.getAttribute('data-name');
                    if (activeProdLink) {
                        activeProdLink.setAttribute('href', card.getAttribute('data-url'));
                        if (card.getAttribute('data-url') === '#') {
                            activeProdLink.style.display = 'none';
                        } else {
                            activeProdLink.style.display = 'inline-block';
                        }
                    }
                }
            });
        }

        function rotateNext() {
            // Shift first state to the back of the queue
            const first = cardStates.shift();
            cardStates.push(first);
            updateCardDisplay();
        }

        function rotatePrev() {
            // Shift last state to the front of the queue
            const last = cardStates.pop();
            cardStates.unshift(last);
            updateCardDisplay();
        }

        if (nextBtn) nextBtn.addEventListener('click', rotateNext);
        if (prevBtn) prevBtn.addEventListener('click', rotatePrev);

        // Swipe / Drag Support
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;
        const swipeThreshold = 40; // minimum distance in px to detect a swipe
        const dragThreshold = 10;  // if cursor moves more than 10px, treat as drag (disable direct click)
        let isDragging = false;
        let clickPrevented = false;

        const stackContainer = document.querySelector('.right-pages-stack');

        if (stackContainer) {
            // Touch events
            stackContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });

            stackContainer.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                endY = e.changedTouches[0].clientY;
                handleGesture();
            }, { passive: true });

            // Mouse events
            stackContainer.addEventListener('mousedown', (e) => {
                startX = e.clientX;
                startY = e.clientY;
                isDragging = true;
                clickPrevented = false;
                // Prevent default image drag behavior
                e.preventDefault();
            });

            stackContainer.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const diffX = Math.abs(e.clientX - startX);
                const diffY = Math.abs(e.clientY - startY);
                if (diffX > dragThreshold || diffY > dragThreshold) {
                    clickPrevented = true;
                }
            });

            stackContainer.addEventListener('mouseup', (e) => {
                if (!isDragging) return;
                endX = e.clientX;
                endY = e.clientY;
                isDragging = false;
                handleGesture();
            });

            stackContainer.addEventListener('mouseleave', () => {
                isDragging = false;
            });

            function handleGesture() {
                const diffX = startX - endX;
                const diffY = startY - endY;
                // Only swipe if horizontal diff is greater than vertical diff
                if (Math.abs(diffX) > swipeThreshold && Math.abs(diffX) > Math.abs(diffY)) {
                    if (diffX > 0) {
                        rotateNext();
                    } else {
                        rotatePrev();
                    }
                }
            }
        }

        // Clicking a card directly also brings it to the front
        stackCards.forEach((card, idx) => {
            card.addEventListener('click', function(e) {
                if (clickPrevented) {
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                }
                const targetState = cardStates[idx];
                // Rotate until targetState becomes 0
                for (let i = 0; i < targetState; i++) {
                    const first = cardStates.shift();
                    cardStates.push(first);
                }
                updateCardDisplay();
            });
        });

        // Initialize display
        updateCardDisplay();
    }
});
</script>

@include('view.layout.footer')
