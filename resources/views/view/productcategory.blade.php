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
                <form id="filter-form" action="{{ url()->current() }}" method="GET"
                    class="side-menu bg-white rounded shadow-sm p-3 sticky-top"
                    style="top: 20px; z-index: 1; border: 1px solid #ddd;">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="side-menu-title mb-0">Filters</h2>
                        @if(request()->except(['page']))
                        <a href="{{ url()->current() }}" class="text-danger text-decoration-none" style="font-size:13px;">
                            <i class="fas fa-times-circle me-1"></i>Clear All
                        </a>
                        @endif
                    </div>

                    {{-- ── Price Range (Slider) ────────────────────────────────── --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Price Range
                            <span class="price-range-value text-success" style="color: #72a420 !important;" id="display-price-range">₹{{ request('price_max', 10000) }}</span>
                        </h3>
                        <div class="filter-options">
                            <input type="range" name="price_max" id="price-range-slider" class="form-range"
                                min="0" max="10000" step="100" value="{{ request('price_max', 10000) }}" style="width: 100%;">
                            <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 11px;">
                                <span>₹0</span>
                                <span>₹10,000+</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Discount ─────────────────────────────────────────── --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Discount
                        </h3>
                        <div class="filter-options">
                            @php
                            $discountOptions = [
                            'all' => 'All Products',
                            '50' => '50% and above',
                            '30-50' => '30% – 50%',
                            '10-30' => '10% – 30%',
                            'below10' => 'Below 10%',
                            ];
                            @endphp
                            @foreach($discountOptions as $val => $label)
                            @php $cnt = $filterCounts['discount'][$val] ?? 0; @endphp
                            @if($cnt > 0)
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-{{ $val }}"
                                    class="filter-radio" value="{{ $val }}"
                                    {{ request('discount', 'all') == $val ? 'checked' : '' }}>
                                <label for="discount-{{ $val }}" class="filter-label">
                                    {{ $label }}
                                    <span class="filter-count">({{ $cnt }})</span>
                                </label>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Availability ─────────────────────────────────────── --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Availability
                        </h3>
                        <div class="filter-options">
                            @php
                            $availOptions = [
                            'in-stock' => 'In Stock',
                            'out-of-stock' => 'Out of Stock',
                            ];
                            @endphp
                            @foreach($availOptions as $val => $label)
                            @php $cnt = $filterCounts['availability'][$val] ?? 0; @endphp
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="avail-{{ $val }}"
                                    class="filter-checkbox" value="{{ $val }}"
                                    {{ is_array(request('availability')) && in_array($val, request('availability')) ? 'checked' : '' }}>
                                <label for="avail-{{ $val }}" class="filter-label">
                                    {{ $label }}
                                    <span class="filter-count">({{ $cnt }})</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Shape ────────────────────────────────────────────── --}}
                    @php $shapeTotal = array_sum($filterCounts['shape'] ?? []); @endphp
                    @if($shapeTotal > 0)
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Shape
                        </h3>
                        <div class="filter-options">
                            @foreach($filterCounts['shape'] as $val => $cnt)
                            @if($cnt > 0)
                            <div class="filter-item">
                                <input type="radio" name="shape" id="shape-{{ Str::slug($val) }}"
                                    class="filter-radio" value="{{ $val }}"
                                    {{ request('shape') == $val ? 'checked' : '' }}>
                                <label for="shape-{{ Str::slug($val) }}" class="filter-label">
                                    {{ $val }}
                                    <span class="filter-count">({{ $cnt }})</span>
                                </label>
                            </div>
                            @endif
                            @endforeach
                            @if(request('shape'))
                            <div class="filter-item mt-1">
                                <input type="radio" name="shape" id="shape-all" class="filter-radio" value="">
                                <label for="shape-all" class="filter-label text-muted">All Shapes</label>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- ── Material ─────────────────────────────────────────── --}}
                    @php $matTotal = array_sum($filterCounts['material'] ?? []); @endphp
                    @if($matTotal > 0)
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Material
                        </h3>
                        <div class="filter-options">
                            @foreach($filterCounts['material'] as $val => $cnt)
                            @if($cnt > 0)
                            <div class="filter-item">
                                <input type="checkbox" name="material[]" id="mat-{{ Str::slug($val) }}"
                                    class="filter-checkbox" value="{{ $val }}"
                                    {{ is_array(request('material')) && in_array($val, request('material')) ? 'checked' : '' }}>
                                <label for="mat-{{ Str::slug($val) }}" class="filter-label">
                                    {{ $val }}
                                    <span class="filter-count">({{ $cnt }})</span>
                                </label>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- ── Weight ───────────────────────────────────────────── --}}
                    @php $weightTotal = array_sum($filterCounts['weight'] ?? []); @endphp
                    @if($weightTotal > 0)
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Weight
                        </h3>
                        <div class="filter-options">
                            @php
                            $weightOptions = [
                                '0to1'   => '0 kg – 1 kg',
                                '1to3'   => '1 kg – 3 kg',
                                '3to5'   => '3 kg – 5 kg',
                                'above5' => 'Above 5 kg',
                            ];
                            @endphp
                            @foreach($weightOptions as $val => $label)
                            @php $cnt = $filterCounts['weight'][$val] ?? 0; @endphp
                            @if($cnt > 0)
                            <div class="filter-item">
                                <input type="radio" name="weight_range" id="weight-{{ $val }}"
                                    class="filter-radio" value="{{ $val }}"
                                    {{ request('weight_range') == $val ? 'checked' : '' }}>
                                <label for="weight-{{ $val }}" class="filter-label">
                                    {{ $label }}
                                    <span class="filter-count">({{ $cnt }})</span>
                                </label>
                            </div>
                            @endif
                            @endforeach
                            @if(request('weight_range'))
                            <div class="filter-item mt-1">
                                <input type="radio" name="weight_range" id="weight-all" class="filter-radio" value="">
                                <label for="weight-all" class="filter-label text-muted">All Weights</label>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- ── Sort By ──────────────────────────────────────────── --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Sort By
                        </h3>
                        <div class="filter-options">
                            @php
                            $sortOptions = [
                            'popularity' => 'Popularity',
                            'price-low' => 'Price: Low to High',
                            'price-high' => 'Price: High to Low',
                            'newest' => 'Newest First',
                            'discount' => 'Best Discount',
                            ];
                            @endphp
                            @foreach($sortOptions as $val => $label)
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-{{ $val }}"
                                    class="filter-radio" value="{{ $val }}"
                                    {{ request('sort', 'popularity') == $val ? 'checked' : '' }}>
                                <label for="sort-{{ $val }}" class="filter-label">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ── Actions ─────────────────────────────────────────── --}}
                    <div class="filter-actions d-flex gap-2 mt-3">
                        <button class="btn-filter btn-apply flex-fill" type="submit">Apply Filters</button>
                        <a href="{{ url()->current() }}"
                            class="btn-filter btn-reset flex-fill text-center text-decoration-none"
                            style="display:flex; justify-content:center; align-items:center;">Reset All</a>
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
                        @forelse($products as $product)
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
                            @include('view.partials.product-card', ['product' => $product])
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
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Price Range Slider Sync
        const priceSlider = document.getElementById('price-range-slider');
        const displayPriceRange = document.getElementById('display-price-range');
        if (priceSlider && displayPriceRange) {
            priceSlider.addEventListener('input', function() {
                displayPriceRange.textContent = `₹${this.value}`;
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
                if (this.parentElement.querySelector('.filter-options')) {
                    this.parentElement.classList.toggle('active');
                }
            });
        });
    });
</script>

@include('view.layout.footer')