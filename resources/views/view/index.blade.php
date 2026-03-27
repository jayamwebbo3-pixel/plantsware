@include('view.layout.header')

<section class="plant-categories-section">
    <div class="plant-categories-container">

        <div class="categories-carousel-container">
            <!-- Carousel -->
            <div class="plant-categories-carousel" id="plantCategoriesCarousel">
                @forelse($categories as $category)
                    <div class="plant-category-item">
                        <a href="{{ route('category.show', $category->slug ?? $category->id) }}" class="plant-category-card">
                            <div class="category-image-container">
                                @if($category->badge_type)
                                    <span class="category-badge badge-{{ $category->badge_type }}">{{ \Illuminate\Support\Str::upper($category->badge_type) }}</span>
                                @endif
                                <div class="plant-category-image">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                                    @else
                                        <img src="{{ asset('assets/images/category-placeholder.jpg') }}" alt="{{ $category->name }}">
                                    @endif
                                </div>
                            </div>
                            <div class="plant-category-content">
                                <h3 class="plant-category-name">{{ $category->name }}</h3>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="plant-category-item">
                        <p class="text-center w-100">No categories available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- vertical menu and slider -->
<div id="home_vertical_menu" class="menu_slider ">
    <div class="row ">
        <div class="col-lg-12 col-md-12 main_slider">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                @if($sliders->count() > 0)
                    <ol class="carousel-indicators">
                        @foreach($sliders as $index => $slider)
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach($sliders as $index => $slider)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $slider->image) }}" class="d-block w-100 img-fluid" alt="{{ $slider->title }}">
                                <div class="carousel-caption container silder_text">
                                    @if($slider->subtitle)<p class="arrival">{{ $slider->subtitle }}</p>@endif
                                    @if($slider->title)<h5 class="headding">{{ $slider->title }}</h5>@endif
                                    @if($slider->button_link && $slider->button_text)<a href="{{ $slider->button_link }}" type="btn" class="shop-now">{{ $slider->button_text }}</a>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('uploads/img2/slider/11.png') }}" class="d-block w-100 img-fluid" alt="s1">
                            <div class="carousel-caption container silder_text">
                                <p class="arrival">Complete Care for Every Plant</p>
                                <h5 class="headding">From Soil to<br>Bloom Naturally</h5>
                                <a type="btn" class="shop-now">Shop Now</a>
                            </div>
                        </div>
                    </div>
                @endif
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                    data-slide="prev"></a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                    data-slide="next"></a>
            </div>
        </div>
    </div>
</div>
<!-- vertical menu and slider end -->

<!-- services -->
<div class="container-fluid">
    <div class="main_services">
        <div class="row">
            @php
                $highlights = $serviceHighlights;
                if (empty($highlights)) {
                    $highlights = [
                        ['icon' => 'fas fa-headset', 'title' => 'Fast Delivery', 'description' => 'Fast shipping on all orders'],
                        ['icon' => 'fas fa-shield-alt', 'title' => 'Secure Payment', 'description' => '100% Secure Payment'],
                        ['icon' => 'fas fa-undo', 'title' => 'Easy Returns', 'description' => '30-Day Return Policy'],
                        ['icon' => 'fas fa-award', 'title' => 'Quality Guarantee', 'description' => 'Premium Quality Products']
                    ];
                }
            @endphp
            @foreach($highlights as $index => $item)
            <div class="col-md-3 col-sm-6 col-12 m_service ">
                <ul class="bg-white service service-{{ $index + 1 }} rounded text-center  animate__animated animate__fadeInUp"
                    data-wow-duration="0.8s" data-wow-delay="{{ 0.1 * ($index + 1) }}s">
                    <li class="ser-svg d-lg-inline-block d-md-block  align-middle">
                        @if(isset($item['icon']) && str_starts_with($item['icon'], 'fa'))
                            <i class="{{ $item['icon'] }} service-icon" style="font-size: 32px;"></i>
                        @else
                            <span class="icon-image"></span>
                        @endif
                    </li>
                    <li class="ser-t d-lg-inline-block d-md-block  align-middle text-left">
                        <h6>{{ $item['title'] ?? '' }}</h6>
                        <span class="mb-0 text-muted">{{ $item['description'] ?? '' }}</span>
                    </li>
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- services end -->



