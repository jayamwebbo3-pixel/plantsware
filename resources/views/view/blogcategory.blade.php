
@include('view.layout.header')



{{-- 
<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder">
                        <a href="{{ url('/') }}" class="text-decoration-none" style="color: #333;">home</a>
                    </li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder">
                        <a href="#" class="text-decoration-none" style="color: #333;">Blogs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
 --}}

<section class="blog-categories-wrapper">
    <div class="container">
        <div class="row g-4">
            @foreach($categories as $category)
                @php
                    $img = $category->image ?: 'assets/images/product/product11.jpg';
                @endphp
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('blog.category.show', $category->slug) }}" class="category-card">
                        <div class="category-icon">
                            <img src="{{ asset($img) }}" alt="{{ $category->name }}">
                        </div>
                        <div class="category-content">
                            <h3 class="category-title">{{ $category->name }}</h3>
                            <div class="category-description">
                                {!! Str::limit(strip_tags($category->description), 150) !!}
                            </div>
                            <!-- <div class="category-meta">
                                <span>Learn More →</span>
                            </div> -->
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('view.layout.footer')