@include('view.layout.header')

<style>
    /* USER REQUESTED BLOCKQUOTE STYLE (Pro Tip / Key Takeaway) */
    .ck-content blockquote {
        background: linear-gradient(135deg, rgba(110, 168, 32, 0.05) 0%, rgba(74, 120, 86, 0.05) 100%);
        border-left: 4px solid var(--primary-color, #73bb44);
        padding: 20px;
        margin: 30px 0;
        border-radius: 8px;
        font-style: normal;
    }
    .ck-content blockquote strong {
        color: var(--primary-color, #73bb44);
        font-weight: 700;
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