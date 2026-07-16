
<div class="products-grid row g-4">
    @forelse($comboPacks as $combo)
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 mb-4">
            <div class="product-card {{ $combo->stock_quantity <= 0 ? 'out-of-stock' : '' }}">
                <div class="product-image-container">
                    <a href="{{ route('combo_packs.frontend_show', $combo->slug) }}">
                        @php
                            $images = $combo->images;
                            $mainImage = count($images) > 0 ? asset('storage/' . $images[0]) : asset('assets/images/product/default.jpg');
                        @endphp
                        
                        <img src="{{ $mainImage }}" alt="{{ $combo->name }}" class="product-image main-image w-100 h-100" loading="lazy" decoding="async">
                        
                        @if($combo->stock_quantity <= 0)
                            <div class="out-of-stock-overlay">
                                <span class="out-of-stock-badge">Out Of Stock</span>
                            </div>
                        @endif

                        @if(count($images) > 1)
                            <span class="combo-badge creative-more-badge">
                                +{{ count($images) - 1 }} More
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('combo_packs.frontend_show', $combo->slug) }}" class="product-quick-view-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    @auth
                        <div class="wishlist-image-container">
                            <button type="button" class="btn-wishlist-circle btn-wishlist btn-wishlist-action wishlist-btn-combo" data-id="{{ $combo->id }}">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    @else
                        <div class="wishlist-image-container">
                            <a href="{{ route('login') }}" class="btn-wishlist-circle btn-wishlist btn-wishlist-action d-flex align-items-center justify-content-center">
                                <i class="far fa-heart"></i>
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="product-info">
                    <h3 class="product-title">
                        <a href="{{ route('combo_packs.frontend_show', $combo->slug) }}" class="text-decoration-none text-dark">
                            {{ $combo->name }}
                        </a>
                    </h3>

                    <div class="product-price">
                        @if($combo->stock_quantity > 0)
                            @if($combo->offer_price && $combo->offer_price < $combo->total_price)
                                <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($combo->total_price, 0) }}</span>
                                <span class="current-price ms-2 fw-bold">₹{{ number_format($combo->offer_price, 0) }}</span>
                                <span class="price-discount-tag">{{ round((($combo->total_price - $combo->offer_price) / $combo->total_price) * 100) }}% OFF</span>
                            @else
                                <span class="current-price fw-bold">₹{{ number_format($combo->total_price, 0) }}</span>
                            @endif
                        @else
                            <div class="spacer-24" style="height: 24px;"></div>
                        @endif
                    </div>

                    <div class="product-actions mt-3 d-flex align-items-stretch">
                        @if($combo->stock_quantity > 0)
                            @auth
                                <form action="{{ route('cart.add_combo', $combo->id) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                    @csrf
                                    <input type="hidden" name="buy_now" value="1">
                                    <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap">
                                        <i class="fas fa-credit-card"></i> <span class="ms-1">Buy</span>
                                    </button>
                                </form>
                                <form action="{{ route('cart.add_combo', $combo->id) }}" method="POST" class="flex-grow-1 d-flex cart-form-wrapper">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap">
                                        <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                                    </button>
                                </form>
                            @else
                                <div class="flex-grow-1 d-flex view-btn-wrapper">
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                        <i class="fas fa-credit-card"></i> <span class="ms-1">Buy</span>
                                    </a>
                                </div>
                                <div class="flex-grow-1 d-flex view-btn-wrapper">
                                    <a href="{{ route('login') }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                        <i class="fas fa-shopping-cart"></i> <span class="ms-1">Cart</span>
                                    </a>
                                </div>
                            @endauth
                        @else
                            <div class="flex-grow-1 d-flex">
                                <button type="button" class="btn btn-secondary w-100 h-100 text-nowrap d-flex align-items-center justify-content-center out-of-stock-btn" disabled>
                                    Out of Stock
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h3>No combo packs found matching your filters.</h3>
            <a href="{{ route('combo_packs.frontend_index') }}" class="btn btn-danger mt-3">Reset Filters</a>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-5">
    {{ $comboPacks->links() }}
</div>
