@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Customers</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Header: Search & Per Page -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <form method="GET">
                        @foreach(request()->except(['search', 'page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
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
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Total Purchase Value</th>
                            <th>Address</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="text-center align-middle">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td class="text-center align-middle">{{ $user->name }}</td>
                                <td class="text-center align-middle">{{ $user->email }}</td>
                                <td class="text-center align-middle">{{ $user->phone ?? 'N/A' }}</td>
                                <td class="text-center align-middle"><strong>₹{{ number_format($user->total_purchase_value, 2) }}</strong></td>
                                <td class="align-middle">
                                    @php
                                        $latestOrder = $user->orders()->latest()->first();
                                        $displayAddress = null;
                                        $isLegacy = false;
                                        $sourceLabel = '';

                                        if ($latestOrder && !empty($latestOrder->shipping_address)) {
                                            $displayAddress = $latestOrder->shipping_address;
                                            $sourceLabel = 'Last Ordered Address';
                                        } else {
                                            $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();
                                            if ($defaultAddress) {
                                                $displayAddress = [
                                                    'name' => $defaultAddress->first_name . ' ' . $defaultAddress->last_name,
                                                    'door_number' => $defaultAddress->door_number,
                                                    'street' => $defaultAddress->street,
                                                    'city' => $defaultAddress->city,
                                                    'state' => $defaultAddress->state,
                                                    'pincode' => $defaultAddress->post_code,
                                                    'phone' => $defaultAddress->phone_number
                                                ];
                                                $sourceLabel = 'Default Address';
                                            } else {
                                                $legacyAddress = $user->address;
                                                if (is_string($legacyAddress) && is_array(json_decode($legacyAddress, true))) {
                                                    $displayAddress = json_decode($legacyAddress, true);
                                                } elseif (is_array($legacyAddress)) {
                                                    $displayAddress = $legacyAddress;
                                                }
                                                $isLegacy = true;
                                                $sourceLabel = 'Legacy Address';
                                            }
                                        }
                                    @endphp

                                    @if($displayAddress)
                                        <div class="p-2 border rounded bg-light" style="font-size: 0.9rem;">
                                            <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.7rem;">{{ $sourceLabel }}</small>
                                            
                                            <strong>{{ $displayAddress['name'] ?? ($user->name ?? 'User') }}</strong><br>
                                            
                                            @if(!empty($displayAddress['door_number'])){{ $displayAddress['door_number'] }}, @endif
                                            @if(!empty($displayAddress['door_no'])){{ $displayAddress['door_no'] }}, @endif
                                            @if(!empty($displayAddress['street'])){{ $displayAddress['street'] }}, @endif
                                            {{ $displayAddress['address'] ?? '' }}
                                            
                                            @if(isset($displayAddress['city']) || isset($displayAddress['state']) || isset($displayAddress['pincode']))
                                                <br>{{ implode(', ', array_filter([$displayAddress['city'] ?? null, $displayAddress['state'] ?? null, $displayAddress['pincode'] ?? null])) }}
                                            @endif
                                            
                                            @if(!empty($displayAddress['phone']))
                                                <div class="mt-1"><small class="text-muted"><i class="fas fa-phone-alt me-1"></i> {{ $displayAddress['phone'] }}</small></div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">No address available</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-primary view-report-btn" data-user-id="{{ $user->id }}">
                                        <i class="fas fa-chart-line me-1"></i> View Report
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer: Pagination Info & Links -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Report Modal -->
<div class="modal fade" id="purchaseReportModal" tabindex="-1" aria-labelledby="purchaseReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="purchaseReportModalLabel">
                    <i class="fas fa-chart-bar me-2"></i> Purchase Report: <span id="reportCustomerName">...</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Summary Section -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light text-center">
                            <small class="text-uppercase fw-bold text-muted d-block mb-1">Total Orders</small>
                            <h3 class="mb-0 text-dark fw-bold" id="reportTotalOrders">0</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light text-center">
                            <small class="text-uppercase fw-bold text-muted d-block mb-1">Completed Orders</small>
                            <h3 class="mb-0 text-success fw-bold" id="reportCompletedOrders">0</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light text-center">
                            <small class="text-uppercase fw-bold text-muted d-block mb-1">Total Purchase Value</small>
                            <h3 class="mb-0 text-primary fw-bold">₹<span id="reportPurchaseValue">0.00</span></h3>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <h6 class="fw-bold mb-3"><i class="fas fa-list-ul me-2"></i> Order History (Date-Wise)</h6>
                <div class="table-responsive" style="max-height: 350px;">
                    <table class="table table-sm table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Order Number</th>
                                <th>Items</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="reportOrdersTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No orders found.</td>
                            </tr>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportModal = new bootstrap.Modal(document.getElementById('purchaseReportModal'));
        const nameSpan = document.getElementById('reportCustomerName');
        const totalOrders = document.getElementById('reportTotalOrders');
        const completedOrders = document.getElementById('reportCompletedOrders');
        const purchaseValue = document.getElementById('reportPurchaseValue');
        const ordersBody = document.getElementById('reportOrdersTableBody');

        document.querySelectorAll('.view-report-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                
                // Show loading
                nameSpan.innerText = 'Loading...';
                totalOrders.innerText = '0';
                completedOrders.innerText = '0';
                purchaseValue.innerText = '0.00';
                ordersBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...
                        </td>
                    </tr>
                `;
                reportModal.show();

                fetch(`{{ url('admin/users') }}/${userId}/report`)
                    .then(res => res.json())
                    .then(data => {
                        nameSpan.innerText = data.user.name;
                        totalOrders.innerText = data.summary.total_orders;
                        completedOrders.innerText = data.summary.total_completed_orders;
                        purchaseValue.innerText = data.summary.total_purchase_value;

                        ordersBody.innerHTML = '';
                        if (data.orders.length === 0) {
                            ordersBody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-muted">No orders found</td></tr>';
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
                                    <span class="badge ${order.badge_class}">${order.status}</span>
                                </td>
                            `;
                            ordersBody.appendChild(tr);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        nameSpan.innerText = 'Error loading report';
                        ordersBody.innerHTML = '<tr><td colspan="5" class="text-center py-3 text-danger">Failed to load purchase report.</td></tr>';
                    });
            });
        });
    });
</script>
@endpush
