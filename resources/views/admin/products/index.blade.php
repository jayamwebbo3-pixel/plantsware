@extends('admin.layout')

@section('title', 'Products - ' . ($subcategory->name ?? 'All'))

@section('content')
        <!-- <div class="mb-4">
            <a href="{{ route('admin.categories.subcategories', $subcategory->category) }}" class="text-decoration-none">
                ← Back to Subcategories
            </a>
        </div> -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-secondary">Products in "{{ $subcategory->name }}"</h4>
            <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.subcategories', $subcategory->category) }}" class="text-decoration-none">
                <i class="fas fa-arrow-left"></i> Back to Subcategories
            </a>
            <a href="{{ route('admin.products.create') }}?subcategory_id={{ $subcategory->id }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Search & Per Page Controls -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.subcategories.products', $subcategory) }}" class="d-flex">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search products by name..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <form method="GET" action="{{ route('admin.subcategories.products', $subcategory) }}" class="d-inline">
                            <label class="me-2 text-muted">Show</label>
                            <select name="per_page" onchange="this.form.submit()" class="form-select d-inline w-auto">
                                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 20) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>S.NO</th>
                                <th>Image</th>
                                <th>Name
                                    <a href="{{ route('admin.subcategories.products', $subcategory) }}?sort=name&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&per_page={{ request('per_page') }}">
                                        
                                        @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Price
                                    <a href="{{ route('admin.subcategories.products', $subcategory) }}?sort=price&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&per_page={{ request('per_page') }}">
                                        
                                        @if(request('sort') == 'price')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Stock
                                    <a href="{{ route('admin.subcategories.products', $subcategory) }}?sort=stock_quantity&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&per_page={{ request('per_page') }}">
                                        
                                        @if(request('sort') == 'stock_quantity')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Sort Order
                                    <a href="{{ route('admin.subcategories.products', $subcategory) }}?sort=sort_order&direction={{ request('direction') == 'asc' ? 'desc' : 'asc' }}&search={{ request('search') }}&per_page={{ request('per_page') }}">
                                        
                                        @if(request('sort') == 'sort_order')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light border d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if($product->sale_price && $product->sale_price < $product->price)

                                            <span class="text-success">₹{{ number_format($product->sale_price, 2) }}</span><br>
                                             <del class="text-muted small">₹{{ number_format($product->price, 2) }}</del>
                                        @else
                                            ₹{{ number_format($product->price, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $product->sort_order ?? 0 }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                                data-id="{{ $product->id }}" 
                                                {{ $product->is_active ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.reviews.index') }}?product_id={{ $product->id }}">
                                            <div class="d-flex align-items-center">
                                                <span class="text-warning me-1"><i class="fas fa-star"></i></span>
                                                <strong>{{ number_format($product->avg_rating, 1) }}</strong>
                                                <small class="text-muted ms-1">({{ $product->total_reviews }})</small>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('Are you sure you want to delete this product?', this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        No products found in this subcategory.
                                        <a href="{{ route('admin.products.create') }}?subcategory_id={{ $subcategory->id }}" class="d-block mt-2">
                                            <i class="fas fa-plus"></i> Add your first product
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination & Info -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusToggles = document.querySelectorAll('.status-toggle');
        statusToggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const productId = this.dataset.id;
                const isActive = this.checked ? 1 : 0;

                fetch(`/admin/products/${productId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ is_active: isActive })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        this.checked = !this.checked;
                        alert('Failed to update status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked;
                    alert('An error occurred.');
                });
            });
        });
    });
    </script>
@endsection