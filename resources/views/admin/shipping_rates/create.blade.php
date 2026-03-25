@extends('admin.layout')

@section('title', 'Add Shipping Rate')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Shipping Rate</h2>
    <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.shipping-rates.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="state_name" class="form-label fw-bold">State Name</label>
                <input type="text" name="state_name" class="form-control rounded-3" id="state_name" value="{{ old('state_name') }}" required placeholder="e.g. Tamil Nadu">
                @error('state_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="base_weight" class="form-label fw-bold">Base Weight (KG)</label>
                    <input type="number" name="base_weight" class="form-control rounded-3" id="base_weight" value="{{ old('base_weight', 1) }}" required>
                    @error('base_weight')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="base_cost" class="form-label fw-bold">Base Cost (₹)</label>
                    <input type="number" step="0.01" name="base_cost" class="form-control rounded-3" id="base_cost" value="{{ old('base_cost', 40) }}" required>
                    @error('base_cost')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="additional_weight_unit" class="form-label fw-bold">Additional Weight Unit (KG)</label>
                    <input type="number" name="additional_weight_unit" class="form-control rounded-3" id="additional_weight_unit" value="{{ old('additional_weight_unit', 1) }}" required>
                    @error('additional_weight_unit')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="additional_cost_per_unit" class="form-label fw-bold">Additional Cost Per Unit (₹)</label>
                    <input type="number" step="0.01" name="additional_cost_per_unit" class="form-control rounded-3" id="additional_cost_per_unit" value="{{ old('additional_cost_per_unit', 30) }}" required>
                    @error('additional_cost_per_unit')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-5 rounded-3">Add Rate</button>
            </div>
        </form>
    </div>
</div>
@endsection
