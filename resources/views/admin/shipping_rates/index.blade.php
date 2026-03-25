@extends('admin.layout')

@section('title', 'Shipping Cost')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Shipping Cost</h2>
    <a href="{{ route('admin.shipping-rates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add State
    </a>
</div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-muted">Shipping Cost Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light text-uppercase small">
                    <tr>
                        <th class="text-muted">ID</th>
                        <th class="text-muted">STATE NAME</th>
                        <th class="text-muted">BASE WEIGHT (KG)</th>
                        <th class="text-muted">BASE COST (₹)</th>
                        <th class="text-muted">ADDITIONAL WEIGHT UNIT (KG)</th>
                        <th class="text-muted">ADDITIONAL COST PER UNIT (₹)</th>
                        <th class="text-muted">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippingRates as $rate)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $rate->state_name }}</td>
                        <td>{{ $rate->base_weight }}</td>
                        <td>{{ number_format($rate->base_cost, 2) }}</td>
                        <td>{{ $rate->additional_weight_unit }}</td>
                        <td>{{ number_format($rate->additional_cost_per_unit, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.shipping-rates.edit', $rate) }}" class="btn btn-sm btn-info text-white rounded-3 px-3">
                                Edit
                            </a>
                            <form action="{{ route('admin.shipping-rates.destroy', $rate) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No shipping rates found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
