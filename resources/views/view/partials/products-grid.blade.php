{{-- products-grid.blade.php — rendered via AJAX for filter/sort updates --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-3 rounded-4 shadow-sm mb-4" style="border: 1px solid rgba(0,0,0,0.05);">
    {{-- Left: Category Title + Count --}}
    <div class="d-flex align-items-center mb-3 mb-md-0">
        <div class="bg-light text-success d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 45px; height: 45px; margin-right: 15px; color: #2e6a39 !important;">
            <i class="fas fa-leaf fa-lg"></i>
        </div>
        <div>
            <h2 class="h5 fw-bold mb-1" style="font-family: var(--font-main); color: #2c3e50;">
                @if(request()->filled('q'))
                    Search results for &ldquo;{{ request('q') }}&rdquo;
                @elseif(isset($category))
                    {{ $category->name }}
                @elseif(isset($subcategory))
                    {{ $subcategory->name }}
                @else
                    All Categories
                @endif
            </h2>
            <span class="badge" style="background: rgba(46, 106, 57, 0.1); color: #2e6a39; font-size: 0.75rem; letter-spacing: 0.5px; padding: 4px 8px; border-radius: 6px;">
                {{ $products->total() }} Products
            </span>
        </div>
    </div>

    {{-- Right: Sort Dropdown --}}
    <div class="d-flex align-items-center">
        <span class="text-muted small mr-2 fw-bold text-uppercase" style="margin-right: 10px; font-size: 0.75rem; letter-spacing: 0.5px;">Sort By:</span>
        <div class="position-relative">
            <select id="sort-products" class="form-select form-control shadow-none pe-4" style="border-radius: 8px; border: 1px solid #ddd; font-weight: 500; color: #444; height: 38px; cursor: pointer; padding-right: 30px; font-size: 0.9rem; min-width: 160px;">
                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                <option value="price-low"  {{ request('sort') == 'price-low'  ? 'selected' : '' }}>Price: Low → High</option>
                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High → Low</option>
                <option value="newest"     {{ request('sort') == 'newest'     ? 'selected' : '' }}>Newest First</option>
                <option value="discount"   {{ request('sort') == 'discount'   ? 'selected' : '' }}>Best Discount</option>
            </select>
        </div>
    </div>
</div>

<div class="row g-4" id="products-inner-grid">
    @forelse($products as $product)
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 mb-4">
        @include('view.partials.product-card', ['product' => $product])
    </div>
    @empty
    <div class="col-12 py-5 text-center">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <p class="h4 text-muted">
            @if(request()->filled('q'))
                No products found for "{{ request('q') }}".
            @else
                No products available in this category.
            @endif
        </p>
        <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-2 mt-3" style="background-color: var(--primary-color, #6ea820); border: none; border-radius: 8px; font-weight: 700; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; display: inline-flex; align-items: center; justify-content: center; min-width: 180px; box-shadow: 0 4px 12px rgba(110, 168, 32, 0.2); transition: all 0.3s ease;">View All Products</a>
    </div>
    @endforelse

    @if(isset($products) && method_exists($products, 'links'))
    <div class="col-12 mt-4 text-center ajax-pagination">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