<!-- Section 1: New Arrivals -->
<section class="bg-white-section">
    <div class="container-fluid px-4">
        <div class="section-title">
            <h2>New Arrivals</h2>
            <div class="title-link">
                <a href="{{ url('categories') }}">More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                @forelse($newArrivals as $product)
                    <div class="swiper-slide">
                        @include('view.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center w-100">No products found</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!-- Section 2: Garden Products -->
<section class="bg-light-section">
    <div class="container-fluid px-4">
        <div class="section-title">
            <h2>Garden Products</h2>
            <div class="title-link">
                <a href="{{ $gardenCategory ? route('category.show', $gardenCategory->slug) : url('categories') }}">More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                @forelse($gardenProducts as $product)
                    <div class="swiper-slide">
                        @include('view.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center w-100">No products found</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<section class="ad-banner">
    <div class="banner-content">
        <h1 class="big-title">Fresh Plant-Based Goodness</h1>
        <p class="small-title">Discover our organic, sustainable products for a healthier lifestyle</p>
        <a href="{{ url('categories') }}" class="contact-btn">Shop Now <i class="fas fa-leaf"></i></a>
    </div>
</section>

<!-- Section 3: Planted Aquarium Products -->
<section class="bg-white-section">
    <div class="container-fluid px-4">
        <div class="section-title">
            <h2>Planted Aquarium Products</h2>
            <div class="title-link">
                <a href="{{ $aquariumCategory ? route('category.show', $aquariumCategory->slug) : url('categories') }}">More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                @forelse($aquariumProducts as $product)
                    <div class="swiper-slide">
                        @include('view.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center w-100">No products found</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!-- Section 4: Natural Products -->
<section class="bg-light-section">
    <div class="container-fluid px-4">
        <div class="section-title">
            <h2>Natural Products</h2>
            <div class="title-link">
                <a href="{{ $naturalCategory ? route('category.show', $naturalCategory->slug) : url('categories') }}">More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                @forelse($naturalProducts as $product)
                    <div class="swiper-slide">
                        @include('view.partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="swiper-slide"><p class="text-center w-100">No products found</p></div>
                @endforelse
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>

<!---------------------------------------------------------- testimonials ------------------------------------------------------->
<div class="reviews-carousel">
    <div class="carousel-header">
        <h2 class="section-title1 text-center">Trusted by thousands</h2>
        
    </div>

    <!-- Swiper -->
    <div class="swiper testimonial-swiper">
        <div class="swiper-wrapper">
            @forelse(($testimonials ?? []) as $testimonial)
                @php /** @var \App\Models\Testimonial $testimonial */ @endphp
                <div class="swiper-slide">
                    <div class="review-card">
                        <div class="reviewer-info">
                            <div class="reviewer-name">{{ $testimonial->name }}</div>
                            @if($testimonial->is_verified)
                                <div class="verified-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Verified Buyer</span>
                                </div>
                            @endif
                        </div>
                        <div class="review-date">
                            @if($testimonial->date)
                                {{ \Carbon\Carbon::parse($testimonial->date)->format('m/d/y') }}
                            @elseif($testimonial->created_at)
                                {{ $testimonial->created_at->format('m/d/y') }}
                            @endif
                        </div>
                        <div class="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($testimonial->rating ?? 5) ? 'star' : 'star-muted' }}" style="color: {{ $i <= ($testimonial->rating ?? 5) ? 'var(--star-color)' : '#ddd' }}"></i>
                            @endfor
                        </div>
                        @if(isset($testimonial->title) && $testimonial->title)
                            <h3 class="review-title">{{ $testimonial->title }}</h3>
                        @endif
                        <p class="review-content">
                            {{ $testimonial->content ?? '' }}
                        </p>
                    </div>
                </div>
            @empty
                <!-- Default Hardcoded Examples if no DB testimonials -->
                <div class="swiper-slide">
                    <div class="review-card">
                        <div class="reviewer-info">
                            <div class="reviewer-name">Emma J.</div>
                            <div class="verified-badge">
                                <i class="fas fa-badge-check"></i>
                                <span>Verified Buyer</span>
                            </div>
                        </div>
                        <div class="review-date">11/07/25</div>
                        <div class="star-rating">
                            <i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i>
                        </div>
                        <h3 class="review-title">Very happy with all of</h3>
                        <p class="review-content">
                            Very happy with all of my new plants. Prices were good, delivery was fast, and the plants are gorgeous.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
        
    </div>

    <!-- Navigation -->
    <div class="carousel-nav">
        <div class="nav-btn swiper-button-prev1">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="nav-btn swiper-button-next1">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>

<section class="bg-light-section">
    <div class="container-fluid px-4">
        <div class="section-title">
            <h2>Blogs</h2>
            <div class="title-link">
                <a href="{{ route('blog.index') }}">More <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>

        <div class="col-12">
            <div class="blog-grid">
                <div class="row g-4">
                    @forelse(($blogs ?? []) as $blog)
                        @php /** @var \App\Models\Blog $blog */ @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="blog-card">
                                <div class="blog-card-image">
                                    @if(isset($blog->image) && $blog->image)
                                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/product/product11.jpg') }}" alt="{{ $blog->title }}">
                                    @endif
                                    <span class="blog-card-category">{{ $blog->category->name ?? 'General' }}</span>
                                </div>
                                <div class="blog-card-content">
                                    <div class="blog-card-date">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : ($blog->created_at ? $blog->created_at->format('M d, Y') : '') }}</div>
                                    <h3 class="blog-card-title">{{ $blog->title }}</h3>
                                    <p class="blog-card-excerpt">
                                        {{ isset($blog->content) ? \Illuminate\Support\Str::limit(strip_tags($blog->content), 100) : '' }}
                                    </p>
                                    <div class="blog-card-footer">
                                        <span class="blog-card-author">{{ $blog->author_name ?? 'Admin' }}</span>
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="read-more-link">Read →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Default Blog Cards -->
                        <div class="col-md-6 col-lg-4">
                            <div class="blog-card">
                                <div class="blog-card-image">
                                    <img src="{{ asset('assets/images/product/product11.jpg') }}" alt="Indoor plant care">
                                    <span class="blog-card-category">Plant Care</span>
                                </div>
                                <div class="blog-card-content">
                                    <div class="blog-card-date">Nov 15, 2024</div>
                                    <h3 class="blog-card-title">10 Essential Tips to Keep Indoor Plants Healthy</h3>
                                    <p class="blog-card-excerpt">
                                        Learn the fundamental watering, lighting, and soil requirements that help indoor plants grow stronger and greener.
                                    </p>
                                    <div class="blog-card-footer">
                                        <span class="blog-card-author">Sophia Green</span>
                                        <a href="#" class="read-more-link">Read →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@include('view.layout.footer')

<!-- Notification after Add to Cart -->
<div id="cart-alert-container" style="position: fixed; z-index: 99999; left: 50%; transform: translateX(-50%); top: 20px; display: none;">
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="cart-added-alert" style="min-width: 250px;">
        Item added with success!
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart-form, .wishlist-form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            setTimeout(function() {
                var alertContainer = document.getElementById('cart-alert-container');
                if (alertContainer) {
                    alertContainer.style.display = 'block';
                    setTimeout(function() {
                        alertContainer.style.display = 'none';
                    }, 2000);
                }
            }, 200);
        });
    });
});
</script>