
@include('view.layout.header')


    <!-- Page Header --> 
    <div class="bg-light py-5">
        <div class="container text-center">
            <h1 class="fw-bold mb-3">All Blogs</h1>
            <p class="text-muted lead">Explore all our plant care tips, guides, and inspiration</p>
        </div>
    </div>

    <!-- Blogs Grid -->
    <section class="py-5">
        <div class="container">
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
                            
                            <!--Feature img not loading in multiblog fix 22/01/2026 -->
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

                            <!--end here -->

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column p-4">
                                <!-- Tags Badge -->
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
                                <p class="card-text text-muted mb-4 flex-grow-1">
                                    {{ Str::limit(strip_tags($blog->excerpt ?: $blog->content), 100) }}
                                </p>

                                <!-- Meta -->
                                <div class="mt-auto d-flex justify-content-between align-items-center small text-muted">
                                    <span><i class="far fa-calendar-alt me-1"></i> {{ $blog->published_at ? $blog->published_at->format('d M Y') : 'Draft' }}</span>
                                    <span><i class="fas fa-user me-1"></i> {{ $blog->author_name ?? 'Admin' }}</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="card-footer bg-white border-0 pt-0 px-4 pb-4">
                                <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-outline-success w-100">
                                    Read More <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-4">No blogs found yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                <!-- {{ $blogs->links() }} -->
                  {{ $blogs->links('pagination::bootstrap-5') }}

            </div>
        </div> 
    </section>


@include('view.layout.footer')