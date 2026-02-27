@include('view.layout.header')

<style>
    .single-blog-share {
        align-items: center;
        justify-content: center;
        display: flex;
        flex-direction: row;
        gap: 15px; /* Add space between icons */
        flex-wrap: nowrap; /* Prevent wrapping */
        margin: 20px 0;
    }

    .single-blog-share .share-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex; /* Changed from inline-flex to flex */
        align-items: center !important;
        justify-content: center !important;
        color: #fff;
        font-size: 18px;
        transition: transform 0.2s ease, opacity 0.2s ease;
        border: none;
        text-decoration: none; /* Remove underline from links */
        cursor: pointer;
        padding: 0; /* Reset padding */
    }

    .single-blog-share .share-btn:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }

    .share-btn.facebook { background: #1877f2; }
    .share-btn.linkedin { background: #0a66c2; }
    .share-btn.twitter  { background: #1da1f2; }
    .share-btn.instagram {
        background: radial-gradient(circle at 30% 107%,
            #fdf497 0%, #fdf497 5%,
            #fd5949 45%, #d6249f 60%, #285AEB 90%);
    }
    
    /* Make sure the icons are properly centered */
    .single-blog-share .share-btn i {
        display: block;
        line-height: 1;
    }
</style>

<div class="sp_header bg-white p-3"> 
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('home') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="{{ route('blog.categories') }}" class="text-decoration-none">Blogs</a></li>
                    @if(isset($blog) && $blog->category)
                        <li class="d-inline-block font-weight-bolder mx-2">/</li>
                        <li class="d-inline-block font-weight-bolder"><a href="{{ route('blog.category.show', $blog->category->slug) }}" class="text-decoration-none">{{ $blog->category->name }}</a></li>
                    @endif
                    @if(isset($blog))
                        <li class="d-inline-block font-weight-bolder mx-2">/</li>
                        <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">{{ $blog->title }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Article Main -->
            <div class="col-lg-8">
                @if(isset($blog))
                    <!-- Article Header -->
                    <article class="single-blog-header">
                        @if($blog->category)
                            <span class="single-blog-category">{{ $blog->category->name }}</span>
                        @endif
                        <h1 class="single-blog-title">{{ $blog->title }}</h1>
                        <div class="single-blog-meta">
                            <div class="single-blog-meta-item">
                                <span class="single-blog-meta-item-label">By</span>
                                <span>{{ $blog->author_name ?? 'Admin' }}</span>
                            </div>
                            <div class="single-blog-meta-item">
                                <span class="single-blog-meta-item-label">Published</span>
                                <span>{{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </article>

                    <!-- Featured Image -->
                    <!--@if($blog->image)-->
                    <!--    <div class="single-blog-image">-->
                    <!--        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">-->
                    <!--    </div>-->
                    <!--@else-->
                    <!--    <div class="single-blog-image">-->
                    <!--        <img src="{{ asset('assets/images/product/product5.jpg') }}" alt="{{ $blog->title }}" class="img-fluid">-->
                    <!--    </div>-->
                    <!--@endif-->
                    
                    <!--Image not loading in single blog fix 22/01/2026-->
                    <!-- Featured Image -->
                    @if($blog->image)
                        <div class="single-blog-image">
                            <img src="{{ asset($blog->image) }}" 
                                 alt="{{ $blog->title }}" 
                                 class="img-fluid">
                        </div>
                    @else
                        <div class="single-blog-image">
                            <img src="{{ asset('assets/images/product/product5.jpg') }}" 
                                 alt="{{ $blog->title }}" 
                                 class="img-fluid">
                        </div>
                    @endif

                    <!--end-->

                    <!-- Article Body / Content -->

<div class="single-blog-body">
    {!! \Illuminate\Support\Str::of($blog->content)
        ->replaceMatches('/<figure class="media">\s*<div data-oembed-url="([^"]+)">[\s\S]*?<\/figure>/', function ($match) {
            $url = $match[1];

            // YouTube detection
            if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m);
                $videoId = $m[1] ?? '';
                if ($videoId) {

       return '<div class="mb-4 youtube-video-embed" style="width: 70%;">
            <div style="position: relative; padding-bottom: 56.25%; height: 0; min-height: 200px; background: #000;">
                <iframe src="https://www.youtube.com/embed/' . $videoId . '?rel=0&modestbranding=1" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                        title="YouTube video" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
            </div>
        </div>';
                }
            }

            // Generic fallback
            return '<div class="youtube-video-wrapper mb-4">
                        <div class="ratio ratio-16x9">
                            <iframe src="' . $url . '" 
                                    frameborder="0" 
                                    allowfullscreen></iframe>
                        </div>
                    </div>';
        })
        ->toHtmlString() !!}
</div>
                                    @else
                    <p>Blog post not found.</p>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Table of Contents -->
                <div class="single-blog-sidebar-widget">
                    <div class="single-blog-sidebar-title">Table of Contents</div>
                    <a href="#" class="single-blog-sidebar-link">Essential Gardening Tools</a>
                    <a href="#" class="single-blog-sidebar-link">Hand Tools</a>
                    <a href="#" class="single-blog-sidebar-link">Long-Handled Tools</a>
                    <a href="#" class="single-blog-sidebar-link">Soil and Fertilizer Products</a>
                    <a href="#" class="single-blog-sidebar-link">Watering and Irrigation Products</a>
                    <a href="#" class="single-blog-sidebar-link">Pest and Disease Control</a>
                </div>

                <!-- Related Category -->
                <div class="single-blog-sidebar-widget">
                    <div class="single-blog-sidebar-title">Related Contents</div>
                    <a href="#" class="single-blog-sidebar-link">Best Tools for Small Spaces</a>
                    <a href="#" class="single-blog-sidebar-link">Seasonal Garden Product Guide</a>
                    <a href="#" class="single-blog-sidebar-link">Budget-Friendly Garden Products</a>
                    <a href="#" class="single-blog-sidebar-link">Eco-Friendly Garden Supplies</a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- social share section  -->
 @if(isset($blog))
<div class="single-blog-share mt-5">
    <h5 class="mb-3 fw-bold">Share this article</h5>

    @php
        $shareUrl = urlencode(url()->current());
        $shareTitle = urlencode($blog->title);
    @endphp

    <div class="d-flex align-items-center gap-3">
        <!-- Facebook -->
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
           target="_blank"
           class="share-btn facebook"
           title="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>

        <!-- LinkedIn -->
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
           target="_blank"
           class="share-btn linkedin"
           title="Share on LinkedIn">
            <i class="fab fa-linkedin-in"></i>
        </a>

        <!-- Twitter / X (optional but recommended) -->
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
           target="_blank"
           class="share-btn twitter"
           title="Share on Twitter">
            <i class="fab fa-twitter"></i>
        </a>

        <!-- Instagram (copy link) -->
        <button onclick="copyBlogLink()" class="share-btn instagram" title="Copy link for Instagram">
            <i class="fab fa-instagram"></i>
        </button>
    </div>
</div>
@endif

 <!-- social share section end  -->

@include('view.layout.footer')