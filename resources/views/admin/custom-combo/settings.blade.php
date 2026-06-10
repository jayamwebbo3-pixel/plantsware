@extends('admin.layout')

@section('title', 'Custom Combo Pack Settings')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Custom Combo Pack Module</h2>
        
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-4" id="comboTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') !== 'slabs' ? 'active' : '' }}" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="{{ request('tab') !== 'slabs' ? 'true' : 'false' }}">
                    <i class="fas fa-cog me-2"></i>General Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') === 'slabs' ? 'active' : '' }}" id="slabs-tab" data-bs-toggle="tab" data-bs-target="#slabs" type="button" role="tab" aria-controls="slabs" aria-selected="{{ request('tab') === 'slabs' ? 'true' : 'false' }}">
                    <i class="fas fa-tags me-2"></i>Discount Slabs
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="comboTabsContent">
            
            <!-- Tab 1: General Settings -->
            <div class="tab-pane fade {{ request('tab') !== 'slabs' ? 'show active' : '' }}" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="fas fa-sliders-h me-2"></i> Combo Pack Module Control</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.custom-combo.settings.update') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="is_enabled" class="form-label fw-semibold">Enable Combo Pack Feature</label>
                                        <select name="is_enabled" id="is_enabled" class="form-select @error('is_enabled') is-invalid @enderror">
                                            <option value="1" {{ $settings->is_enabled ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ !$settings->is_enabled ? 'selected' : '' }}>No</option>
                                        </select>
                                        <div class="form-text small">If disabled, customer-created combo pack options will not be visible on the frontend.</div>
                                        @error('is_enabled')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="max_products" class="form-label fw-semibold">Maximum Products Allowed</label>
                                        <select name="max_products" id="max_products" class="form-select @error('max_products') is-invalid @enderror">
                                            @for($i = 2; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ $settings->max_products == $i ? 'selected' : '' }}>{{ $i }} Products</option>
                                            @endfor
                                        </select>
                                        <div class="form-text small">Defines the maximum number of items a customer can add to their combo.</div>
                                        @error('max_products')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-save me-2"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Discount Slabs -->
            <div class="tab-pane fade {{ request('tab') === 'slabs' ? 'show active' : '' }}" id="slabs" role="tabpanel" aria-labelledby="slabs-tab">
                <div class="row">
                    <!-- Create Slab Form -->
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white py-3">
                                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-plus me-2"></i> Add Discount Slab</h5>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.custom-combo.slabs.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="min_amount" class="form-label fw-semibold">Product Total From (₹)</label>
                                        <input type="number" step="0.01" min="0" name="min_amount" id="min_amount" class="form-control @error('min_amount') is-invalid @enderror" value="{{ old('min_amount') }}" required placeholder="e.g. 500">
                                        @error('min_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="max_amount" class="form-label fw-semibold">Product Total To (₹)</label>
                                        <input type="number" step="0.01" min="0" name="max_amount" id="max_amount" class="form-control @error('max_amount') is-invalid @enderror" value="{{ old('max_amount') }}" required placeholder="e.g. 1000">
                                        @error('max_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="discount_percentage" class="form-label fw-semibold">Discount Percentage (%)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" max="100" name="discount_percentage" id="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror" value="{{ old('discount_percentage') }}" required placeholder="e.g. 10">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('discount_percentage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2">
                                        <i class="fas fa-plus me-1"></i> Add Slab
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Slabs List -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white py-3">
                                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-list me-2"></i> Discount Slabs List</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">Min Amount (₹)</th>
                                                <th class="text-center">Max Amount (₹)</th>
                                                <th class="text-center">Discount (%)</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($slabs as $slab)
                                            <tr>
                                                <form action="{{ route('admin.custom-combo.slabs.update', $slab->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <td>
                                                        <input type="number" step="0.01" min="0" name="min_amount" class="form-control form-control-sm text-center" value="{{ $slab->min_amount }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0" name="max_amount" class="form-control form-control-sm text-center" value="{{ $slab->max_amount }}" required>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" min="0" max="100" name="discount_percentage" class="form-control text-center" value="{{ $slab->discount_percentage }}" required>
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select name="status" class="form-select form-select-sm">
                                                            <option value="1" {{ $slab->status ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !$slab->status ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-nowrap text-center">
                                                        <button type="submit" class="btn btn-sm btn-success me-1" title="Save Changes">
                                                            <i class="fas fa-save"></i> Save
                                                        </button>
                                                        <a href="{{ route('admin.custom-combo.slabs.delete', $slab->id) }}" class="btn btn-sm btn-danger" onclick="event.preventDefault(); confirmDelete('Are you sure you want to delete this slab?', document.getElementById('delete-form-{{ $slab->id }}'))" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </form>
                                                <form id="delete-form-{{ $slab->id }}" action="{{ route('admin.custom-combo.slabs.delete', $slab->id) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">No discount slabs configured. Configure ranges to offer discounts based on custom combo value.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
