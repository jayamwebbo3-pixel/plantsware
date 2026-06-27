@extends('admin.layout')

@section('title', 'Create Coupon')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .targeting-section {
        background-color: #f8faf9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
    }
    .audience-card {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 15px;
        background: #ffffff;
        margin-top: 15px;
        display: none;
    }
    .user-scroll-list {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px;
        background: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Create Coupon</h4>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
        @csrf
        
        <div class="row">
            <!-- Left Column: Details -->
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Coupon Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="coupon_code" class="form-label fw-bold">Coupon Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="coupon_code" id="coupon_code" class="form-control text-uppercase" placeholder="e.g. SAVE20" value="{{ old('coupon_code') }}" required>
                                    <button type="button" class="btn btn-outline-primary" id="generateCodeBtn">
                                        <i class="fas fa-random me-1"></i> Generate
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="discount_type" class="form-label fw-bold">Discount Type <span class="text-danger">*</span></label>
                                <select name="discount_type" id="discount_type" class="form-select" required>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="discount_value" class="form-label fw-bold">Discount Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="discount_value" id="discount_value" class="form-control" placeholder="Value" value="{{ old('discount_value') }}" required min="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6" id="maxDiscountContainer">
                                <label for="max_discount" class="form-label fw-bold">Discount Deduction Limit (₹)</label>
                                <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control" placeholder="Deduction Limit" value="{{ old('max_discount') }}" min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="minimum_order_amount" class="form-label fw-bold">Min Order Amount (₹)</label>
                                <input type="number" step="0.01" name="minimum_order_amount" id="minimum_order_amount" class="form-control" placeholder="e.g. 1000" value="{{ old('minimum_order_amount', 0) }}" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="validity_range" class="form-label fw-bold">Validity Period (From - To)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" name="validity_range" id="validity_range" class="form-control" placeholder="Select date range..." value="{{ old('validity_range') }}">
                            </div>
                            <small class="text-muted">Leave empty for unlimited validity.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Targeting -->
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Coupon Audience</h5>
                    </div>
                    <div class="card-body">
                        <div class="targeting-section">
                            <div class="form-check mb-2">
                                <input class="form-check-input audience-radio" type="radio" name="audience_type" id="audience_all" value="all" checked>
                                <label class="form-check-label fw-bold" for="audience_all">
                                    All Customers (Current & Past 5 Months Registered)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input audience-radio" type="radio" name="audience_type" id="audience_specific" value="specific">
                                <label class="form-check-label fw-bold" for="audience_specific">
                                    Specific Customers
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input audience-radio" type="radio" name="audience_type" id="audience_value" value="value">
                                <label class="form-check-label fw-bold" for="audience_value">
                                    Customers Above Purchase Value
                                </label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input audience-radio" type="radio" name="audience_type" id="audience_top" value="top">
                                <label class="form-check-label fw-bold" for="audience_top">
                                    Top N Customers
                                </label>
                            </div>
                        </div>

                        <!-- Specific Customers Input Panel -->
                        <div class="audience-card" id="specificCustomersPanel">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Search Customer</h6>
                            <div class="row g-2 mb-3">
                                <div class="col-md-9">
                                    <input type="text" id="customerSearchInput" class="form-control form-control-sm" placeholder="Search Email or Phone...">
                                </div>
                                <div class="col-md-3">
                                    <button type="button" id="searchCustomerBtn" class="btn btn-primary btn-sm w-100">Search</button>
                                </div>
                            </div>
                            
                            <div class="user-scroll-list" id="specificCustomersList">
                                <span class="text-muted small">Search and select customers.</span>
                            </div>
                        </div>

                        <!-- Customers Above Purchase Value Input Panel -->
                        <div class="audience-card" id="purchaseValuePanel">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Minimum Purchase Value</h6>
                            <div class="input-group mb-3">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="target_min_purchase" id="target_min_purchase" class="form-control" placeholder="10000" min="0">
                                <button type="button" id="findCustomersBtn" class="btn btn-primary">Find Customers</button>
                            </div>
                            <div id="valueMatchingCount" class="alert alert-info py-2 mb-0 d-none">
                                Matching Customers: <strong>0</strong>
                            </div>
                        </div>

                        <!-- Top N Customers Input Panel -->
                        <div class="audience-card" id="topCustomersPanel">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Top Customers (by purchase value)</h6>
                            <div class="input-group mb-3">
                                <input type="number" name="target_top_count" id="target_top_count" class="form-control" placeholder="20" min="1">
                                <button type="button" id="loadTopCustomersBtn" class="btn btn-primary">Load Customers</button>
                            </div>
                            <div id="topMatchingCount" class="alert alert-info py-2 mb-0 d-none">
                                Found: <strong>Top 0 Customers by Purchase Value</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i> Save Coupon
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Date Range Picker
        flatpickr("#validity_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today"
        });

        // Generate Code
        const codeInput = document.getElementById('coupon_code');
        const generateBtn = document.getElementById('generateCodeBtn');
        generateBtn.addEventListener('click', function() {
            const length = 8;
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            for (let i = 0; i < length; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            codeInput.value = code;
        });

        // Discount type interactive toggles
        const discountType = document.getElementById('discount_type');
        const maxDiscountContainer = document.getElementById('maxDiscountContainer');
        function toggleMaxDiscount() {
            if (discountType.value === 'percentage') {
                maxDiscountContainer.style.display = 'block';
            } else {
                maxDiscountContainer.style.display = 'none';
                document.getElementById('max_discount').value = '';
            }
        }
        discountType.addEventListener('change', toggleMaxDiscount);
        toggleMaxDiscount(); // Run initially

        // Audience type selection interactive toggles
        const radios = document.querySelectorAll('.audience-radio');
        const specificPanel = document.getElementById('specificCustomersPanel');
        const valuePanel = document.getElementById('purchaseValuePanel');
        const topPanel = document.getElementById('topCustomersPanel');

        function toggleAudiencePanels() {
            // Hide all first
            specificPanel.style.display = 'none';
            valuePanel.style.display = 'none';
            topPanel.style.display = 'none';

            // Find selected radio
            const selected = document.querySelector('.audience-radio:checked').value;
            if (selected === 'specific') {
                specificPanel.style.display = 'block';
            } else if (selected === 'value') {
                valuePanel.style.display = 'block';
            } else if (selected === 'top') {
                topPanel.style.display = 'block';
            }
        }

        radios.forEach(radio => radio.addEventListener('change', toggleAudiencePanels));
        toggleAudiencePanels(); // Run initially

        // AJAX search for Specific Customers
        const customerSearchInput = document.getElementById('customerSearchInput');
        const searchCustomerBtn = document.getElementById('searchCustomerBtn');
        const specificCustomersList = document.getElementById('specificCustomersList');

        searchCustomerBtn.addEventListener('click', function() {
            const query = customerSearchInput.value.trim();
            if (!query) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please enter an email or phone number to search.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            specificCustomersList.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';

            fetch(`{{ route('admin.coupons.search-users') }}?type=specific&query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    specificCustomersList.innerHTML = '';
                    if (data.users.length === 0) {
                        specificCustomersList.innerHTML = '<span class="text-muted small">No customers found.</span>';
                        return;
                    }

                    data.users.forEach(user => {
                        const div = document.createElement('div');
                        div.className = 'form-check mb-2';
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="user_ids[]" value="${user.id}" id="user_${user.id}">
                            <label class="form-check-label" for="user_${user.id}">
                                <strong>${user.name}</strong> (${user.email}) ${user.phone ? '- ' + user.phone : ''}
                            </label>
                        `;
                        specificCustomersList.appendChild(div);
                    });
                })
                .catch(err => {
                    console.error(err);
                    specificCustomersList.innerHTML = '<span class="text-danger small">Error loading customers.</span>';
                });
        });

        // AJAX search for Customers Above Purchase Value
        const targetMinPurchase = document.getElementById('target_min_purchase');
        const findCustomersBtn = document.getElementById('findCustomersBtn');
        const valueMatchingCount = document.getElementById('valueMatchingCount');

        findCustomersBtn.addEventListener('click', function() {
            const value = targetMinPurchase.value.trim();
            if (value === '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please enter a purchase value limit.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            findCustomersBtn.disabled = true;
            findCustomersBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            fetch(`{{ route('admin.coupons.search-users') }}?type=above_value&value=${value}`)
                .then(res => res.json())
                .then(data => {
                    valueMatchingCount.querySelector('strong').innerText = data.count;
                    valueMatchingCount.classList.remove('d-none');
                })
                .finally(() => {
                    findCustomersBtn.disabled = false;
                    findCustomersBtn.innerText = 'Find Customers';
                });
        });

        // AJAX search for Top N Customers
        const targetTopCount = document.getElementById('target_top_count');
        const loadTopCustomersBtn = document.getElementById('loadTopCustomersBtn');
        const topMatchingCount = document.getElementById('topMatchingCount');

        loadTopCustomersBtn.addEventListener('click', function() {
            const n = targetTopCount.value.trim();
            if (n === '') {
                Swal.fire({
                    icon: 'warning',
                    text: 'Please enter a count of top customers.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            loadTopCustomersBtn.disabled = true;
            loadTopCustomersBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            fetch(`{{ route('admin.coupons.search-users') }}?type=top_n&value=${n}`)
                .then(res => res.json())
                .then(data => {
                    topMatchingCount.querySelector('strong').innerText = `Top ${data.count} Customers by Purchase Value`;
                    topMatchingCount.classList.remove('d-none');
                })
                .finally(() => {
                    loadTopCustomersBtn.disabled = false;
                    loadTopCustomersBtn.innerText = 'Load Customers';
                });
        });
    });
</script>
@endpush
