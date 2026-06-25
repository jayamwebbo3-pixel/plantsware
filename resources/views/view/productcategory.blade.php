@include('view.layout.header')
<style>
    .product-card{
margin: 0 5px !important;
    }
    </style>
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('home') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder">
                        <a href="{{ route('products.index') }}" class="text-decoration-none">
                            @if(request()->filled('q'))
                                Search Results
                            @else
                                Categories
                            @endif
                        </a>
                    </li>
                    @if(isset($category))
                        <li class="d-inline-block font-weight-bolder mx-2">/</li>
                        <li class="d-inline-block font-weight-bolder">{{ $category->name }}</li>
                    @elseif(isset($subcategory))
                        <li class="d-inline-block font-weight-bolder mx-2">/</li>
                        <li class="d-inline-block font-weight-bolder">{{ $subcategory->name }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="py-4">
    <div class="container-fluid">
        <div class="row">
            <!-- Mobile Filter Overlay Backdrop -->
            <div class="filter-overlay" id="filterOverlay"></div>

            <!-- Side Menu for Categories with Filters -->
            <div class="col-lg-3 col-md-4 mb-4 filter-desktop-col">
                <div class="side-menu-wrapper" id="filterSidebar">
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

    {{-- noUiSlider for Dual Price Range --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

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
                    <span class="fw-bold text-dark" id="price-min-label" style="font-size: 14px;">â‚¹{{ request('price_min', $minPrice) }}</span>
                </div>
                <div class="flex-column d-flex text-end">
                    <small class="text-muted mb-1" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Maximum</small>
                    <span class="fw-bold text-dark" id="price-max-label" style="font-size: 14px;">â‚¹{{ request('price_max', $maxPrice) }}</span>
                </div>
            </div>
            <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min', $minPrice) }}">
            <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max', $maxPrice) }}">
        </div>
    </div>

                    {{-- â”€â”€ Discount â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Discount
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options">
                            @php
                            $discountOptions = [
                            'all' => 'All Products',
                            '50' => '50% and above',
                            '30-50' => '30% - 50%',
                            '10-30' => '10% - 30%',
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

                    {{-- â”€â”€ Availability â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Availability
                            <i class="fas fa-chevron-down toggle-icon"></i>
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

                    {{-- â”€â”€ Shape â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    @php $shapeTotal = array_sum($filterCounts['shape'] ?? []); @endphp
                    @if($shapeTotal > 0)
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Shape
                            <i class="fas fa-chevron-down toggle-icon"></i>
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

                    {{-- â”€â”€ Material â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    @php $matTotal = array_sum($filterCounts['material'] ?? []); @endphp
                    @if($matTotal > 0)
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Material
                            <i class="fas fa-chevron-down toggle-icon"></i>
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

                    {{-- â”€â”€ Weight â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    @php $weightTotal = array_sum($filterCounts['weight'] ?? []); @endphp
                    @if($weightTotal > 0)
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Weight
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </h3>
                        <div class="filter-options">
                            @php
                            $weightOptions = [
                                '0to1'   => '0 Kg - 1 Kg',
                                '1to3'   => '1 Kg - 3 Kg',
                                '3to5'   => '3 Kg - 5 Kg',
                                'above5' => 'Above 5 Kg',
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

                    {{-- â”€â”€ Sort By â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    <div class="filter-section active mb-3">
                        <h3 class="filter-title d-flex justify-content-between align-items-center mb-2">
                            Sort By
                            <i class="fas fa-chevron-down toggle-icon"></i>
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

                    {{-- â”€â”€ Actions â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
                    <div class="filter-actions d-flex gap-2 mt-3">
                        <button class="btn-filter btn-apply flex-fill" type="submit" id="applyFiltersBtn">Apply Filters</button>
                        <a href="{{ url()->current() }}"
                            class="btn-filter btn-reset flex-fill text-center text-decoration-none"
                            style="display:flex; justify-content:center; align-items:center;">Reset All</a>
                    </div>
                </form>
                </div><!-- /.side-menu-wrapper -->
            </div>

            <!-- Products Display Area -->
            <div class="col-lg-9 col-md-8 col-12">
                <div class="products-area">
                    <!-- Mobile Filter Toggle Button -->
                    <div class="mobile-filter-bar d-md-none d-flex align-items-center mb-3 px-1">
                        <button type="button" id="filterToggleBtn" class="btn-filter-toggle">
                            <i class="fas fa-sliders-h"></i>
                            Filters
                            @php $activeFilters = count(array_filter(request()->except(['page', 'sort']))); @endphp
                            @if($activeFilters > 0)
                                <span class="filter-active-badge">{{ $activeFilters }}</span>
                            @endif
                        </button>
                    </div>

                    <!-- Products Container â€” updated via AJAX; no full page reload -->
                    <div id="products-container">
                        @include('view.partials.products-grid')
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // â”€â”€ Helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        function isMobile() { return window.innerWidth < 768; }

        var filterForm        = document.getElementById('filter-form');
        var applyFiltersBtn   = document.getElementById('applyFiltersBtn');
        var productsContainer = document.getElementById('products-container');
        var csrfMeta          = document.querySelector('meta[name="csrf-token"]');
        var csrfToken         = csrfMeta ? csrfMeta.getAttribute('content') : '';

        // â”€â”€ Loading state â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        function showLoading() {
            productsContainer.style.opacity       = '0.4';
            productsContainer.style.pointerEvents = 'none';
            productsContainer.style.transition    = 'opacity 0.2s ease';
        }
        function hideLoading() {
            productsContainer.style.opacity       = '1';
            productsContainer.style.pointerEvents = '';
        }

        // â”€â”€ Core AJAX fetch â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        function fetchProducts(params, pushUrl) {
            showLoading();
            var freshUrl = new URL(window.location.pathname, window.location.origin);
            params.forEach(function(pair) {
                if (pair[1] !== '') freshUrl.searchParams.append(pair[0], pair[1]);
            });
            fetch(freshUrl.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(function(res) { return res.text(); })
            .then(function(html) {
                productsContainer.innerHTML = html;
                hideLoading();
                if (isMobile()) productsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (pushUrl) window.history.pushState({ ajaxParams: params }, '', freshUrl.toString());
                bindPaginationLinks();
                bindSortDropdown();
            })
            .catch(function(err) {
                console.error('AJAX filter error:', err);
                hideLoading();
            });
        }

        // ── Collect form field values ────────────────────────────
        function getFormParams() {
            if (!filterForm) return [];
            var params = [];
            (new FormData(filterForm)).forEach(function(v, k) { params.push([k, v]); });
            
            // Preserve 'q' search parameter from URL if present
            var urlParams = new URLSearchParams(window.location.search);
            var qVal = urlParams.get('q');
            if (qVal) {
                var hasQ = params.some(function(p) { return p[0] === 'q'; });
                if (!hasQ) {
                    params.push(['q', qVal]);
                }
            }
            return params;
        }

        function submitFiltersAjax() { fetchProducts(getFormParams(), true); }

        // â”€â”€ Price Slider â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        var priceSlider = document.getElementById('price-range-slider');
        if (priceSlider) {
            var minInput  = document.getElementById('price_min');
            var maxInput  = document.getElementById('price_max');
            var minLabel  = document.getElementById('price-min-label');
            var maxLabel  = document.getElementById('price-max-label');

            noUiSlider.create(priceSlider, {
                start  : [parseInt(minInput.value) || {{ $minPrice }}, parseInt(maxInput.value) || {{ $maxPrice }}],
                connect: true,
                step   : 10,
                range  : { 'min': {{ $minPrice }}, 'max': {{ $maxPrice }} },
                format : { to: function(v){ return Math.round(v); }, from: function(v){ return parseFloat(v); } }
            });
            priceSlider.noUiSlider.on('update', function(values) {
                minLabel.textContent = '\u20B9' + values[0];
                maxLabel.textContent = '\u20B9' + values[1];
                minInput.value = values[0];
                maxInput.value = values[1];
            });
            // Desktop: AJAX on slider release
            priceSlider.noUiSlider.on('end', function() {
                if (!isMobile()) submitFiltersAjax();
            });
        }

        // â”€â”€ Desktop: AJAX on radio / checkbox change â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (filterForm) {
            filterForm.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    if (!isMobile()) submitFiltersAjax();
                });
            });
        }

        // â”€â”€ Collapsible Filter Sections â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        document.querySelectorAll('.filter-title').forEach(function(title) {
            title.addEventListener('click', function(e) {
                e.stopPropagation();
                var section = this.closest('.filter-section');
                if (section) section.classList.toggle('active');
            });
        });

        // â”€â”€ Sort dropdown (re-bound after each AJAX refresh) â”€â”€â”€
        function bindSortDropdown() {
            var sortSel = document.getElementById('sort-products');
            if (!sortSel) return;
            sortSel.addEventListener('change', function() {
                var params = getFormParams().filter(function(p) { return p[0] !== 'sort'; });
                params.push(['sort', this.value]);
                fetchProducts(params, true);
            });
        }
        bindSortDropdown();

        // â”€â”€ Pagination links (re-bound after each AJAX refresh) â”€
        function bindPaginationLinks() {
            productsContainer.querySelectorAll('.ajax-pagination a').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var href = this.getAttribute('href');
                    if (!href) return;
                    var pageUrl = new URL(href, window.location.origin);
                    var params  = [];
                    pageUrl.searchParams.forEach(function(v, k) { params.push([k, v]); });
                    fetchProducts(params, true);
                });
            });
        }
        bindPaginationLinks();

        // â”€â”€ Browser back / forward â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        window.addEventListener('popstate', function() {
            var params = [];
            new URLSearchParams(window.location.search).forEach(function(v, k) { params.push([k, v]); });
            fetchProducts(params, false);
        });

        // â”€â”€ Mobile Filter Drawer â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        var filterToggleBtn = document.getElementById('filterToggleBtn');
        var filterSidebar   = document.getElementById('filterSidebar');
        var filterOverlay   = document.getElementById('filterOverlay');

        function openFilterDrawer() {
            if (!filterSidebar) return;
            filterSidebar.classList.add('open');
            if (filterOverlay) filterOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeFilterDrawer() {
            if (!filterSidebar) return;
            filterSidebar.classList.remove('open');
            if (filterOverlay) filterOverlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (filterToggleBtn) {
            filterToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                filterSidebar && filterSidebar.classList.contains('open') ? closeFilterDrawer() : openFilterDrawer();
            });
        }
        if (filterOverlay) filterOverlay.addEventListener('click', closeFilterDrawer);

        // Mobile: Apply Filters â†’ AJAX + close drawer
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (isMobile()) { closeFilterDrawer(); setTimeout(submitFiltersAjax, 320); }
                else { submitFiltersAjax(); }
            });
        }

        if (isMobile()) closeFilterDrawer();

        window.addEventListener('resize', function() {
            if (!isMobile()) { closeFilterDrawer(); document.body.style.overflow = ''; }
        });
    });
</script>

@include('view.layout.footer')
