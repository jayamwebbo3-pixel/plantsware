@extends('admin.layout')

@section('title', 'Add New Slider')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-chevron-left"></i> Back to Sliders
    </a>
</div>

<div class="card shadow">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Add New Home Slider</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <!-- Image Upload Section -->
                <div class="col-md-5 border-end">
                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Slider Image <span class="text-danger">*</span></label>
                        <div class="p-4 border rounded bg-light text-center">
                            <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded mb-3 shadow-sm" style="display: none; max-height: 250px;">
                            <div id="imagePlaceholder">
                                <i class="fas fa-image fa-4x text-muted mb-2"></i>
                                <p class="text-muted small">Select an image for the slider background</p>
                            </div>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required 
                                onchange="if(this.files[0]) { document.getElementById('imagePlaceholder').style.display='none'; document.getElementById('imagePreview').src = window.URL.createObjectURL(this.files[0]); document.getElementById('imagePreview').style.display = 'block'; }">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted d-block mt-2">Recommended size: 1920x800px or similar aspect ratio. Max size: 2MB.</small>
                    </div>
                </div>

                <!-- Content Details Section -->
                <div class="col-md-7">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Main Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. From Soil To Bloom Naturally">
                        <small class="text-muted">Primary large text on the slider</small>
                    </div>

                    <div class="mb-3">
                        <label for="subtitle" class="form-label fw-bold">Subtitle</label>
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Complete Care For Every Plant">
                        <small class="text-muted">Smaller text shown above or below title</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="button_text" class="form-label fw-bold">Button Text</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text', 'SHOP NOW') }}" placeholder="e.g. SHOP NOW">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="button_link" class="form-label fw-bold">Button Link</label>
                            <input type="text" class="form-control" id="button_link" name="button_link" value="{{ old('button_link', '/products') }}" placeholder="e.g. /products or https://...">
                        </div>
                    </div>

                    <div class="row align-items-center mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label fw-bold">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Publish Slider</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-save me-2"></i> Save Slider
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
