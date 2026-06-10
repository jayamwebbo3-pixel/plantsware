<div class="product-card">
    <div class="product-image-container" style="background: #ffffff;">
        <a href="{{ route('product.show', $product->slug) }}">
            @php
            $mainImage = $product->image ? asset('storage/' . $product->image) : null;
            $galleryImages = $product->gallery_images ?? [];
            $hoverImage = !empty($galleryImages) ? asset('storage/' . $galleryImages[0]) : $mainImage;
            @endphp
            <img src="{{ $mainImage }}"
                 alt="{{ $product->name }}" class="product-image main-image w-100 h-100" style="object-fit: contain; background: #ffffff;" loading="lazy" decoding="async">
            <img src="{{ $hoverImage }}"
                 alt="{{ $product->name }}" class="product-image hover-image w-100 h-100" style="object-fit: contain; background: #ffffff;" loading="lazy" decoding="async">
            @if($product->stock_quantity <= 0)
                <span class="discount-badge" style="background-color: #dc3545 !important;">OUT OF STOCK</span>
            @elseif($product->sale_price && $product->sale_price < $product->price)
                <span class="discount-badge">
                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                </span>
            @endif
            @if($product->combo_pack_eligible === 'Yes')
                <span class="combo-badge" style="position: absolute; top: 10px; right: 10px; background-color: #2e7d32; color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; z-index: 10; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1;">
                    Combo Eligible
                </span>
            @endif
        </a>
    </div>

    <div class="product-info">
        <h3 class="product-title">
            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Product Rating -->
        @if(($product->total_reviews ?? 0) > 0)
        <div class="product-rating mb-2">
            <span class="stars" style="color: #ffc107; font-size: 13px;">
                @php $avg = $product->avg_rating ?? 0; @endphp
                @for($i = 1; $i <= 5; $i++)
                    @if($i <=floor($avg))
                    <i class="fas fa-star"></i>
                    @elseif($i == ceil($avg) && ($avg - floor($avg) >= 0.5))
                    <i class="fas fa-star-half-alt"></i>
                    @else
                    <i class="far fa-star"></i>
                    @endif
                    @endfor
            </span>
            <span class="rating-count text-muted small">({{ $product->total_reviews }})</span>
        </div>
        @else
        <div class="product-rating mb-2" style="height: 19px;"></div> <!-- Spacer -->
        @endif

        <div class="product-price">
            @if($product->stock_quantity > 0)
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                <span class="current-price ms-2 fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                @else
                <span class="current-price fw-bold">₹{{ number_format($product->price, 2) }}</span>
                @endif
                @else
                <div style="height: 24px;"></div> <!-- Spacer to keep layout consistent -->
                @endif
        </div>

@php
$hasAttributes = false;
if ($product->has_variants && !empty($product->size)) {
    if (is_array($product->size)) {
        $hasAttributes = count($product->size) > 0;
    } else {
        $decoded = json_decode($product->size, true);
        if (is_array($decoded)) {
            $hasAttributes = count($decoded) > 0;
        } else {
            $parts = array_filter(array_map('trim', explode(',', $product->size)));
            $hasAttributes = count($parts) > 0;
        }
    }
}
@endphp

        <div class="product-actions mt-3 d-flex align-items-stretch" style="gap: 4px !important;">
            @if($product->stock_quantity > 0)
                @if($hasAttributes)
                    @if(request()->routeIs('home'))
                        @if($product->combo_pack_eligible === 'Yes')
                        <div class="flex-grow-1 d-flex">
                            <a href="{{ route('combo-builder.index', ['add_product' => $product->id]) }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content; text-decoration: none;">
                                Add To Combo
                            </a>
                        </div>
                        @else
                        <div class="flex-grow-1 d-flex">
                            <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content; opacity: 0.4; cursor: not-allowed;">
                                Add To Combo
                            </button>
                        </div>
                        @endif
                        <div class="flex-grow-1 d-flex">
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content; text-decoration: none;">
                                View Options
                            </a>
                        </div>
                    @else
                        <div class="flex-grow-1 d-flex">
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content; text-decoration: none;">
                                View Options
                            </a>
                        </div>
                    @endif
                @else
                    @if(request()->routeIs('home'))
                        @if($product->combo_pack_eligible === 'Yes')
                        <div class="flex-grow-1 d-flex">
                            <a href="{{ route('combo-builder.index', ['add_product' => $product->id]) }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content; text-decoration: none;">
                                Add To Combo
                            </a>
                        </div>
                        @else
                        <div class="flex-grow-1 d-flex">
                            <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content; opacity: 0.4; cursor: not-allowed;">
                                Add To Combo
                            </button>
                        </div>
                        @endif
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content;">
                                Add To Cart
                            </button>
                        </form>
                    @else
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="buy_now" value="1">
                            <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content;">
                                Buy Now
                            </button>
                        </form>
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap" style="white-space: nowrap !important; padding: 6px 2px !important; font-size: 12px !important; min-width: max-content;">
                                Add To Cart
                            </button>
                        </form>
                    @endif
                @endif
            @else
            <div class="flex-grow-1 d-flex">
                <button class="btn btn-secondary w-100 h-100" disabled>Out of Stock</button>
            </div>
            @endif

            <div class="wishlist-btn-container d-flex">
                @if(isset($isWishlistPage) && $isWishlistPage)
                <button type="button" class="btn btn-wishlist btn-wishlist-action h-100" onclick="removeFromWishlist({{ $product->id }})" style="background-color: #fff5f5 !important; border-color: #dc3545 !important; color: #dc3545 !important;">
                    <i class="fas fa-heart text-danger" style="font-size: 18px !important;"></i>
                </button>
                @else
                <form action="{{ route('wishlist.add', $product) }}" method="POST" class="d-flex h-100">
                    @csrf
                    <button type="submit" class="btn btn-wishlist btn-wishlist-action h-100" style="background-color: #fff5f5 !important; border-color: #dc3545 !important; color: #dc3545 !important;">
                        <i class="far fa-heart" style="font-size: 18px !important;"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>