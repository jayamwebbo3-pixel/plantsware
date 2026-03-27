@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 text-gray-800">Edit Blog Category</h1>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.blog-categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description / Category Summary</label>
                            <textarea id="description" name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                @if($category->image)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold d-block">Current Image</label>
                                        <img src="{{ asset($category->image) }}" class="img-fluid rounded mb-2 border p-1" style="max-height: 180px;">
                                    </div>
                                @endif
                                
                                <div class="mb-3 text-start">
                                    <label for="image" class="form-label fw-bold">Update Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="text-muted small">Keep empty to leave image unchanged.</small>
                                </div>

                                <div class="mb-3 mt-4 text-start">
                                    <label class="form-label fw-bold d-block text-secondary">Status</label>
                                    <div class="form-check form-switch custom-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Is Active?</label>
                                    </div>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-save me-1"></i> Update Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/41.4.2/ckeditor5.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
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
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: data
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server returned ' + response.status + ' - ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(result => {
                        if (result.uploaded && result.url) {
                            resolve({ default: result.url });
                        } else {
                            reject(result.error || 'No URL in response');
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        reject(error);
                    });
                }));
            }

            abort() {}
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = loader => new MyUploadAdapter(loader);
        }

        ClassicEditor
            .create(document.querySelector('#description'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'alignment', '|',
                    'blockQuote', 'insertTable', '|',
                    'imageUpload', 'mediaEmbed', '|',
                    'undo', 'redo'
                ],
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ]
                },
                mediaEmbed: {
                    previewsInData: true
                },
                pasteFromOffice: {
                    removeStyles: false
                }
            })
            .then(editor => {
                console.log('CKEditor ready with custom adapter!');
            })
            .catch(error => {
                console.error('CKEditor error:', error);
            });
    </script>
@endpush

<style>
    .btn-primary { background-color: #6ea820; border-color: #6ea820; }
    .btn-primary:hover { background-color: #5d8e1a; border-color: #5d8e1a; }
</style>
@endsection
