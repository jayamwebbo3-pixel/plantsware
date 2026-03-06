@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('combo_packs.frontend_index') }}" class="text-decoration-none">Combo Packs</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder">{{ $comboPack->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="combo-detail py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden" style="min-height: 400px; display: flex; align-items: center; justify-content: center; background: #fdfdfd;">
                    @php
                        $images = is_string($comboPack->image) ? json_decode($comboPack->image, true) : $comboPack->image;
                    @endphp

                    @if(!is_array($images))
                        <img src="{{ $comboPack->image ? asset('storage/' . $comboPack->image) : asset('assets/images/product/default.jpg') }}" 
                             class="img-fluid p-4" style="object-fit: cover; max-height: 400px;"
                             alt="{{ $comboPack->name }}">
                    @else
                        <div class="dual-image-wrapper p-4 d-flex align-items-center justify-content-center w-100 h-100">
                            @if(count($images) >= 2)
                                {{-- First product image (links to product page) --}}
                                @if(!empty($productLinks[0]))
                                    <a href="{{ $productLinks[0] }}" title="View Product" class="text-decoration-none position-relative combo-img-link">
                                        <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $comboPack->name }} 1" class="img-fluid rounded shadow-sm" style="width: 100%; max-width: 200px; max-height: 350px; object-fit: contain;">
                                        <span class="combo-img-hint">View Product &rarr;</span>
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $comboPack->name }} 1" class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 350px; object-fit: contain;">
                                @endif

                                <span class="image-plus-sign mx-3 fs-1 fw-bold" style="color: #72a420;">+</span>

                                {{-- Second product image (links to product page) --}}
                                @if(!empty($productLinks[1]))
                                    <a href="{{ $productLinks[1] }}" title="View Product" class="text-decoration-none position-relative combo-img-link">
                                        <img src="{{ asset('storage/' . $images[1]) }}" alt="{{ $comboPack->name }} 2" class="img-fluid rounded shadow-sm" style="width: 100%; max-width: 200px; max-height: 350px; object-fit: contain;">
                                        <span class="combo-img-hint">View Product &rarr;</span>
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . $images[1]) }}" alt="{{ $comboPack->name }} 2" class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 350px; object-fit: contain;">
                                @endif
                            @elseif(count($images) == 1)
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $comboPack->name }}" class="img-fluid rounded" style="max-height: 400px; object-fit: contain;">
                            @else
                                <img src="{{ asset('assets/images/product/default.jpg') }}" alt="{{ $comboPack->name }}" class="img-fluid p-4">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-page-info">
                    <h1 class="product-page-title mb-3">{{ $comboPack->name }}</h1>
                    
                    <div class="product-page-price mb-4">
                        <span class="product-page-current-price">₹{{ number_format($comboPack->offer_price, 2) }}</span>
                        @if($comboPack->total_price > $comboPack->offer_price)
                            <span class="product-page-original-price">₹{{ number_format($comboPack->total_price, 2) }}</span>
                            <span class="product-page-discount-badge">
                                Save ₹{{ number_format($comboPack->total_price - $comboPack->offer_price, 2) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="product-page-option-label">Description:</h5>
                        <div class="product-page-description">
                            {!! $comboPack->description !!}
                        </div>
                    </div>

                <!-- Quantity Selector -->
                <div class="product-page-quantity-selector d-flex align-items-center mb-4">
                    <label class="product-page-qty-label me-3 fw-bold" for="quantityInput">Quantity:</label>
                    <div class="product-page-qty-control d-flex align-items-center border rounded">
                        <button type="button" class="product-page-qty-btn border-0 bg-transparent px-3" onclick="updateQty(-1)">−</button>
                        <input type="number" id="quantityInput" name="quantity" class="product-page-qty-input border-0 text-center" value="1" min="1" readonly>
                        <button type="button" class="product-page-qty-btn border-0 bg-transparent px-3" onclick="updateQty(1)">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="product-page-action-buttons d-flex gap-3 mb-4">
                    @if($comboPack->stock_quantity > 0)
                        <form action="{{ route('cart.add_combo', $comboPack->id) }}" method="POST" class="d-inline" id="buyNowForm">
                            @csrf
                            <input type="hidden" name="quantity" id="buyNowQuantity" value="1">
                            <input type="hidden" name="buy_now" value="1">
                            <button type="submit" class="product-page-btn-add-cart btn btn-lg btn-success d-flex align-items-center gap-2">
                                <i class="fas fa-bolt"></i>
                                Buy Now
                            </button>
                        </form>
                        <form action="{{ route('cart.add_combo', $comboPack->id) }}" method="POST" class="d-inline" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="quantity" id="cartQuantity" value="1">
                            <button type="submit" class="product-page-btn-add-cart btn btn-lg btn-primary d-flex align-items-center gap-2">
                                <i class="fas fa-shopping-bag"></i>
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <button class="product-page-btn-add-cart btn btn-lg btn-secondary d-flex align-items-center gap-2" style="cursor: not-allowed; opacity: 0.5; background-color: #6c757d; border-color: #6c757d;" disabled>
                            Out of Stock
                        </button>
                    @endif
                    <!-- Wishlist Form -->
                    <button type="button" 
                            class="product-page-btn-wishlist btn btn-lg btn-outline-danger d-flex align-items-center gap-2 wishlist-btn-combo" 
                            data-id="{{ $comboPack->id }}">
                        <i class="far fa-heart"></i>
                        Add to Wishlist
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<section class="related-products py-5 bg-light">
    <div class="container">
        <div class="section-title mb-4 row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-dark">Related Products</h2>
                <p class="text-muted">You might also like these individual plants</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('products.index') }}" class="btn btn-outline-success">View All Products &rarr;</a>
            </div>
        </div>
        
        <div class="row g-4">
            @php
                $relatedProducts = \App\Models\Product::where('is_active', 1)->inRandomOrder()->limit(4)->get();
            @endphp
            @foreach($relatedProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="product-custom-card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; overflow: hidden; transition: 0.3s;">
                        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                            <div class="position-relative" style="height: 250px; background: #f9f9f9; display: flex; align-items: center; justify-content: center; padding: 15px;">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/default.jpg') }}" 
                                     alt="{{ $product->name }}" 
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                            <div class="p-3">
                                <h5 class="text-dark fw-bold mb-2 text-truncate">{{ $product->name }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-success fw-bold fs-5">₹{{ number_format($product->sale_price ?? $product->price, 2) }}</span>
                                    @if($product->sale_price < $product->price)
                                        <del class="text-muted small">₹{{ number_format($product->price, 2) }}</del>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Quantity Update
    window.updateQty = function(change) {
        var input = document.getElementById('quantityInput');
        var value = parseInt(input.value) || 1;
        value = value + change;
        if (value < 1) value = 1;
        
        // Check max stock if available
        var maxStock = {{ $comboPack->stock_quantity }};
        if (value > maxStock) {
            value = maxStock;
            alert("Only " + maxStock + " items available in stock.");
        }
        
        input.value = value;
        
        // Update hidden inputs for forms
        const cartQtyInput = document.getElementById('cartQuantity');
        if(cartQtyInput) cartQtyInput.value = value;
        
        const buyNowQtyInput = document.getElementById('buyNowQuantity');
        if(buyNowQtyInput) buyNowQtyInput.value = value;
    };

    // AJAX Wishlist
    $('.wishlist-btn-combo').on('click', function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        $.ajax({
            url: "{{ url('/wishlist/add-combo') }}/" + id,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('.price_cart').first().text(response.wishlist_count);
                    // Update UI to show it's added
                    btn.find('i').removeClass('far').addClass('fas text-danger');
                    // If using a themed outline button, maybe just change text color or background
                    btn.css('background-color', '#fff5f5');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                } else {
                    alert(xhr.responseJSON.message || "Something went wrong");
                }
            }
        });
    });
});
</script>

<style>
    .combo-img-link {
        display: inline-block;
        transition: transform 0.2s;
    }
    .combo-img-link:hover {
        transform: scale(1.03);
    }
    .combo-img-hint {
        display: block;
        text-align: center;
        font-size: 0.78rem;
        color: #72a420;
        font-weight: 600;
        margin-top: 6px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .combo-img-link:hover .combo-img-hint {
        opacity: 1;
    }
</style>

@include('view.layout.footer')
