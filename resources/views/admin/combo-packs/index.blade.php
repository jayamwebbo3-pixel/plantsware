@extends('admin.layout')

@section('title', 'Combo Packs Management')

@section('content')
    <div class="container-fluid">
    <!-- Alerts removed as they are in layout.blade.php -->

    <!-- Action Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Combo Packs</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <!-- <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active">Combo Packs</li> -->
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success fw-bold" onclick="showForm('combo_only')">
                    <i class="fas fa-plus me-1"></i> Add Combo Only
                </button>
                <button class="btn btn-primary fw-bold" onclick="showForm('standard')">
                    <i class="fas fa-layer-group me-1"></i> Add Standard Combo
                </button>
            </div>
        </div>

        <!-- Search and List Section -->
        <div id="list-section">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('admin.combo-packs.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Search combos by name..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Filter List</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Preview</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Pricing</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comboPacks as $combo)
                                    <tr>
                                        <td class="ps-4">
                                            @if($combo->is_combo_only)
                                                <img src="{{ asset('storage/' . $combo->image) }}" class="rounded border" width="50" height="50" style="object-fit: cover;">
                                            @else
                                                @php $imgs = json_decode($combo->image) @endphp
                                                <div class="composite-preview">
                                                    @foreach(array_slice($imgs ?? [], 0, 2) as $img)
                                                        <img src="{{ asset('storage/' . $img) }}" class="rounded shadow-xs border composite-img" style="z-index: {{ 2 - $loop->index }}; left: {{ $loop->index * 15 }}px;">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $combo->name }}</div>
                                            <small class="text-muted">{{ $combo->slug }}</small>
                                        </td>
                                        <td>
                                            @php 
                                                                                                                                                                                                                                                                                                                        $catIds = is_array($combo->category_id) ? $combo->category_id : ($combo->category_id ? [$combo->category_id] : []);
                                                $names = $allCategories->whereIn('id', $catIds)->pluck('name')->implode(', ');
                                            @endphp
                                            @if($names)
                                                <span class="badge bg-light text-dark border" title="{{ $names }}">{{ Str::limit($names, 25) }}</span>
                                            @else
                                                <span class="text-muted small">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-success fw-bold">₹{{ number_format($combo->offer_price, 2) }}</div>
                                            <del class="text-muted x-small">₹{{ number_format($combo->total_price, 2) }}</del>
                                        </td>
                                        <td>
                                            @if($combo->is_combo_only)
                                                <span class="badge bg-success-subtle text-success border border-success">Combo Only</span>
                                            @else
                                                <span class="badge bg-primary-subtle text-primary border border-primary">Standard</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch p-0 m-0">
                                                <input class="form-check-input ms-0 status-toggle" type="checkbox" data-id="{{ $combo->id }}" {{ $combo->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-primary border-0" onclick="editCombo({{ $combo->id }})"><i class="fas fa-edit"></i></button>
                                            <form action="{{ route('admin.combo-packs.destroy', $combo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this combo?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">No combo packs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Creation Section (Hidden by Default) -->
        <div id="form-section" style="display: none;">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0" id="form-title">Create Combo</h5>
                    <button class="btn btn-sm btn-light border" onclick="hideForm()"><i class="fas fa-times"></i></button>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.combo-packs.store') }}" method="POST" enctype="multipart/form-data" id="comboForm">
                        @csrf
                        <div id="method_field"></div>
                        <input type="hidden" name="action_type" id="action_type">

                        <div class="row g-4">
                            <div class="col-lg-5 mx-auto" id="left-form-col">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter name">
                                </div>

                                <!-- Categorization (Only for Standard) -->
                                <div id="categorization_block" class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Category</label>
                                        <select name="category_id" id="category_id" class="form-select">
                                            <option value="">Select Category</option>
                                            @foreach($allCategories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Subcategory</label>
                                        <select name="subcategory_id" id="subcategory_id" class="form-select">
                                            <option value="">Select Subcategory</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" id="total_price_label">Total Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">₹</span>
                                            <input type="number" id="total_price" name="total_price" class="form-control" required value="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Offer Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">₹</span>
                                            <input type="number" name="offer_price" class="form-control" required placeholder="0.00" step="0.01">
                                        </div>
                                    </div>
                                </div>

                                <div id="image_upload_section" class="mb-3">
                                    <label class="form-label fw-bold">Upload Image</label>
                                    <div id="current-image-container" class="mb-2" style="display: none;">
                                        <div class="d-flex align-items-center gap-2 border rounded p-2 bg-light">
                                            <img id="current-image-display" src="" class="rounded border shadow-sm" width="60" height="60" style="object-fit: cover;">
                                            <span class="small text-muted">Currently saved image</span>
                                        </div>
                                    </div>
                                    <input type="file" name="image" id="image_input" class="form-control" accept="image/*">
                                </div>

                                <div class="accordion mb-4" id="advancedDetailsAccordion">
                                    <div class="accordion-item border-0 shadow-sm rounded">
                                        <h2 class="accordion-header" id="headingAdvanced">
                                            <button class="accordion-button collapsed rounded fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdvanced" aria-expanded="false" aria-controls="collapseAdvanced">
                                                Advanced Product Details
                                            </button>
                                        </h2>
                                        <div id="collapseAdvanced" class="accordion-collapse collapse" aria-labelledby="headingAdvanced">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">SKU</label>
                                                        <input type="text" name="sku" class="form-control" placeholder="Unique identifier">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Stock Quantity</label>
                                                        <input type="number" name="stock_quantity" class="form-control" value="0" min="0">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold">Short Description</label>
                                                        <textarea name="short_description" class="form-control" rows="2" placeholder="Brief summary..."></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Meta Title</label>
                                                        <input type="text" name="meta_title" class="form-control" placeholder="SEO Title">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Meta Keywords</label>
                                                        <input type="text" name="meta_keywords" class="form-control" placeholder="comma, separated, keywords">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Meta Description</label>
                                                        <textarea name="meta_description" class="form-control" rows="2" placeholder="SEO Description..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-2 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> SAVE COMBO
                                </button>
                            </div>

                        <div class="col-lg-7" id="right-selection-col">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold mb-0">Selection List</label>
                                    <input type="text" id="item_search" class="form-control form-control-sm w-50" placeholder="Search items...">
                                </div>
                                <div id="item-selection-grid" class="border rounded p-3 bg-light overflow-auto" style="height: 400px;">
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-info-circle mb-2 fa-2x"></i>
                                        <p id="selection-hint">Please select category to load items.</p>
                                    </div>
                                </div>
                                <div class="mt-2 text-end">
                                    <span class="badge bg-dark rounded-pill" id="selected_count">0 items selected</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .composite-preview { position: relative; width: 65px; height: 50px; }
            .composite-img { width: 45px; height: 45px; object-fit: cover; position: absolute; border: 2px solid #fff; }
            .item-box { transition: 0.2s; border: 1px solid #dee2e6; border-radius: 8px; cursor: pointer; }
            .item-box:hover { background: #f8f9fa; border-color: #0d6efd; }
            .item-box.selected { border-color: #0d6efd; background: #e7f1ff; }
            .x-small { font-size: 0.75rem; }
        </style>
    @endpush

    @push('scripts')
        <script>
            let selectedProductIds = new Set();
            let productPrices = {}; // ID -> Price mapping for calculation

            function showForm(type) {
                document.getElementById('list-section').style.display = 'none';
                document.getElementById('form-section').style.display = 'block';
                document.getElementById('action_type').value = type;

                const catBlock = document.getElementById('categorization_block');
                const totalPriceField = document.getElementById('total_price');
                const totalPriceLabel = document.getElementById('total_price_label');
                const uploadSection = document.getElementById('image_upload_section');
                const imgInput = document.getElementById('image_input');
                const leftCol = document.getElementById('left-form-col');
                const rightCol = document.getElementById('right-selection-col');
                const advancedDetails = document.getElementById('advancedDetailsAccordion');

                if (type === 'combo_only') {
                    document.getElementById('form-title').innerHTML = '<i class="fas fa-star text-success me-1"></i> Create Combo Only product';
                    catBlock.style.display = 'none';
                    totalPriceField.removeAttribute('readonly');
                    totalPriceField.classList.remove('bg-light');
                    totalPriceLabel.innerText = "Total Price (Editable)";
                    uploadSection.style.display = 'block';
                    imgInput.setAttribute('required', 'required');

                    // Simplified UI: Single column
                    leftCol.classList.replace('col-lg-5', 'col-lg-8');
                    rightCol.style.display = 'none';
                    advancedDetails.style.display = 'block';
                } else {
                    document.getElementById('form-title').innerHTML = '<i class="fas fa-layer-group text-primary me-1"></i> Create Standard Combo bundle';
                    catBlock.style.display = 'flex';
                    totalPriceField.setAttribute('readonly', 'readonly');
                    totalPriceField.classList.add('bg-light');
                    totalPriceLabel.innerText = "Total Price (Calculated)";
                    uploadSection.style.display = 'none';
                    imgInput.removeAttribute('required');

                    // Revert UI: Two columns
                    leftCol.classList.replace('col-lg-8', 'col-lg-5');
                    rightCol.style.display = 'block';
                    advancedDetails.style.display = 'none';
                    document.getElementById('item-selection-grid').innerHTML = `
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-search-plus fa-2x mb-3"></i>
                            <p>Select category or use search to find products across all categories.</p>
                            <button type="button" class="btn btn-sm btn-outline-dark mt-2" onclick="fetchFilteredItems(true)">Load All products</button>
                        </div>`;
                }
            }

            function hideForm() {
                document.getElementById('form-section').style.display = 'none';
                document.getElementById('list-section').style.display = 'block';
                document.getElementById('comboForm').reset();
                document.getElementById('comboForm').action = "{{ route('admin.combo-packs.store') }}";
                document.getElementById('method_field').innerHTML = '';
                document.getElementById('form-title').innerText = 'Create Combo';
                document.getElementById('current-image-container').style.display = 'none';
                document.getElementById('current-image-display').src = '';

                selectedProductIds.clear();
                productPrices = {};
                syncSelectedToInputs();
                updateSummary();
            }

            function editCombo(id) {
                fetch(`{{ url('admin/combo-packs') }}/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        const combo = data.combo;
                        const prices = data.item_prices;

                        showForm(combo.is_combo_only ? 'combo_only' : 'standard');

                        document.getElementById('form-title').innerText = 'Edit Combo: ' + combo.name;
                        document.getElementById('comboForm').action = `{{ url('admin/combo-packs') }}/${id}`;
                        document.getElementById('method_field').innerHTML = '@method("PUT")';

                        const form = document.getElementById('comboForm');
                        form.querySelector('[name="name"]').value = combo.name || '';
                        form.querySelector('[name="offer_price"]').value = combo.offer_price || '';
                        form.querySelector('[name="total_price"]').value = combo.total_price || '';

                        // Populate Advanced Details
                        form.querySelector('[name="sku"]').value = combo.sku || '';
                        form.querySelector('[name="stock_quantity"]').value = combo.stock_quantity || 0;
                        form.querySelector('[name="short_description"]').value = combo.short_description || '';
                        form.querySelector('[name="meta_title"]').value = combo.meta_title || '';
                        form.querySelector('[name="meta_description"]').value = combo.meta_description || '';
                        form.querySelector('[name="meta_keywords"]').value = combo.meta_keywords || '';

                        // Populate prices map for calculation
                        Object.assign(productPrices, prices);

                        // Image Preview
                        const previewContainer = document.getElementById('current-image-container');
                        const previewImg = document.getElementById('current-image-display');
                        const imgInput = document.getElementById('image_input');

                        if (combo.image) {
                            previewContainer.style.display = 'block';
                            imgInput.removeAttribute('required'); // Remove required if it already has an image
                            let displayPath = combo.image;
                            if (!combo.is_combo_only) {
                                try {
                                    const imgs = JSON.parse(combo.image);
                                    if(Array.isArray(imgs) && imgs.length > 0) displayPath = imgs[0];
                                } catch(e) { console.error("Error parsing images:", e); }
                            }
                            previewImg.src = `{{ asset('storage') }}/${displayPath}`;
                        } else {
                            previewContainer.style.display = 'none';
                        }

                        if (!combo.is_combo_only) {
                            const catId = Array.isArray(combo.category_id) && combo.category_id.length > 0 ? combo.category_id[0] : (combo.category_id || '');
                            const subId = Array.isArray(combo.subcategory_id) && combo.subcategory_id.length > 0 ? combo.subcategory_id[0] : (combo.subcategory_id || '');

                            form.querySelector('[name="category_id"]').value = catId;

                            // Pre-load selected IDs
                            selectedProductIds.clear();
                            if(combo.combo_product && combo.combo_product.product_ids) {
                                combo.combo_product.product_ids.forEach(id => selectedProductIds.add(String(id)));
                                syncSelectedToInputs();
                            }

                            // Load subcategories then set value and load items
                            const subSelect = document.getElementById('subcategory_id');
                            if (catId) {
                                fetch(`{{ url('admin/categories') }}/${catId}/subcategories_json`)
                                    .then(r => r.json())
                                    .then(subs => {
                                        subSelect.innerHTML = '<option value="">Select Subcategory</option>';
                                        subs.forEach(sub => {
                                            subSelect.innerHTML += `<option value="${sub.id}" ${sub.id == subId ? 'selected' : ''}>${sub.name}</option>`;
                                        });
                                        fetchFilteredItems(false);
                                    });
                            } else {
                                fetchFilteredItems(true);
                            }
                        }
                    });
            }

            // Sync persistent selection to hidden inputs for form submission
            function syncSelectedToInputs() {
                // Remove existing hidden inputs to avoid duplicates
                document.querySelectorAll('.persistent-p-id').forEach(el => el.remove());

                selectedProductIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'product_ids[]';
                    input.value = id;
                    input.className = 'persistent-p-id';
                    document.getElementById('comboForm').appendChild(input);
                });
            }

            // Status Toggle
            document.querySelectorAll('.status-toggle').forEach(el => {
                el.addEventListener('change', function() {
                    const id = this.getAttribute('data-id');
                    const status = this.checked ? 1 : 0;

                    fetch(`{{ url('admin/combo-packs') }}/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: status })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(!data.success) {
                            alert('Failed to update status');
                            this.checked = !this.checked;
                        }
                    });
                });
            });

            function updateSummary() {
                let total = 0;
                let count = selectedProductIds.size;

                // We use productPrices map to calculate total across all categories
                selectedProductIds.forEach(id => {
                    if (productPrices[id]) total += parseFloat(productPrices[id]);
                });

                if (document.getElementById('action_type').value === 'standard') {
                    document.getElementById('total_price').value = total.toFixed(2);
                }
                document.getElementById('selected_count').innerText = count + " items selected";
            }

            function fetchFilteredItems(global = false) {
                const catId = global ? '' : document.getElementById('category_id').value;
                const subId = global ? '' : document.getElementById('subcategory_id').value;
                const search = document.getElementById('item_search').value;
                const grid = document.getElementById('item-selection-grid');

                // Allow fetching if search is present even without category
                if (!global && !search && (!catId || !subId)) return;

                grid.innerHTML = '<div class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

                let url = `{{ route('admin.combo-packs.get-items') }}?search=${search}`;
                if (!global && (catId || subId)) url += `&category_id=${catId}&subcategory_id=${subId}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        let html = '<div class="row g-2">';
                        const items = [...data.products.map(p => ({...p, type: 'p'})), ...data.combos.map(c => ({...c, type: 'c'}))];

                        items.forEach(item => {
                            const price = item.type === 'p' ? (item.sale_price ?? item.price) : item.offer_price;
                            const id = `${item.type}_${item.id}`;

                            // Save price for global calculation
                            productPrices[id] = price;

                            const isChecked = selectedProductIds.has(String(id)) ? 'checked' : '';
                            const isSelectedClass = selectedProductIds.has(String(id)) ? 'selected' : '';

                            html += `
                                <div class="col-md-6">
                                    <div class="form-check item-box p-2 mb-0 h-100 ${isSelectedClass}">
                                        <input class="form-check-input item-checkbox d-none" type="checkbox" value="${id}" data-price="${price}" id="check_${id}" ${isChecked}>
                                        <label class="form-check-label w-100 cursor-pointer" for="check_${id}">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage') }}/${item.image}" class="rounded me-2" width="35" height="35" style="object-fit: cover;">
                                                <div class="text-truncate">
                                                    <div class="small fw-bold text-dark">${item.name}</div>
                                                    <div class="text-primary x-small">₹${price}</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>`;
                        });
                        grid.innerHTML = html + '</div>';
                        if(items.length === 0) grid.innerHTML = '<div class="text-center py-5 text-muted">No items found.</div>';

                        // Attach listeners to newly created checkboxes
                        grid.querySelectorAll('.item-checkbox').forEach(cb => {
                            cb.addEventListener('change', function() {
                                if (this.checked) {
                                    selectedProductIds.add(String(this.value));
                                } else {
                                    selectedProductIds.delete(String(this.value));
                                }
                                this.closest('.item-box').classList.toggle('selected', this.checked);
                                syncSelectedToInputs();
                                updateSummary();
                            });
                        });

                        updateSummary();
                    });
            }

            // Handlers
            document.getElementById('category_id').addEventListener('change', function() {
                const catId = this.value;
                const subSelect = document.getElementById('subcategory_id');
                subSelect.innerHTML = '<option value="">Select Subcategory</option>';
                if (catId) {
                    fetch(`{{ url('admin/categories') }}/${catId}/subcategories_json`)
                        .then(r => r.json())
                        .then(data => {
                            data.forEach(sub => subSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`);
                        });
                }
            });

            document.getElementById('subcategory_id').addEventListener('change', () => fetchFilteredItems(false));
            document.getElementById('item_search').addEventListener('input', () => {
                const isComboOnlyMode = document.getElementById('action_type').value === 'combo_only';
                fetchFilteredItems(isComboOnlyMode);
            });
        </script>
    @endpush
@endsection
