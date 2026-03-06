@extends('admin.layout')

@section('title', 'Combo Only Products')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">Combo Only Products</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active text-muted small">Items for selection in bundles</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.combo-packs.index') }}" class="btn btn-outline-primary fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Back to Packs
                </a>
                <button class="btn btn-success fw-bold" onclick="showForm()">
                    <i class="fas fa-plus me-1"></i> Add Combo Only Product
                </button>
            </div>
        </div>

        {{-- List Section --}}
        <div id="list-section">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('admin.combo-only-products.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Filter</button>
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
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td class="ps-4">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/default.jpg') }}"
                                                 class="rounded border" width="50" height="50" style="object-fit: cover;">
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->slug }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">₹{{ number_format($product->price, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch p-0 m-0">
                                                <input class="form-check-input ms-0 status-toggle" type="checkbox"
                                                       data-id="{{ $product->id }}" {{ $product->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-primary border-0" onclick="editProduct({{ $product->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.combo-only-products.destroy', $product->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No combo only products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($products->hasPages())
                    <div class="card-footer bg-white">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Create / Edit Form Section --}}
        <div id="form-section" style="display: none;">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0" id="form-title">Add Combo Only Product</h5>
                    <button class="btn btn-sm btn-light border" onclick="hideForm()"><i class="fas fa-times"></i></button>
                </div>
                <div class="card-body p-4">
                    <form id="productForm" action="{{ route('admin.combo-only-products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="method_field"></div>

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="Product name">
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Price <span class="text-muted fw-normal">(optional)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold">₹</span>
                                            <input type="number" name="price" class="form-control" value="0" step="0.01" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Stock Quantity</label>
                                        <input type="number" name="stock_quantity" class="form-control" value="0" min="0">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Image</label>
                                    <div id="current-image-container" class="mb-2" style="display:none;">
                                        <div class="d-flex align-items-center gap-2 border rounded p-2 bg-light">
                                            <img id="current-image-display" src="" class="rounded border" width="60" height="60" style="object-fit: cover;">
                                            <span class="small text-muted">Current image</span>
                                        </div>
                                    </div>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                                    <i class="fas fa-save me-1"></i> Save Product
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function showForm() {
                document.getElementById('list-section').style.display = 'none';
                document.getElementById('form-section').style.display = 'block';
                document.getElementById('form-title').innerText = 'Add Combo Only Product';
                document.getElementById('productForm').action = "{{ route('admin.combo-only-products.store') }}";
                document.getElementById('method_field').innerHTML = '';
                document.getElementById('productForm').reset();
                document.getElementById('current-image-container').style.display = 'none';
            }

            function hideForm() {
                document.getElementById('form-section').style.display = 'none';
                document.getElementById('list-section').style.display = 'block';
            }

            function editProduct(id) {
                fetch(`{{ url('admin/combo-only-products') }}/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        const p = data.product;
                        showForm();
                        document.getElementById('form-title').innerText = 'Edit: ' + p.name;
                        document.getElementById('productForm').action = `{{ url('admin/combo-only-products') }}/${id}`;
                        document.getElementById('method_field').innerHTML = '@method("PUT")';

                        const form = document.getElementById('productForm');
                        form.querySelector('[name="name"]').value = p.name || '';
                        form.querySelector('[name="price"]').value = p.price || 0;
                        form.querySelector('[name="stock_quantity"]').value = p.stock_quantity || 0;
                        form.querySelector('[name="description"]').value = p.description || '';

                        if (p.image) {
                            document.getElementById('current-image-container').style.display = 'block';
                            document.getElementById('current-image-display').src = `{{ asset('storage') }}/${p.image}`;
                        }
                    });
            }

            // Status toggle
            document.querySelectorAll('.status-toggle').forEach(el => {
                el.addEventListener('change', function () {
                    const id = this.getAttribute('data-id');
                    fetch(`{{ url('admin/combo-only-products') }}/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: this.checked ? 1 : 0 })
                    }).then(res => res.json()).then(data => {
                        if (!data.success) {
                            alert('Failed to update status');
                            this.checked = !this.checked;
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
