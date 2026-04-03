@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('home') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder">Combo Packs</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="py-4">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu for Filters -->
            <div class="col-lg-3 col-md-4 mb-4">
                <form id="filter-form" action="{{ url()->current() }}" method="GET" class="side-menu bg-white rounded shadow-sm p-3 sticky-top" style="top: 20px; z-index: 1000; border: 1px solid #ddd;">
                    <h2 class="side-menu-title mb-3">Filters</h2>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Price Range
                            <span class="price-range-value" id="display-price-range">₹0 - ₹{{ request('price_max', 10000) }}</span>
                        </h3>
                        <div class="filter-options d-flex align-items-center">
                            <input type="range" name="price_max" class="form-range flex-grow-1 me-2" min="0" max="10000" step="100" id="price-max" value="{{ request('price_max', 10000) }}" style="width: 100%;" onchange="this.form.submit()">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Categories</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="category" id="cat-all" class="filter-radio" value="" onchange="this.form.submit()" {{ !request('category') ? 'checked' : '' }}>
                                <label for="cat-all" class="filter-label">All Categories</label>
                            </div>
                            @foreach($categories as $cat)
                                <div class="filter-item">
                                    <input type="radio" name="category" id="cat-{{ $cat->id }}" class="filter-radio" value="{{ $cat->id }}" onchange="this.form.submit()" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                    <label for="cat-{{ $cat->id }}" class="filter-label">{{ $cat->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Discount Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Discount</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-all" class="filter-radio" value="all" onchange="this.form.submit()" {{ request('discount', 'all') == 'all' ? 'checked' : '' }}>
                                <label for="discount-all" class="filter-label">All Discounts</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-50" class="filter-radio" value="50" onchange="this.form.submit()" {{ request('discount') == '50' ? 'checked' : '' }}>
                                <label for="discount-50" class="filter-label">50% and above</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-30-50" class="filter-radio" value="30-50" onchange="this.form.submit()" {{ request('discount') == '30-50' ? 'checked' : '' }}>
                                <label for="discount-30" class="filter-label">30% - 50%</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-10-30" class="filter-radio" value="10-30" onchange="this.form.submit()" {{ request('discount') == '10-30' ? 'checked' : '' }}>
                                <label for="discount-10" class="filter-label">10% - 30%</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Availability -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Availability</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="in-stock" class="filter-checkbox" value="in-stock" onchange="this.form.submit()" {{ is_array(request('availability')) && in_array('in-stock', request('availability')) ? 'checked' : '' }}>
                                <label for="in-stock" class="filter-label">In Stock</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="out-of-stock" class="filter-checkbox" value="out-of-stock" onchange="this.form.submit()" {{ is_array(request('availability')) && in_array('out-of-stock', request('availability')) ? 'checked' : '' }}>
                                <label for="out-of-stock" class="filter-label">Out of Stock</label>
                            </div>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Sort By</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-new" class="filter-radio" value="newest" onchange="this.form.submit()" {{ request('sort', 'newest') == 'newest' ? 'checked' : '' }}>
                                <label for="sort-new" class="filter-label">Newest First</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-price-low" class="filter-radio" value="price-low" onchange="this.form.submit()" {{ request('sort') == 'price-low' ? 'checked' : '' }}>
                                <label for="sort-price-low" class="filter-label">Price: Low to High</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-price-high" class="filter-radio" value="price-high" onchange="this.form.submit()" {{ request('sort') == 'price-high' ? 'checked' : '' }}>
                                <label for="sort-price-high" class="filter-label">Price: High to Low</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-discount" class="filter-radio" value="discount" onchange="this.form.submit()" {{ request('sort') == 'discount' ? 'checked' : '' }}>
                                <label for="sort-discount" class="filter-label">Discount</label>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="filter-actions d-flex gap-2 mt-3">
                        <button class="btn btn-primary flex-fill" style="background-color: #72a420; border-color: #72a420;" type="submit">Apply Filters</button>
                        <a href="{{ route('combo_packs.frontend_index') }}" class="btn btn-outline-secondary flex-fill text-center text-decoration-none d-flex align-items-center justify-content-center">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Products Display Area -->
            <div class="col-lg-9 col-md-8">
                <div class="products-area">
                    <div class="products-header bg-white rounded p-3 mb-4 d-flex justify-content-between align-items-center border">
                        <h2 class="category-name mb-0">Combo Packs Listing</h2>
                    </div>

                    <div class="products-grid row g-3">
                        @forelse($comboPacks as $combo)
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
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
                                                    <img src="{{ asset('storage/' . $images[1]) }}" alt="{{ $combo->name }} 2">
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
                                                <span class="old-price">₹{{ number_format($combo->total_price, 2) }}</span>
                                                <span class="new-price">₹{{ number_format($combo->offer_price, 2) }}</span>
                                            @else
                                                <div style="height: 30px;"></div>
                                            @endif
                                        </div>
                                        <div class="card-actions-row">
                                            @if($combo->stock_quantity > 0)
                                                <form action="{{ route('cart.add_combo', $combo->id) }}" method="POST" class="flex-grow-1">
                                                    @csrf
                                                    <input type="hidden" name="buy_now" value="1">
                                                    <button type="submit" class="btn-buy-now">Buy Now</button>
                                                </form>
                                                <form action="{{ route('cart.add_combo', $combo->id) }}" method="POST" class="flex-grow-1">
                                                    @csrf
                                                    <button type="submit" class="btn-add-to-cart">Add To Cart</button>
                                                </form>
                                            @else
                                                <button type="button" class="btn-out-of-stock flex-grow-1" disabled>Out of Stock</button>
                                            @endif
                                            <button type="button" class="btn-wishlist-custom wishlist-btn-combo" data-id="{{ $combo->id }}">
                                                <i class="fa-regular fa-heart"></i>
                                            </button>
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
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price Range Display Sync
    const priceInput = document.getElementById('price-max');
    const displayPriceRange = document.getElementById('display-price-range');
    if(priceInput) {
        priceInput.addEventListener('input', function() {
            displayPriceRange.textContent = `₹0 - ₹${priceInput.value}`;
        });
    }

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
                    btn.find('i').removeClass('fa-regular').addClass('fa-solid').css('color', '#e53e3e');
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
/* Filter Sidebar Styles */
.side-menu {
    border: 1px solid #eee;
}
.filter-section .filter-title {
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}
.filter-item {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}
.filter-label {
    margin-left: 8px;
    margin-bottom: 0;
    cursor: pointer;
    color: #555;
    font-size: 14px;
}

/* Custom Card Styling based on Screenshot */
.product-custom-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
    transition: transform 0.3s ease;
}

.product-custom-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card-img-container {
    position: relative;
    padding: 10px;
    height: 320px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fdfdfd;
}

.card-img-container a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.single-combo-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.dual-image-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    width: 100%;
    height: 100%;
}

