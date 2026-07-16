@include('view.layout.header')


<!-- Breadcrumb Section -->
{{-- 
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('categories') }}" class="text-decoration-none">Categories</a></li>
                    @if(isset($subcategory) && $subcategory->category)
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('category.show', $subcategory->category->slug) }}" class="text-decoration-none">{{ $subcategory->category->name }}</a></li>
                    @endif
                    @if(isset($subcategory))
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">{{ $subcategory->name }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
 --}}


<!-- ======================================================
   SECTION 1 - WHITE BG
======================================================-->
<section class="sub-category-section pt-0" style="background:var(--white);">
    <div class="container">

        <div class="sub-category-header-wrap text-center">
            @if(isset($subcategory) && $subcategory->image)
            <div class="subcategory-image-banner mb-4">
                <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" style="max-height: 300px; width: 100%; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            </div>
            @endif
            <h1 class="sub-category-title">{{ isset($subcategory) ? $subcategory->name : 'Subcategory' }}</h1>
            <span class="sub-category-count">Result: {{ isset($products) ? $products->total() : 0 }} products.</span>

            <p class="sub-category-description mt-3">
                {{ isset($subcategory) && $subcategory->description ? $subcategory->description : 'Browse our collection of products in this category.' }}
            </p>
        </div>

        @if(isset($products) && $products->count() > 0)
        <div class="products-grid row g-4 mt-4">
            @foreach($products as $product)
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-6 mb-4">
                @include('view.partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @else
        <div class="sub-category-grid">
            <p class="text-center w-100">No products found in this subcategory.</p>
        </div>
        @endif

    </div>
</section>


<!-- ======================================================
   SECTION 2 - LIGHT ALT BG
======================================================-->
<section class="sub-category-section" style="background: var(--light-bg-color);">
    <div class="container">




        @include('view.layout.footer')