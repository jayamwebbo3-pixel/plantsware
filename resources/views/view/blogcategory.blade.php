
@include('view.layout.header')

<style>
    /* Final Style Adjustment for Blog Categories to match live site exactly */
    .blog-categories-wrapper {
        padding: 40px 0;
        background-color: #fff;
    }
    .category-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        text-decoration: none !important;
        display: flex;
        flex-direction: column;
        height: 100%;
        color: inherit;
        border: 1px solid #f0f0f0;
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .category-icon {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: #f9f9f9;
    }
    .category-icon img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    .category-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .category-title {
        font-size: 22.4px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 12px;
    }
    .category-description {
        font-size: 15.2px;
        color: #666666;
        margin-bottom: 15px;
        line-height: 1.6;
        flex: 1;
    }
    .category-meta {
        color: #6ea820;
        font-weight: 600;
        font-size: 13.6px;
        padding-top: 15px;
        display: block;
    }
</style>

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