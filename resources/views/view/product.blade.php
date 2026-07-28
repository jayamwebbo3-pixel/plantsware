@include('view.layout.header')


<!-- Breadcrumb -->
{{-- 
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    @if($product->category)
                    <li class="d-inline-block font-weight-bolder">
                        <a href="{{ route('category.show', $product->category->slug) }}" class="text-decoration-none">{{ $product->category->name }}</a>
                    </li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    @endif
                    <li class="d-inline-block font-weight-bolder">{{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
 --}}

<div class="">
    <div class="container-fluid product-page-container">
        <div class="product-page-section">
            <div class="row">
                <!-- Product Gallery -->
                <div class="col-md-6 col-lg-6">
                    {{-- Dual-panel zoom wrapper --}}
                    <div class="dpz-wrapper">

                        {{-- Thumbnail column --}}
                        <div class="dpz-thumbs" id="dpzThumbs">
                            <div class="dpz-thumb active" data-src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}" alt="{{ $product->name }}">
                            </div>
                            @if(!empty($product->gallery_images) && count($product->gallery_images) > 0)
                            @foreach($product->gallery_images as $gi)
                            <div class="dpz-thumb" data-src="{{ asset('storage/' . $gi) }}">
                                <img src="{{ asset('storage/' . $gi) }}" alt="{{ $product->name }}">
                            </div>
                            @endforeach
                            @endif
                        </div>

                        {{-- Main image panel --}}
                        <div class="dpz-main">
                            {{-- Share button lives here --}}
                            <div class="product-share-container">
                                <button type="button" class="btn-share-toggle" id="shareToggle">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                                <div class="share-dropdown" id="shareDropdown">
                                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank" class="share-item whatsapp"><i class="fab fa-whatsapp"></i><span>WhatsApp</span></a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-item facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
                                </div>
                            </div>

                            {{-- Image + tracking lens (preview is a fixed sibling) --}}
                            <div class="dpz-img-wrap {{ $product->stock_quantity <= 0 ? 'out-of-stock' : '' }}" id="dpzImgWrap">
                                <img id="mainProductImage"
                                    src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="dpz-img">
                                {{-- Tracking lens only --}}
                                <div id="dpzLens"></div>
                                @if($product->stock_quantity <= 0)
                                    <div class="out-of-stock-overlay">
                                        <span class="out-of-stock-badge">Out Of Stock</span>
                                    </div>

                                @endif
                                @if($product->combo_pack_eligible === 'Yes')
                                    <span class="product-page-badge-combo" style="position: absolute; top: 15px; right: 15px; background-color: #2e7d32; color: white; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; z-index: 10; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1;">
                                        Combo Eligible
                                    </span>
                                @endif
                            </div>
                            {{-- Preview lives OUTSIDE the overflow:hidden wrap --}}
                            <div id="dpzPreview"></div>
                        </div>

                    </div>{{-- /dpz-wrapper --}}
                </div>
                <!-- Product Info -->
                <div class="col-md-6 col-lg-6">
                    <div class="product-page-info position-relative">
                        <h1 class="product-detail-title">{{ $product->name }}</h1>
                        @if($product->combo_pack_eligible === 'Yes')
                            <div class="mb-3">
                                <span class="combo-eligible-badge">
                                    <i class="fas fa-check-circle"></i> Combo Eligible
                                </span>
                            </div>
                        @endif


                        <!-- Dynamic Rating -->
                        @if(($product->total_reviews ?? 0) > 0 && ($product->avg_rating ?? 0) > 0)
                        @php $avg = $product->avg_rating ?? 0; @endphp
                        <div class="product-page-rating mb-3 d-flex align-items-center gap-2">
                            <div class="product-page-rating-badge">
                                <span>{{ number_format($avg, 1) }}</span>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="product-page-reviews-count text-secondary" style="font-size: 0.85rem; font-weight: 500;">({{ $product->total_reviews ?? 0 }} Reviews)</span>
                        </div>
                        @endif
                        <!-- Price -->
                        <div class="product-page-price">
                            <div class="d-flex align-items-center " style="gap: 10px;">
                                @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                    <span class="product-page-current-price" data-default="₹{{ number_format($product->sale_price, 2) }}">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="product-page-original-price" data-default="₹{{ number_format($product->price, 2) }}">₹{{ number_format($product->price, 2) }}</span>
                                    @php
                                        $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                    @endphp
                                    <span class="badge-discount-save">
                                        ({{ $discount }}% OFF)
                                    </span>
                                @else
                                    <span class="product-page-current-price" data-default="₹{{ number_format($product->price, 2) }}">₹{{ number_format($product->price, 2) }}</span>
                                    <span class="product-page-original-price" style="display:none;" data-default=""></span>
                                @endif
                            </div>
                            <div class="product-page-tax-info mt-1">Inclusive of all taxes</div>
                        </div>
                        @if($product->has_variants && $product->size)
                        @php
                        $sizeData = [];
                        $isJsonSizes = false;
                        $rawSize = $product->size;
                        if ($rawSize) {
                        if (is_array($rawSize)) {
                        $isJsonSizes = true;
                        $sizeData = $rawSize;
                        } elseif (is_string($rawSize)) {
                        $decoded = json_decode($rawSize, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $isJsonSizes = true;
                        $sizeData = $decoded;
                        } else {
                        $parts = array_map('trim', explode(',', $rawSize));
                        foreach($parts as $p) {
                        if($p) $sizeData[$p] = null;
                        }
                        }
                        }
                        }
                        @endphp
                        @if(count($sizeData) > 0)
                        @php
                            $sizeItems = [];
                            $colorItems = [];
                            $defaultVariantName = null;
                            $firstVariantName = null;
                            foreach($sizeData as $name => $val) {
                                if (!$firstVariantName) $firstVariantName = $name;
                                $type = is_array($val) ? ($val['type'] ?? 'size') : 'size';
                                $stock = is_array($val) ? (int)($val['stock'] ?? 0) : 0;
                                if ($stock > 0 && !$defaultVariantName) {
                                    $defaultVariantName = $name;
                                }
                                if ($type === 'color') $colorItems[$name] = $val;
                                else $sizeItems[$name] = $val;
                            }
                            if (!$defaultVariantName) $defaultVariantName = $firstVariantName;
                            $loopIndex = 0;
                        @endphp

                        <div class="product-page-attributes">
                            @if(count($sizeItems) > 0)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-bold m-0">Available Size</label>
                            </div>
                            <div class="custom-size-selector-container mb-3" id="sizeSelectorContainer">
                                @foreach($sizeItems as $sizeName => $sizeValue)
                                @php 
                                    $price = is_array($sizeValue) ? ($sizeValue['price'] ?? null) : $sizeValue;
                                    $salePrice = is_array($sizeValue) ? ($sizeValue['sale_price'] ?? null) : null;
                                    $image = is_array($sizeValue) ? ($sizeValue['image'] ?? null) : null;
                                    $stock = is_array($sizeValue) ? ($sizeValue['stock'] ?? null) : null;
                                    $weight = is_array($sizeValue) ? ($sizeValue['weight'] ?? null) : null;
                                @endphp
                                <input type="radio" class="custom-size-radio size-radio" name="size" id="size-{{ $loopIndex }}" value="{{ $sizeName }}" autocomplete="off" form="mainCartForm" {{ $sizeName === $defaultVariantName ? 'checked' : '' }} required 
                                    data-price="{{ $price ?? '' }}" 
                                    data-sale-price="{{ $salePrice ?? '' }}"
                                    data-image="{{ $image ? asset('storage/' . $image) : '' }}"
                                    data-stock="{{ $stock ?? '' }}"
                                    data-weight="{{ $weight ?? '' }}"
                                    onchange="updateProductPrice(this)">
                                <label class="custom-size-label" for="size-{{ $loopIndex }}">
                                    {{ $sizeName }}
                                    @if($salePrice)
                                        <small>(₹{{ $salePrice }})</small>
                                    @elseif($price)
                                        <small>(₹{{ $price }})</small>
                                    @endif
                                </label>
                                @php $loopIndex++; @endphp
                                @endforeach
                            </div>
                            @endif

                            @if(count($colorItems) > 0)
                            <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                                <label class="fw-bold m-0">Available Color</label>
                            </div>
                            <div class="custom-size-selector-container mb-3" id="colorSelectorContainer">
                                @foreach($colorItems as $sizeName => $sizeValue)
                                @php 
                                    $price = is_array($sizeValue) ? ($sizeValue['price'] ?? null) : $sizeValue;
                                    $salePrice = is_array($sizeValue) ? ($sizeValue['sale_price'] ?? null) : null;
                                    $image = is_array($sizeValue) ? ($sizeValue['image'] ?? null) : null;
                                    $stock = is_array($sizeValue) ? ($sizeValue['stock'] ?? null) : null;
                                    $weight = is_array($sizeValue) ? ($sizeValue['weight'] ?? null) : null;
                                    
                                    $colorNameStr = preg_replace('/[^a-zA-Z]/', '', $sizeName);
                                    if(empty($colorNameStr)) $colorNameStr = 'transparent';
                                @endphp
                                <input type="radio" class="custom-size-radio size-radio" name="size" id="size-{{ $loopIndex }}" value="{{ $sizeName }}" autocomplete="off" form="mainCartForm" {{ $sizeName === $defaultVariantName ? 'checked' : '' }} required 
                                    data-price="{{ $price ?? '' }}" 
                                    data-sale-price="{{ $salePrice ?? '' }}"
                                    data-image="{{ $image ? asset('storage/' . $image) : '' }}"
                                    data-stock="{{ $stock ?? '' }}"
                                    data-weight="{{ $weight ?? '' }}"
                                    onchange="updateProductPrice(this)">
                                <label class="custom-size-label" for="size-{{ $loopIndex }}">
                                    {{ $sizeName }}
                                    @if($salePrice)
                                        <small>(₹{{ $salePrice }})</small>
                                    @elseif($price)
                                        <small>(₹{{ $price }})</small>
                                    @endif
                                </label>
                                @php $loopIndex++; @endphp
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <script>
                            function updateProductPrice(radio) {
                                let qtyInput = document.getElementById('quantityInput');
                                
                                // Reset Quantity to 1 on size change (User Request: "quantity will be restart to 1")
                                if (qtyInput) {
                                    qtyInput.value = 1;
                                    if (document.getElementById('cartQuantity')) document.getElementById('cartQuantity').value = 1;
                                    if (document.getElementById('buyNowQuantity')) document.getElementById('buyNowQuantity').value = 1;
                                    
                                    // Hide minus button for quantity 1
                                    const minusBtn = document.querySelector('.qty-btn-inline:first-child');
                                    if (minusBtn) minusBtn.style.visibility = 'hidden';
                                }

                                // Update URL to reflect selection (Size and Reset Quantity)
                                const url = new URL(window.location);
                                url.searchParams.set('size', radio.value);
                                url.searchParams.set('qty', 1);
                                window.history.replaceState({}, '', url);

                                let newPrice = radio.getAttribute('data-price');
                                let newSalePrice = radio.getAttribute('data-sale-price');
                                let newStock = radio.getAttribute('data-stock');
                                let stockVal = parseInt(newStock) || 0;
                                
                                let addCartBtn = document.querySelector('.product-page-btn-add-cart');
                                let buyNowBtn = document.querySelector('.product-page-btn-buy-now');
                                let currentPriceEl = document.querySelector('.product-page-current-price');
                                let originalPriceEl = document.querySelector('.product-page-original-price');

                                if (newSalePrice && parseFloat(newSalePrice) > 0) {
                                    if (currentPriceEl) currentPriceEl.innerText = '₹' + parseFloat(newSalePrice).toFixed(2);
                                    if (originalPriceEl) {
                                        originalPriceEl.style.display = 'inline';
                                        originalPriceEl.innerText = '₹' + parseFloat(newPrice).toFixed(2);
                                    }
                                } else if (newPrice && parseFloat(newPrice) > 0) {
                                    if (currentPriceEl) currentPriceEl.innerText = '₹' + parseFloat(newPrice).toFixed(2);
                                    if (originalPriceEl) originalPriceEl.style.display = 'none';
                                } else {
                                    // Restore default prices from data attributes
                                    if (currentPriceEl) {
                                        currentPriceEl.innerText = currentPriceEl.getAttribute('data-default');
                                    }
                                    if (originalPriceEl) {
                                        const defaultOrig = originalPriceEl.getAttribute('data-default');
                                        if (defaultOrig) {
                                            originalPriceEl.style.display = 'inline';
                                            originalPriceEl.innerText = defaultOrig;
                                        } else {
                                            originalPriceEl.style.display = 'none';
                                        }
                                    }
                                }

                                // Sync with image and zoom
                                let mainImageEl = document.getElementById('mainProductImage');
                                let newImage = radio.getAttribute('data-image');
                                if (newImage && mainImageEl) {
                                    mainImageEl.src = newImage;
                                } else if (mainImageEl) {
                                    // Fallback to default product image
                                    mainImageEl.src = "{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}";
                                }

                                let preview = document.getElementById('dpzPreview');
                                if (preview && mainImageEl) {
                                    preview.style.backgroundImage = 'url("' + mainImageEl.src + '")';
                                }
                                if (typeof window.attachZoom === 'function') {
                                    window.attachZoom();
                                }

                                // Stock Status logic removed as requested

                                if (newStock !== '' && newStock !== null && stockVal <= 0) {
                                    if (addCartBtn) {
                                        addCartBtn.disabled = true;
                                        addCartBtn.innerHTML = 'Out of Stock';
                                        addCartBtn.classList.remove('btn-primary');
                                        addCartBtn.classList.add('btn-secondary');
                                    }
                                    if (buyNowBtn) buyNowBtn.style.setProperty('display', 'none', 'important');
                                } else {
                                    if (addCartBtn) {
                                        addCartBtn.disabled = false;
                                        addCartBtn.innerHTML = '<i class="fas fa-shopping-cart"></i> Add to Cart';
                                        addCartBtn.classList.remove('btn-secondary');
                                        addCartBtn.classList.add('btn-primary');
                                    }
                                    if (buyNowBtn) buyNowBtn.style.setProperty('display', 'flex', 'important');
                                }
                            }
                            document.addEventListener('DOMContentLoaded', function() {
                                // Handle pre-selection from URL
                                const urlParams = new URLSearchParams(window.location.search);
                                const sizeParam = urlParams.get('size');
                                const qtyParam = urlParams.get('qty');

                                if (sizeParam) {
                                    const targetRadio = document.querySelector(`.size-radio[value="${CSS.escape(sizeParam)}"]`);
                                    if (targetRadio) {
                                        targetRadio.checked = true;
                                    }
                                }

                                if (qtyParam) {
                                    let qtyInput = document.getElementById('quantityInput');
                                    if (qtyInput) {
                                        qtyInput.value = Math.max(1, parseInt(qtyParam) || 1);
                                        if (document.getElementById('cartQuantity')) document.getElementById('cartQuantity').value = qtyInput.value;
                                        if (document.getElementById('buyNowQuantity')) document.getElementById('buyNowQuantity').value = qtyInput.value;
                                    }
                                }

                                let activeRadio = document.querySelector('.size-radio:checked');
                                if (activeRadio) {
                                    updateProductPrice(activeRadio);
                                }
                            });
                        </script>
                        @endif
                        @endif

                        <!-- Description -->
                        <p class="product-page-description mb-4">
                            {{ $product->description ? $product->description : ($product->short_description ? $product->short_description : 'No description available.') }}
                        </p>

                        <!-- Quantity Selector and Action Buttons inside Single Form -->
                        @if($product->stock_quantity > 0)
                        @auth
                        @php
                            $inWishlist = false;
                            if (auth()->check() && auth()->user()->wishlist) {
                                $inWishlist = auth()->user()->wishlist->contains('product_id', $product->id);
                            }
                        @endphp
                        <!-- Removed Standalone Wishlist Form -->

                        <!-- Quantity Selector and Action Buttons inside Single Form -->
                        <form action="{{ route('cart.add', $product) }}" method="POST" id="mainCartForm">
                            @csrf

                            <!-- Quantity and Wishlist Row -->
                            <div class="d-flex align-items-center mb-4 flex-nowrap" style="gap: 15px;">
                                <div class="product-page-quantity-selector d-flex align-items-center mb-0 pe-2">
                                    <label class="product-page-qty-label me-3 fw-bold text-nowrap" for="quantityInput">Quantity:</label>
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

                                <button type="button" class="wishlist-btn flex-shrink-0 ajax-wishlist-btn"
                                        data-add-url="{{ route('wishlist.add', $product) }}"
                                        data-remove-url="{{ route('wishlist.remove', $product) }}"
                                        data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}">
                                    <i class="{{ $inWishlist ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="product-page-action-buttons">
                                <button type="submit" class="product-page-btn-add-cart btn d-flex align-items-center">
                                    <i class="fas fa-shopping-cart" style="margin-right: 6px;"></i>
                                    <span class="d-none d-md-inline">Add Cart</span>
                                    <span class="d-inline d-md-none">Cart</span>
                                </button>
                                <button type="submit" name="buy_now" value="1" class="product-page-btn-buy-now btn d-flex align-items-center">
                                    <i class="fas fa-credit-card" style="margin-right: 6px;"></i>
                                    <span class="d-none d-md-inline">Buy Now</span>
                                    <span class="d-inline d-md-none">Buy</span>
                                </button>
                            </div>
                        </form>
                        @else
                        {{-- Guest: redirect all actions to login --}}
                        <div class="d-flex align-items-center mb-4 flex-nowrap" style="gap: 15px;">
                            <div class="product-page-quantity-selector d-flex align-items-center mb-0 pe-2">
                                <label class="product-page-qty-label me-3 fw-bold text-nowrap">Quantity:</label>
                                <div class="product-page-qty-control qty-pill-control d-flex align-items-center">
                                    <span class="qty-btn-inline border-0 bg-transparent"><i class="fas fa-minus"></i></span>
                                    <input type="number" class="product-page-qty-input border-0 text-center" value="1" min="1" readonly style="width: 40px; font-weight: 700;">
                                    <span class="qty-btn-inline border-0 bg-transparent"><i class="fas fa-plus"></i></span>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="wishlist-btn flex-shrink-0 d-flex align-items-center justify-content-center">
                                <i class="far fa-heart"></i>
                            </a>
                        </div>
                        <div class="product-page-action-buttons">
                            <a href="{{ route('login') }}" class="product-page-btn-add-cart btn d-flex align-items-center">
                                <i class="fas fa-shopping-cart" style="margin-right: 6px;"></i>
                                <span class="d-none d-md-inline">Add Cart</span>
                                <span class="d-inline d-md-none">Cart</span>
                            </a>
                            <a href="{{ route('login') }}" class="product-page-btn-buy-now btn d-flex align-items-center">
                                <i class="fas fa-credit-card" style="margin-right: 6px;"></i>
                                <span class="d-none d-md-inline">Buy Now</span>
                                <span class="d-inline d-md-none">Buy</span>
                            </a>
                        </div>
                        @endauth
                        @else
                        <div class="product-page-action-buttons">
                            <button class="product-page-btn-add-cart btn btn-lg d-flex align-items-center gap-2" disabled>
                                Out of Stock
                            </button>
                        </div>
                        @endif



                    </div>
                </div>
                <!-- End Product Info -->
            </div>
        </div>
    </div>
