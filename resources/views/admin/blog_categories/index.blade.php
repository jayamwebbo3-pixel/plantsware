@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Blog Category Management</h1>
        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm border-0 border-top border-4 border-primary">
                    <div class="position-relative">
                        @if($category->image)
                            <img src="{{ asset($category->image) }}" class="card-img-top" alt="{{ $category->name }}" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-image fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $category->name }}</h5>
                        <p class="card-text text-muted small">
                            {{ Str::limit(strip_tags($category->description), 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="small text-secondary">
                                <i class="fas fa-newspaper me-1"></i> {{ $category->blogs_count }} Blogs
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3 px-3">
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.blogs.index', ['category' => $category->id]) }}" class="btn btn-outline-primary btn-sm flex-grow-1 text-nowrap">
                                Manage Blogs
                            </a>
                            <a href="{{ route('admin.blog-categories.edit', $category->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.blog-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

<style>
    .border-primary { border-color: #6ea820 !important; }
    .btn-primary { background-color: #6ea820; border-color: #6ea820; }
    .btn-primary:hover { background-color: #5d8e1a; border-color: #5d8e1a; }
    .btn-outline-primary { color: #6ea820; border-color: #6ea820; }
    .btn-outline-primary:hover { background-color: #6ea820; color: #fff; }
</style>
@endsection
