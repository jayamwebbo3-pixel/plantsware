@include('view.layout.header')

<style>
    /* Styling for the single blog page */
    .single-blog-header {
        margin-bottom: 2rem;
    }
    .single-blog-category {
        display: inline-block;
        background-color: #e6f7e9;
        color: #2d8a39;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .single-blog-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a202c;
        line-height: 1.2;
        margin-bottom: 1.5rem;
    }
    .single-blog-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #718096;
        font-size: 0.95rem;
    }
    .single-blog-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .single-blog-meta-item-label {
        font-weight: 600;
        color: #4a5568;
    }
    .single-blog-image {
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .single-blog-image img {
        width: 100%;
        height: auto;
        display: block;
    }
    .single-blog-body {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #2d3748;
    }
    .single-blog-body h2 {
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        color: #1a202c;
    }
    .single-blog-body h3 {
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #2d3748;
    }
    .single-blog-body p {
        margin-bottom: 1.5rem;
    }
    .single-blog-body ul, .single-blog-body ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }
    .single-blog-body li {
        margin-bottom: 0.5rem;
    }
    .single-blog-highlight-box {
        background: #f7fafc;
        border-left: 5px solid #48bb78;
        padding: 25px;
        border-radius: 8px;
        margin: 2.5rem 0;
        font-style: italic;
    }
    
    /* Sidebar Widgets */
    .single-blog-sidebar-widget {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: 1px solid #f0f0f0;
    }
    .single-blog-sidebar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #edf2f7;
    }
    .single-blog-sidebar-link {
        display: block;
        color: #4a5568;
        text-decoration: none;
        padding: 10px 0;
        border-bottom: 1px solid #f7fafc;
        transition: all 0.2s;
        font-weight: 500;
    }
    .single-blog-sidebar-link:hover {
        color: #38a169;
        padding-left: 5px;
    }
    .single-blog-sidebar-link:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .single-blog-title {
            font-size: 1.8rem;
        }
    }
</style>

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none" style="color: #666;">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('blog.index') }}" class="text-decoration-none" style="color: #666;">Blogs</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none" style="color: #333;">{{ $blog->title }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="py-4 bg-white">
    <div class="container">
        <div class="row">
            <!-- Article Main -->
            <div class="col-lg-8">
                <!-- Article Header -->
                <article class="single-blog-header">
                    <span class="single-blog-category">{{ $blog->category->name ?? 'Plant Care' }}</span>
                    <h1 class="single-blog-title">{{ $blog->title }}</h1>
                    <div class="single-blog-meta">
                        <div class="single-blog-meta-item">
                            <span class="single-blog-meta-item-label">By</span>
                            <span>{{ $blog->author_name ?: 'Admin' }}</span>
                        </div>
                        <div class="single-blog-meta-item">
                            <span class="single-blog-meta-item-label">Published</span>
                            <span>{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </article>

                <!-- Featured Image -->
                <div class="single-blog-image">
                    @if($blog->image)
                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                    @else
                        <img src="{{ asset('assets/images/product/product5.jpg')}}" alt="{{ $blog->title }}">
                    @endif
                </div>

                <!-- Article Body -->
                <div class="single-blog-body ck-content">
                    {!! $blog->content !!}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Related Category -->
                @php
                    $relatedBlogs = \App\Models\Blog::where('blog_category_id', $blog->blog_category_id)
                        ->where('id', '!=', $blog->id)
                        ->active()
                        ->take(5)
                        ->get();
                @endphp
                
                @if($relatedBlogs->isNotEmpty())
                <div class="single-blog-sidebar-widget">
                    <div class="single-blog-sidebar-title">Related Contents</div>
                    @foreach($relatedBlogs as $related)
                        <a href="{{ route('blog.show', $related->slug) }}" class="single-blog-sidebar-link">
                            {{ $related->title }}
                        </a>
                    @endforeach
                </div>
                @endif

                <!-- Tag Cloud or Popular Categories -->
                <div class="single-blog-sidebar-widget">
                    <div class="single-blog-sidebar-title">Categories</div>
                    @php
                        $allCats = \App\Models\BlogCategory::active()->withCount('blogs')->get();
                    @endphp
                    @foreach($allCats as $cat)
                        <a href="{{ route('blog.category.show', $cat->slug) }}" class="single-blog-sidebar-link d-flex justify-content-between">
                            <span>{{ $cat->name }}</span>
                            <span class="badge rounded-pill bg-light text-dark">{{ $cat->blogs_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@include('view.layout.footer')