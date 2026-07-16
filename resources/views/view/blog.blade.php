
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
                        <a href="{{ route('blog.index') }}" class="text-decoration-none" style="color: #333;">Blogs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
 --}}

    <!-- Blogs Grid -->
    <section class="blog-categories-wrapper">
        <div class="container">
            <div class="row g-4">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="category-card">
                            <div class="category-icon">
                                @if($blog->image)
                                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                                @else
                                    <img src="{{ asset('assets/images/seed-to-plant.webp') }}" alt="{{ $blog->title }}">
                                @endif
                                <span class="blog-card-category">{{ $blog->category->name ?? 'General' }}</span>
                            </div>
                            <div class="category-content">
                                <h3 class="category-title">{{ $blog->title }}</h3>
                                <div class="category-description">
                                    {{ Str::limit(strip_tags($blog->content), 120) }}
                                </div>
                                <div class="category-meta">
                                    <span style="color: #888; font-weight: 500;"><i class="far fa-calendar-alt me-1"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : ($blog->created_at ? $blog->created_at->format('M d, Y') : '') }}</span>
                                    <span>Read More →</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-4">No blogs found yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                  {{ $blogs->links('pagination::bootstrap-5') }}
            </div>
        </div> 
    </section>

@include('view.layout.footer')
