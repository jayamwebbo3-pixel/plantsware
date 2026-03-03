@extends('admin.layout')

@section('title', 'Edit ' . $page->title)

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Edit Informative Page: {{ $page->title }}</h4>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST">
                @csrf
                @method('PUT')

                @if($page->slug === 'return-refund-policy')
                    <div class="mb-4">
                        <label for="policy_date" class="form-label fw-bold">Return Window End Date / Effective Date</label>
                        <input type="date" name="policy_date" id="policy_date" class="form-control" style="max-width: 300px;" value="{{ old('policy_date', $page->policy_date) }}">
                        <small class="text-muted">You can adjust the editable date for the Return/Refund policy here.</small>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="editor" class="form-label fw-bold">Page Content</label>
                    <textarea name="content" id="editor" class="form-control">{{ old('content', $page->content) }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light ms-2">Cancel</a>
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
    </script>
@endpush
