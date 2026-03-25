<div class="product-card">
    <div class="product-image-container">
        <a href="{{ route('product.show', $product->slug) }}">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}"
                alt="{{ $product->name }}" class="product-image main-image w-100 h-100 object-fit-contain">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/product1.jpg') }}"
                alt="{{ $product->name }}" class="product-image hover-image w-100 h-100 object-fit-contain">
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="discount-badge">
                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
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
                    @if($i <= floor($avg))
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
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                <span class="current-price ms-2 fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                @else
                <span class="current-price fw-bold">₹{{ number_format($product->price, 2) }}</span>
                @endif
        </div>

        <div class="product-actions mt-3 d-flex align-items-stretch" style="gap: 4px !important;">
            @if($product->stock_quantity > 0)
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
            @else
            <div class="flex-grow-1 d-flex">
                <button class="btn btn-secondary w-100 h-100" disabled>Out of Stock</button>
            </div>
            @endif

            <div class="wishlist-btn-container d-flex">
                @if(isset($isWishlistPage) && $isWishlistPage)
                <button type="button" class="btn btn-wishlist btn-wishlist-action h-100" onclick="removeFromWishlist({{ $product->id }})">
                    <i class="fas fa-heart text-danger"></i>
                </button>
                @else
                <form action="{{ route('wishlist.add', $product) }}" method="POST" class="d-flex h-100">
                    @csrf
                    <button type="submit" class="btn btn-wishlist btn-wishlist-action h-100">
                        <i class="far fa-heart"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>