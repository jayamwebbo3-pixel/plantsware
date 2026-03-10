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
                        <li class="breadcrumb-item active text-muted small">Combo Management</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.combo-only-products.index') }}" class="btn btn-outline-success fw-bold">
                    <i class="fas fa-list me-1"></i> Manage Combo Only
                </a>
                <button class="btn btn-primary fw-bold" onclick="showForm()">
                    <i class="fas fa-layer-group me-1"></i> Add from Product
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
                                    <th>Pricing</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comboPacks as $combo)
                                    <tr>
                                        <td class="ps-4">
                                            @php $imgs = $combo->images @endphp
                                            <div class="composite-preview">
                                                @foreach(array_slice($imgs ?? [], 0, 2) as $img)
                                                    <img src="{{ asset('storage/' . $img) }}" class="rounded shadow-xs border composite-img" style="z-index: {{ 2 - $loop->index }}; left: {{ $loop->index * 15 }}px;">
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $combo->name }}</div>
                                            <small class="text-muted">{{ $combo->slug }}</small>
                                        </td>

                                        <td>
                                            <div class="text-success fw-bold">₹{{ number_format($combo->offer_price, 2) }}</div>
                                            <del class="text-muted x-small">₹{{ number_format($combo->total_price, 2) }}</del>
                                        </td>
                                        <td>
                                            @php
                                                $productStocks = [];
                                                if ($combo->comboProduct && $combo->comboProduct->product_ids) {
                                                    foreach ($combo->comboProduct->product_ids as $pid) {
                                                        if (str_starts_with($pid, 'p_')) {
                                                            $realId = str_replace('p_', '', $pid);
                                                            $prod = \App\Models\Product::find($realId);
                                                            $productStocks[] = $prod ? $prod->stock_quantity : 0;
                                                        } elseif (str_starts_with($pid, 'c_')) {
                                                            $realId = str_replace('c_', '', $pid);
                                                            $nested = \App\Models\ComboPack::find($realId);
                                                            $productStocks[] = $nested ? $nested->stock_quantity : 0;
                                                        } elseif (str_starts_with($pid, 'co_')) {
                                                            $realId = str_replace('co_', '', $pid);
                                                            $coCombo = \App\Models\ComboOnlyProduct::find($realId);
                                                            $productStocks[] = $coCombo ? $coCombo->stock_quantity : 0;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if(count($productStocks) > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($productStocks as $qty)
                                                        <span class="badge {{ $qty > 0 ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }}">
                                                            {{ $qty }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="form-check form-switch p-0 m-0">
                                                <input class="form-check-input ms-0 status-toggle" type="checkbox" data-id="{{ $combo->id }}" {{ $combo->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.reviews.index') }}?combo_pack_id={{ $combo->id }}" class="text-decoration-none">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-warning me-1"><i class="fas fa-star"></i></span>
                                                    <strong>{{ number_format($combo->avg_rating, 1) }}</strong>
                                                    <small class="text-muted ms-1">({{ $combo->total_reviews }})</small>
                                                </div>
                                            </a>
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

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Enter combo description"></textarea>
                                </div>





                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-2 fw-bold shadow-sm">
                                    <i class="fas fa-save me-1"></i> SAVE COMBO
                                </button>
                            </div>

                        <div class="col-lg-7" id="right-selection-col">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label fw-bold mb-0">Select Items</label>
                                        <input type="text" id="item_search" class="form-control form-control-sm w-50" placeholder="Search items...">
                                    </div>
                                    <div class="btn-group btn-group-sm w-100 mb-2" role="group">
                                        <input type="radio" class="btn-check" name="item_type_filter" id="type_p" value="p" checked onchange="filterGridByType()">
                                        <label class="btn btn-outline-secondary" for="type_p">Products</label>

                                        <input type="radio" class="btn-check" name="item_type_filter" id="type_co" value="co" onchange="filterGridByType()">
                                        <label class="btn btn-outline-secondary" for="type_co">Combo Only</label>
                                    </div>
                                </div>
                                <div id="item-selection-table" class="border rounded bg-white overflow-auto" style="height: 400px;">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="ps-3" style="width: 40px;"></th>
                                                <th>Preview</th>
                                                <th>Item Name</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="item-selection-body">
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="fas fa-search mb-2 fa-2x"></i>
                                                    <p>Search or select category to load items</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-dark rounded-pill" id="selected_count">0 items selected</span>
                                    <button type="button" class="btn btn-sm btn-link text-primary p-0" onclick="fetchFilteredItems(true)">Load All</button>
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

            function showForm() {
                document.getElementById('list-section').style.display = 'none';
                document.getElementById('form-section').style.display = 'block';

                document.getElementById('form-title').innerHTML = '<i class="fas fa-layer-group text-primary me-1"></i> Create Product Combo';
                const body = document.getElementById('item-selection-body');
                if (body) {
                    body.innerHTML = `
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

                        showForm();

                        document.getElementById('form-title').innerText = 'Edit Combo: ' + combo.name;
                        document.getElementById('comboForm').action = `{{ url('admin/combo-packs') }}/${id}`;
                        document.getElementById('method_field').innerHTML = '@method("PUT")';

                        const form = document.getElementById('comboForm');
                        form.querySelector('[name="name"]').value = combo.name || '';
                        form.querySelector('[name="offer_price"]').value = combo.offer_price || '';
                        form.querySelector('[name="total_price"]').value = combo.total_price || '';
                        if (form.querySelector('[name="description"]')) {
                            form.querySelector('[name="description"]').value = combo.description || '';
                        }

                        // Hide rating fields on edit

                        // Populate prices map for calculation
                        Object.assign(productPrices, prices);

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

                document.getElementById('total_price').value = total.toFixed(2);
                document.getElementById('selected_count').innerText = count + " items selected";
            }

            function fetchFilteredItems(global = false) {
                const catId = global ? '' : document.getElementById('category_id').value;
                const subId = global ? '' : document.getElementById('subcategory_id').value;
                const search = document.getElementById('item_search').value;
                const typeFilter = document.querySelector('input[name="item_type_filter"]:checked').value;
                const body = document.getElementById('item-selection-body');

                // Allow fetching if search is present even without category
                if (!global && !search && (!catId || !subId)) return;

                body.innerHTML = '<tr><td colspan="4" class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary"></div></td></tr>';

                let url = `{{ route('admin.combo-packs.get-items') }}?search=${search}`;
                if (!global && (catId || subId)) url += `&category_id=${catId}&subcategory_id=${subId}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        let items = [
                            ...data.products.map(p => ({...p, type: 'p'})), 
                            ...data.combo_only_items.map(co => ({...co, type: 'co'}))
                        ];

                        // Filter by type
                        if (typeFilter === 'p') {
                            items = items.filter(i => i.type === 'p');
                        } else if (typeFilter === 'co') {
                            items = items.filter(i => i.type === 'co');
                        }

                        let html = '';
                        items.forEach(item => {
                            const price = item.type === 'p' ? (item.sale_price ?? item.price) : (item.type === 'c' ? item.offer_price : item.price);
                            const id = `${item.type}_${item.id}`;
                            const isChecked = selectedProductIds.has(String(id)) ? 'checked' : '';

                            productPrices[id] = price;

                            html += `
                                <tr class="item-row ${isChecked ? 'table-primary' : ''}">
                                    <td class="ps-3">
                                        <input class="form-check-input item-checkbox" type="checkbox" value="${id}" data-price="${price}" id="check_${id}" ${isChecked}>
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage') }}/${item.image}" class="rounded border" width="40" height="40" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <label class="d-block cursor-pointer mb-0" for="check_${id}">
                                            <div class="fw-bold text-dark">${item.name}</div>
                                            <small class="text-muted">${item.type === 'p' ? 'Product' : 'Combo Only'}</small>
                                        </label>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold">₹${price}</span>
                                    </td>
                                </tr>`;
                        });

                        body.innerHTML = html;
                        if(items.length === 0) body.innerHTML = '<tr><td colspan="4" class="text-center py-5 text-muted">No items found.</td></tr>';

                        // Attach listeners
                        body.querySelectorAll('.item-checkbox').forEach(cb => {
                            cb.addEventListener('change', function() {
                                if (this.checked) {
                                    selectedProductIds.add(String(this.value));
                                } else {
                                    selectedProductIds.delete(String(this.value));
                                }
                                this.closest('tr').classList.toggle('table-primary', this.checked);
                                syncSelectedToInputs();
                                updateSummary();
                            });
                        });
                        updateSummary();
                    });
            }

            function filterGridByType() {
                fetchFilteredItems(false);
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
                fetchFilteredItems(false);
            });
        </script>
    @endpush
@endsection