</div> <!-- Close outer container from line 27 -->

    <!-- Related Products Section -->
    <section class="bg-light py-5">
        <div class="container-fluid px-4">
            <div class="section-title d-flex justify-content-between align-items-center mb-4">
                <h2>Related Products</h2>
                <!-- <a href="{{ route('products.index') }}" class="title-link">More <i class="fas fa-chevron-right"></i></a> -->
            </div>
            <div class="swiper product-swiper">
                <div class="swiper-wrapper">
                    @forelse($relatedProducts as $relatedProduct)
                    <div class="swiper-slide">
                        @include('view.partials.product-card', ['product' => $relatedProduct])
                    </div>
                    @empty
                    <div class="swiper-slide text-center py-5">
                        <p>No related products available</p>
                    </div>
                    @endforelse
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    @if($product->reviews->count() > 0)
    <div class="product-reviews-section py-5 bg-light border-top">
        <div class="container-fluid px-4">
            <div class="section-title mb-4">
                <h2>Customer Reviews</h2>
            </div>
            <div class="swiper product-swiper" style="padding: 10px;">
                <div class="swiper-wrapper">
                    @foreach($product->reviews as $review)
                    <div class="swiper-slide h-auto">
                        <div class="review-card bg-white p-4 rounded shadow-sm h-100 border" style="border-color: #e2e8f0 !important;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="reviewer-name fw-bold mb-1" style="color: #0f172a; font-size: 1.05rem;">{{ $review->user->name ?? 'Anonymous' }}</h5>
                                    <div class="review-date text-muted" style="font-size: 0.8rem; font-weight: 500;">{{ $review->created_at->format('M d, Y') }}</div>
                                </div>
                                <div class="review-rating" style="color: #facc15; font-size: 0.9rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($review->review)
                            <div class="review-text" style="color: #475569; font-size: 0.95rem; line-height: 1.6;">
                                "{{ $review->review }}"
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-button-next" style="right: 0;"></div>
                <div class="swiper-button-prev" style="left: 0;"></div>
            </div>
        </div>
    </div>
    @endif


    {{-- Lightbox markup --}}
    <div id="zoomLightbox">
        <span class="lb-close" id="lbClose">&times;</span>
        <img id="lbImg" src="" alt="Product zoom">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ─── Gallery thumbnail click ──────────────────────────────
            document.querySelectorAll('.dpz-thumb').forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    document.querySelectorAll('.dpz-thumb').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    this.classList.add('active');
                    var newSrc = this.getAttribute('data-src');
                    var img = document.getElementById('mainProductImage');
                    img.src = newSrc;
                    // Update preview panel source
                    var preview = document.getElementById('dpzPreview');
                    if (preview) preview.style.backgroundImage = 'url("' + newSrc + '")';
                    attachZoom(); // re‑bind zoom to new image
                });
            });

            // ─── Quantity update ─────────────────────────────────────
            window.updateQty = function(change) {
                var input = document.getElementById('quantityInput');
                var currentVal = parseInt(input.value) || 1;
                var newVal = Math.max(1, currentVal + change);

                if (change > 0) {
                    // Get base stock
                    let stock = parseInt('{{ $product->stock_quantity ?? 0 }}') || 0;
                    
                    // Check if a specific variant is selected and has its own stock
                    let activeRadio = document.querySelector('.size-radio:checked');
                    if (activeRadio) {
                        let sizeStock = activeRadio.getAttribute('data-stock');
                        if (sizeStock !== '' && sizeStock !== null) {
                            stock = parseInt(sizeStock);
                        }
                    }

                    if (newVal > stock) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stock Limit',
                            text: `Only ${stock} of stock only available`,
                            confirmButtonColor: '#6EA820'
                        });
                        return;
                    }
                }

                input.value = newVal;

                // Hide minus button if quantity is 1
                var minusBtn = document.querySelector('.qty-btn-inline:first-child');
                if (minusBtn) {
                    if (newVal <= 1) {
                        minusBtn.style.visibility = 'hidden';
                    } else {
                        minusBtn.style.visibility = 'visible';
                    }
                }

                var cartQty = document.getElementById('cartQuantity');
                if (cartQty) cartQty.value = newVal;
                var buyNow = document.getElementById('buyNowQuantity');
                if (buyNow) buyNow.value = newVal;

                // Update URL to reflect quantity
                const url = new URL(window.location);
                url.searchParams.set('qty', newVal);
                window.history.replaceState({}, '', url);
            };

            // ─── Share toggle ────────────────────────────────────────
            var shareToggle = document.getElementById('shareToggle');
            var shareDropdown = document.getElementById('shareDropdown');
            if (shareToggle && shareDropdown) {
                shareToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    shareDropdown.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!shareToggle.contains(e.target) && !shareDropdown.contains(e.target))
                        shareDropdown.classList.remove('show');
                });
            }

            // ─── Dual-Panel Zoom Engine ──────────────────────────
            var dpzWrap = document.getElementById('dpzImgWrap');
            var dpzImg = document.getElementById('mainProductImage');
            var dpzLens = document.getElementById('dpzLens');
            var dpzPreview = document.getElementById('dpzPreview');
            var MAGNIFY = 2.5; // zoom factor

            function dpzAttach() {
                if (!dpzWrap || !dpzImg || !dpzLens || !dpzPreview) return;

                var init = function() {
                    dpzPreview.style.backgroundImage = 'url("' + dpzImg.src + '")';

                    dpzWrap.addEventListener('mousemove', function(e) {
                        if (window.innerWidth < 992) return;

                        var rect = dpzWrap.getBoundingClientRect();
                        var lW = dpzLens.offsetWidth || 180;
                        var lH = dpzLens.offsetHeight || 180;
                        var pW = dpzPreview.offsetWidth || 460;
                        var pH = dpzPreview.offsetHeight || 460;

                        // Cursor inside container (relative)
                        var x = e.clientX - rect.left;
                        var y = e.clientY - rect.top;

                        // Clamp lens inside container
                        var lx = Math.min(Math.max(x - lW / 2, 0), rect.width - lW);
                        var ly = Math.min(Math.max(y - lH / 2, 0), rect.height - lH);

                        dpzLens.style.transform = 'translate(' + lx + 'px,' + ly + 'px)';
                        dpzLens.style.display = 'block';

                        // Position preview panel to the RIGHT of the image using fixed coords
                        var previewLeft = rect.right + 16;
                        var previewTop = rect.top;
                        // Keep on screen if near right edge
                        if (previewLeft + pW > window.innerWidth) {
                            previewLeft = rect.left - pW - 16;
                        }
                        dpzPreview.style.left = previewLeft + 'px';
                        dpzPreview.style.top = previewTop + 'px';
                        dpzPreview.style.display = 'block';

                        // Magnified background
                        var bgW = rect.width * MAGNIFY;
                        var bgH = rect.height * MAGNIFY;
                        var bgX = -(lx * MAGNIFY) + (pW - lW * MAGNIFY) / 2;
                        var bgY = -(ly * MAGNIFY) + (pH - lH * MAGNIFY) / 2;

                        dpzPreview.style.backgroundSize = bgW + 'px ' + bgH + 'px';
                        dpzPreview.style.backgroundPosition = bgX + 'px ' + bgY + 'px';
                    });

                    dpzWrap.addEventListener('mouseleave', function() {
                        dpzLens.style.display = 'none';
                        dpzPreview.style.display = 'none';
                    });

                    // Mobile: tap opens lightbox
                    dpzWrap.addEventListener('click', function() {
                        if (window.innerWidth < 992) openLightbox();
                    });
                };

                if (dpzImg.complete) {
                    init();
                } else {
                    dpzImg.addEventListener('load', init, {
                        once: true
                    });
                }
            }

            function attachZoom() {
                dpzAttach();
            }
            attachZoom();

            // ─── Lightbox (desktop click OR mobile tap) ──────────────
            var lightbox = document.getElementById('zoomLightbox');
            var lbImg = document.getElementById('lbImg');
            var lbClose = document.getElementById('lbClose');

            if (dpzWrap && lightbox) {
                dpzWrap.addEventListener('click', function() {
                    if (lbImg && dpzImg) {
                        lbImg.src = dpzImg.src;
                        lightbox.classList.add('open');
                    }
                });
                if (lbClose && lightbox) {
                    [lbClose, lightbox].forEach(function(el) {
                        el.addEventListener('click', function(e) {
                            if (e.target === lightbox || e.target === lbClose)
                                lightbox.classList.remove('open');
                        });
                    });
                }
            }
        });
    </script>

    @include('view.layout.footer')