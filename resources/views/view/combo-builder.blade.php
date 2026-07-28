@include('view.layout.header')



<section class="combo-py-3">
    <div class="combo-layout-container">

        <div class="combo-layout-row">
            <!-- Left Column: Products Grid & Search -->
            <div class="combo-main-col combo-mb-4">
                <!-- Search & Filters -->
                <div class="combo-filter-box builder-filter-wrapper">
                    <div class="combo-input-group combo-search-group">
                        <span class="combo-input-icon"><i class="fas fa-search combo-text-muted"></i></span>
                        <input type="text" id="builderSearch" class="combo-text-input" placeholder="Search products...">
                    </div>
                    <div class="combo-filter-select-wrap builder-filter-select-container">
                        <span class="combo-text-muted combo-small combo-filter-label">Filter:</span>
                        <select id="builderCategory" class="combo-select-input">
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
                <div class="combo-product-grid" id="productsGrid">
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

                            $price = $product->sale_price > 0 && $product->sale_price < $product->price ? $product->sale_price : $product->price;
                            $hasDiscount = $product->sale_price > 0 && $product->sale_price < $product->price;
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
                        <div class="product-item-col" 
                             data-id="{{ $product->id }}" 
                             data-name="{{ $product->name }}" 
                             data-base-price="{{ $price }}" 
                             data-original-price="{{ $product->price }}"
                             data-image="{{ $image }}"
                             data-category="{{ $product->category_id }}">
                            
                            <div class="combo-card-pure {{ $product->stock_quantity <= 0 ? 'out-of-stock' : '' }} combo-product-card">
                                <div class="combo-img-container combo-mb-3" style="position:relative;">
                                    <img src="{{ $image }}" class="combo-img combo-w-100" alt="{{ $product->name }}" loading="lazy">
                                    
                                    @if($hasDiscount)
                                        <div class="product-discount-badge">{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF</div>
                                    @endif

                                    @if($product->stock_quantity <= 0)
                                        <div class="out-of-stock-overlay combo-oos-overlay">
                                            <span class="combo-badge combo-badge-secondary">Out Of Stock</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="product-info combo-flex-1">
                                    <div class="combo-card-header">
                                        <h3 class="combo-card-title">
                                            {{ $product->name }}
                                        </h3>
                                        
                                        @if(count($sizeData) > 0)
                                            <div>
                                                <select class="combo-select-input combo-size-select" data-product-id="{{ $product->id }}" onchange="changeProductSize({{ $product->id }}, this)">
                                                    @foreach($sizeData as $sizeName => $sizeValue)
                                                        @php
                                                            $sizePrice = is_array($sizeValue) ? ($sizeValue['price'] ?? null) : $sizeValue;
                                                        @endphp
                                                        <option value="{{ $sizeName }}" data-price="{{ $sizePrice ?? '' }}">
                                                            {{ $sizeName }} @if($sizePrice) (₹{{ number_format($sizePrice, 0) }}) @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-price price-container-{{ $product->id }}">
                                        @if($hasDiscount)
                                            <span class="original-price combo-text-muted combo-text-strike">₹{{ number_format($product->price, 0) }}</span>
                                            <span class="current-price combo-fw-bold combo-ms-2">₹{{ number_format($product->sale_price, 0) }}</span>
                                        @else
                                            <span class="current-price combo-fw-bold">₹{{ number_format($price, 0) }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="combo-card-actions">
                                        @auth
                                        <button type="button" class="combo-btn combo-btn-block combo-btn-pill toggle-product-btn combo-fw-bold combo-btn-add" onclick="toggleProduct({{ $product->id }})">
                                            <i class="fas fa-plus-circle combo-me-1"></i> Add to Combo
                                        </button>
                                        @else
                                        <a href="{{ route('login') }}" class="combo-btn combo-btn-block combo-btn-pill combo-fw-bold combo-btn-add combo-flex-center">
                                            <i class="fas fa-sign-in-alt combo-me-1"></i> Login to Add
                                        </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Sticky Summary Panel -->
            <div class="combo-sidebar-col summary-panel-wrapper">
                <div class="combo-card-pure combo-sticky-panel summary-panel">
                    <div class="combo-card-header combo-border-0 combo-pb-0">
                        <h4 class="combo-card-title combo-d-flex combo-align-center"><i class="fas fa-box-open combo-text-success combo-me-2"></i> Combo Pack Summary</h4>
                    </div>
                    <div class="combo-p-3 combo-flex-col-grow">
                        <!-- Progress / Limits -->
                        <div class="combo-mb-4">
                            <div class="combo-d-flex combo-justify-between combo-align-center combo-mb-2">
                                <span class="combo-fw-bold combo-text-dark">Selection Progress</span>
                                <span class="combo-badge combo-badge-progress combo-fs-6" id="progressText">0 / {{ $settings->max_products }}</span>
                            </div>
                            <div class="combo-progress-wrapper combo-progress-bg">
                                <div class="combo-progress-fill combo-progress-striped" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{ $settings->max_products }}"></div>
                            </div>
                            <small class="combo-text-muted combo-d-block combo-mt-2">Add between 2 and {{ $settings->max_products }} products to complete the combo.</small>
                        </div>

                        <!-- Selected Items List -->
                        <div class="combo-mb-4">
                            <div class="combo-d-flex combo-justify-between combo-align-center combo-border-bottom combo-pb-2 combo-mb-3">
                                <h5 class="combo-fw-bold combo-text-dark combo-mb-0">Selected Products</h5>
                                <button type="button" class="combo-btn-link combo-btn-danger-link combo-clear-btn combo-btn-bare" id="clearAllComboBtn" onclick="clearComboBuilder()">Clear All</button>
                            </div>
                            <div id="selectedProductsList" class="combo-d-flex combo-flex-column combo-gap-2 combo-selected-list">
                                <div class="combo-text-center combo-py-3 combo-text-muted" id="emptyPlaceholder">
                                    <i class="fas fa-shopping-basket fa-2x combo-mb-2 combo-text-muted combo-opacity-50"></i>
                                    <p class="combo-mb-0 combo-small">No products selected yet.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Discount slabs visual tracker -->
                        @if($slabs->count() > 0)
                            <div class="combo-mb-4 combo-p-3 combo-bg-white combo-rounded-3 combo-shadow-sm combo-border combo-slab-container">
                                <h6 class="combo-fw-bold combo-text-dark combo-mb-3"><i class="fas fa-percentage combo-text-warning-me-2"></i>Discount Slabs</h6>
                                <ul class="combo-list-unstyled combo-mb-0 combo-d-flex combo-flex-column">
                                    @foreach($slabs as $slab)
                                        <li class="combo-d-flex combo-justify-between combo-align-center combo-py-2 combo-border-bottom slab-indicator-item combo-slab-item" data-min="{{ $slab->min_amount }}" data-percent="{{ $slab->discount_percentage }}">
                                            <span class="combo-text-muted combo-small combo-fw-semibold slab-text combo-slab-text">Above ₹{{ number_format($slab->min_amount, 2) }}</span>
                                            <span class="combo-badge combo-badge-secondary combo-badge-pill slab-badge combo-slab-badge">{{ floatval($slab->discount_percentage) }}% Off</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Pricing Breakdown -->
                        <div class="combo-border-top combo-pt-3 combo-mb-4 combo-mt-auto">
                            <div class="combo-d-flex combo-justify-between combo-align-center combo-mb-2">
                                <span class="combo-text-muted combo-fw-semibold combo-subtotal-text">Subtotal (Products)</span>
                                <span class="combo-fw-bold combo-text-dark" id="subtotalPrice">₹0.00</span>
                            </div>
                            <div class="combo-d-flex combo-justify-between combo-align-center combo-mb-3 combo-text-success combo-hidden" id="discountRow">
                                <span class="combo-fw-semibold combo-subtotal-text">Combo Discount (<span id="discountPercent">0</span>%)</span>
                                <span class="combo-fw-bold">-₹<span id="discountAmount">0.00</span></span>
                            </div>
                            <div class="combo-d-flex combo-justify-between combo-align-center combo-pt-3 combo-mt-2 combo-total-box">
                                <span class="combo-fw-bold combo-text-dark combo-fs-5">Total Combo Price</span>
                                <span class="combo-fw-bold combo-fs-4 combo-total-price" id="totalPrice">₹0.00</span>
                            </div>
                            <div class="combo-alert combo-mt-3 combo-p-3 combo-small combo-border-0 combo-mb-0 combo-d-none combo-rounded-3 combo-slab-notice" id="slabNotice">
                                <!-- slab progress notice will be updated here -->
                            </div>
                        </div>

                        <!-- Action Button & Add Form -->
                        @auth
                        <form action="{{ route('cart.add_custom_combo') }}" method="POST" id="customComboForm" class="combo-mt-2">
                            @csrf
                            <div id="hiddenFieldsContainer"></div>
                            <button type="submit" class="combo-btn combo-btn-block combo-btn-add combo-fw-bold" id="checkoutBtn" disabled>
                                Add Combo To Cart
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="combo-btn combo-btn-block combo-btn-add combo-fw-bold combo-flex-center combo-mt-2">
                            <i class="fas fa-sign-in-alt combo-me-2"></i> Login to Add Combo To Cart
                        </a>
                        @endauth
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

    function clearComboBuilder() {
        sessionStorage.removeItem('selected_combo_products');
        sessionStorage.removeItem('editing_combo_id');
        selectedProducts = [];
        editingComboId = null;
        updateUI();
    }

    window.clearComboBuilder = clearComboBuilder;

    function removeProduct(productId) {
        toggleProduct(productId);
    }

    function updateUI() {
        const clearAllBtn = document.getElementById('clearAllComboBtn');
        if (clearAllBtn) {
            clearAllBtn.style.display = selectedProducts.length > 0 ? 'inline-block' : 'none';
        }

        // Update product list col cards button state
        document.querySelectorAll('.product-item-col').forEach(col => {
            const id = parseInt(col.getAttribute('data-id'));
            const isSelected = selectedProducts.some(p => p.id === id);
            const btn = col.querySelector('.toggle-product-btn');
            
            if (btn) {
                if (isSelected) {
                    btn.className = 'combo-btn combo-btn-block combo-btn-pill toggle-product-btn combo-fw-bold combo-btn-added';
                    btn.removeAttribute('style');
                    btn.innerHTML = '<i class="fas fa-check-circle combo-me-1"></i> Added';
                } else {
                    btn.className = 'combo-btn combo-btn-block combo-btn-pill toggle-product-btn combo-fw-bold combo-btn-add';
                    btn.removeAttribute('style');
                    btn.innerHTML = '<i class="fas fa-plus-circle combo-me-1"></i> Add to Combo';
                }
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

        // Clear list
        selectedList.innerHTML = '';

        if (selectedProducts.length === 0) {
            selectedList.innerHTML = `
                <div class="text-center py-4 text-muted" id="emptyPlaceholder">
                    <i class="fas fa-shopping-basket fa-2x mb-2 text-muted opacity-50"></i>
                    <p class="mb-0 small">No products selected yet.</p>
                </div>
            `;
        } else {
            selectedProducts.forEach(p => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'combo-d-flex combo-align-center combo-justify-between combo-p-2 combo-mb-2 combo-border combo-rounded combo-bg-light';
                
                let priceHtml = '';
                if (p.originalPrice && p.originalPrice > p.price) {
                    priceHtml = `<del class="combo-text-muted combo-small combo-sel-del">₹${p.originalPrice.toFixed(2)}</del><span class="combo-text-success combo-fw-bold combo-small">₹${p.price.toFixed(2)}</span>`;
                } else {
                    priceHtml = `<span class="combo-text-muted combo-small">₹${p.price.toFixed(2)}</span>`;
                }

                const sizeBadgeHtml = p.size ? `<span class="combo-badge combo-badge-light combo-border combo-ms-2 combo-sel-badge">Size: ${p.size}</span>` : '';

                itemDiv.innerHTML = `
                    <div class="combo-d-flex combo-align-center combo-gap-2 combo-overflow-hidden combo-sel-item">
                        <img src="${p.image}" class="combo-rounded combo-border combo-sel-img" alt="">
                        <div class="combo-d-flex combo-flex-column combo-justify-center">
                            <span class="combo-d-block combo-text-dark combo-fw-bold combo-sel-title">${p.name} ${sizeBadgeHtml}</span>
                            <div>${priceHtml}</div>
                        </div>
                    </div>
                    <button type="button" class="combo-btn-link combo-btn-danger-link combo-btn-bare combo-ms-2" onclick="removeProduct(${p.id})">
                        <i class="fas fa-times-circle combo-fs-5"></i>
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
            subtotalPriceEl.innerHTML = `<del class="combo-text-muted combo-small combo-me-2 combo-del-sm">₹${originalSubtotal.toFixed(2)}</del><span class="combo-text-dark">₹${subtotal.toFixed(2)}</span>`;
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
            const textSpan = item.querySelector('.slab-text');

            if (subtotal >= min && percent === applicableDiscountPercent) {
                // Active Premium Styling
                item.classList.add('combo-slab-active');
                textSpan.classList.remove('combo-text-muted');
                textSpan.classList.add('combo-text-success');
                badge.classList.remove('combo-badge-secondary');
                badge.classList.add('combo-badge-success');
            } else {
                // Default Styling
                item.classList.remove('combo-slab-active');
                textSpan.classList.remove('combo-text-success');
                textSpan.classList.add('combo-text-muted');
                badge.classList.remove('combo-badge-success');
                badge.classList.add('combo-badge-secondary');
            }
        });

        // Apply discount
        const discountAmount = subtotal * (applicableDiscountPercent / 100);
        const total = subtotal - discountAmount;

        const discountRow = document.getElementById('discountRow');
        if (applicableDiscountPercent > 0) {
            discountRow.classList.remove('combo-hidden');
            document.getElementById('discountPercent').textContent = applicableDiscountPercent;
            document.getElementById('discountAmount').textContent = discountAmount.toFixed(2);
        } else {
            discountRow.classList.add('combo-hidden');
        }

        const totalPriceEl = document.getElementById('totalPrice');
        if (originalSubtotal > total) {
            totalPriceEl.innerHTML = `<del class="combo-text-muted combo-small combo-me-2">₹${originalSubtotal.toFixed(2)}</del><span class="combo-text-success">₹${total.toFixed(2)}</span>`;
        } else {
            totalPriceEl.textContent = `₹${total.toFixed(2)}`;
        }

        // Next slab notice
        const slabNotice = document.getElementById('slabNotice');
        if (nextSlab) {
            const remaining = nextSlab.min_amount - subtotal;
            slabNotice.innerHTML = `<i class="fas fa-info-circle combo-me-1 combo-icon-green"></i> Add <strong>₹${remaining.toFixed(2)}</strong> more to get a <strong>${nextSlab.discount_percentage}%</strong> discount!`;
            slabNotice.classList.remove('combo-hidden');
        } else if (applicableDiscountPercent > 0) {
            slabNotice.innerHTML = `<i class="fas fa-check-circle combo-me-1 combo-icon-green"></i> Maximum discount level of <strong>${applicableDiscountPercent}%</strong> achieved!`;
            slabNotice.classList.remove('combo-hidden');
        } else {
            slabNotice.classList.add('combo-hidden');
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

        // Form submit listener to submit via AJAX and open side cart drawer
        const form = document.getElementById('customComboForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const checkoutBtn = document.getElementById('checkoutBtn');
                const originalText = checkoutBtn.innerHTML;
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding to Cart...';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token') || ''
                    },
                    body: formData
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => { throw new Error(err.message || 'Failed to add custom combo pack.') });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.custom_combo_id) {
                        editingComboId = data.custom_combo_id;
                        sessionStorage.setItem('editing_combo_id', editingComboId);
                    }
                    updateUI();

                    if (typeof window.updateCartCountBadges === 'function') {
                        window.updateCartCountBadges(data.cart_count);
                    }
                    if (typeof window.refreshCartDrawer === 'function') {
                        window.refreshCartDrawer();
                    }
                    if (typeof window.openCartDrawer === 'function') {
                        window.openCartDrawer();
                    }
                })
                .catch(err => {
                    console.error('Error adding custom combo:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.message || 'Something went wrong.',
                        confirmButtonColor: '#198754'
                    });
                })
                .finally(() => {
                    checkoutBtn.innerHTML = originalText;
                    checkoutBtn.disabled = false;
                });
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



@include('view.layout.footer')
