<div class="product-card h-100 d-flex flex-column">
    <div class="product-image-container position-relative">
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

    <div class="product-info mt-3 flex-grow-1 d-flex flex-column">
        <h3 class="product-title mb-2">
            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h3>

        <div class="product-price mb-3">
            @if($product->sale_price && $product->sale_price < $product->price)
                <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</span>
                <span class="current-price ms-2 fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
            @else
                <span class="current-price fw-bold">₹{{ number_format($product->price, 2) }}</span>
            @endif
        </div>

        <div class="product-actions mt-auto d-flex gap-2">
            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="buy_now" value="1">
                    <button type="submit" class="btn btn-buy-now btn-sm w-100">
                        Buy Now
                    </button>
                </form>
                <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-fill">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-add-cart btn-sm w-100">
                        Add To Cart
                    </button>
                </form>
            @else
                <button class="btn btn-secondary btn-sm w-100" disabled>Out of Stock</button>
            @endif

            @if(isset($isWishlistPage) && $isWishlistPage)
                <button type="button" class="btn btn-wishlist-action btn-sm" onclick="removeFromWishlist({{ $product->id }})">
                    <i class="fas fa-heart text-danger"></i>
                </button>
            @else
                <form action="{{ route('wishlist.add', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-wishlist-action btn-sm">
                        <i class="far fa-heart"></i>
                    </button>
                </form>
            @endif
        </div>

        <style>
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
        </style>
    </div>
</div>