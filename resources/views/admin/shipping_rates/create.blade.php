@extends('admin.layout')

@section('title', 'Add Shipping Rate')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Add Shipping Rate</h2>
    <a href="{{ route('admin.shipping-rates.index') }}" class="text-decoration-none"> <i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.shipping-rates.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="state_name" class="form-label fw-bold">Select State</label>
                <select name="state_name" id="state_name" class="form-select rounded-3 @error('state_name') is-invalid @enderror" required>
                    <option value="">Select a State</option>
                    <option value="Andhra Pradesh" {{ old('state_name') == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                    <option value="Arunachal Pradesh" {{ old('state_name') == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                    <option value="Assam" {{ old('state_name') == 'Assam' ? 'selected' : '' }}>Assam</option>
                    <option value="Bihar" {{ old('state_name') == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                    <option value="Chhattisgarh" {{ old('state_name') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                    <option value="Delhi" {{ old('state_name') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                    <option value="Goa" {{ old('state_name') == 'Goa' ? 'selected' : '' }}>Goa</option>
                    <option value="Gujarat" {{ old('state_name') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                    <option value="Haryana" {{ old('state_name') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                    <option value="Himachal Pradesh" {{ old('state_name') == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                    <option value="Jharkhand" {{ old('state_name') == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                    <option value="Karnataka" {{ old('state_name') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                    <option value="Kerala" {{ old('state_name') == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                    <option value="Madhya Pradesh" {{ old('state_name') == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                    <option value="Maharashtra" {{ old('state_name') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                    <option value="Manipur" {{ old('state_name') == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                    <option value="Meghalaya" {{ old('state_name') == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                    <option value="Mizoram" {{ old('state_name') == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                    <option value="Nagaland" {{ old('state_name') == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                    <option value="Odisha" {{ old('state_name') == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                    <option value="Punjab" {{ old('state_name') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                    <option value="Rajasthan" {{ old('state_name') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                    <option value="Sikkim" {{ old('state_name') == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                    <option value="Tamil Nadu" {{ old('state_name') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                    <option value="Telangana" {{ old('state_name') == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                    <option value="Tripura" {{ old('state_name') == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                    <option value="Uttar Pradesh" {{ old('state_name') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                    <option value="Uttarakhand" {{ old('state_name') == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                    <option value="West Bengal" {{ old('state_name') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                </select>
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
