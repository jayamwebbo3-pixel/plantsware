@extends('admin.layout')

@section('title', 'Edit ' . $page->title)

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit Informative Page: {{ $page->title }}</h4>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($page->slug === 'return-refund-policy')
                    <div class="mb-4">
                        <label for="policy_date" class="form-label fw-bold">Return Window End Date / Effective Date</label>
                        <input type="date" name="policy_date" id="policy_date" class="form-control" style="max-width: 300px;" value="{{ old('policy_date', $page->policy_date) }}">
                        <small class="text-muted">You can adjust the editable date for the Return/Refund policy here.</small>
                    </div>
                @endif

                @if($page->slug === 'about-us' || $page->slug === 'services')
                    @if($page->slug === 'about-us')
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">About Image</label>
                        @if($page->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $page->image) }}" alt="About Image" style="max-width: 200px;" class="img-thumbnail">
                            </div>
                        @else
                            <p class="text-muted small">Current: assets/images/about.jpg (Default)</p>
                        @endif
                        <input type="file" name="image" id="image" class="form-control">
                        <small class="text-muted">Upload a new image to replace the current one.</small>
                    </div>
                    @endif

                    @if($page->slug !== 'services')
                    <div class="mb-4">
                        <label for="editor" class="form-label fw-bold">Page Content</label>
                        <textarea name="content" id="editor" class="form-control">{{ old('content', $page->content) }}</textarea>
                    </div>
                    @endif

                    @if($page->slug !== 'services')
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light ms-2">Cancel</a>
                    </div>
                    @endif

                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-success btn-sm" id="add-feature"><i class="fas fa-plus"></i> Add New Feature</button></div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ $page->slug === 'about-us' ? 'Why Choose Us Features' : 'Service Highlights' }}</label>
                        <div id="features-container">
                            @php
                                $features = $page->extra_content['features'] ?? [];
                                if (empty($features)) {
                                    if ($page->slug === 'about-us') {
                                        $features = [
                                            ['icon' => 'fas fa-leaf', 'title' => 'Eco-Friendly Products', 'description' => 'All our products are carefully selected for their environmental sustainability and minimal ecological impact.'],
                                            ['icon' => 'fas fa-award', 'title' => 'Premium Quality', 'description' => 'We source only the highest quality products that meet our rigorous standards for performance and durability.'],
                                            ['icon' => 'fas fa-shipping-fast', 'title' => 'Fast Shipping', 'description' => 'Free shipping on orders over ₹50. Most orders ship within 24 hours and arrive within 3-5 business days.']
                                        ];
                                    } else {
                                        $features = [
                                            ['icon' => 'fas fa-headset', 'title' => 'Fast Delivery', 'description' => 'Fast Shipping On All Orders'],
                                            ['icon' => 'fas fa-coins', 'title' => 'Secure Payment', 'description' => '100% Secure Payment'],
                                            ['icon' => 'fas fa-truck', 'title' => 'Easy Returns', 'description' => '30-Day Return Policy'],
                                            ['icon' => 'fas fa-gift', 'title' => 'Quality Guarantee', 'description' => 'Premium Quality Products']
                                        ];
                                    }
                                }
                            @endphp

                            <table class="table table-bordered" id="features-table">
                                <thead>
                                    <tr>
                                        <th>Icon (FontAwesome class)</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($features as $index => $feature)
                                        <tr class="feature-row">
                                            <td>
                                                <input type="text" name="features[{{ $index }}][icon]" class="form-control" value="{{ $feature['icon'] ?? '' }}" placeholder="fas fa-star">
                                            </td>
                                            <td>
                                                <input type="text" name="features[{{ $index }}][title]" class="form-control" value="{{ $feature['title'] ?? '' }}" placeholder="Feature Title">
                                            </td>
                                            <td>
                                                <textarea name="features[{{ $index }}][description]" class="form-control" rows="2" placeholder="Feature Description">{{ $feature['description'] ?? '' }}</textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-feature"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="mb-4">
                        <label for="editor" class="form-label fw-bold">Page Content</label>
                        <textarea name="content" id="editor" class="form-control">{{ old('content', $page->content) }}</textarea>
                    </div>
                @endif
                
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        // Custom Upload Adapter (reusing from blog integration)
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);

                    fetch('{{ route("admin.ckeditor.upload") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: data
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            resolve({ default: result.url }); 
                        } else {
                            reject(result.error || 'Upload failed');
                        }
                    })
                    .catch(error => reject(error));
                }));
            }

            abort() {}
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = loader => new MyUploadAdapter(loader);
        }

        ClassicEditor
            .create(document.querySelector('#editor'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'imageUpload', 'mediaEmbed', '|',
                    'undo', 'redo'
                ]
            })
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });

        // Dynamic Features Logic
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-feature');
            const tableBody = document.querySelector('#features-table tbody');
            let rowIndex = {{ isset($features) ? count($features) : 0 }};

            if (addBtn) {
                addBtn.addEventListener('click', function() {
                    const newRow = `
                        <tr class="feature-row">
                            <td>
                                <input type="text" name="features[${rowIndex}][icon]" class="form-control" placeholder="fas fa-star">
                            </td>
                            <td>
                                <input type="text" name="features[${rowIndex}][title]" class="form-control" placeholder="Feature Title">
                            </td>
                            <td>
                                <textarea name="features[${rowIndex}][description]" class="form-control" rows="2" placeholder="Feature Description"></textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-feature"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', newRow);
                    rowIndex++;
                });
            }

            if (tableBody) {
                tableBody.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-feature')) {
                        e.target.closest('.feature-row').remove();
                    }
                });
            }
        });
    </script>
@endpush
