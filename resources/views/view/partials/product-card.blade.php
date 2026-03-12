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

        <div class="product-price">
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                <span class="current-price ms-2 fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                @else
                <span class="current-price fw-bold">₹{{ number_format($product->price, 2) }}</span>
                @endif
        </div>

        <div class="product-actions">
            @if($product->stock_quantity > 0)
            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex w-100">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="buy_now" value="1">
                <button type="submit" class="btn btn-primary">
                    Buy Now
                </button>
            </form>
            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex w-100">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-secondary">
                    Add To Cart
                </button>
            </form>
            @else
            <button class="btn btn-secondary" disabled>Out of Stock</button>
            @endif

            @if(isset($isWishlistPage) && $isWishlistPage)
            <button type="button" class="btn btn-wishlist" onclick="removeFromWishlist({{ $product->id }})">
                <i class="fas fa-heart text-danger"></i>
            </button>
            @else
            <form action="{{ route('wishlist.add', $product) }}" method="POST" class="d-flex w-100">
                @csrf
                <button type="submit" class="btn btn-wishlist">
                    <i class="far fa-heart"></i>
                </button>
            </form>
            @endif
        </div>

        <!-- <style>
            .btn-buy-now {
                background-color: #72a420 !important;
                color: white !important;
                border-radius: 8px !important;
                border: none !important;
                font-weight: 600 !important;
                padding: 10px 16px !important;
                font-size: 14px !important;
                white-space: nowrap;
            }
            .btn-add-cart {
                background-color: #e9ecef !important;
                color: #212529 !important;
                border-radius: 8px !important;
                border: none !important;
                font-weight: 600 !important;
                padding: 10px 16px !important;
                font-size: 14px !important;
                white-space: nowrap;
            }
            .btn-wishlist-action {
                background-color: white !important;
                color: #6c757d !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 8px !important;
                padding: 10px 16px !important;
                font-size: 14px !important;
            }
            .btn-wishlist-action:hover {
                color: #dc3545 !important;
                border-color: #dc3545 !important;
            }
            .product-actions {
                display: flex;
                align-items: center;
            }
            .current-price {
                color: #72a420 !important;
            }
        </style> -->
    </div>
</div>