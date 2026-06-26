{{-- products-grid.blade.php — rendered via AJAX for filter/sort updates --}}
<div class="products-header-bar mb-4">
    <div class="products-header-inner">
        {{-- Left: Category Title + Count --}}
        <div class="products-header-left">
            <div class="category-title-group">
                <i class="fas fa-leaf category-leaf-icon"></i>
                <h2 class="category-name-heading">
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
            </div>
            <span class="product-count-pill">
                {{ $products->total() }} Products
            </span>
        </div>

        {{-- Right: Sort Dropdown --}}
        <div class="products-header-right">
            <div class="sort-select-wrapper">
                <i class="fas fa-sort-amount-down sort-icon"></i>
                <select id="sort-products" class="sort-select-styled">
                    <option value="default"    {{ request('sort') == 'default'    ? 'selected' : '' }}>Popularity</option>
                    <option value="name-asc"   {{ request('sort') == 'name-asc'   ? 'selected' : '' }}>Name: A to Z</option>
                    <option value="name-desc"  {{ request('sort') == 'name-desc'  ? 'selected' : '' }}>Name: Z to A</option>
                    <option value="price-low"  {{ request('sort') == 'price-low'  ? 'selected' : '' }}>Price: Low → High</option>
                    <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High → Low</option>
                </select>
            </div>
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
