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
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="sale_price" class="form-label">Sale Price</label>
                                <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0" placeholder="e.g. 0.5">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
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

                    <!-- Product Attributes Section -->
                    <hr class="my-5">
                    <h4 class="mb-4">Product Attributes</h4>

                    <div class="row g-4">
                        {{-- SIZE column --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Size &amp; Price</label>
                            <div id="size-checkboxes">
                                @php
                                    $selectedShape = old('shape', $product->shape ?? 'Circular');

                                    $savedSize = $product->size;
                                    $selectedSizes = [];

                                    if (!empty($savedSize)) {
                                        if (is_array($savedSize)) {
                                            $selectedSizes = $savedSize;
                                        } else {
                                            $decoded = json_decode($savedSize, true);
                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $selectedSizes = $decoded;
                                            } else {
                                                // Fallback for comma separated strings
                                                $parts = array_map('trim', explode(',', $savedSize));
                                                foreach ($parts as $p) {
                                                    if ($p) $selectedSizes[$p] = null;
                                                }
                                            }
                                        }
                                    }

                                    // Overwrite with old input if validation failed
                                    $oldSizes = old('sizes');
                                    if (is_array($oldSizes)) {
                                        $selectedSizes = [];
                                        foreach ($oldSizes as $s => $data) {
                                            if (!empty($data['checked'])) {
                                                $selectedSizes[$s] = $data['price'] ?? null;
                                            }
                                        }
                                    }

                                    $circularSizes    = ['6x6 Inch','9x9 Inch','12x12 Inch','12x15 Inch','15x12 Inch','15x15 Inch',
                                                         '18x6 Inch','18x9 Inch','18x18 Inch','24x6 Inch','24x24 Inch'];
                                    $rectangularSizes = ['18x12x12 Inch','18x12x9 Inch','24x12x9 Inch','24x12x12 Inch','24x24x12 Inch','24x24x18 Inch'];
                                @endphp

                                {{-- Circular --}}
                                <div id="circular-sizes" style="{{ $selectedShape === 'Circular' ? '' : 'display:none;' }}">
                                    @foreach($circularSizes as $index => $size)
                                        @php
                                            // PHP converts spaces to underscores in form array keys
                                            $phpKey    = str_replace(' ', '_', $size);
                                            $isChecked = array_key_exists($phpKey, $selectedSizes)
                                                         || array_key_exists($size, $selectedSizes);
                                            $priceVal  = $isChecked
                                                         ? ($selectedSizes[$phpKey] ?? $selectedSizes[$size] ?? '')
                                                         : '';
                                        @endphp
                                        <div class="d-flex align-items-center mb-2 gap-3">
                                            <div class="form-check mb-0" style="min-width: 130px;">
                                                <input class="form-check-input size-checkbox" type="checkbox"
                                                    name="sizes[{{ $size }}][checked]"
                                                    id="size-circular-{{ $index }}"
                                                    value="1" {{ $isChecked ? 'checked' : '' }}
                                                    onchange="const inp = this.closest('.d-flex').querySelector('.size-price-input'); inp.disabled = !this.checked; if(!this.checked) inp.value='';">
                                                <label class="form-check-label" for="size-circular-{{ $index }}">{{ $size }}</label>
                                            </div>
                                            <div class="input-group input-group-sm" style="max-width: 160px;">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control size-price-input"
                                                    name="sizes[{{ $size }}][price]"
                                                    value="{{ $priceVal }}"
                                                    placeholder="Enter price"
                                                    step="0.01"
                                                    {{ $isChecked ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Rectangular --}}
                                <div id="rectangular-sizes" style="{{ $selectedShape === 'Rectangular' ? '' : 'display:none;' }}">
                                    @foreach($rectangularSizes as $index => $size)
                                        @php
                                            $phpKey    = str_replace(' ', '_', $size);
                                            $isChecked = array_key_exists($phpKey, $selectedSizes)
                                                         || array_key_exists($size, $selectedSizes);
                                            $priceVal  = $isChecked
                                                         ? ($selectedSizes[$phpKey] ?? $selectedSizes[$size] ?? '')
                                                         : '';
                                        @endphp
                                        <div class="d-flex align-items-center mb-2 gap-3">
                                            <div class="form-check mb-0" style="min-width: 150px;">
                                                <input class="form-check-input size-checkbox" type="checkbox"
                                                    name="sizes[{{ $size }}][checked]"
                                                    id="size-rectangular-{{ $index }}"
                                                    value="1" {{ $isChecked ? 'checked' : '' }}
                                                    onchange="const inp = this.closest('.d-flex').querySelector('.size-price-input'); inp.disabled = !this.checked; if(!this.checked) inp.value='';">
                                                <label class="form-check-label" for="size-rectangular-{{ $index }}">{{ $size }}</label>
                                            </div>
                                            <div class="input-group input-group-sm" style="max-width: 160px;">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control size-price-input"
                                                    name="sizes[{{ $size }}][price]"
                                                    value="{{ $priceVal }}"
                                                    placeholder="Enter price"
                                                    step="0.01"
                                                    {{ $isChecked ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div id="square-sizes" style="{{ $selectedShape === 'Square' ? '' : 'display:none;' }}">
                                    <div class="text-muted">No sizes defined for Square.</div>
                                </div>
                            </div>
                        </div>

                        {{-- Shape & Material column --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="shape" class="form-label fw-semibold">Shape</label>
                                <select class="form-select" id="shape" name="shape">
                                    <option value="">Select Shape</option>
                                    <option value="Circular" {{ old('shape', $product->shape) == 'Circular' ? 'selected' : '' }}>Circular</option>
                                    <option value="Rectangular" {{ old('shape', $product->shape) == 'Rectangular' ? 'selected' : '' }}>Rectangular</option>
                                    {{-- <option value="Square" {{ old('shape', $product->shape) == 'Square' ? 'selected' : '' }}>Square</option> --}}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="material" class="form-label fw-semibold">Material</label>
                                <select class="form-select" id="material" name="material">
                                    <option value="">Select Material</option>
                                    <option value="HDPE" {{ old('material', $product->material) == 'HDPE' ? 'selected' : '' }}>HDPE</option>
                                    <option value="Fabric" {{ old('material', $product->material) == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                    <option value="Non-woven" {{ old('material', $product->material) == 'Non-woven' ? 'selected' : '' }}>Non-woven</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            function toggleSizeCheckboxes() {
                                var shape = document.getElementById('shape').value;
                                document.getElementById('circular-sizes').style.display    = (shape === 'Circular')    ? '' : 'none';
                                document.getElementById('rectangular-sizes').style.display = (shape === 'Rectangular') ? '' : 'none';
                                document.getElementById('square-sizes').style.display      = (shape === 'Square')      ? '' : 'none';
                            }
                            document.getElementById('shape').addEventListener('change', toggleSizeCheckboxes);
                            toggleSizeCheckboxes();
                        });
                    </script>

                    <div class="row">
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
                    </div>

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
            const allSubcategories = @json($subcategories);
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