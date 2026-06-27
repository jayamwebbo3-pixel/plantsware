<div class="products-grid row g-4">
    @forelse($comboPacks as $combo)
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 mb-4">
            <div class="product-custom-card">
                <div class="card-img-container">
                    <a href="{{ route('combo_packs.frontend_show', $combo->slug) }}" class="w-100 h-100">
                        @php
                            $images = $combo->images;
                        @endphp

                        <div class="dual-image-wrapper">
                            @if(count($images) >= 2)
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $combo->name }} 1">
                                <span class="image-plus-sign">+</span>
                                <div class="image-wrap-more">
                                    <img src="{{ asset('storage/' . $images[1]) }}" alt="{{ $combo->name }} 2">
                                    @if(count($images) > 2)
                                        <span class="combo-more-badge">+{{ count($images) - 2 }} More</span>
                                    @endif
                                </div>
                            @elseif(count($images) == 1)
                                <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $combo->name }}" class="single-combo-img">
                            @else
                                <img src="{{ asset('assets/images/product/default.jpg') }}" alt="{{ $combo->name }}" class="single-combo-img">
                            @endif
                        </div>
                    </a>
                    @php
                        $discount = 0;
                        if ($combo->total_price > 0) {
                            $discount = round((($combo->total_price - $combo->offer_price) / $combo->total_price) * 100);
                        }
                    @endphp
                    @if($combo->stock_quantity <= 0)
                        <div class="custom-discount-badge" style="background: #dc3545 !important;">OUT OF STOCK</div>
                    @elseif($discount > 0)
                        <div class="custom-discount-badge">{{ $discount }}% OFF</div>
                    @endif
                </div>
                <div class="card-content">
                    <h3 class="card-title">
                        <a href="{{ route('combo_packs.frontend_show', $combo->slug) }}">{{ $combo->name }}</a>
                    </h3>
                    <div class="card-price-row">
                        @if($combo->stock_quantity > 0)
                            <span class="old-price">₹{{ number_format($combo->total_price, 0) }}</span>
                            <span class="new-price">₹{{ number_format($combo->offer_price, 0) }}</span>
                        @else
                            <div style="height: 30px;"></div>
                        @endif
                    </div>
                    <div class="card-actions-row d-flex gap-2 align-items-stretch">
                        @if($combo->stock_quantity > 0)
                            @auth
                            <form action="{{ route('cart.add_combo', $combo->id) }}" method="POST" class="flex-grow-1 d-flex">
                                @csrf
                                <input type="hidden" name="buy_now" value="1">
                                <button type="submit" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <span class="d-none d-xl-inline">Buy Now</span>
                                    <span class="d-inline d-xl-none">Buy</span>
                                </button>
                            </form>
                            <form action="{{ route('cart.add_combo', $combo->id) }}" method="POST" class="flex-grow-1 d-flex">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <span class="d-none d-xl-inline">Add To Cart</span>
                                    <span class="d-inline d-xl-none">Cart</span>
                                </button>
                            </form>
                            @else
                            <div class="flex-grow-1 d-flex">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-buy-now w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <span class="d-none d-xl-inline">Buy Now</span>
                                    <span class="d-inline d-xl-none">Buy</span>
                                </a>
                            </div>
                            <div class="flex-grow-1 d-flex">
                                <a href="{{ route('login') }}" class="btn btn-secondary btn-add-cart w-100 h-100 text-nowrap d-flex align-items-center justify-content-center">
                                    <span class="d-none d-xl-inline">Add To Cart</span>
                                    <span class="d-inline d-xl-none">Cart</span>
                                </a>
                            </div>
                            @endauth
                        @else
                            <div class="flex-grow-1 d-flex">
                                <button type="button" class="btn btn-secondary w-100 h-100 text-nowrap d-flex align-items-center justify-content-center" style="background-color: #f1f5f9 !important; color: #94a3b8 !important; border: 1px solid #e2e8f0 !important; cursor: not-allowed !important; font-size: 0.85rem !important; font-weight: 600 !important; border-radius: 8px !important;" disabled>
                                    Out of Stock
                                </button>
                            </div>
                        @endif
                        @auth
                        <button type="button" class="btn-wishlist-custom wishlist-btn-combo" data-id="{{ $combo->id }}">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="btn-wishlist-custom d-flex align-items-center justify-content-center">
                            <i class="fa-regular fa-heart"></i>
                        </a>
                        @endauth
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
