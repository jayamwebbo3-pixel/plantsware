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
            <div class="col-lg-3 col-md-4 mb-4 side-menu-wrapper" id="filterMenuWrapper">
                <form id="filter-form" action="{{ url()->current() }}" method="GET" class="side-menu bg-white rounded shadow-sm p-3 sticky-top" style="top: 20px; z-index: 1000; border: 1px solid #ddd;">
                    <div class="d-flex justify-content-between align-items-center d-md-none mb-3">
                        <h4 class="fw-bold mb-0" style="color: #6EA820;">Filters</h4>
                        <button type="button" class="btn-close" id="mobileFilterClose" style="border: none; background: transparent; font-size: 1.5rem; color: #333; line-height: 1;">&times;</button>
                    </div>
                    <h2 class="side-menu-title mb-3 d-none d-md-block">Filters</h2>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            <span>Price Range <span class="price-range-value ms-2" id="display-price-range" style="font-size: 13px; font-weight: normal; color: #72a420;">₹0 - ₹{{ request('price_max', 10000) }}</span></span>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options d-flex align-items-center">
                            <input type="range" name="price_max" class="form-range flex-grow-1 me-2" min="0" max="10000" step="100" id="price-max" value="{{ request('price_max', 10000) }}" style="width: 100%;">
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
                    <div class="filter-actions d-flex gap-2 mt-3">
                        <button class="btn btn-primary flex-fill" id="applyFiltersBtn" style="background-color: #72a420; border-color: #72a420;" type="submit">Apply Filters</button>
                        <a href="{{ route('combo_packs.frontend_index') }}" class="btn btn-outline-secondary flex-fill text-center text-decoration-none d-flex align-items-center justify-content-center">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Products Display Area -->
            <div class="col-lg-9 col-md-8">
                <div class="products-area">
                    <div class="products-header bg-white rounded p-3 mb-3 d-flex justify-content-between align-items-center border">
                        <h2 class="category-name mb-0">Combo Packs Listing</h2>
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
    if (filterForm) {
        filterForm.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function (input) {
            input.addEventListener('change', function () {
                if (!isMobile()) fetchProducts(buildUrl());
            });
        });

        // Price slider — debounce on 'input', fire on 'change'
        if (priceInput) {
            priceInput.addEventListener('input', function () {
                if (displayPriceRange) displayPriceRange.textContent = '\u20B90 - \u20B9' + priceInput.value;
                if (!isMobile()) {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () { fetchProducts(buildUrl()); }, 400);
                }
            });
            priceInput.addEventListener('change', function () {
                if (!isMobile()) {
                    clearTimeout(debounceTimer);
                    fetchProducts(buildUrl());
                }
            });
        }

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
