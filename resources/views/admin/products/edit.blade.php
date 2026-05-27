@extends('admin.layout')

@section('title', $product->subcategory ? 'Edit Product - ' . $product->name : 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Edit Product - {{ $product->name }}</h4>
    <a href="{{ route('admin.subcategories.products', $product->subcategory_id) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column: Main Details + Attributes -->
                <div class="col-md-8">

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select bg-light" id="category_id" name="category_id_display" disabled>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="category_id" value="{{ $product->category_id }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subcategory_id" class="form-label">Subcategory</label>
                                <select class="form-select bg-light" id="subcategory_id" name="subcategory_id_display" disabled>
                                    <option value="">Select Subcategory</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="subcategory_id" value="{{ $product->subcategory_id }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_code" class="form-label">Product Code</label>
                                <input type="text" class="form-control" id="product_code" name="product_code" value="{{ old('product_code', $product->product_code) }}" placeholder="e.g. PRD-001">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="batch_code" class="form-label">Batch Code</label>
                                <input type="text" class="form-control" id="batch_code" name="batch_code" value="{{ old('batch_code', $product->batch_code) }}" placeholder="e.g. BATCH-2024-01">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight (grams)</label>
                                <input type="number" step="any" class="form-control" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0" placeholder="e.g. 500">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}">
                                <small class="text-muted">Lower numbers appear first on frontend.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Product Attributes Section -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 mt-2 overflow-hidden">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-tags text-primary me-2"></i>Product Attributes</h5>
                                <p class="text-muted small mb-0">Manage sizes, types, and custom price overrides</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm shadow-sm" onclick="addNewAttributeRow()">
                                    <i class="fas fa-plus me-1"></i> Add Manual
                                </button>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                                        <i class="fas fa-magic me-1"></i> Use Presets
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2">
                                        <li><h6 class="dropdown-header">Select a Template</h6></li>
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addAttributePreset('circular')"><i class="fas fa-circle-notch me-2 text-muted"></i>Grow Bag - Circular</a></li>
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addAttributePreset('rectangular')"><i class="fas fa-vector-square me-2 text-muted"></i>Grow Bag - Rectangular</a></li>
                                        <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addAttributePreset('shadenets')"><i class="fas fa-border-all me-2 text-muted"></i>Shade Net Sizes</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-light-subtle pt-0">
                            <div id="attributes-container" class="mt-3">
                                <div class="table-responsive rounded-3 bg-white shadow-sm border">
                                    <table class="table table-hover align-middle mb-0" id="attributes-table">
                                        <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4 py-3 text-uppercase small fw-bold text-muted" style="width: 25%;">Attribute Option</th>
                                                    <th class="py-3 text-uppercase small fw-bold text-muted" style="width: 20%;">Price Override (₹)</th>
                                                    <th class="py-3 text-uppercase small fw-bold text-muted" style="width: 15%;">Stock</th>
                                                    <th class="py-3 text-uppercase small fw-bold text-muted" style="width: 15%;">Weight (grams)</th>
                                                    <th class="py-3 text-uppercase small fw-bold text-muted" style="width: 20%;">Image</th>
                                                    <th class="text-center py-3 text-uppercase small fw-bold text-muted" style="width: 50px;">Remove</th>
                                                </tr>
                                        </thead>
                                        <tbody id="attributes-body">
                                            @php
                                                $savedSize = $product->size;
                                                $currentSizes = [];

                                                if (!empty($savedSize)) {
                                                    if (is_array($savedSize)) {
                                                        $currentSizes = $savedSize;
                                                    } else {
                                                        $decoded = json_decode($savedSize, true);
                                                        if (is_array($decoded)) {
                                                            $currentSizes = $decoded;
                                                        }
                                                    }
                                                }

                                                // Overwrite with old input if validation failed
                                                $oldSizes = old('sizes');
                                                if (is_array($oldSizes)) {
                                                    $currentSizes = [];
                                                    foreach ($oldSizes as $s => $data) {
                                                        if (!empty($data['checked'])) {
                                                            $currentSizes[$s] = [
                                                                'price' => $data['price'] ?? null,
                                                                'stock' => $data['stock'] ?? null,
                                                                'weight' => $data['weight'] ?? null,
                                                                'image' => $data['existing_image'] ?? null
                                                            ];
                                                        }
                                                    }
                                                }
                                                
                                                $hasAny = count($currentSizes) > 0;
                                            @endphp

                                            @foreach($currentSizes as $name => $data)
                                                {{-- Handle bothold string values and new array objects for backward compatibility during migration period --}}
                                                @php 
                                                    $priceValue = is_array($data) ? ($data['price'] ?? '') : $data;
                                                    $stockValue = is_array($data) ? ($data['stock'] ?? '') : '';
                                                    $weightValue = is_array($data) ? ($data['weight'] ?? '') : '';
                                                    $imagePath = is_array($data) ? ($data['image'] ?? null) : null;
                                                @endphp
                                                <tr class="attribute-row animate__animated animate__fadeIn">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <input type="hidden" name="sizes[{{ $name }}][checked]" value="1">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-3 px-2 py-1"><i class="fas fa-tag"></i></span>
                                                            <input type="text" class="form-control form-control-sm border-0 bg-transparent fw-semibold attr-name-display" value="{{ $name }}" readonly title="Click to edit name" onclick="editAttributeName(this)" style="cursor: pointer;">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm w-100">
                                                            <span class="input-group-text bg-light border-end-0">₹</span>
                                                            <input type="number" step="0.01" name="sizes[{{ $name }}][price]" class="form-control border-start-0" value="{{ $priceValue }}" placeholder="Use Base Price">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm w-100">
                                                            <input type="number" name="sizes[{{ $name }}][stock]" class="form-control" value="{{ $stockValue }}" placeholder="Qty">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm w-100">
                                                            <input type="number" step="any" name="sizes[{{ $name }}][weight]" class="form-control" value="{{ $weightValue }}" placeholder="grams">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if($imagePath)
                                                                <img src="{{ asset('storage/' . $imagePath) }}" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;" title="Existing Image">
                                                                <input type="hidden" name="sizes[{{ $name }}][existing_image]" value="{{ $imagePath }}">
                                                            @endif
                                                            <input type="file" name="sizes[{{ $name }}][image]" class="form-control form-control-sm" accept="image/*">
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove(); checkEmptyAttributes();">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if(!$hasAny)
                                                <tr id="no-attributes-msg">
                                                    <td colspan="3" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-layer-group fa-3x text-light mb-3"></i>
                                                            <h6 class="text-muted">No attributes defined</h6>
                                                            <p class="text-secondary small">Add custom sizes or use one of our predefined templates</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Secondary Attributes (Shape / Material) -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <label for="shape" class="form-label fw-bold text-dark d-flex align-items-center mb-3">
                                        <i class="fas fa-shapes text-info me-2"></i>Product Shape
                                    </label>
                                    <select class="form-select border-light-subtle py-2" id="shape" name="shape">
                                        <option value="">Standard / N/A</option>
                                        <option value="Circular" {{ old('shape', $product->shape) == 'Circular' ? 'selected' : '' }}>Circular (Round)</option>
                                        <option value="Rectangular" {{ old('shape', $product->shape) == 'Rectangular' ? 'selected' : '' }}>Rectangular (Wide)</option>
                                        <option value="Square" {{ old('shape', $product->shape) == 'Square' ? 'selected' : '' }}>Square (Box)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-body p-4">
                                    <label for="material" class="form-label fw-bold text-dark d-flex align-items-center mb-3">
                                        <i class="fas fa-boxes-stacked text-warning me-2"></i>Primary Material
                                    </label>
                                    <select class="form-select border-light-subtle py-2" id="material" name="material">
                                        <option value="">Not Specified</option>
                                        <option value="HDPE" {{ old('material', $product->material) == 'HDPE' ? 'selected' : '' }}>HDPE (High-Density Polyethylene)</option>
                                        <option value="Fabric" {{ old('material', $product->material) == 'Fabric' ? 'selected' : '' }}>Premium Fabric</option>
                                        <option value="Non-woven" {{ old('material', $product->material) == 'Non-woven' ? 'selected' : '' }}>Non-Woven Geotextile</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        .bg-light-subtle { background-color: #f8f9fa !important; }
                        .attr-name-display:focus { box-shadow: none !important; }
                        .attribute-row:hover { background-color: #fcfcfc; }
                        .attribute-row .btn-link { 
                            transition: transform 0.2s; 
                            opacity: 0.5;
                        }
                        .attribute-row:hover .btn-link { opacity: 1; }
                        .attribute-row .btn-link:hover { transform: scale(1.2); }
                        .empty-state h6 { font-weight: 600; letter-spacing: 0.5px; }
                    </style>

                    <script>
                        function addNewAttributeRow(name = '', price = '') {
                            if (!name) {
                                Swal.fire({
                                    title: 'New Attribute',
                                    input: 'text',
                                    inputLabel: 'Enter Attribute Name (e.g. 12x12 Inch or 50% Shade)',
                                    inputPlaceholder: 'Attribute name...',
                                    showCancelButton: true,
                                    confirmButtonColor: '#134e5e',
                                    inputValidator: (value) => {
                                        if (!value) return 'You need to write something!'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        renderAttributeRow(result.value, price);
                                    }
                                });
                            } else {
                                renderAttributeRow(name, price);
                            }
                        }

                        function renderAttributeRow(displayName, price) {
                            const body = document.getElementById('attributes-body');
                            const noMsg = document.getElementById('no-attributes-msg');
                            if (noMsg) noMsg.remove();

                            const row = document.createElement('tr');
                            row.className = 'attribute-row animate__animated animate__fadeIn';
                            
                            row.innerHTML = `
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <input type="hidden" name="sizes[${displayName}][checked]" value="1">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-3 px-2 py-1"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control form-control-sm border-0 bg-transparent fw-semibold attr-name-display" value="${displayName}" readonly title="Click to edit name" onclick="editAttributeName(this)" style="cursor: pointer;">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm w-100">
                                        <span class="input-group-text bg-light border-end-0">₹</span>
                                        <input type="number" step="0.01" name="sizes[${displayName}][price]" class="form-control border-start-0" value="${price}" placeholder="Use Base Price">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm w-100">
                                        <input type="number" name="sizes[${displayName}][stock]" class="form-control" placeholder="Qty">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm w-100">
                                        <input type="number" step="any" name="sizes[${displayName}][weight]" class="form-control" placeholder="grams">
                                    </div>
                                </td>
                                <td>
                                    <input type="file" name="sizes[${displayName}][image]" class="form-control form-control-sm" accept="image/*">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-link text-danger p-0" onclick="this.closest('tr').remove(); checkEmptyAttributes();">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            `;
                            body.appendChild(row);
                        }

                        function editAttributeName(input) {
                            Swal.fire({
                                title: 'Edit Attribute Name',
                                input: 'text',
                                inputValue: input.value,
                                showCancelButton: true,
                                confirmButtonColor: '#134e5e',
                                inputValidator: (value) => {
                                    if (!value) return 'Name cannot be empty!'
                                }
                            }).then((result) => {
                                if (result.isConfirmed && result.value !== input.value) {
                                    const newName = result.value;
                                    const row = input.closest('tr');
                                    input.value = newName;
                                    row.querySelector('input[name*="[checked]"]').name = `sizes[${newName}][checked]`;
                                    row.querySelector('input[name*="[price]"]').name = `sizes[${newName}][price]`;
                                    row.querySelector('input[name*="[stock]"]').name = `sizes[${newName}][stock]`;
                                    row.querySelector('input[name*="[weight]"]').name = `sizes[${newName}][weight]`;
                                    row.querySelector('input[name*="[image]"]').name = `sizes[${newName}][image]`;
                                    const existingImg = row.querySelector('input[name*="existing_image"]');
                                    if (existingImg) existingImg.name = `sizes[${newName}][existing_image]`;
                                }
                            });
                        }

                        function checkEmptyAttributes() {
                            const body = document.getElementById('attributes-body');
                            if (body.querySelectorAll('.attribute-row').length === 0) {
                                body.innerHTML = `<tr id="no-attributes-msg"><td colspan="3" class="text-center py-3 text-muted">No attributes added yet. Use "Quick Add" or click "+" to add one.</td></tr>`;
                            }
                        }

                        function addAttributePreset(type) {
                            const presets = {
                                'circular': ['6x6 Inch', '9x9 Inch', '12x12 Inch', '12x15 Inch', '15x12 Inch', '15x15 Inch', '18x6 Inch', '18x9 Inch', '18x18 Inch', '24x6 Inch', '24x24 Inch'],
                                'rectangular': ['18x12x12 Inch', '18x12x9 Inch', '24x12x9 Inch', '24x12x12 Inch', '24x24x12 Inch', '24x24x18 Inch'],
                                'shadenets': ['30% Shade', '50% Shade', '75% Shade', '90% Shade', 'Creeper Net']
                            };

                            if (presets[type]) {
                                Swal.fire({
                                    title: 'Add Presets?',
                                    text: `Populate list with ${type.replace('_', ' ')} presets? This won't remove existing rows.`,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#134e5e',
                                    confirmButtonText: 'Yes, add them'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        presets[type].forEach(name => {
                                            const existing = Array.from(document.querySelectorAll('.attr-name')).some(el => el.value === name);
                                            if (!existing) {
                                                renderAttributeRow(name, '');
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    </script>

                    <!-- <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="color" name="color" value="{{ old('color', $product->color) }}" placeholder="e.g., Green, Black">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="gsm" class="form-label">GSM (Thickness)</label>
                                <input type="number" class="form-control" id="gsm" name="gsm" value="{{ old('gsm', $product->gsm) }}" min="0" placeholder="e.g., 220">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="shade_percentage" class="form-label">Shade Percentage</label>
                                <input type="text" class="form-control" id="shade_percentage" name="shade_percentage" value="{{ old('shade_percentage', $product->shade_percentage) }}" placeholder="e.g., 50%, 75%">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3 form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="has_handles" name="has_handles" value="1" {{ old('has_handles', $product->has_handles) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_handles">Has Handles</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3 form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="uv_treated" name="uv_treated" value="1" {{ old('uv_treated', $product->uv_treated) ? 'checked' : '' }}>
                                <label class="form-check-label" for="uv_treated">UV Treated</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pack_quantity" class="form-label">Pack Quantity</label>
                                <input type="number" class="form-control" id="pack_quantity" name="pack_quantity" value="{{ old('pack_quantity', $product->pack_quantity ?? 1) }}" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warranty_months" class="form-label">Warranty (months)</label>
                                <input type="number" class="form-control" id="warranty_months" name="warranty_months" value="{{ old('warranty_months', $product->warranty_months) }}" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="width_meters" class="form-label">Width (meters) - for shade nets</label>
                                <input type="number" step="0.01" class="form-control" id="width_meters" name="width_meters" value="{{ old('width_meters', $product->width_meters) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="length_meters" class="form-label">Length (meters) - for shade nets</label>
                                <input type="number" step="0.01" class="form-control" id="length_meters" name="length_meters" value="{{ old('length_meters', $product->length_meters) }}" min="0">
                            </div>
                        </div>
                    </div> -->

                </div>

                <!-- Right Column: Images & Flags -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Main Product Image</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px; height: auto;">
                                <small class="text-muted d-block mt-1">Current image</small>
                            </div>
                        @endif
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="if(this.files[0]) { document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('previewContainer').style.display = 'block'; } else { document.getElementById('previewContainer').style.display = 'none'; }">
                        <div id="previewContainer" class="mt-2 text-center" style="display: none;">
                            <img id="imagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="max-height: 200px;">
                            <small class="text-success d-block mt-1">New Image Preview</small>
                        </div>
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Gallery Images</label>
                        <div id="existingGalleryContainer" class="d-flex flex-wrap gap-2 mb-2">
                            @if($product->gallery_images && count($product->gallery_images) > 0)
                                @foreach($product->gallery_images as $index => $imagePath)
                                    <div class="position-relative d-inline-block existing-gallery-item" data-path="{{ $imagePath }}">
                                        <img src="{{ asset('storage/' . $imagePath) }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle remove-existing-gallery" 
                                                style="width: 20px; height: 20px; padding: 0; transform: translate(30%, -30%);"
                                                onclick="removeExistingImage(this, '{{ $imagePath }}')">
                                            &times;
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small">No gallery images uploaded yet.</p>
                            @endif
                        </div>
                        {{-- Hidden input to store paths of images to be deleted --}}
                        <div id="deletedImagesContainer"></div>

                        <label for="gallery_images" class="form-label">Add More Gallery Images</label>
                        <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                        <div id="galleryPreviewContainer" class="d-flex flex-wrap mt-2"></div>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple. These will be added to your current gallery.</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Mark as Featured Product</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-5 text-end">
                <a href="{{ route('admin.subcategories.products', $product->subcategory_id) }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-5">Update Product</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dynamic subcategory filtering based on selected category (preserves current selection on edit)
            const allSubcategories = {!! json_encode($subcategories) !!};
            const currentCategoryId = "{{ old('category_id', $product->category_id) }}";
            const currentSubcategoryId = "{{ old('subcategory_id', $product->subcategory_id) }}";

            function populateSubcategories(categoryId) {
                const subcategorySelect = document.getElementById('subcategory_id');
                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

                if (categoryId) {
                    const filtered = allSubcategories.filter(sub => sub.category_id == categoryId);

                    filtered.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.id;
                        option.textContent = sub.name;
                        if (sub.id == currentSubcategoryId) option.selected = true;
                        subcategorySelect.appendChild(option);
                    });
                }
            }

            // On page load: populate subcategories if category is already selected
            if (currentCategoryId) {
                populateSubcategories(currentCategoryId);
            }

            // On category change
            document.getElementById('category_id').addEventListener('change', function() {
                populateSubcategories(this.value);
            });

            // --- Gallery Management ---
            const galleryInput = document.getElementById('gallery_images');
            const galleryPreviewContainer = document.getElementById('galleryPreviewContainer');
            const deletedImagesContainer = document.getElementById('deletedImagesContainer');
            let selectedFiles = [];

            // 1. Handle Existing Gallery Deletion
            window.removeExistingImage = function(btn, path) {
                if (confirm('Mark this image for deletion? It will be removed when you save.')) {
                    // Hide the UI element
                    btn.closest('.existing-gallery-item').classList.add('d-none');
                    // Add to deleted images list
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deleted_gallery_images[]';
                    input.value = path;
                    deletedImagesContainer.appendChild(input);
                }
            };

            // 2. Handle New Gallery Upload Previews
            if (galleryInput) {
                galleryInput.addEventListener('change', function(e) {
                    const newFiles = Array.from(e.target.files);
                    newFiles.forEach(newFile => {
                        const exists = selectedFiles.some(existingFile => 
                            existingFile.name === newFile.name && existingFile.size === newFile.size
                        );
                        if (!exists) {
                            selectedFiles.push(newFile);
                        }
                    });
                    updateInputFiles();
                    updateGalleryPreview();
                });
            }

            function updateGalleryPreview() {
                galleryPreviewContainer.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'position-relative d-inline-block m-1';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail border-success" style="width: 80px; height: 80px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle" 
                                    style="width: 20px; height: 20px; padding: 0; transform: translate(30%, -30%);"
                                    onclick="removeNewImage(${index})">
                                &times;
                            </button>
                            <small class="d-block text-center text-success" style="font-size: 10px;">New</small>
                        `;
                        galleryPreviewContainer.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }

            window.removeNewImage = function(index) {
                selectedFiles.splice(index, 1);
                updateInputFiles();
                updateGalleryPreview();
            };

            function updateInputFiles() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                galleryInput.files = dataTransfer.files;
            }
        });
    </script>
@endpush