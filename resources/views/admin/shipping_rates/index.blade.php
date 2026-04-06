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
    <div class="card-body">
        <!-- Header: Search & Per Page -->
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <form method="GET">
                    @foreach(request()->except(['search', 'page']) as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search State..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary btn-sm" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <form method="GET" class="d-inline">
                    @foreach(request()->except(['per_page', 'page']) as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <small class="me-2 text-muted">Show</small>
                    <select name="per_page" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light text-nowrap">
                    <tr>
                        <th class="text-center">S.NO</th>
                        <th>STATE NAME</th>
                        <th class="text-center">BASE WEIGHT (KG)</th>
                        <th class="text-center">BASE COST (₹)</th>
                        <th class="text-center">ADDITIONAL WEIGHT UNIT (KG)</th>
                        <th class="text-center">ADDITIONAL COST PER UNIT (₹)</th>
                        <th class="text-center">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippingRates as $rate)
                    <tr>
                        <td class="text-center align-middle">{{ ($shippingRates->currentPage() - 1) * $shippingRates->perPage() + $loop->iteration }}</td>
                        <td class="fw-bold">{{ $rate->state_name }}</td>
                        <td class="text-center align-middle">{{ $rate->base_weight }}</td>
                        <td class="text-center align-middle">{{ number_format($rate->base_cost, 2) }}</td>
                        <td class="text-center align-middle">{{ $rate->additional_weight_unit }}</td>
                        <td class="text-center align-middle">{{ number_format($rate->additional_cost_per_unit, 2) }}</td>
                        <td class="text-nowrap text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.shipping-rates.edit', $rate) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.shipping-rates.destroy', $rate) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('Are you sure you want to delete this shipping rate?', this)">
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
                        <td colspan="7" class="text-center py-4 text-muted">No shipping rates found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer: Pagination Info & Links -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $shippingRates->firstItem() ?? 0 }} to {{ $shippingRates->lastItem() ?? 0 }} of {{ $shippingRates->total() }} entries
            </div>
            <div>
                {{ $shippingRates->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection