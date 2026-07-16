@include('view.layout.header')


{{-- noUiSlider --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

<section class="py-4">
    <div class="container-fluid">
        <div class="row">
            <!-- Side Menu for Filters -->
            <div class="col-lg-3 col-md-4 mb-4 side-menu-wrapper" id="filterMenuWrapper">
                <form id="filter-form" action="{{ url()->current() }}" method="GET" class="side-menu bg-white rounded shadow-sm p-3 sticky-top" style="top: 20px; z-index: 1000; border: 1px solid #ddd;">
                    <div class="d-flex justify-content-between align-items-center d-md-none mb-3">
                        <h4 class="fw-bold mb-0" style="color: #6EA820;">Filters</h4>
                        <button type="button" class="btn-close" id="mobileFilterClose" style="border: none; background: transparent; font-size: 1.5rem; color: #333; line-height: 1;">&times;</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="side-menu-title mb-0 d-none d-md-block">Filters</h2>
                        @if(request()->except(['page']))
                        <a href="{{ url()->current() }}" class="text-danger text-decoration-none" style="font-size:13px; font-weight:600;">
                            <i class="fas fa-times-circle me-1"></i>Clear All
                        </a>
                        @endif
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-section active mb-4">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-3">
                            Price Range
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options px-2">
                            <div id="price-range-slider" class="mb-4 mt-3"></div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="flex-column d-flex">
                                    <small class="text-muted mb-1" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Minimum</small>
                                    <span class="fw-bold text-dark" id="price-min-label" style="font-size: 14px;">₹{{ request('price_min', 0) }}</span>
                                </div>
                                <div class="flex-column d-flex text-end">
                                    <small class="text-muted mb-1" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Maximum</small>
                                    <span class="fw-bold text-dark" id="price-max-label" style="font-size: 14px;">₹{{ request('price_max', 10000) }}</span>
                                </div>
                            </div>
                            <input type="hidden" name="price_min" id="price-min" value="{{ request('price_min', 0) }}">
                            <input type="hidden" name="price_max" id="price-max" value="{{ request('price_max', 10000) }}">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Categories
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="category" id="cat-all" class="filter-radio" value="" {{ !request('category') ? 'checked' : '' }}>
                                <label for="cat-all" class="filter-label">All Categories</label>
                            </div>
                            @foreach($categories as $cat)
                                <div class="filter-item">
                                    <input type="radio" name="category" id="cat-{{ $cat->id }}" class="filter-radio" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                    <label for="cat-{{ $cat->id }}" class="filter-label">{{ $cat->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Discount Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Discount
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-all" class="filter-radio" value="all" {{ request('discount', 'all') == 'all' ? 'checked' : '' }}>
                                <label for="discount-all" class="filter-label">All Discounts</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-50" class="filter-radio" value="50" {{ request('discount') == '50' ? 'checked' : '' }}>
                                <label for="discount-50" class="filter-label">50% and above</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-30-50" class="filter-radio" value="30-50" {{ request('discount') == '30-50' ? 'checked' : '' }}>
                                <label for="discount-30" class="filter-label">30% - 50%</label>
                            </div>
                            <div class="filter-item">
                                <input type="radio" name="discount" id="discount-10-30" class="filter-radio" value="10-30" {{ request('discount') == '10-30' ? 'checked' : '' }}>
                                <label for="discount-10" class="filter-label">10% - 30%</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Availability -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Availability
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="in-stock" class="filter-checkbox" value="in-stock" {{ is_array(request('availability')) && in_array('in-stock', request('availability')) ? 'checked' : '' }}>
                                <label for="in-stock" class="filter-label">In Stock</label>
                            </div>
                            <div class="filter-item">
                                <input type="checkbox" name="availability[]" id="out-of-stock" class="filter-checkbox" value="out-of-stock" {{ is_array(request('availability')) && in_array('out-of-stock', request('availability')) ? 'checked' : '' }}>
                                <label for="out-of-stock" class="filter-label">Out of Stock</label>
                            </div>
                        </div>
                    </div>

                    <!-- Sort By -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Sort By
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options">
                            <div class="filter-item">
                                <input type="radio" name="sort" id="sort-new" class="filter-radio" value="newest" {{ request('sort', 'newest') == 'newest' ? 'checked' : '' }}>
                                <label for="sort-new" class="filter-label">Newest First</label>
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
                                <input type="radio" name="sort" id="sort-discount" class="filter-radio" value="discount" {{ request('sort') == 'discount' ? 'checked' : '' }}>
                                <label for="sort-discount" class="filter-label">Discount</label>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="filter-actions d-flex mt-3">
                        <button class="btn-filter btn-apply flex-fill mr-2" style="margin-right: 8px;" id="applyFiltersBtn" type="submit">Apply Filters</button>
                        <a href="{{ route('combo_packs.frontend_index') }}" class="btn-filter btn-reset flex-fill text-center text-decoration-none" style="display:flex; justify-content:center; align-items:center;">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Products Display Area -->
            <div class="col-lg-9 col-md-8">
                <div class="products-area">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-3 rounded-4 shadow-sm mb-4" style="border: 1px solid rgba(0,0,0,0.05);">
                        {{-- Left: Category Title + Count --}}
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-light text-success d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 45px; height: 45px; margin-right: 15px; color: #2e6a39 !important;">
                                <i class="fas fa-boxes fa-lg"></i>
                            </div>
                            <div>
                                <h2 class="h5 fw-bold mb-1" style="font-family: var(--font-main); color: #2c3e50;">
                                    Combo Packs Collection
                                </h2>
                                <span class="badge" style="background: rgba(46, 106, 57, 0.1); color: #2e6a39; font-size: 0.75rem; letter-spacing: 0.5px; padding: 4px 8px; border-radius: 6px;">
                                    {{ $comboPacks->total() }} Combos
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Filter Trigger (Visible only on mobile/tablet < 768px) -->
                    <div class="d-md-none mb-3">
                        <button type="button" class="btn btn-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2" id="mobileFilterToggle" style="background-color: #6EA820; border-color: #6EA820; font-weight: 600; border-radius: 8px;">
                            <i class="fas fa-filter"></i> Filter & Sort Combo Packs
                        </button>
                    </div>

                    <!-- AJAX Products Area -->
                    <div id="ajax-products-area" style="position: relative; min-height: 200px; transition: opacity 0.25s ease;">
                        @include('view.partials.combo-packs-grid')
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    'use strict';

    var filterForm    = document.getElementById('filter-form');
    var productsArea  = document.getElementById('ajax-products-area');
    var priceInput    = document.getElementById('price-max');
    var displayPriceRange = document.getElementById('display-price-range');
    var filterToggle  = document.getElementById('mobileFilterToggle');
    var filterClose   = document.getElementById('mobileFilterClose');
    var filterMenu    = document.getElementById('filterMenuWrapper');
    var debounceTimer = null;

    function isMobile() { return window.innerWidth < 768; }

    // ── Loading helpers ─────────────────────────────────────────────
    function showLoading() {
        if (productsArea) productsArea.style.opacity = '0.4';
    }
    function hideLoading() {
        if (productsArea) productsArea.style.opacity = '1';
    }

    // ── Core AJAX fetch ─────────────────────────────────────────────
    function fetchProducts(url) {
        showLoading();
        // Update browser URL bar without reload
        window.history.pushState({}, '', url);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (productsArea && data.html) {
                productsArea.innerHTML = data.html;
            }
            hideLoading();
            productsArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(function (err) {
            console.error('Combo packs AJAX filter error:', err);
            hideLoading();
        });
    }

    // Build query string from filter form and optional extra params
    function buildUrl(extraParams) {
        if (!filterForm) return window.location.href;
        var formData = new FormData(filterForm);
        var params   = new URLSearchParams();
        formData.forEach(function (value, key) {
            if (value !== '') params.append(key, value);
        });
        if (extraParams) {
            Object.keys(extraParams).forEach(function (k) {
                params.set(k, extraParams[k]);
            });
        }
        return filterForm.getAttribute('action') + '?' + params.toString();
    }

    // ── Desktop: auto-submit on filter change ─────────────────────
    var priceSlider   = document.getElementById('price-range-slider');
    var minInput      = document.getElementById('price-min');
    var maxInput      = document.getElementById('price-max');
    var minLabel      = document.getElementById('price-min-label');
    var maxLabel      = document.getElementById('price-max-label');

    if (priceSlider) {
        noUiSlider.create(priceSlider, {
            start: [parseInt(minInput.value) || 0, parseInt(maxInput.value) || 10000],
            connect: true,
            step: 50,
            range: { 'min': 0, 'max': 10000 },
            format: { to: function(v){ return Math.round(v); }, from: function(v){ return parseFloat(v); } }
        });
        priceSlider.noUiSlider.on('update', function(values) {
            minLabel.textContent = '₹' + values[0];
            maxLabel.textContent = '₹' + values[1];
            minInput.value = values[0];
            maxInput.value = values[1];
        });
        priceSlider.noUiSlider.on('end', function() {
            if (!isMobile()) fetchProducts(buildUrl());
        });
    }

    if (filterForm) {
        filterForm.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function (input) {
            input.addEventListener('change', function () {
                if (!isMobile()) fetchProducts(buildUrl());
            });
        });

        // Prevent native form submit — always use AJAX
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (isMobile()) closeMobileFilter();
            setTimeout(function () { fetchProducts(buildUrl()); }, isMobile() ? 320 : 0);
        });
    }

    // ── Pagination: delegate clicks on nav links inside products area ──
    document.addEventListener('click', function (e) {
        // Only intercept links inside a <nav> (Laravel pagination wrapper)
        var paginationLink = e.target.closest('#ajax-products-area nav a[href]');
        if (paginationLink) {
            e.preventDefault();
            fetchProducts(paginationLink.href);
            return;
        }

        // "Reset Filters" button inside products area (empty state only)
        var resetBtn = e.target.closest('#ajax-products-area .col-12.text-center a.btn-danger');
        if (resetBtn) {
            e.preventDefault();
            fetchProducts(resetBtn.href);
            return;
        }
    });

    // ── "Reset" sidebar link — clear all filters via AJAX ─────────
    var sidebarResetLink = document.querySelector('#filter-form ~ * a.btn-outline-secondary, .filter-actions a.btn-outline-secondary');
    if (sidebarResetLink) {
        sidebarResetLink.addEventListener('click', function (e) {
            e.preventDefault();
            fetchProducts(this.href);
        });
    }

    // ── AJAX Wishlist (delegated — works after AJAX re-render) ─────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.wishlist-btn-combo');
        if (!btn) return;

        var id = btn.getAttribute('data-id');

        fetch('{{ url("/wishlist/add-combo") }}/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            if (response.status === 401) {
                window.location.href = '{{ route("login") }}';
                return;
            }
            return response.json().then(function (data) {
                if (!response.ok) throw new Error(data.message || 'Something went wrong');
                return data;
            });
        })
        .then(function (data) {
            if (data && data.success) {
                alert(data.message);
                document.querySelectorAll('.wishlist-icon-link .price_cart').forEach(function (el) {
                    el.textContent = data.wishlist_count;
                });
                var icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    icon.style.color = '#e53e3e';
                }
            }
        })
        .catch(function (error) {
            if (error && error.message) alert(error.message);
        });
    });

    // ── Collapsible Filter Sections ────────────────────────────────
    document.querySelectorAll('.filter-title').forEach(function (title) {
        title.addEventListener('click', function (e) {
            e.stopPropagation();
            var section = this.closest('.filter-section');
            if (section) section.classList.toggle('active');
        });
    });

    // ── Mobile drawer open/close ───────────────────────────────────
    var overlay = document.createElement('div');
    overlay.className = 'filter-overlay';
    document.body.appendChild(overlay);

    if (filterToggle && filterMenu) {
        filterToggle.addEventListener('click', function () {
            filterMenu.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }

    function closeMobileFilter() {
        if (filterMenu) filterMenu.classList.remove('open');
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (filterClose) filterClose.addEventListener('click', closeMobileFilter);
    overlay.addEventListener('click', closeMobileFilter);

    // ── Handle browser back/forward ────────────────────────────────
    window.addEventListener('popstate', function () {
        fetchProducts(window.location.href);
    });

}());
</script>



@include('view.layout.footer')
