@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('home') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">Categories</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="py-4">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu for Categories with Filters -->
            <div class="col-lg-3 col-md-4 mb-4">
                <form id="filter-form" action="{{ url()->current() }}" method="GET" class="side-menu bg-white rounded shadow-sm p-3 sticky-top" style="top: 20px; z-index: 1; border: 1px solid #ddd;">
                    <h2 class="side-menu-title mb-3">Filters</h2>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Price Range
                            <span class="price-range-value" id="display-price-range">₹0 - ₹{{ request('price_max', 5000) }}</span>
                        </h3>
                        <div class="filter-options d-flex align-items-center">
                            <input type="range" name="price_max" class="form-range flex-grow-1 me-2" min="0" max="10000" step="100" id="price-max" value="{{ request('price_max', 5000) }}" style="width: 100%;">
                        </div>
                    </div>
                    
                    <!-- Discount Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Discount</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-all" class="filter-radio" value="all" {{ request('discount', 'all') == 'all' ? 'checked' : '' }}>
                                <label for="discount-all" class="filter-label">
                                    All Discounts
                                    <span class="filter-count">(45)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-50" class="filter-radio" value="50" {{ request('discount') == '50' ? 'checked' : '' }}>
                                <label for="discount-50" class="filter-label">
                                    50% and above
                                    <span class="filter-count">(8)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-30" class="filter-radio" value="30-50" {{ request('discount') == '30-50' ? 'checked' : '' }}>
                                <label for="discount-30" class="filter-label">
                                    30% - 50%
                                    <span class="filter-count">(12)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-10" class="filter-radio" value="10-30" {{ request('discount') == '10-30' ? 'checked' : '' }}>
                                <label for="discount-10" class="filter-label">
                                    10% - 30%
                                    <span class="filter-count">(18)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-below10" class="filter-radio" value="below10" {{ request('discount') == 'below10' ? 'checked' : '' }}>
                                <label for="discount-below10" class="filter-label">
                                    Below 10%
                                    <span class="filter-count">(7)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Weight Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Weight</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" id="weight-100g" class="filter-checkbox" value="100g">
                                <label for="weight-100g" class="filter-label">
                                    Up to 100g
                                    <span class="filter-count">(6)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="weight-500g" class="filter-checkbox" value="500g">
                                <label for="weight-500g" class="filter-label">
                                    100g - 500g
                                    <span class="filter-count">(12)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="weight-1kg" class="filter-checkbox" value="1kg">
                                <label for="weight-1kg" class="filter-label">
                                    500g - 1kg
                                    <span class="filter-count">(15)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="weight-5kg" class="filter-checkbox" value="5kg">
                                <label for="weight-5kg" class="filter-label">
                                    1kg - 5kg
                                    <span class="filter-count">(8)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="weight-above5kg" class="filter-checkbox" value="above5kg">
                                <label for="weight-above5kg" class="filter-label">
                                    Above 5kg
                                    <span class="filter-count">(4)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Brand</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" id="brand-organic" class="filter-checkbox" value="Organic Harvest">
                                <label for="brand-organic" class="filter-label">
                                    Organic Harvest
                                    <span class="filter-count">(10)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="brand-garden" class="filter-checkbox" value="Garden Pro">
                                <label for="brand-garden" class="filter-label">
                                    Garden Pro
                                    <span class="filter-count">(8)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="brand-green" class="filter-checkbox" value="Green Thumb">
                                <label for="brand-green" class="filter-label">
                                    Green Thumb
                                    <span class="filter-count">(12)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="brand-natural" class="filter-checkbox" value="Natural Growth">
                                <label for="brand-natural" class="filter-label">
                                    Natural Growth
                                    <span class="filter-count">(6)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="brand-premium" class="filter-checkbox" value="Premium Plant">
                                <label for="brand-premium" class="filter-label">
                                    Premium Plant
                                    <span class="filter-count">(9)</span>
                                </label>
                            </div>
                        </div>
                    </div>


                    <!-- Availability -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Availability</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="in-stock" class="filter-checkbox" value="in-stock" {{ is_array(request('availability')) && in_array('in-stock', request('availability')) ? 'checked' : (!request()->has('availability') ? 'checked' : '') }}>
                                <label for="in-stock" class="filter-label">
                                    In Stock
                                    <span class="filter-count">(40)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="out-of-stock" class="filter-checkbox" value="out-of-stock" {{ is_array(request('availability')) && in_array('out-of-stock', request('availability')) ? 'checked' : '' }}>
                                <label for="out-of-stock" class="filter-label">
                                    Out of Stock
                                    <span class="filter-count">(5)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="fast-delivery" class="filter-checkbox" value="fast-delivery">
                                <label for="fast-delivery" class="filter-label">
                                    Fast Delivery
                                    <span class="filter-count">(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Sort By</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-popular" class="filter-radio" value="popularity" {{ request('sort', 'popularity') == 'popularity' ? 'checked' : '' }}>
                                <label for="sort-popular" class="filter-label">Popularity</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-price-low" class="filter-radio" value="price-low" {{ request('sort') == 'price-low' ? 'checked' : '' }}>
                                <label for="sort-price-low" class="filter-label">Price: Low to High</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-price-high" class="filter-radio" value="price-high" {{ request('sort') == 'price-high' ? 'checked' : '' }}>
                                <label for="sort-price-high" class="filter-label">Price: High to Low</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-new" class="filter-radio" value="newest" {{ request('sort') == 'newest' ? 'checked' : '' }}>
                                <label for="sort-new" class="filter-label">Newest First</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-discount" class="filter-radio" value="discount" {{ request('sort') == 'discount' ? 'checked' : '' }}>
                                <label for="sort-discount" class="filter-label">Discount</label>
                            </div>
                        </div>
                    </div>

                    <!-- Value Filter (Best Value, Premium, etc.) -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Value</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" id="value-best" class="filter-checkbox" value="best">
                                <label for="value-best" class="filter-label">
                                    Best Value
                                    <span class="filter-count">(15)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="value-premium" class="filter-checkbox" value="premium">
                                <label for="value-premium" class="filter-label">
                                    Premium
                                    <span class="filter-count">(10)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="value-budget" class="filter-checkbox" value="budget">
                                <label for="value-budget" class="filter-label">
                                    Budget Friendly
                                    <span class="filter-count">(20)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Options -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">Delivery Options</h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" id="delivery-free" class="filter-checkbox" value="free-delivery">
                                <label for="delivery-free" class="filter-label">
                                    Free Delivery
                                    <span class="filter-count">(35)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="delivery-same-day" class="filter-checkbox" value="same-day">
                                <label for="delivery-same-day" class="filter-label">
                                    Same Day Delivery
                                    <span class="filter-count">(12)</span>
                                </label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" id="delivery-next-day" class="filter-checkbox" value="next-day">
                                <label for="delivery-next-day" class="filter-label">
                                    Next Day Delivery
                                    <span class="filter-count">(28)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="filter-actions d-flex gap-2 mt-3">
                        <button class="btn-filter btn-apply flex-fill" id="apply-filters" type="submit">Apply Filters</button>
                        <a href="{{ url()->current() }}" class="btn-filter btn-reset flex-fill text-center text-decoration-none" style="display:flex; justify-content:center; align-items:center;">Reset All</a>
                    </div>
                </form>
            </div>

            <!-- Products Display Area -->
            <div class="col-lg-9 col-md-8">
                <div class="products-area">
                    <div class="products-header bg-white rounded p-3 mb-4">
                        @if(isset($category) && $category->image)
                            <div class="category-image-banner mb-4 text-center">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="max-height: 300px; width: 100%; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            </div>
                        @endif
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h2 class="category-name mb-2 mb-md-0">{{ isset($category) ? $category->name : 'All Categories' }}</h2>

                            <div class="sort-options">
                                <select id="sort-products" class="form-select">
                                    <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Sort by: Popularity</option>
                                    <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Name: A to Z</option>
                                    <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Name: Z to A</option>
                                    <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="products-grid row g-3" id="products-container">
                        @if(isset($products))
                            @forelse($products as $product)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
                                    <div class="product-card">
                                        <div class="product-image-container">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                        class="product-image main-image">
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                        class="product-image hover-image">
                                                @else
                                                    <img src="{{ asset('assets/images/product/product1.jpg') }}" alt="{{ $product->name }}"
                                                        class="product-image main-image">
                                                    <img src="{{ asset('assets/images/product/product1.jpg') }}" alt="{{ $product->name }}"
                                                        class="product-image hover-image">
                                                @endif
                                                @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price && $product->discount_percentage > 0)
                                                    <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="product-info">
                                            <h3 class="product-title">{{ $product->name }}</h3>
                                            <div class="product-price">
                                                @if($product->sale_price && $product->sale_price > 0 && $product->sale_price < $product->price)
                                                    <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                                                    <span class="current-price">₹{{ number_format($product->sale_price, 2) }}</span>
                                                @else
                                                    <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </div>
                                            <div class="product-actions">
                                                @if($product->stock_quantity > 0)
                                                    <form class="d-inline-block" method="POST" action="{{ route('cart.add', $product->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="buy_now" value="1">
                                                        <button class="btn btn-primary" data-tooltip="Buy Now" type="submit">
                                                            <span class="btn-text">Buy Now</span><i class="btn-icon fas fa-shopping-bag"></i>
                                                        </button>
                                                    </form>
                                                    <form class="add-to-cart-form d-inline-block" method="POST" action="{{ route('cart.add', $product->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary" data-tooltip="Add to Cart">
                                                            <span class="btn-text">Add to Cart</span><i class="btn-icon fas fa-shopping-cart"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-secondary w-75" style="background-color: #6c757d; border-color: #6c757d; color: white; cursor: not-allowed;" disabled>
                                                        <span class="btn-text">Out of Stock</span>
                                                    </button>
                                                @endif
                                                <form class="d-inline-block" method="POST" action="{{ route('wishlist.add', $product->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-wishlist" data-tooltip="Wishlist">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-center">No products found in this category.</p>
                                </div>
                            @endforelse
                            @if(isset($products) && method_exists($products, 'links'))
                                <div class="col-12 mt-4 text-center">
                                    {{ $products->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        @endif

                        <!-- Demo products for display if nothing found (optional, comment out for production) -->
                        <!--
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="product-card">
                                <div class="product-image-container">
                                    <a href="#">
                                        <img src="{{ asset('assets/images/product/product2.jpg') }}" alt="vermiculite" class="product-image main-image">
                                        <img src="{{ asset('assets/images/product/product2.jpg') }}" alt="vermiculite" class="product-image hover-image">
                                    </a>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">Vermiculite</h3>
                                    <div class="product-price"><span class="current-price">₹699.00</span></div>
                                    <div class="product-actions">
                                        <form class="d-inline-block" method="POST" action="{{ route('cart.add', $product->id) }}">
                                            @csrf
                                            <input type="hidden" name="buy_now" value="1">
                                            <button class="btn btn-primary" data-tooltip="Buy Now" type="submit">
                                                <span class="btn-text">Buy Now</span><i class="btn-icon fas fa-shopping-bag"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-secondary" data-tooltip="Add to Cart"><span class="btn-text">Add to Cart</span><i class="btn-icon fas fa-shopping-cart"></i></button>
                                        <button class="btn btn-wishlist" data-tooltip="Wishlist"><i class="far fa-heart"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <!-- Remove static demo product cards from final code -->
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
    let minPrice = 0;
    if(priceInput) {
        priceInput.addEventListener('input', function() {
            displayPriceRange.textContent = `₹${minPrice} - ₹${priceInput.value}`;
        });
    }

    // Sort Dropdown Auto Submit
    const sortDropdown = document.getElementById('sort-products');
    if (sortDropdown) {
        sortDropdown.addEventListener('change', function() {
            let url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.href;
        });
    }

    // Toggle heart icon color dynamically for wishlist
    document.querySelectorAll('.btn-wishlist').forEach(button => {
        button.addEventListener('mousedown', function() {
            const icon = this.querySelector('i');
            if (icon && icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#e53e3e';
            }
        });
    });

    // Collapsible filter sections
    document.querySelectorAll('.filter-title').forEach(title => {
        title.addEventListener('click', function(e) {
            if(this.parentElement.querySelector('.filter-options')) {
                this.parentElement.classList.toggle('active');
            }
        });
    });
});
</script>

@include('view.layout.footer')