
@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder text-capitalize"><a href="{{ url('/') }}" class="text-decoration-none" style="color: #333;">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder text-capitalize">
                        @if(isset($title))
                            <a href="{{ route('blog.index') }}" class="text-decoration-none" style="color: #666;">Blogs</a>
                        @else
                            <a href="#" class="text-decoration-none" style="color: #333;">Blogs</a>
                        @endif
                    </li>
                    @if(isset($title))
                        <li class="d-inline-block font-weight-bolder mx-2">/</li>
                        <li class="d-inline-block font-weight-bolder text-capitalize"><a href="#" class="text-decoration-none" style="color: #333;">{{ str_replace('Category: ', '', $title) }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

    <!-- Blogs Grid -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="blog-card">
                            <div class="blog-card-image">
                                <a href="{{ route('blog.show', $blog->slug) }}" class="d-block w-100 h-100">
                                    @if($blog->image)
                                        <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/seed-to-plant.webp') }}" alt="{{ $blog->title }}">
                                    @endif
                                </a>
                                <span class="blog-card-category">{{ $blog->category->name ?? 'General' }}</span>
                            </div>
                            <div class="blog-card-content">
                                <div class="blog-card-date">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : ($blog->created_at ? $blog->created_at->format('M d, Y') : '') }}</div>
                                <h3 class="blog-card-title">
                                    <a href="{{ route('blog.show', $blog->slug) }}">
                                        {{ $blog->title }}
                                    </a>
                                </h3>
                                <p class="blog-card-excerpt">
                                    {{ Str::limit(strip_tags($blog->content), 120) }}
                                </p>
                                <div class="blog-card-footer">
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="read-more-link">Read <i class="fas fa-arrow-right"></i></a>
                                </div>
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
                  {{ $blogs->links('pagination::bootstrap-5') }}
            </div>
        </div> 
    </section>

@include('view.layout.footer')
