@extends('admin.layout')

@section('content')
<div class="container-fluid py-2">
    <!-- Page Title & Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark d-flex align-items-center">
            <i class="fas fa-users text-teal me-2" style="color: #134e5e;"></i> Customers
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.export', array_merge(request()->query(), ['format' => 'excel'])) }}"
               class="btn btn-sm btn-export-excel d-flex align-items-center gap-2"
               title="Download as Excel (.csv)">
                <i class="fas fa-file-excel"></i>
                <span>Export to Excel</span>
            </a>
            <a href="{{ route('admin.users.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
               class="btn btn-sm btn-export-pdf d-flex align-items-center gap-2"
               title="Download as PDF">
                <i class="fas fa-file-pdf"></i>
                <span>Export to PDF</span>
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <!-- Header: Search & Per Page -->
            <div class="row g-3 mb-4 align-items-center">
                <div class="col-md-6">
                    <form method="GET" class="position-relative">
                        @foreach(request()->except(['search', 'page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <div class="input-group search-bar">
                            <span class="input-group-text bg-white border-end-0 border-light-subtle rounded-start-pill ps-3 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 border-light-subtle rounded-end-pill py-2 px-3 fs-7" placeholder="Search customer by name, email, or phone..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ request()->url() }}" class="btn btn-link text-muted position-absolute end-0 top-50 translate-middle-y me-2 pe-3 z-3">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <form method="GET" class="d-inline-flex align-items-center justify-content-md-end w-100">
                        @foreach(request()->except(['per_page', 'page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <span class="me-2 text-muted fs-7">Show</span>
                        <select name="per_page" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill px-3 py-2 w-auto border-light-subtle font-semibold text-dark fs-7">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 entries</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 entries</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 entries</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 entries</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive rounded-4 border border-light-subtle">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="text-center py-3" style="width: 70px;">S.No</th>
                            <th class="py-3" style="width: 250px;">Customer Details</th>
                            <th class="py-3" style="width: 300px;">Email Address</th>
                            <th class="py-3" style="width: 180px;">Phone Number</th>
                            <th class="text-end py-3 pe-4" style="width: 160px;">Total Purchases</th>
                            <th class="text-center py-3" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem;">
                        @forelse($users as $user)
                            <tr class="customer-row">
                                <td class="text-center py-3 text-muted fw-semibold">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="text-dark text-break">{{ $user->email }}</span>
                                </td>
                                <td class="py-3">
                                    @if($user->phone)
                                        <span class="text-dark"><i class="fas fa-phone-alt text-muted me-1 fs-8"></i> {{ $user->phone }}</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-phone-alt text-muted opacity-25 me-1 fs-8"></i> N/A</span>
                                    @endif
                                </td>
                                <td class="text-end py-3 pe-4">
                                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #e0f2fe; color: #1e3a8a; font-size: 0.85rem;">
                                        ₹{{ number_format($user->total_purchase_value, 2) }}
                                    </span>
                                </td>
                                <td class="text-center py-3">
                                    <button type="button" class="btn btn-sm btn-action view-customer-btn" data-user-id="{{ $user->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash fs-2 mb-3 d-block text-muted-light"></i>
                                    No customers found matching your search.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer: Pagination Info & Links -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                <div class="text-muted small">
                    Showing <span class="fw-semibold text-dark">{{ $users->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $users->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $users->total() }}</span> entries
                </div>
                <div class="pagination-container">
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Detail Modal -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #134e5e, #2c7a7b); border-bottom: none; padding: 20px 24px;">
                <h5 class="modal-title d-flex align-items-center" id="customerDetailModalLabel">
                    <i class="fas fa-user-circle fs-3 me-3"></i>
                    <div>
                        <span id="detailCustomerName" class="fw-bold d-block">...</span>
                        <small class="text-white-50" style="font-size: 0.8rem;" id="detailCustomerJoined">Joined on ...</small>
                    </div>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light-subtle">
                <div class="row g-4">
                    <!-- Left Column: Customer Info & Addresses -->
                    <div class="col-lg-5">
                        <!-- Contact Info Card -->
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h6 class="mb-3 fw-bold text-dark d-flex align-items-center border-bottom pb-2">
                                    <i class="fas fa-id-card me-2" style="color: #134e5e;"></i> Contact Information
                                </h6>
                                <div class="mb-3">
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Email Address</small>
                                    <span class="fw-medium text-dark d-block text-break" id="detailCustomerEmail">...</span>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Phone Number</small>
                                    <span class="fw-medium text-dark d-block" id="detailCustomerPhone">...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Address Cards Container -->
                        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h6 class="mb-3 fw-bold text-dark d-flex align-items-center border-bottom pb-2">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i> Addresses History
                                </h6>
                                <div style="max-height: 400px; overflow-y: auto;" id="detailAddressesContainer">
                                    <!-- Dynamically populated address list -->
                                    <div class="text-center py-4 text-muted">
                                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                                        <div>Loading addresses...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Purchase Summary & Orders -->
                    <div class="col-lg-7">
                        <!-- Summary Section -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100" style="border-radius: 12px; background-color: #f8fafc; border-left: 4px solid #64748b;">
                                    <div class="card-body p-3">
                                        <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem;">Total Orders</small>
                                        <h3 class="mb-0 text-dark fw-bold" id="detailTotalOrders">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100" style="border-radius: 12px; background-color: #f0fdf4; border-left: 4px solid #10b981;">
                                    <div class="card-body p-3">
                                        <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem;">Completed Orders</small>
                                        <h3 class="mb-0 text-success fw-bold" id="detailCompletedOrders">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm text-center h-100" style="border-radius: 12px; background-color: #eff6ff; border-left: 4px solid #3b82f6;">
                                    <div class="card-body p-3">
                                        <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem;">Total Purchase Value</small>
                                        <h3 class="mb-0 text-primary fw-bold">₹<span id="detailPurchaseValue">0.00</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders List -->
                        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <h6 class="mb-3 fw-bold text-dark d-flex align-items-center border-bottom pb-2">
                                    <i class="fas fa-shopping-bag text-primary me-2"></i> Order History
                                </h6>
                                <div class="table-responsive" style="max-height: 350px;">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light text-muted uppercase font-monospace" style="font-size: 0.75rem;">
                                            <tr>
                                                <th>Date</th>
                                                <th>Order Number</th>
                                                <th>Items</th>
                                                <th class="text-end">Total Amount</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detailOrdersTableBody" style="font-size: 0.85rem;">
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No orders found.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3 px-4">
                <button type="button" class="btn btn-secondary px-4 py-2" style="border-radius: 20px; font-weight: 500; font-size: 0.85rem;" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Export buttons */
    .btn-export-excel {
        background-color: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        border-radius: 20px;
        padding: 6px 16px;
        transition: all 0.2s ease;
        font-size: 0.82rem;
        text-decoration: none;
    }
    .btn-export-excel:hover {
        background-color: #047857;
        color: #fff;
        border-color: #047857;
        box-shadow: 0 4px 10px rgba(4,120,87,0.2);
    }
    .btn-export-pdf {
        background-color: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
        font-weight: 600;
        border-radius: 20px;
        padding: 6px 16px;
        transition: all 0.2s ease;
        font-size: 0.82rem;
        text-decoration: none;
    }
    .btn-export-pdf:hover {
        background-color: #be123c;
        color: #fff;
        border-color: #be123c;
        box-shadow: 0 4px 10px rgba(190,18,60,0.2);
    }
    .text-teal {
        color: #134e5e;
    }
    .fs-7 {
        font-size: 0.85rem !important;
    }
    .fs-8 {
        font-size: 0.75rem !important;
    }
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #134e5e, #71b280);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .customer-row {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .customer-row:hover {
        background-color: #f8fafc !important;
    }
    .btn-action {
        background-color: #fff;
        border: 1px solid #134e5e;
        color: #134e5e;
        font-weight: 500;
        border-radius: 20px;
        padding: 5px 15px;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        background-color: #134e5e;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(19, 78, 94, 0.15);
    }
    .search-bar .input-group-text,
    .search-bar .form-control {
        border-color: #e2e8f0;
    }
    .search-bar .form-control:focus {
        box-shadow: 0 0 0 3px rgba(19, 78, 94, 0.15);
        border-color: #134e5e;
    }
    .pagination-container .pagination {
        margin-bottom: 0;
        gap: 4px;
    }
    .pagination-container .page-item .page-link {
        border-radius: 50% !important;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #134e5e;
        border: 1px solid #e2e8f0;
        margin: 0;
        font-size: 0.85rem;
    }
    .pagination-container .page-item.active .page-link {
        background-color: #134e5e;
        border-color: #134e5e;
        color: #fff;
    }
    .pagination-container .page-item .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    /* Address card styling */
    .address-box {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #fff;
        transition: all 0.2s ease;
    }
    .address-box:hover {
        border-color: #134e5e;
        box-shadow: 0 4px 10px rgba(19, 78, 94, 0.05);
    }
    .address-badge {
        font-size: 0.65rem;
        font-weight: bold;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .badge-default {
        background-color: #ecfdf5;
        color: #059669;
    }
    .badge-shipping {
        background-color: #eff6ff;
        color: #2563eb;
    }
    .badge-legacy {
        background-color: #f1f5f9;
        color: #475569;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
        const nameSpan = document.getElementById('detailCustomerName');
        const joinedSpan = document.getElementById('detailCustomerJoined');
        const emailSpan = document.getElementById('detailCustomerEmail');
        const phoneSpan = document.getElementById('detailCustomerPhone');
        const totalOrders = document.getElementById('detailTotalOrders');
        const completedOrders = document.getElementById('detailCompletedOrders');
        const purchaseValue = document.getElementById('detailPurchaseValue');
        const ordersBody = document.getElementById('detailOrdersTableBody');
        const addressesContainer = document.getElementById('detailAddressesContainer');

        document.querySelectorAll('.view-customer-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                
                // Show loading states
                nameSpan.innerText = 'Loading...';
                joinedSpan.innerText = 'Joined on ...';
                emailSpan.innerText = '...';
                phoneSpan.innerText = '...';
                totalOrders.innerText = '0';
                completedOrders.innerText = '0';
                purchaseValue.innerText = '0.00';
                
                addressesContainer.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <div>Loading addresses...</div>
                    </div>
                `;
                
                ordersBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading orders...
                        </td>
                    </tr>
                `;
                
                detailModal.show();

                fetch(`{{ url('admin/users') }}/${userId}/report`)
                    .then(res => res.json())
                    .then(data => {
                        // Customer Details
                        nameSpan.innerText = data.user.name;
                        joinedSpan.innerText = `Joined on ${data.user.joined_at}`;
                        emailSpan.innerText = data.user.email;
                        phoneSpan.innerText = data.user.phone;

                        // Summary
                        totalOrders.innerText = data.summary.total_orders;
                        completedOrders.innerText = data.summary.total_completed_orders;
                        purchaseValue.innerText = data.summary.total_purchase_value;

                        // Addresses
                        addressesContainer.innerHTML = '';
                        if (!data.addresses || data.addresses.length === 0) {
                            addressesContainer.innerHTML = `
                                <div class="text-center py-4 text-muted bg-light rounded border border-dashed">
                                    <i class="fas fa-map-marked fs-3 mb-2 text-muted"></i>
                                    <div>No addresses available for this customer.</div>
                                </div>
                            `;
                        } else {
                            data.addresses.forEach(addr => {
                                const isDefault = addr.is_default || addr.type === 'Default Address';
                                const badgeClass = addr.type === 'Default Address' ? 'badge-default' : 'badge-shipping';
                                
                                const addressCard = document.createElement('div');
                                addressCard.className = 'address-box p-3 mb-3';
                                addressCard.innerHTML = `
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="address-badge ${badgeClass}">${addr.type}</span>
                                        ${isDefault ? '<span class="text-success fs-8"><i class="fas fa-check-circle me-1"></i>Default</span>' : ''}
                                    </div>
                                    <div class="fw-bold text-dark fs-7">${addr.name}</div>
                                    <div class="text-muted fs-7 mt-1">
                                        ${addr.door_number ? addr.door_number + ', ' : ''}
                                        ${addr.street ? addr.street + ', ' : ''}
                                        ${addr.city ? addr.city + ', ' : ''}
                                        ${addr.state ? addr.state : ''}
                                        ${addr.pincode ? ' - ' + addr.pincode : ''}
                                    </div>
                                    ${addr.phone ? `<div class="mt-2 text-muted fs-8"><i class="fas fa-phone-alt me-1"></i> ${addr.phone}</div>` : ''}
                                `;
                                addressesContainer.appendChild(addressCard);
                            });
                        }

                        // Orders
                        ordersBody.innerHTML = '';
                        if (data.orders.length === 0) {
                            ordersBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">No orders found</td></tr>';
                            return;
                        }

                        data.orders.forEach(order => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><small>${order.date}</small></td>
                                <td><strong>${order.order_number}</strong></td>
                                <td><small class="text-muted">${order.items_summary}</small></td>
                                <td class="text-end">₹${order.total}</td>
                                <td class="text-center">
                                    <span class="badge ${order.badge_class}" style="font-size: 0.75rem;">${order.status}</span>
                                </td>
                            `;
                            ordersBody.appendChild(tr);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        nameSpan.innerText = 'Error loading details';
                        addressesContainer.innerHTML = '<div class="text-center py-3 text-danger">Failed to load addresses.</div>';
                        ordersBody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-danger">Failed to load order history.</td></tr>';
                    });
            });
        });
    });
</script>
@endpush
