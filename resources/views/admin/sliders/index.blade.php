@extends('admin.layout')

@section('title', 'Manage Sliders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Home Sliders</h2>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Slider
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80">Sort</th>
                        <th width="150">Image</th>
                        <th>Content</th>
                        <th>Button</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                        <tr>
                            <td>{{ $slider->sort_order }}</td>
                            <td>
                                @if($slider->image)
                                    <img src="{{ asset('storage/' . $slider->image) }}" alt="Slider" class="img-thumbnail" style="height: 60px; width: 100px; object-fit: cover;">
                                @else
                                    <span class="text-muted small">No image</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $slider->title ?? 'Untitled' }}</strong><br>
                                <small class="text-muted">{{ $slider->subtitle }}</small>
                            </td>
                            <td>
                                @if($slider->button_text)
                                    <span class="badge bg-info text-dark">{{ $slider->button_text }}</span>
                                    <div class="small text-muted text-truncate" style="max-width: 150px;">{{ $slider->button_link }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($slider->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slider?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No sliders found. Click "Add New Slider" to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $sliders->links() }}
        </div>
    </div>
</div>
@endsection
