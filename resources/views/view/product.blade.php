@include('view.layout.header')


<style>
    .wishlist-btn {
        width: 42px;
        height: 42px;
        border: 1px solid #ccc;
        /* grey border */
        border-radius: 50%;
        /* circle shape */
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #000;
        /* black heart */
        transition: 0.3s ease;
    }

    .wishlist-btn i {
        font-size: 18px;
    }

    /* hover effect (your given style) */
    .wishlist-btn:hover {
        background: #ff4d4d;
        color: #fff;
        border-color: #ff4d4d;
    }
</style>

<!-- Breadcrumb -->
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

<div class="container">
    <div class="container-fluid product-page-container">
        <div class="product-page-section">
            <div class="row">
                <!-- Product Gallery -->
                <div class="col-lg-6">
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
                                    <i class="fa-solid fa-share-from-square"></i>
                                </button>
                                <div class="share-dropdown" id="shareDropdown">
                                    <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank" class="share-item whatsapp"><i class="fab fa-whatsapp"></i><span>WhatsApp</span></a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-item facebook"><i class="fab fa-facebook-f"></i><span>Facebook</span></a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" target="_blank" class="share-item twitter"><i class="fab fa-twitter"></i><span>Twitter</span></a>
                                    <a href="https://www.instagram.com/" target="_blank" class="share-item instagram"><i class="fab fa-instagram"></i><span>Instagram</span></a>
                                </div>
                            </div>

                            {{-- Image + tracking lens (preview is a fixed sibling) --}}
                            <div class="dpz-img-wrap" id="dpzImgWrap">
                                <img id="mainProductImage"
                                    src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="dpz-img">
                                {{-- Tracking lens only --}}
                                <div id="dpzLens"></div>
                            </div>
                            {{-- Preview lives OUTSIDE the overflow:hidden wrap --}}
                            <div id="dpzPreview"></div>

                            @if($product->stock_quantity <= 0)
                                <span class="product-page-badge-sale" style="background: #dc3545 !important;">OUT OF STOCK</span>
                                @elseif($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                    <span class="product-page-badge-sale">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                    </span>
                                    @endif
                        </div>

                    </div>{{-- /dpz-wrapper --}}
                </div>
                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-page-info position-relative">
                        <h1>{{ $product->name }}</h1>

                        <style>
                            .product-share-container {
                                position: absolute;
                                top: 20px;
                                right: 20px;
                                z-index: 100;
                            }

                            .btn-share-toggle {
                                background: #ffffff;
                                color: #333;
                                border: none;
                                border-radius: 50%;
                                width: 44px;
                                height: 44px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 20px;
                                cursor: pointer;
                                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
                                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                border: 1px solid rgba(0, 0, 0, 0.05);
                            }

                            .btn-share-toggle:hover {
                                background: #72a420;
                                color: #fff;
                                transform: translateY(-3px) scale(1.05);
                                box-shadow: 0 12px 25px rgba(114, 164, 32, 0.3);
                            }

                            .share-dropdown {
                                position: absolute;
                                top: 55px;
                                right: 0;
                                background: #ffffff;
                                border-radius: 12px;
                                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
                                padding: 12px;
                                width: 180px;
                                display: none;
                                border: 1px solid #f0f0f0;
                                animation: shareFadeIn 0.3s ease;
                            }

                            .share-dropdown.show {
                                display: block;
                            }

                            .share-item {
                                display: flex;
                                align-items: center;
                                gap: 12px;
                                padding: 10px 14px;
                                border-radius: 8px;
                                text-decoration: none !important;
                                color: #2d3436;
                                transition: all 0.2s ease;
                                font-size: 14px;
                                font-weight: 500;
                            }

                            .share-item:hover {
                                background: #f8f9fa;
                                transform: translateX(5px);
                                color: #72a420;
                            }

                            .share-item i {
                                font-size: 18px;
                                width: 24px;
                                text-align: center;
                            }

                            .share-item.whatsapp i {
                                color: #25D366;
                            }

                            .share-item.facebook i {
                                color: #1877F2;
                            }

                            .share-item.twitter i {
                                color: #1DA1F2;
                            }

                            .share-item.instagram i {
                                color: #E4405F;
                            }

                            @keyframes shareFadeIn {
                                from {
                                    opacity: 0;
                                    transform: translateY(-12px) scale(0.95);
                                }

                                to {
                                    opacity: 1;
                                    transform: translateY(0) scale(1);
                                }
                            }
                        </style>
                        <!-- Dynamic Rating -->
                        @if(($product->total_reviews ?? 0) > 0 && ($product->avg_rating ?? 0) > 0)
                        <div class="product-page-rating mb-3">
                            <div class="product-page-stars d-inline" style="color: #ffc107;">
                                @php $avg = $product->avg_rating ?? 0; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=floor($avg))
                                    <i class="fas fa-star"></i>
                                    @elseif($i == ceil($avg) && ($avg - floor($avg) >= 0.5))
                                    <i class="fas fa-star-half-alt"></i>
                                    @else
                                    <i class="far fa-star text-muted"></i>
                                    @endif
                                    @endfor
                            </div>
                            <span class="product-page-rating-text ms-2 fw-bold" style="color: #333;">{{ number_format($avg, 1) }}</span>
                            <span class="product-page-reviews-count text-muted ms-1">({{ $product->total_reviews ?? 0 }} Reviews)</span>
                        </div>
                        @endif
                        <!-- Price -->
                        <div class="product-page-price mb-4">
                            @if($product->stock_quantity > 0)
                            @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                <span class="product-page-current-price h3">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="product-page-original-price ms-3 text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                <span class="product-page-current-price h3">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                                @endif
                        </div>
                        <!-- Description -->
                        <p class="product-page-description mb-4">
                            {{ $product->description ? $product->description : ($product->short_description ? $product->short_description : 'No description available.') }}
                        </p>
                        <!-- Attributes Selector -->
                        @if($product->size)
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
                        <div class="product-page-attributes mb-4">
                            <label class="fw-bold mb-2">Select Size:</label>
                            <div class="d-flex flex-wrap gap-2" id="sizeSelectorContainer">
                                @php $loopIndex = 0; @endphp
                                @foreach($sizeData as $sizeName => $sizePrice)
                                <input type="radio" class="btn-check size-radio" name="size" id="size-{{ $loopIndex }}" value="{{ $sizeName }}" autocomplete="off" form="mainCartForm" {{ $loopIndex === 0 ? 'checked' : '' }} required data-price="{{ $sizePrice ?? '' }}" onchange="updateProductPrice(this)">
                                <label class="btn btn-outline-success" for="size-{{ $loopIndex }}">
                                    {{ $sizeName }}
                                    @if($sizePrice) <small>(₹{{ $sizePrice }})</small> @endif
                                </label>
                                @php $loopIndex++; @endphp
                                @endforeach
                            </div>
                        </div>
                        <script>
                            function updateProductPrice(radio) {
                                let newPrice = radio.getAttribute('data-price');
                                let currentPriceEl = document.querySelector('.product-page-current-price');
                                let originalPriceEl = document.querySelector('.product-page-original-price');

                                if (newPrice && parseFloat(newPrice) > 0) {
                                    if (currentPriceEl) currentPriceEl.innerText = '₹' + parseFloat(newPrice).toFixed(2);
                                    if (originalPriceEl) originalPriceEl.style.display = 'none';
                                } else {
                                    // Restore default prices
                                    @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                    if (currentPriceEl) currentPriceEl.innerText = '₹{{ number_format($product->sale_price, 2) }}';
                                    if (originalPriceEl) {
                                        originalPriceEl.style.display = 'inline';
                                        originalPriceEl.innerText = '₹{{ number_format($product->price, 2) }}';
                                    }
                                    @else
                                    if (currentPriceEl) currentPriceEl.innerText = '₹{{ number_format($product->price, 2) }}';
                                    if (originalPriceEl) originalPriceEl.style.display = 'none';
                                    @endif
                                }
                            }
                            document.addEventListener('DOMContentLoaded', function() {
                                let firstRadio = document.querySelector('.size-radio:checked');
                                if (firstRadio) updateProductPrice(firstRadio);
                            });
                        </script>
                        @endif
                        @endif

                        <!-- Quantity Selector and Action Buttons inside Single Form -->
                        @if($product->stock_quantity > 0)
                        <!-- Wishlist Form (Standalone to avoid HTML nested form issue) -->
                        <form id="wishlistForm-{{ $product->id }}" action="{{ route('wishlist.add', $product) }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        <!-- Quantity Selector and Action Buttons inside Single Form -->
                        <form action="{{ route('cart.add', $product) }}" method="POST" id="mainCartForm">
                            @csrf

                            <!-- Quantity and Wishlist Row -->
                            <div class="d-flex align-items-center mb-4 flex-nowrap" style="gap: 15px;">
                                <div class="product-page-quantity-selector d-flex align-items-center mb-0 pe-2">
                                    <label class="product-page-qty-label me-3 fw-bold text-nowrap" for="quantityInput">Quantity:</label>
                                    <div class="product-page-qty-control d-flex align-items-center border rounded">
                                        <button type="button" class="product-page-qty-btn border-0 bg-transparent px-3" onclick="updateQty(-1)">−</button>
                                        <input type="number" id="quantityInput" name="quantity" class="product-page-qty-input border-0 text-center" value="1" min="1" readonly style="width: 50px;">
                                        <button type="button" class="product-page-qty-btn border-0 bg-transparent px-3" onclick="updateQty(1)">+</button>
                                    </div>
                                </div>

                                <button type="submit" form="wishlistForm-{{ $product->id }}" class="wishlist-btn flex-shrink-0">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="product-page-action-buttons d-flex gap-3 mb-4 flex-md-nowrap flex-wrap">
                                <button type="submit" class="product-page-btn-add-cart btn btn-lg btn-primary d-flex align-items-center justify-content-center gap-2 flex-grow-1 w-100-mobile">
                                    <i class="fas fa-shopping-bag"></i>
                                    Add to Cart
                                </button>
                                <button type="submit" name="buy_now" value="1"
                                    class="product-page-btn-buy-now btn btn-lg d-flex align-items-center justify-content-center flex-grow-1 w-100-mobile"
                                    style="
                                        padding: 15px 20px;
                                        background: white;
                                        color: #6EA820;
                                        border: 2px solid #6EA820;
                                        border-radius: 8px;
                                        font-weight: 600;
                                        cursor: pointer;
                                        transition: all 0.3s;
                                        font-size: 18px;
                                    "
                                    onmouseover="this.style.background='#6EA820'; this.style.color='#fff';"
                                    onmouseout="this.style.background='white'; this.style.color='#6EA820';">
                                    Buy Now
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="product-page-action-buttons mb-4">
                            <button class="product-page-btn-add-cart btn btn-lg btn-secondary d-flex align-items-center gap-2" style="cursor: not-allowed;" disabled>
                                Out of Stock
                            </button>
                        </div>
                        @endif
                        <!-- Wishlist Form -->
                        <!-- <form action="{{ route('wishlist.add', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="product-page-btn-wishlist btn btn-lg btn-outline-danger d-flex align-items-center gap-2">
                                <i class="far fa-heart"></i>
                                Add to Wishlist
                            </button>
                        </form> -->

                    </div>
                    <!-- Info Badges -->
                    <!-- <div class="product-page-info-badges d-flex gap-4">
                        <div class="product-page-info-badge d-flex align-items-center gap-2">
                            <span class="product-page-badge-icon"><i class="fas fa-leaf text-success"></i></span>
                            <span>100% Healthy Plant</span>
                        </div>
                        <div class="product-page-info-badge d-flex align-items-center gap-2">
                            <span class="product-page-badge-icon"><i class="fas fa-shield-alt text-primary"></i></span>
                            <span>30-Day Guarantee</span>
                        </div>
                    </div>
                </div> -->
                </div>
                <!-- End Product Info -->
            </div>
        </div>
    </div>

    @if($product->reviews->count() > 0)
    <div class="product-reviews-section py-5 bg-white border-top">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4">Customer Reviews</h3>
                    <div class="reviews-list">
                        @foreach($product->reviews as $review)
                        <div class="review-item mb-4 pb-4 border-bottom">
                            <div class="reviewer-header d-flex justify-content-between align-items-center mb-2">
                                <div class="reviewer-name fw-bold" style="color: #333; font-size: 16px;">{{ $review->user->name ?? 'Anonymous' }}</div>
                                <div class="review-date text-muted small">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="review-rating mb-2" style="color: #ffc107;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                            </div>
                            @if($review->review)
                            <div class="review-text text-secondary" style="font-size: 15px; line-height: 1.6;">
                                {{ $review->review }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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

    <style>
        /* ─── Dual-Panel Zoom (Amazon-Style) ────────────────────── */

        /* Outer flex wrapper: thumbs | main */
        .dpz-wrapper {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        /* Thumbnail column */
        .dpz-thumbs {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-shrink: 0;
        }

        .dpz-thumb {
            width: 72px;
            height: 72px;
            border: 2px solid #e8e8e8;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            transition: border-color 0.2s ease;
            background: #fff;
            padding: 3px;
        }

        .dpz-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .dpz-thumb.active,
        .dpz-thumb:hover {
            border-color: #6EA820;
        }

        /* Main image panel */
        .dpz-main {
            flex: 1;
            position: relative;
            min-width: 0;
        }

        /* Image wrapper — the hover target */
        .dpz-img-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid #ececec;
            background: #fff;
            cursor: crosshair;
            user-select: none;
        }

        .dpz-img {
            display: block;
            width: 100%;
            min-height: 420px;
            max-height: 600px;
            object-fit: contain;
            object-position: bottom right;
            border-radius: 7px;
        }

        /* Tracking lens */
        #dpzLens {
            position: absolute;
            width: 180px;
            height: 180px;
            border: 1px solid rgba(110, 168, 32, 0.6);
            background: rgba(110, 168, 32, 0.08);
            backdrop-filter: blur(1px);
            display: none;
            pointer-events: none;
            z-index: 10;
            border-radius: 4px;
        }

        /* Live zoom preview — fixed so NO parent clips it */
        #dpzPreview {
            position: fixed;
            width: 480px;
            height: 480px;
            background: #fff no-repeat;
            border: 1px solid #d0d0d0;
            border-radius: 8px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
            display: none;
            z-index: 99999;
            overflow: hidden;
            pointer-events: none;
        }

        /* Keep parent row overflow visible so preview can escape */
        .product-page-section,
        .product-page-section .row,
        .dpz-main {
            overflow: visible !important;
        }

        /* Mobile: hide preview + lens, show tap-to-lightbox cursor */
        @media (max-width: 991px) {
            .dpz-thumbs {
                flex-direction: row;
                flex-wrap: wrap;
            }

            #dpzLens,
            #dpzPreview {
                display: none !important;
            }

            .dpz-img-wrap {
                cursor: zoom-in;
            }
        }

        /* ─── Lightbox (mobile / click) ─────────────────────── */
        #zoomLightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.88);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        }

        #zoomLightbox.open {
            display: flex;
        }

        #zoomLightbox img {
            max-width: 92vw;
            max-height: 88vh;
            border-radius: 8px;
            object-fit: contain;
            box-shadow: 0 12px 60px rgba(0, 0, 0, 0.6);
        }

        #zoomLightbox .lb-close {
            position: absolute;
            top: 16px;
            right: 20px;
            color: #fff;
            font-size: 36px;
            line-height: 1;
            cursor: pointer;
            opacity: 0.85;
            font-weight: 300;
            transition: opacity 0.2s;
        }

        #zoomLightbox .lb-close:hover {
            opacity: 1;
        }
    </style>

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
                var value = Math.max(1, (parseInt(input.value) || 1) + change);
                input.value = value;
                
                // Hide minus button if quantity is 1
                var minusBtn = document.querySelector('.product-page-qty-btn:first-child');
                if (minusBtn) {
                    if (value <= 1) {
                        minusBtn.style.visibility = 'hidden';
                    } else {
                        minusBtn.style.visibility = 'visible';
                    }
                }

                var cartQty = document.getElementById('cartQuantity');
                if (cartQty) cartQty.value = value;
                var buyNow = document.getElementById('buyNowQuantity');
                if (buyNow) buyNow.value = value;
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

                        var rect = dpzImg.getBoundingClientRect();
                        var lW = dpzLens.offsetWidth || 180;
                        var lH = dpzLens.offsetHeight || 180;
                        var pW = dpzPreview.offsetWidth || 460;
                        var pH = dpzPreview.offsetHeight || 460;

                        // Cursor inside image (relative)
                        var x = e.clientX - rect.left;
                        var y = e.clientY - rect.top;

                        // Clamp lens inside image
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

            if (container && lightbox) {
                container.addEventListener('click', function() {
                    lbImg.src = mainImg.src;
                    lightbox.classList.add('open');
                });
                [lbClose, lightbox].forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        if (e.target === lightbox || e.target === lbClose)
                            lightbox.classList.remove('open');
                    });
                });
            }
        });
    </script>

    @include('view.layout.footer')