<div class="product-card {{ $product->stock_quantity <= 0 ? 'out-of-stock' : '' }}">
    <div class="product-image-container">
        <a href="{{ route('product.show', $product->slug) }}">
            @php
            $mainImage = $product->image ? asset('storage/' . $product->image) : null;
            @endphp
            <img src="{{ $mainImage }}"
                 alt="{{ $product->name }}" class="product-image main-image w-100 h-100" loading="lazy" decoding="async">
            @if($product->stock_quantity <= 0)
                <div class="out-of-stock-overlay">
                    <span class="out-of-stock-badge">Out Of Stock</span>
                </div>
            @endif
            <div class="product-badges-row d-flex justify-content-between w-100 position-absolute" style="top: 8px; left: 0; padding: 0 8px; z-index: 105; pointer-events: none;">
                <div style="pointer-events: auto;">
                    @if($product->sale_price > 0 && $product->sale_price < $product->price)
                        <div class="product-discount-badge" style="position: static !important;">{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF</div>
                    @endif
                </div>
                <div style="pointer-events: auto;">
                    @if($product->combo_pack_eligible === 'Yes')
                        <div class="combo-badge-wrapper" style="position: static !important;" onclick="event.preventDefault(); event.stopPropagation();">
                            <span class="combo-badge">
                                <i class="fas fa-layer-group" style="margin-right: 3px;"></i> Combo
                            </span>
                            <div class="combo-tooltip">This product is combo eligible, you can add it to build your custom combo pack.</div>
                        </div>
                    @endif
                </div>
            </div>
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

        @if(isset($isWishlistPage) && $isWishlistPage)
        <div class="wishlist-image-container">
            <button type="button" class="btn-wishlist-circle btn-wishlist btn-wishlist-action" onclick="removeFromWishlist({{ $product->id }})">
                <i class="fas fa-heart text-danger"></i>
            </button>
        </div>
        @else
            @auth
            @php
                $inWishlist = false;
                if (auth()->check() && auth()->user()->wishlist) {
                    $inWishlist = auth()->user()->wishlist->contains('product_id', $product->id);
                }
            @endphp
            <div class="wishlist-image-container">
                <button type="button" class="btn-wishlist-circle btn-wishlist btn-wishlist-action ajax-wishlist-btn"
                        data-add-url="{{ route('wishlist.add', $product) }}"
                        data-remove-url="{{ route('wishlist.remove', $product) }}"
                        data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}">
                    <i class="{{ $inWishlist ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
                </button>
            </div>
            @else
            <div class="wishlist-image-container">
                <a href="{{ route('login') }}" class="btn-wishlist-circle btn-wishlist btn-wishlist-action d-flex align-items-center justify-content-center">
                    <i class="far fa-heart"></i>
                </a>
            </div>
            @endauth
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
                @if($product->sale_price > 0 && $product->sale_price < $product->price)
                    <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($product->price, 0) }}</span>
                    <span class="current-price ms-2 fw-bold">₹{{ number_format($product->sale_price, 0) }}</span>
                @else
                    <span class="current-price fw-bold">₹{{ number_format($product->price, 0) }}</span>
                @endif
            @else
                <div class="spacer-24"></div> <!-- Spacer to keep layout consistent -->
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
                @auth
                    @if($hasAttributes)
                        @if(request()->routeIs('home'))
                            @if($product->combo_pack_eligible === 'Yes')
                            <div class="flex-grow-1 d-flex combo-btn-wrapper">
                                <a href="{{ route('combo-builder.index', ['add_product' => $product->id]) }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus-circle"></i> <span class="ms-1">Combo</span>
                                </a>
                            </div>
                            @else
                            <div class="flex-grow-1 d-flex combo-btn-wrapper">
                                <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled>
                                    <i class="fas fa-plus-circle"></i> <span class="ms-1">Combo</span>
                                </button>
                            </div>
                            @endif
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                    <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                                </button>
                            </form>
                        @else
                            <div class="flex-grow-1 d-flex view-btn-wrapper">
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <i class="fas fa-credit-card"></i> <span class="ms-1">Buy</span>
                                </a>
                            </div>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap">
                                    <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                                </button>
                            </form>
                        @endif
                    @else
                        @if(request()->routeIs('home'))
                            @if($product->combo_pack_eligible === 'Yes')
                            <div class="flex-grow-1 d-flex combo-btn-wrapper">
                                <a href="{{ route('combo-builder.index', ['add_product' => $product->id]) }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus-circle"></i> <span class="ms-1">Combo</span>
                                </a>
                            </div>
                            @else
                            <div class="flex-grow-1 d-flex combo-btn-wrapper">
                                <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled>
                                    <i class="fas fa-plus-circle"></i> <span class="ms-1">Combo</span>
                                </button>
                            </div>
                            @endif
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                    <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="buy_now" value="1">
                                <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                    <i class="fas fa-credit-card"></i> <span class="ms-1">Buy</span>
                                </button>
                            </form>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap">
                                    <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    {{-- Guest: show login-redirect buttons --}}
                    @if(request()->routeIs('home'))
                        @if($product->combo_pack_eligible === 'Yes')
                        <div class="flex-grow-1 d-flex combo-btn-wrapper">
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <i class="fas fa-plus-circle"></i> <span class="ms-1">Combo</span>
                            </a>
                        </div>
                        @else
                        <div class="flex-grow-1 d-flex combo-btn-wrapper">
                            <button type="button" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" disabled>
                                <i class="fas fa-plus-circle"></i> <span class="ms-1">Combo</span>
                            </button>
                        </div>
                        @endif
                        <div class="flex-grow-1 d-flex cart-form-wrapper">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                            </a>
                        </div>
                    @else
                        <div class="flex-grow-1 d-flex view-btn-wrapper">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <i class="fas fa-credit-card"></i> <span class="ms-1">Buy</span>
                            </a>
                        </div>
                        <div class="flex-grow-1 d-flex cart-form-wrapper">
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                            </a>
                        </div>
                    @endif
                @endauth
            @else
            <div class="flex-grow-1 d-flex combo-btn-wrapper">
                <button class="btn btn-secondary btn-add-cart w-100 h-100" disabled>Out of Stock</button>
            </div>
            @endif

        </div>

    </div>
</div>