@extends('admin.layout')

@section('title', 'Subcategories - ' . $category->name)

@section('content')
<!-- <div class="mb-4">
    <a href="{{ route('admin.products.management') }}" class="text-decoration-none">
        <i class="fas fa-arrow-left"></i> Back to Categories
    </a>
</div> -->

<div class="d-flex justify-content-between align-items-center mb-4 ">
    <h4 class="text-secondary">Subcategories in "{{ $category->name }}"</h4>
    <div class="d-flex gap-2">
    <a href="{{ route('admin.products.management') }}" class="text-decoration-none">
        <i class="fas fa-arrow-left"></i> Back to Categories
    </a>
    <a href="{{ route('admin.categories.subcategories.create', ['category' => $category->id]) }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Subcategory
    </a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        {{-- Search & Filters --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('admin.categories.subcategories', ['category' => $category->id]) }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search subcategories..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <form action="{{ route('admin.categories.subcategories', ['category' => $category->id]) }}" method="GET" class="d-inline">
                    <label class="me-2">Show</label>
                    <select name="per_page" onchange="this.form.submit()" class="form-select d-inline w-auto">
                        <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 20) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'sort_order') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
                </form>
            </div>
        </div>

        <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name
                        <a href="{{ route('admin.categories.subcategories', [
                                'category' => $category->id,
                                'sort' => 'name',
                                'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc',
                                'search' => request('search'),
                                'per_page' => request('per_page', 20),
                            ]) }}">
                            
                            @if(request('sort') == 'name')
                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @endif
                        </a>
                    </th>
                    <th>Sort Order
                        <a href="{{ route('admin.categories.subcategories', [
                                'category' => $category->id,
                                'sort' => 'sort_order',
                                'direction' => request('sort') == 'sort_order' && request('direction') == 'asc' ? 'desc' : 'asc',
                                'search' => request('search'),
                                'per_page' => request('per_page', 20),
                            ]) }}">
                            
                            @if(request('sort') == 'sort_order')
                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @endif
                        </a>
                    </th>
                    {{-- <th>Status</th> --}}
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subcategories as $subcategory)
                <tr>
                    <td>
                       {{ $loop->iteration }}
                    </td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->sort_order }}</td>
                    {{-- <td>
                        <span class="badge {{ $subcategory->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td> --}}
                    <td class="text-nowrap">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.subcategories.products', $subcategory) }}" class="btn btn-sm btn-outline-primary">
                                View Products &rarr;
                            </a>
                            <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('Are you sure?', this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No subcategories yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $subcategories->firstItem() }} to {{ $subcategories->lastItem() }} of {{ $subcategories->total() }} entries
            </div>
            {{ $subcategories->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
