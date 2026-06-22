@include('view.layout.header')

<div class="sp_header bg-white p-3 border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0 d-flex align-items-center">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('home') }}" class="text-decoration-none text-success">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2 text-muted">/</li>
                    <li class="d-inline-block font-weight-bolder text-muted">Build a Combo</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="py-5" style="background-color: #f7f9fb;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold text-dark mb-2">Build Your Custom Combo Pack</h1>
                <p class="text-muted lead">Choose eligible products, meet discount slabs, and save big on your custom bundle!</p>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Products Grid & Search -->
            <div class="col-lg-8 mb-4">
                <!-- Search & Filters -->
                <div class="bg-white rounded-3 shadow-sm p-3 mb-4 border d-flex flex-wrap gap-2 justify-content-between align-items-center builder-filter-wrapper">
                    <div class="input-group style-search-group" style="max-width: 400px; flex-grow: 1;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="builderSearch" class="form-control bg-light border-start-0" placeholder="Search products...">
                    </div>
                    <div class="d-flex align-items-center gap-2 builder-filter-select-container">
                        <span class="text-muted small text-nowrap">Filter:</span>
                        <select id="builderCategory" class="form-select bg-light">
                            <option value="">All Categories</option>
                            @php
                                $usedCategories = $products->pluck('category')->unique('id');
                            @endphp
                            @foreach($usedCategories as $cat)
                                @if($cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="productsGrid">
                    @foreach($products as $product)
                        @php
                            $sizeData = [];
                            if ($product->has_variants) {
                                $rawSize = $product->size;
                                if ($rawSize) {
                                    if (is_array($rawSize)) {
                                        $sizeData = $rawSize;
                                    } elseif (is_string($rawSize)) {
                                        $decoded = json_decode($rawSize, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $sizeData = $decoded;
                                        }
                                    }
                                    // Filter to only include sizes with combo_eligible = 'Yes'
                                    if (is_array($sizeData)) {
                                        $sizeData = array_filter($sizeData, function($val) {
                                            return is_array($val) && ($val['combo_eligible'] ?? 'No') === 'Yes';
                                        });
                                    }
                                }
                            }

                            $price = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
                            $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                            $image = $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/default.jpg');

                            if (count($sizeData) > 0) {
                                $firstSizeName = array_key_first($sizeData);
                                $firstSizeVal = $sizeData[$firstSizeName];
                                $firstSizePrice = is_array($firstSizeVal) ? ($firstSizeVal['price'] ?? null) : $firstSizeVal;
                                if ($firstSizePrice !== null && $firstSizePrice > 0) {
                                    $price = $firstSizePrice;
                                    $hasDiscount = false;
                                }
                            }
                        @endphp
                        <div class="col product-item-col" 
                             data-id="{{ $product->id }}" 
                             data-name="{{ $product->name }}" 
                             data-base-price="{{ $price }}" 
                             data-original-price="{{ $product->price }}"
                             data-image="{{ $image }}"
                             data-category="{{ $product->category_id }}">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden product-builder-card position-relative">
                                @if($hasDiscount)
                                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 z-3">Sale</span>
                                @endif
                                <div class="p-3 bg-white text-center" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ $image }}" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="{{ $product->name }}">
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h5 class="card-title text-dark fs-6 fw-bold text-truncate mb-2" title="{{ $product->name }}">{{ $product->name }}</h5>
                                    
                                    @if(count($sizeData) > 0)
                                        <div class="mb-3">
                                            <label class="small text-muted mb-1 d-block fw-semibold">Select Size:</label>
                                            <select class="form-select form-select-sm size-select" data-product-id="{{ $product->id }}" onchange="changeProductSize({{ $product->id }}, this)" style="border-radius: 6px; border-color: #ced4da; font-size: 13px;">
                                                @foreach($sizeData as $sizeName => $sizeValue)
                                                    @php
                                                        $sizePrice = is_array($sizeValue) ? ($sizeValue['price'] ?? null) : $sizeValue;
                                                    @endphp
                                                    <option value="{{ $sizeName }}" data-price="{{ $sizePrice ?? '' }}">
                                                        {{ $sizeName }} @if($sizePrice) (₹{{ number_format($sizePrice, 2) }}) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="mt-auto">
                                        <div class="d-flex align-items-baseline mb-3 price-container-{{ $product->id }}">
                                            @if($hasDiscount)
                                                <del class="text-muted small me-2">₹{{ number_format($product->price, 2) }}</del>
                                                <span class="text-success fw-bold fs-5">₹{{ number_format($product->sale_price, 2) }}</span>
                                            @else
                                                <span class="text-dark fw-bold fs-5">₹{{ number_format($price, 2) }}</span>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm w-100 rounded-pill toggle-product-btn" onclick="toggleProduct({{ $product->id }})">
                                            <i class="fas fa-plus me-1"></i> Add to Combo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Sticky Summary Panel -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden sticky-top" style="top: 20px; z-index: 100;">
                    <div class="card-header bg-success text-white p-3 border-0">
                        <h4 class="card-title mb-0 fw-bold"><i class="fas fa-box-open me-2"></i>Combo Pack Summary</h4>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <!-- Progress / Limits -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">Selection Progress</span>
                                <span class="badge bg-light text-success border border-success px-3 py-2 fs-6" id="progressText">0 / {{ $settings->max_products }}</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{ $settings->max_products }}"></div>
                            </div>
                            <small class="text-muted d-block mt-2">Add between 2 and {{ $settings->max_products }} products to complete the combo.</small>
                        </div>

                        <!-- Selected Items List -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Selected Products</h5>
                            <div id="selectedProductsList" class="d-flex flex-column gap-2" style="max-height: 240px; overflow-y: auto;">
                                <div class="text-center py-4 text-muted" id="emptyPlaceholder">
                                    <i class="fas fa-shopping-basket fa-2x mb-2 text-muted opacity-50"></i>
                                    <p class="mb-0 small">No products selected yet.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Discount slabs visual tracker -->
                        @if($slabs->count() > 0)
                            <div class="mb-4 p-3 bg-light rounded-3">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-percentage me-2 text-warning"></i>Discount Slabs</h6>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                    @foreach($slabs as $slab)
                                        <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-dashed slab-indicator-item" data-min="{{ $slab->min_amount }}" data-percent="{{ $slab->discount_percentage }}">
                                            <span class="small text-muted">Above ₹{{ number_format($slab->min_amount, 2) }}</span>
                                            <span class="badge bg-secondary rounded-pill slab-badge">{{ $slab->discount_percentage }}% Off</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Pricing Breakdown -->
                        <div class="border-top pt-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Subtotal (Products)</span>
                                <span class="fw-semibold text-dark" id="subtotalPrice">₹0.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 text-success d-none" id="discountRow">
                                <span>Combo Discount (<span id="discountPercent">0</span>%)</span>
                                <span class="fw-semibold">-₹<span id="discountAmount">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">
                                <span class="fw-bold text-dark fs-5">Total Combo Price</span>
                                <span class="fw-bold text-success fs-4" id="totalPrice">₹0.00</span>
                            </div>
                            <div class="alert alert-info mt-3 py-2 px-3 small border-0 mb-0 d-none" id="slabNotice">
                                <!-- slab progress notice will be updated here -->
                            </div>
                        </div>

                        <!-- Action Button & Add Form -->
                        <form action="{{ route('cart.add_custom_combo') }}" method="POST" id="customComboForm">
                            @csrf
                            <div id="hiddenFieldsContainer"></div>
                            <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold" id="checkoutBtn" disabled>
                                Add Combo To Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CDN Script for SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.addEventListener('pageshow', (event) => {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });

    const maxProducts = {{ $settings->max_products }};
    const discountSlabs = @json($slabs);
    const customCombos = @json($customCombos ?? []);
    const editComboId = '{{ $editComboId ?? '' }}';
    let selectedProducts = [];
    let editingComboId = editComboId || null;

    function saveState() {
        sessionStorage.setItem('selected_combo_products', JSON.stringify(selectedProducts));
        if (editingComboId) {
            sessionStorage.setItem('editing_combo_id', editingComboId);
        } else {
            sessionStorage.removeItem('editing_combo_id');
        }
    }

    function toggleProduct(productId) {
        productId = parseInt(productId);
        const index = selectedProducts.findIndex(p => p.id === productId);

        if (index > -1) {
            // Remove
            selectedProducts.splice(index, 1);
            saveState();
            updateUI();
        } else {
            // Add
            if (selectedProducts.length >= maxProducts) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Limit Reached',
                    text: `Maximum ${maxProducts} products are allowed in a Combo Pack.`,
                    confirmButtonColor: '#198754'
                });
                return;
            }

            // Find product metadata from DOM attributes
            const col = document.querySelector(`.product-item-col[data-id="${productId}"]`);
            if (col) {
                const name = col.getAttribute('data-name');
                const basePrice = parseFloat(col.getAttribute('data-base-price'));
                const originalPrice = parseFloat(col.getAttribute('data-original-price')) || basePrice;
                const image = col.getAttribute('data-image');
                
                // Get selected size from dropdown if it exists
                const sizeSelect = col.querySelector('.size-select');
                let selectedSize = null;
                let price = basePrice;
                
                if (sizeSelect) {
                    const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                    selectedSize = selectedOption.value;
                    const sizePriceAttr = selectedOption.getAttribute('data-price');
                    if (sizePriceAttr) {
                        price = parseFloat(sizePriceAttr);
                    }
                }
                
                selectedProducts.push({ 
                    id: productId, 
                    name, 
                    price: price, 
                    originalPrice: originalPrice, 
                    image, 
                    size: selectedSize 
                });
                saveState();
                updateUI();
            }
        }
    }

    function changeProductSize(productId, selectEl) {
        productId = parseInt(productId);
        const selectedOption = selectEl.options[selectEl.selectedIndex];
        const sizeName = selectedOption.value;
        const sizePriceAttr = selectedOption.getAttribute('data-price');
        
        const col = document.querySelector(`.product-item-col[data-id="${productId}"]`);
        if (!col) return;
        
        const basePrice = parseFloat(col.getAttribute('data-base-price'));
        let sizePrice = sizePriceAttr ? parseFloat(sizePriceAttr) : null;
        const displayPrice = sizePrice !== null && sizePrice > 0 ? sizePrice : basePrice;
        
        // Update price display on card
        const priceContainer = document.querySelector(`.price-container-${productId}`);
        if (priceContainer) {
            priceContainer.innerHTML = `<span class="text-dark fw-bold fs-5">₹${displayPrice.toFixed(2)}</span>`;
        }

        // If the product is already in the combo, update it in selectedProducts
        const index = selectedProducts.findIndex(p => p.id === productId);
        if (index > -1) {
            selectedProducts[index].size = sizeName;
            selectedProducts[index].price = displayPrice;
            selectedProducts[index].originalPrice = displayPrice;
            saveState();
            updateUI();
        }
    }

    function removeProduct(productId) {
        toggleProduct(productId);
    }

    function updateUI() {
        // Update product list col cards button state
        document.querySelectorAll('.product-item-col').forEach(col => {
            const id = parseInt(col.getAttribute('data-id'));
            const isSelected = selectedProducts.some(p => p.id === id);
            const btn = col.querySelector('.toggle-product-btn');

            if (isSelected) {
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success');
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Added';
            } else {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
                btn.innerHTML = '<i class="fas fa-plus me-1"></i> Add to Combo';
            }
        });

        // Update progress bar
        const progressCount = selectedProducts.length;
        const progressPercent = (progressCount / maxProducts) * 100;
        document.getElementById('progressText').textContent = `${progressCount} / ${maxProducts}`;
        
        const progressBar = document.getElementById('progressBar');
        progressBar.style.width = `${progressPercent}%`;
        progressBar.setAttribute('aria-valuenow', progressCount);

        // Update selected items list
        const selectedList = document.getElementById('selectedProductsList');
        const emptyPlaceholder = document.getElementById('emptyPlaceholder');

        // Clear list
        selectedList.innerHTML = '';

        if (selectedProducts.length === 0) {
            selectedList.appendChild(emptyPlaceholder);
        } else {
            selectedProducts.forEach(p => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'd-flex align-items-center justify-content-between p-2 rounded border bg-light';
                
                let priceHtml = '';
                if (p.originalPrice && p.originalPrice > p.price) {
                    priceHtml = `<del class="text-muted small me-2">₹${p.originalPrice.toFixed(2)}</del><span class="text-success fw-bold small">₹${p.price.toFixed(2)}</span>`;
                } else {
                    priceHtml = `<span class="text-muted small">₹${p.price.toFixed(2)}</span>`;
                }

                const sizeBadgeHtml = p.size ? `<span class="badge bg-light text-dark border ms-1" style="font-size: 10px; font-weight: 500;">Size: ${p.size}</span>` : '';

                itemDiv.innerHTML = `
                    <div class="d-flex align-items-center gap-2 overflow-hidden" style="flex: 1;">
                        <img src="${p.image}" class="img-thumbnail rounded" style="width: 40px; height: 40px; object-fit: contain; background: white;" alt="">
                        <div class="text-truncate">
                            <span class="d-block text-dark fw-bold small text-truncate">${p.name} ${sizeBadgeHtml}</span>
                            ${priceHtml}
                        </div>
                    </div>
                    <button type="button" class="btn btn-link text-danger btn-sm p-0 ms-2" onclick="removeProduct(${p.id})">
                        <i class="fas fa-times-circle fs-5"></i>
                    </button>
                `;
                selectedList.appendChild(itemDiv);
            });
        }

        // Calculate Pricing
        const subtotal = selectedProducts.reduce((sum, p) => sum + p.price, 0);
        const originalSubtotal = selectedProducts.reduce((sum, p) => sum + (p.originalPrice || p.price), 0);
        
        const subtotalPriceEl = document.getElementById('subtotalPrice');
        if (originalSubtotal > subtotal) {
            subtotalPriceEl.innerHTML = `<del class="text-muted small me-2" style="font-size: 0.85em;">₹${originalSubtotal.toFixed(2)}</del><span class="text-dark">₹${subtotal.toFixed(2)}</span>`;
        } else {
            subtotalPriceEl.textContent = `₹${subtotal.toFixed(2)}`;
        }

        // Find applicable slab
        let applicableDiscountPercent = 0;
        let nextSlab = null;

        // Sort slabs by min_amount ascending
        const sortedSlabs = [...discountSlabs].sort((a, b) => a.min_amount - b.min_amount);
        
        // Find highest matching slab
        sortedSlabs.forEach(slab => {
            if (subtotal >= slab.min_amount) {
                applicableDiscountPercent = slab.discount_percentage;
            } else if (!nextSlab) {
                nextSlab = slab;
            }
        });

        // Update slab indicators
        document.querySelectorAll('.slab-indicator-item').forEach(item => {
            const min = parseFloat(item.getAttribute('data-min'));
            const percent = parseInt(item.getAttribute('data-percent'));
            const badge = item.querySelector('.slab-badge');

            if (subtotal >= min && percent === applicableDiscountPercent) {
                item.classList.add('bg-success-subtle');
                badge.classList.remove('bg-secondary');
                badge.classList.add('bg-success');
            } else {
                item.classList.remove('bg-success-subtle');
                badge.classList.remove('bg-success');
                badge.classList.add('bg-secondary');
            }
        });

        // Apply discount
        const discountAmount = subtotal * (applicableDiscountPercent / 100);
        const total = subtotal - discountAmount;

        const discountRow = document.getElementById('discountRow');
        if (applicableDiscountPercent > 0) {
            discountRow.classList.remove('d-none');
            document.getElementById('discountPercent').textContent = applicableDiscountPercent;
            document.getElementById('discountAmount').textContent = discountAmount.toFixed(2);
        } else {
            discountRow.classList.add('d-none');
        }

        const totalPriceEl = document.getElementById('totalPrice');
        if (originalSubtotal > total) {
            totalPriceEl.innerHTML = `<del class="text-muted small me-2" style="font-size: 0.6em; vertical-align: middle;">₹${originalSubtotal.toFixed(2)}</del><span class="text-success">₹${total.toFixed(2)}</span>`;
        } else {
            totalPriceEl.textContent = `₹${total.toFixed(2)}`;
        }

        // Next slab notice
        const slabNotice = document.getElementById('slabNotice');
        if (nextSlab) {
            const remaining = nextSlab.min_amount - subtotal;
            slabNotice.innerHTML = `<i class="fas fa-info-circle me-1 text-info"></i> Add <strong>₹${remaining.toFixed(2)}</strong> more to get a <strong>${nextSlab.discount_percentage}%</strong> discount!`;
            slabNotice.classList.remove('d-none');
        } else if (applicableDiscountPercent > 0) {
            slabNotice.innerHTML = `<i class="fas fa-check-circle me-1 text-success"></i> Maximum discount level of <strong>${applicableDiscountPercent}%</strong> achieved!`;
            slabNotice.classList.remove('d-none');
        } else {
            slabNotice.classList.add('d-none');
        }

        // Update form submission and checkout buttons
        const checkoutBtn = document.getElementById('checkoutBtn');
        const isEligibleForCart = selectedProducts.length >= 2 && selectedProducts.length <= maxProducts;
        
        checkoutBtn.disabled = !isEligibleForCart;

        // Hidden input fields update
        const hiddenContainer = document.getElementById('hiddenFieldsContainer');
        hiddenContainer.innerHTML = '';
        if (isEligibleForCart) {
            selectedProducts.forEach(p => {
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'product_ids[]';
                inputId.value = p.id;
                hiddenContainer.appendChild(inputId);

                const inputSize = document.createElement('input');
                inputSize.type = 'hidden';
                inputSize.name = 'product_sizes[]';
                inputSize.value = p.size || '';
                hiddenContainer.appendChild(inputSize);
            });
            if (editingComboId) {
                const editInput = document.createElement('input');
                editInput.type = 'hidden';
                editInput.name = 'edit_combo_id';
                editInput.value = editingComboId;
                hiddenContainer.appendChild(editInput);
            }
        }
    }

    // Real-time search and filter
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('builderSearch');
        const categorySelect = document.getElementById('builderCategory');

        function filterProducts() {
            const query = searchInput.value.toLowerCase().trim();
            const categoryId = categorySelect.value;

            document.querySelectorAll('.product-item-col').forEach(col => {
                const name = col.getAttribute('data-name').toLowerCase();
                const prodCat = col.getAttribute('data-category');

                const matchesQuery = name.includes(query);
                const matchesCategory = !categoryId || prodCat === categoryId;

                if (matchesQuery && matchesCategory) {
                    col.style.setProperty('display', 'block', 'important');
                } else {
                    col.style.setProperty('display', 'none', 'important');
                }
            });
        }

        if (searchInput) searchInput.addEventListener('input', filterProducts);
        if (categorySelect) categorySelect.addEventListener('change', filterProducts);

        // Form submit listener to clear sessionStorage
        const form = document.getElementById('customComboForm');
        if (form) {
            form.addEventListener('submit', () => {
                sessionStorage.removeItem('selected_combo_products');
                sessionStorage.removeItem('editing_combo_id');
            });
        }

        // Hydration Logic
        if (editComboId && customCombos[editComboId]) {
            selectedProducts = JSON.parse(JSON.stringify(customCombos[editComboId]));
            editingComboId = editComboId;
            saveState();
        } else {
            const savedEditingId = sessionStorage.getItem('editing_combo_id');
            const savedProductsStr = sessionStorage.getItem('selected_combo_products');

            if (savedEditingId && customCombos[savedEditingId]) {
                editingComboId = savedEditingId;
                if (savedProductsStr) {
                    selectedProducts = JSON.parse(savedProductsStr);
                }
            } else {
                editingComboId = null;
                if (savedProductsStr && !savedEditingId) {
                    selectedProducts = JSON.parse(savedProductsStr);
                }
            }
        }

        // Set size dropdown values and prices based on loaded selectedProducts
        selectedProducts.forEach(p => {
            if (p.size) {
                const sizeSelect = document.querySelector(`.size-select[data-product-id="${p.id}"]`);
                if (sizeSelect) {
                    sizeSelect.value = p.size;
                    const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                    const sizePriceAttr = selectedOption.getAttribute('data-price');
                    if (sizePriceAttr) {
                        const sizePrice = parseFloat(sizePriceAttr);
                        p.price = sizePrice;
                        p.originalPrice = sizePrice;
                    }
                    
                    // Also update card price display
                    const priceContainer = document.querySelector(`.price-container-${p.id}`);
                    if (priceContainer) {
                        priceContainer.innerHTML = `<span class="text-dark fw-bold fs-5">₹${p.price.toFixed(2)}</span>`;
                    }
                }
            }
        });

        // Check for add_product query parameter from home page
        const urlParams = new URLSearchParams(window.location.search);
        const addProductId = urlParams.get('add_product');
        if (addProductId) {
            const addId = parseInt(addProductId);
            const exists = selectedProducts.some(p => p.id === addId);
            if (!exists) {
                // Find product metadata from DOM attributes
                const col = document.querySelector(`.product-item-col[data-id="${addId}"]`);
                if (col) {
                    const name = col.getAttribute('data-name');
                    const basePrice = parseFloat(col.getAttribute('data-base-price'));
                    const originalPrice = parseFloat(col.getAttribute('data-original-price')) || basePrice;
                    const image = col.getAttribute('data-image');
                    
                    // Get selected size from dropdown if it exists
                    const sizeSelect = col.querySelector('.size-select');
                    let selectedSize = null;
                    let price = basePrice;
                    
                    if (sizeSelect) {
                        const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                        selectedSize = selectedOption.value;
                        const sizePriceAttr = selectedOption.getAttribute('data-price');
                        if (sizePriceAttr) {
                            price = parseFloat(sizePriceAttr);
                        }
                    }

                    selectedProducts.push({ 
                        id: addId, 
                        name, 
                        price: price, 
                        originalPrice: originalPrice, 
                        image, 
                        size: selectedSize 
                    });
                    saveState();
                }
            }
            // Clean up the URL query parameter to avoid adding it again on refresh
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }

        updateUI();
    });
</script>

<style>
    /* Styling for the builder */
    .border-dashed {
        border-style: dashed !important;
    }
    .bg-success-subtle {
        background-color: #d1e7dd !important;
        border-radius: 4px;
        padding-left: 5px;
        padding-right: 5px;
    }
    .product-builder-card {
        transition: all 0.3s ease;
        border: 1px solid #eef2f6 !important;
    }
    .product-builder-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .style-search-group .form-control:focus {
        border-color: #ced4da;
        box-shadow: none;
    }
</style>

@include('view.layout.footer')
