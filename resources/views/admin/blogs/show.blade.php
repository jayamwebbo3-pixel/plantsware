@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>{{ $blog->title }}</h3>
                <small>Published: {{ $blog->published_at?->format('d M Y') ?? 'Draft' }}</small>
            </div>

            <div class="card-body">
                <!-- @if($blog->image)
                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid mb-4" style="max-height:400px;">
                @endif -->
 
                <!--@if($blog->image)-->
                <!--    <img src="{{ $blog->image_url }}" -->
                <!--        alt="{{ $blog->title }}" -->
                <!--        class="img-fluid mb-4" -->
                <!--        style="max-height:200px; object-fit: cover;">-->
                <!--@else-->
                <!--    <div class="alert alert-info">No primary image uploaded for this blog.</div>-->
                <!--@endif-->
                
                <!--primary img not showing issue fix 21/01/2026-->
                        @if($blog->image)
            <img src="{{ asset($blog->image) }}" 
                alt="{{ $blog->title }}" 
                class="img-fluid mb-4" 
                style="max-height:200px; object-fit: cover;">
        @else
            <div class="alert alert-info">No primary image uploaded for this blog.</div>
        @endif

                <!--end here -->

                <!--<div class="blog-content">-->
                <!--    {!! $blog->content !!}   {{-- Very important: {!! !!} renders HTML --}}-->
                <!--</div>-->
                
                <!--new change here 22/01/2026 Youtube video aligment fix -->
                <div class = "blog-content">
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
                <!--end here -->
                

                <hr>
<!-- 
                <div>
                    <strong>Category:</strong> {{ $blog->blogCategory?->name ?? 'None' }}<br>
                    <strong>Tags:</strong> 
                    @foreach($blog->tags as $tag)
                        <span class="badge bg-info me-1">{{ $tag->name }}</span>
                    @endforeach
                </div> -->

                <hr>

                <h4>Related Blogs</h4>
                @if($related->count() > 0)
                    <ul>
                        @foreach($related as $rel)
                            <li><a href="{{ route('admin.blogs.show', $rel) }}">{{ $rel->title }}</a></li>
                        @endforeach
                    </ul>
                @else
                    <p>No related blogs yet.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection