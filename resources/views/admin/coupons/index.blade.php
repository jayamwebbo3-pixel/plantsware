@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Coupon Management</h4>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create Coupon
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 70px;">S.No</th>
                            <th>Coupon Code</th>
                            <th>Discount</th>
                            <th>Min Order</th>
                            <th>Validity</th>
                            <th class="text-center">Audience</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-primary">{{ $coupon->coupon_code }}</strong>
                                </td>
                                <td>
                                    @if($coupon->discount_type === 'percentage')
                                        {{ number_format($coupon->discount_value, 0) }}% 
                                        @if($coupon->max_discount)
                                            <small class="text-muted d-block">(Max ₹{{ number_format($coupon->max_discount, 2) }})</small>
                                        @endif
                                    @else
                                        ₹{{ number_format($coupon->discount_value, 2) }}
                                    @endif
                                </td>
                                <td>₹{{ number_format($coupon->minimum_order_amount, 2) }}</td>
                                <td>
                                    @if($coupon->valid_from || $coupon->valid_to)
                                        <small>
                                            <strong>From:</strong> {{ $coupon->valid_from ? $coupon->valid_from->format('d M Y') : 'N/A' }}<br>
                                            <strong>To:</strong> {{ $coupon->valid_to ? $coupon->valid_to->format('d M Y') : 'N/A' }}
                                        </small>
                                    @else
                                        <span class="text-muted small">No limit</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($coupon->is_public)
                                        <span class="badge bg-success">All Customers</span>
                                    @else
                                        <span class="badge bg-info text-dark">Restricted ({{ $coupon->users_count ?? $coupon->users()->count() }})</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($coupon->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <button type="button" class="btn btn-outline-info btn-sm view-coupon-btn" data-coupon-id="{{ $coupon->id }}" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No coupons found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Showing {{ $coupons->firstItem() ?? 0 }} to {{ $coupons->lastItem() ?? 0 }} of {{ $coupons->total() }} entries
                </div>
                <div>
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coupon Details Modal -->
<div class="modal fade" id="couponDetailsModal" tabindex="-1" aria-labelledby="couponDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="couponDetailsModalLabel">
                    <i class="fas fa-ticket-alt text-primary me-2"></i>Coupon Report: <span id="modalCouponCode" class="text-primary font-monospace text-uppercase"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Coupon Details (Above) -->
                <h6 class="fw-bold border-bottom pb-2 mb-3">Coupon Details</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Discount Value</label>
                        <span id="detailDiscount" class="fw-bold text-dark fs-5"></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Minimum Order Amount</label>
                        <span id="detailMinOrder" class="fw-bold text-dark fs-5"></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Status</label>
                        <span id="detailStatus" class="badge"></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Validity Period</label>
                        <span id="detailValidity" class="text-dark small fw-bold"></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Audience</label>
                        <span id="detailAudience" class="text-dark fw-bold"></span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Redemptions / Total Discount</label>
                        <span class="text-dark"><strong id="detailUsages">0</strong> usages (<strong id="detailTotalDiscount" class="text-success">₹0.00</strong>)</span>
                    </div>
                </div>

                <!-- Users Table (Below) -->
                <h6 class="fw-bold border-bottom pb-2 mb-3" id="usersTableHeading">Targeted Customers & Coupon Usage</h6>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-striped table-bordered align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact Info</th>
                                <th class="text-center" style="width: 100px;">Targeted?</th>
                                <th class="text-center" style="width: 150px;">Used?</th>
                                <th class="text-end" style="width: 130px;">Discount Used</th>
                                <th class="text-center" style="width: 150px;">Used Date</th>
                            </tr>
                        </thead>
                        <tbody id="modalUsersTableBody">
                            <!-- JS loaded rows -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('couponDetailsModal'));
        const modalCouponCode = document.getElementById('modalCouponCode');
        const detailDiscount = document.getElementById('detailDiscount');
        const detailMinOrder = document.getElementById('detailMinOrder');
        const detailStatus = document.getElementById('detailStatus');
        const detailValidity = document.getElementById('detailValidity');
        const detailAudience = document.getElementById('detailAudience');
        const detailUsages = document.getElementById('detailUsages');
        const detailTotalDiscount = document.getElementById('detailTotalDiscount');
        const usersTableBody = document.getElementById('modalUsersTableBody');
        const usersTableHeading = document.getElementById('usersTableHeading');

        document.querySelectorAll('.view-coupon-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const couponId = this.getAttribute('data-coupon-id');
                
                // Reset / Loading State
                modalCouponCode.innerText = 'Loading...';
                detailDiscount.innerText = '';
                detailMinOrder.innerText = '';
                detailStatus.innerText = '';
                detailStatus.className = 'badge';
                detailValidity.innerText = '';
                detailAudience.innerText = '';
                detailUsages.innerText = '0';
                detailTotalDiscount.innerText = '₹0.00';
                usersTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading details...
                        </td>
                    </tr>
                `;
                modal.show();

                fetch(`{{ url('admin/coupons') }}/${couponId}/report`)
                    .then(res => res.json())
                    .then(data => {
                        const coupon = data.coupon;
                        modalCouponCode.innerText = coupon.coupon_code;
                        detailDiscount.innerText = coupon.discount_text;
                        detailMinOrder.innerText = coupon.min_order;
                        detailValidity.innerText = coupon.validity;
                        detailAudience.innerText = coupon.audience_text;
                        detailUsages.innerText = coupon.total_usages;
                        detailTotalDiscount.innerText = coupon.total_discount_given;

                        // Status Badge
                        if (coupon.status_text === 'Active') {
                            detailStatus.innerText = 'Active';
                            detailStatus.className = 'badge bg-success text-white';
                        } else {
                            detailStatus.innerText = 'Inactive';
                            detailStatus.className = 'badge bg-secondary text-white';
                        }

                        // Heading & Table
                        usersTableHeading.innerText = coupon.audience_text === 'All Customers (Public)' 
                            ? 'Customers Who Used This Coupon'
                            : 'Targeted Customers & Coupon Usage';

                        usersTableBody.innerHTML = '';
                        if (data.users.length === 0) {
                            usersTableBody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">
                                        ${coupon.audience_text === 'All Customers (Public)' ? 'No customer has used this coupon yet.' : 'No customers targeted or used.'}
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        data.users.forEach(u => {
                            const tr = document.createElement('tr');
                            
                            // Targeted Badge
                            const targetedBadge = u.is_targeted 
                                ? '<span class="badge bg-info text-dark">Yes</span>' 
                                : '<span class="badge bg-secondary text-white">No</span>';

                            // Used Badge
                            const usedBadge = u.has_used 
                                ? `<span class="badge bg-success text-white">Yes (${u.order_number})</span>` 
                                : '<span class="badge bg-light text-dark">No</span>';

                            tr.innerHTML = `
                                <td><strong>${u.name}</strong></td>
                                <td>
                                    <small class="text-muted d-block">${u.email}</small>
                                    ${u.phone ? `<small class="text-muted">${u.phone}</small>` : ''}
                                </td>
                                <td class="text-center">${targetedBadge}</td>
                                <td class="text-center">${usedBadge}</td>
                                <td class="text-end fw-semibold text-success">${u.has_used ? '₹' + u.discount_amount : '-'}</td>
                                <td class="text-center text-muted"><small>${u.used_at || '-'}</small></td>
                            `;
                            usersTableBody.appendChild(tr);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        modalCouponCode.innerText = 'Error';
                        usersTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-danger">Failed to load details.</td></tr>';
                    });
            });
        });
    });
</script>
@endpush
@endsection