.dual-image-wrapper img {
    max-width: 42%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 8px;
}

.image-plus-sign {
    font-size: 28px;
    font-weight: 700;
    color: #72a420;
    margin-top: -5px;
}

.custom-discount-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    background: #fbb034;
    color: #fff;
    padding: 5px 12px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 13px;
    text-transform: uppercase;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 10;
}

.card-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
    color: #333;
}

.card-title a {
    color: inherit;
    text-decoration: none;
}

.card-price-row {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.old-price {
    color: #999;
    text-decoration: line-through;
    font-size: 16px;
}

.new-price {
    color: #72a420;
    font-weight: 700;
    font-size: 20px;
}

.card-actions-row {
    display: flex;
    align-items: stretch;
    gap: 4px !important;
    margin-top: auto;
}

.btn-buy-now {
    background: #72a420;
    color: #fff;
    border: none;
    padding: 6px 2px !important;
    border-radius: 8px;
    font-weight: 600;
    width: 100%;
    font-size: 12px !important;
    transition: background 0.2s;
    white-space: nowrap !important;
    min-width: max-content !important;
}

.btn-buy-now:hover {
    background: #5d871a;
}

.btn-add-to-cart {
    background: #ebf1f5;
    color: #333;
    border: none;
    padding: 6px 2px !important;
    border-radius: 8px;
    font-weight: 600;
    width: 100%;
    font-size: 12px !important;
    transition: background 0.2s;
    white-space: nowrap !important;
    min-width: max-content !important;
}

.btn-add-to-cart:hover {
    background: #dee5e9;
}

.btn-out-of-stock {
    background: #f8f9fa;
    color: #adb5bd;
    border: 1px solid #dee2e6;
    padding: 8px 10px;
    border-radius: 8px;
    font-weight: 600;
    width: 100%;
    font-size: 13px;
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-wishlist-custom {
    background: #fff;
    border: 1px solid #eee;
    color: #999;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    flex-shrink: 0;
    transition: all 0.2s;
    cursor: pointer;
}

.btn-wishlist-custom:hover {
    background: #f9f9f9;
    color: #e53e3e;
    border-color: #f7d7d7;
}

.btn-wishlist-custom i {
    font-size: 16px;
}
</style>

@include('view.layout.footer')
