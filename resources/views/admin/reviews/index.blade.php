@extends('admin.layout')

@section('title', 'Product Reviews')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ $backUrl }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Back to {{ request('product_id') ? 'Products' : (request('combo_pack_id') ? 'Combo Packs' : 'Dashboard') }}
            </a>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                Product Reviews
            </h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ $filterName ? 'Reviews for ' . $filterName : 'All Reviews' }}
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start">S.NO</th>
                                <th class="text-start">User</th>
                                @if(!$filterName)
                                    <th class="text-start">Product/Combo</th>
                                @endif
                                <th class="text-start">Rating</th>
                                <th class="text-start">Review</th>
                                <th class="text-start">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr>
                                    <td class="text-start">{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $loop->iteration }}</td>
                                    <td class="text-start">
                                        <strong>{{ $review->user->name }}</strong><br>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                    </td>
                                    @if(!$filterName)
                                        <td class="text-start">
                                            @if($review->product)
                                                <span class="badge bg-info">Product</span> {{ $review->product->name }}
                                            @elseif($review->comboPack)
                                                <span class="badge bg-warning">Combo</span> {{ $review->comboPack->name }}
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-start">
                                        <div style="color: #ffc107;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-start">{{ $review->review }}</td>
                                    <td class="text-start">{{ $review->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $filterName ? 5 : 6 }}" class="text-center py-4">No reviews found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $reviews->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
