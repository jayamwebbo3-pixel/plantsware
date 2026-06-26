<div class="product-card {{ $product->stock_quantity <= 0 ? 'out-of-stock' : '' }}">
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
                <div class="out-of-stock-overlay">
                    <span class="out-of-stock-badge">Out Of Stock</span>
                </div>
            @endif
            @if($product->combo_pack_eligible === 'Yes')
                <span class="combo-badge" style="position: absolute; top: 10px; right: 10px; background-color: #2e7d32; color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; z-index: 10; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1;">
                    Combo Eligible
                </span>
            @endif
        </a>
        <a href="{{ route('product.show', $product->slug) }}" class="product-quick-view-btn" title="View Details">
            <i class="fas fa-eye"></i>
        </a>
        @if(($product->total_reviews ?? 0) > 0)
            <div class="product-rating-badge">
                <span>{{ number_format($product->avg_rating ?? 0, 1) }}</span>
                <i class="fas fa-star"></i>
            </div>
        @endif
    </div>

    <div class="product-info">
        <h3 class="product-title">
            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h3>



        <div class="product-price">
            @if($product->stock_quantity > 0)
                                @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($product->price, 0) }}</span>
                    <span class="current-price ms-2 fw-bold">₹{{ number_format($product->sale_price, 0) }}</span>
                    <span class="price-discount-tag">{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF</span>
                @else
                    <span class="current-price fw-bold">₹{{ number_format($product->price, 0) }}</span>
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

        <div class="product-actions mt-3 d-flex align-items-stretch">
            @if($product->stock_quantity > 0)
                @if($hasAttributes)
                    @if(request()->routeIs('home'))
                        @if($product->combo_pack_eligible === 'Yes')
                        <div class="flex-grow-1 d-flex combo-btn-wrapper">
                            <a href="{{ route('combo-builder.index', ['add_product' => $product->id]) }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <span class="d-none d-xl-inline">Add to Combo</span>
                                <span class="d-inline d-xl-none">Combo</span>
                            </a>
                        </div>
                        @else
                        <div class="flex-grow-1 d-flex combo-btn-wrapper">
                            <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled>
                                <span class="d-none d-xl-inline">Add to Combo</span>
                                <span class="d-inline d-xl-none">Combo</span>
                            </button>
                        </div>
                        @endif
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                <span class="d-none d-xl-inline">Add to Cart</span>
                                <span class="d-inline d-xl-none">Cart</span>
                            </button>
                        </form>
                    @else
                        <div class="flex-grow-1 d-flex view-btn-wrapper">
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <span class="d-none d-xl-inline">Buy Now</span>
                                <span class="d-inline d-xl-none">Buy</span>
                            </a>
                        </div>
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap">
                                <span class="d-none d-xl-inline">Add to Cart</span>
                                <span class="d-inline d-xl-none">Cart</span>
                            </button>
                        </form>
                    @endif
                @else
                    @if(request()->routeIs('home'))
                        @if($product->combo_pack_eligible === 'Yes')
                        <div class="flex-grow-1 d-flex combo-btn-wrapper">
                            <a href="{{ route('combo-builder.index', ['add_product' => $product->id]) }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <span class="d-none d-xl-inline">Add to Combo</span>
                                <span class="d-inline d-xl-none">Combo</span>
                            </a>
                        </div>
                        @else
                        <div class="flex-grow-1 d-flex combo-btn-wrapper">
                            <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled>
                                <span class="d-none d-xl-inline">Add to Combo</span>
                                <span class="d-inline d-xl-none">Combo</span>
                            </button>
                        </div>
                        @endif
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                <span class="d-none d-xl-inline">Add to Cart</span>
                                <span class="d-inline d-xl-none">Cart</span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="buy_now" value="1">
                            <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                <span class="d-none d-xl-inline">Buy Now</span>
                                <span class="d-inline d-xl-none">Buy</span>
                            </button>
                        </form>
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap">
                                <span class="d-none d-xl-inline">Add to Cart</span>
                                <span class="d-inline d-xl-none">Cart</span>
                            </button>
                        </form>
                    @endif
                @endif
            @else
            <div class="flex-grow-1 d-flex combo-btn-wrapper">
                <button class="btn btn-secondary w-100 h-100" disabled>Out of Stock</button>
            </div>
            @endif

            @if(isset($isWishlistPage) && $isWishlistPage)
            <div class="wishlist-btn-container d-flex h-100">
                <button type="button" class="btn btn-wishlist btn-wishlist-action w-100 h-100" onclick="removeFromWishlist({{ $product->id }})">
                    <i class="fas fa-heart text-danger"></i>
                </button>
            </div>
            @else
            <form action="{{ route('wishlist.add', $product) }}" method="POST" class="wishlist-btn-container d-flex h-100">
                @csrf
                <button type="submit" class="btn btn-wishlist btn-wishlist-action w-100 h-100">
                    <i class="far fa-heart"></i>
                </button>
            </form>
            @endif
        </div>

    </div>
</div>