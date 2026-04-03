@extends('admin.layout')

@section('title', 'Edit Shipping Rate')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Shipping Rate</h2>
    <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-secondary rounded-3">Back</a>
</div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.shipping-rates.update', $shippingRate) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="state_name" class="form-label fw-bold small text-muted text-uppercase">State Name</label>
                <input type="text" name="state_name" class="form-control rounded-3" id="state_name" value="{{ old('state_name', $shippingRate->state_name) }}" required>
                @error('state_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="base_weight" class="form-label fw-bold small text-muted text-uppercase">Base Weight (KG)</label>
                    <input type="number" name="base_weight" class="form-control rounded-3" id="base_weight" value="{{ old('base_weight', $shippingRate->base_weight) }}" required>
                    @error('base_weight')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="base_cost" class="form-label fw-bold small text-muted text-uppercase">Base Cost (₹)</label>
                    <input type="number" step="0.01" name="base_cost" class="form-control rounded-3" id="base_cost" value="{{ old('base_cost', $shippingRate->base_cost) }}" required>
                    @error('base_cost')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="additional_weight_unit" class="form-label fw-bold small text-muted text-uppercase">Additional Weight Unit (KG)</label>
                    <input type="number" name="additional_weight_unit" class="form-control rounded-3" id="additional_weight_unit" value="{{ old('additional_weight_unit', $shippingRate->additional_weight_unit) }}" required>
                    @error('additional_weight_unit')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="additional_cost_per_unit" class="form-label fw-bold small text-muted text-uppercase">Additional Cost Per Unit (₹)</label>
                    <input type="number" step="0.01" name="additional_cost_per_unit" class="form-control rounded-3" id="additional_cost_per_unit" value="{{ old('additional_cost_per_unit', $shippingRate->additional_cost_per_unit) }}" required>
                    @error('additional_cost_per_unit')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-5 rounded-3">Update Rate</button>
            </div>
        </form>
    </div>
</div>
@endsection
