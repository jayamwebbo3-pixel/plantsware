<!-- Blog Section -->
<section class="py-5 bg-light"> 
    <div class="container">
        <!-- Section Heading -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">Latest Blogs</h2>
            <p class="text-muted">Discover tips, care guides, and inspiration for your indoor plants</p>
        </div>

        <!-- Blog Cards Grid -->
        <div class="row g-4">
            
             @forelse($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="card blog-card h-100 shadow-sm border-0 overflow-hidden">
                        
                        <!-- Featured Image -->
                        <!--@if($blog->image)-->
                        <!--    <img src="{{ $blog->image_url }}" class="card-img-top" alt="{{ $blog->title }}" -->
                        <!--         style="height: 220px; object-fit: cover;">-->
                        <!--@else-->
                        <!--    <div class="placeholder-image bg-success text-white d-flex align-items-center justify-content-center" -->
                        <!--         style="height: 220px;">-->
                        <!--        <i class="fas fa-leaf fa-3x"></i>-->
                        <!--    </div>-->
                        <!--@endif-->
                        
                        <!--Featured img not loading fix 22/01/2026-->
                        <!-- Featured Image -->
                        @if($blog->image)
                            <img src="{{ asset($blog->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $blog->title }}" 
                                 style="height: 220px; object-fit: cover;">
                        @else
                            <div class="placeholder-image bg-success text-white d-flex align-items-center justify-content-center" 
                                 style="height: 220px;">
                                <i class="fas fa-leaf fa-3x"></i>
                            </div>
                        @endif
                        <!--end-->

                        <!-- Card Body -->
                        <div class="card-body d-flex flex-column p-4">
                            <!-- Category/Tag Badge (optional) -->
                            @if($blog->tags->isNotEmpty())
                                <span class="badge bg-success mb-2 d-inline-block">
                                    {{ $blog->tags->first()->name }}
                                </span>
                            @endif

                            <!-- Title -->
                            <h5 class="card-title fw-bold mb-3">
                                <a href="{{ route('blog.show', $blog->slug) }}" class="text-dark text-decoration-none">
                                    {{ Str::limit($blog->title, 60) }}
                                </a>
                            </h5>

                            <!-- Excerpt -->
                            <p class="card-text text-muted mb-4">
                                {{ Str::limit(strip_tags($blog->excerpt ?: $blog->content), 100) }}
                            </p>

                            <!-- Meta Info -->
                            <div class="mt-auto d-flex justify-content-between align-items-center small text-muted">
                                <span>
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $blog->published_at ? $blog->published_at->format('d M Y') : 'Draft' }}
                                </span>
                                <span>
                                    <i class="fas fa-user me-1"></i>
                                    {{ $blog->author_name ?? 'Admin' }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer with Read More -->
                        <div class="card-footer bg-white border-0 pt-0 px-4 pb-4">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-outline-success w-100">
                                Read More <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No blogs available yet. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <!-- More Blogs Button -->
   <!-- More Blogs Button - Right aligned, text-only -->
@if($blogs->count() > 0) <!-- Changed from $blog to $blogs -->
    <div class="text-end mt-4"> <!-- Changed from text-center to text-end -->
        <a href="{{ route('blog.index') }}" class="text-success text-decoration-none fw-bold">
            View More Blogs <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
@endif
    </div>
</section>

<style>
    .blog-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .placeholder-image {
        font-size: 4rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

       /* Add this to your existing CSS */
    .text-success.text-decoration-none:hover {
        text-decoration: none !important;
    }


</style>