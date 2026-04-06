@include('view.layout.header')

<div class="checkout-page-wrapper bg-light py-4 py-md-5">
    <div class="container">
        <!-- Modern Breadcrumb/Steps -->
        <nav aria-label="breadcrumb" class="mb-4 d-none d-md-block">
            <ol class="breadcrumb checkout-steps justify-content-center">
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Address</li>
                <li class="breadcrumb-item text-muted">Payment & Review</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Left Column: Address Selection & Form -->
            <div class="col-lg-8">
                @if(!$cartItems || $cartItems->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart text-muted fs-1 opacity-25"></i>
                    </div>
                    <h4 class="fw-bold">Your cart is empty</h4>
                    <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
                    <div class="mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-success rounded-pill px-4 py-2">Continue Shopping</a>
                    </div>
                </div>
                @else
                <div class="checkout-main-content">

                    <!-- Saved Addresses Section (Only if user has addresses) -->
                    @if(auth()->check() && $userAddresses->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="fas fa-address-book text-success me-2"></i> Saved Addresses
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-3">
                                @foreach($userAddresses as $addr)
                                @php
                                $isSelected = (isset($savedAddress['address_id']) && $savedAddress['address_id'] == $addr->id);
                                @endphp
                                <div class="col-md-6">
                                    <div class="card h-100 saved-address-card border-2 transition-all cursor-pointer {{ $isSelected ? 'selected' : 'border-light' }}"
                                        data-address='@json($addr)'
                                        onclick="fillAddressFormFromData(this)">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $addr->first_name }} {{ $addr->last_name }}</h6>
                                                <div class="selection-indicator">
                                                    <div class="indicator-dot"></div>
                                                </div>
                                            </div>
                                            <!-- <div class="mt-1 mb-2">
                                                            @if($addr->is_default)
                                                                <span class="badge bg-primary-soft text-primary extra-small px-2">Default Address</span>
                                                            @endif
                                                        </div> -->
                                            <p class="text-muted small mb-1 line-height-base">
                                                {{ $addr->door_number ? $addr->door_number . ', ' : '' }}{{ $addr->street }}<br>
                                                {{ $addr->city }}, {{ $addr->state }} - {{ $addr->post_code }}
                                            </p>
                                            <div class="text-dark small fw-medium mt-2">
                                                <i class="fas fa-phone-alt me-1 extra-small text-muted"></i> {{ $addr->phone_number }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <!-- Add New Address Card -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-2 border-dashed border-light transition-all cursor-pointer add-new-address-card text-center d-flex align-items-center justify-content-center p-4 bg-light shadow-sm-hover"
                                        onclick="resetAndFocusForm()">
                                        <div class="card-body py-4">
                                            <div class="text-success mb-2 fs-3">
                                                <i class="fas fa-plus-circle"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Add New Address</h6>
                                            <p class="text-muted extra-small mb-0">Enter a different delivery location</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Shipping Form Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-success me-2"></i> Shipping Details
                            </h5>
                            <p class="text-muted extra-small mb-0 mt-1">Please enter your accurate delivery information</p>
                        </div>
                        <div class="card-body pt-2">
                            <form action="{{ route('checkout.saveAddress') }}" method="POST" id="shipping-address-form">
                                @csrf
                                <input type="hidden" name="address_id" id="address_id" value="{{ old('address_id', $savedAddress['address_id'] ?? session('shipping_address')['address_id'] ?? '') }}">

                                <div class="row g-3">
                                    <!-- Full Name -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-dark fw-bold small">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter your full name" required value="{{ old('name', $savedAddress['name'] ?? '') }}">
                                        </div>
                                        @error('name') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Door Number & Street -->
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label for="door_number" class="form-label text-dark fw-bold small">Door / Block No.</label>
                                            <input type="text" name="door_number" id="door_number" class="form-control @error('door_number') is-invalid @enderror"
                                                placeholder="e.g. 12A" value="{{ old('door_number', $savedAddress['door_number'] ?? '') }}">
                                        </div>
                                        @error('door_number') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-7">
                                        <div class="mb-3">
                                            <label for="address" class="form-label text-dark fw-bold small">Street / Road Name <span class="text-danger">*</span></label>
                                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                                placeholder="Enter street or area name" required value="{{ old('address', $savedAddress['address'] ?? '') }}">
                                        </div>
                                        @error('address') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- City, State, Pincode -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="city" class="form-label text-dark fw-bold small">City / Town <span class="text-danger">*</span></label>
                                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                                                placeholder="City" required value="{{ old('city', $savedAddress['city'] ?? '') }}">
                                        </div>
                                        @error('city') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="state" class="form-label text-dark fw-bold small">State <span class="text-danger">*</span></label>
                                            <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror"
                                                placeholder="State name" required value="{{ old('state', $savedAddress['state'] ?? '') }}">
                                        </div>
                                        @error('state') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="pincode" class="form-label text-dark fw-bold small">Pincode <span class="text-danger">*</span></label>
                                            <input type="text" name="pincode" id="pincode" class="form-control @error('pincode') is-invalid @enderror"
                                                placeholder="6-digit PIN code" required value="{{ old('pincode', $savedAddress['pincode'] ?? '') }}">
                                        </div>
                                        @error('pincode') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label text-dark fw-bold small">Phone Number <span class="text-danger">*</span></label>
                                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="10-digit mobile number" required value="{{ old('phone', $savedAddress['phone'] ?? '') }}">
                                        </div>
                                        @error('phone') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="checkout-actions d-flex flex-column flex-md-row justify-content-between mt-4 border-top pt-4 gap-3">
                                    <a href="{{ route('cart.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 order-2 order-md-1 w-100 text-center">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Cart
                                    </a>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-success-hover order-1 order-md-2 w-100 text-center">
                                        CONTINUE TO PAYMENT <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Simplified Summary Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    @if($cartItems && $cartItems->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-light text-dark py-3">
                            <h6 class="mb-0 fw-bold text-center">ORDER SUMMARY</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="cart-items-preview" style="max-height: 300px; overflow-y: auto;">
                                @foreach($cartItems as $item)
                                @php
                                $isCombo = (bool) $item->combo_pack_id;
                                $p = $isCombo ? $item->comboPack : $item->product;
                                $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                @endphp
                                <div class="p-3 d-flex align-items-center border-bottom border-light summary-item-row">
                                    <div class="product-thumb-sm-preview me-3 bg-white border rounded p-1">
                                        @php
                                        $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                        @endphp
                                        <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="" class="w-100 h-100 object-fit-contain">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold extra-small text-dark line-clamp-1">{{ $p->name }}</div>
                                        <div class="text-muted extra-small">Qty: {{ $item->quantity }} • ₹{{ number_format($item->calculated_price * $item->quantity, 2) }}</div>
                                    </div>
                                    <div class="ps-2">
                                        <button type="button" class="btn-close-style" 
                                                onclick="removeFromCartSummary('{{ $item->id }}')" title="Remove Item">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="p-3 bg-light">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Total Products ({{ $itemCount }})</span>
                                    <span class="fw-bold extra-small text-dark">₹{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Total Weight</span>
                                    <span class="fw-bold extra-small text-dark">{{ number_format($totalWeight, 2) }} KG</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Shipping Cost</span>
                                    <span class="text-dark extra-small fw-bold">@if($shipping > 0) +₹{{ number_format($shipping, 2) }} @else ₹0.00 @endif</span>
                                </div>
                                @if($discount > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Discount</span>
                                    <span class="text-success extra-small fw-bold">-₹{{ number_format($discount, 2) }}</span>
                                </div>
                                @endif
                                @if($tax > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Estimated Tax (GST)</span>
                                    <span class="text-danger extra-small fw-bold">+₹{{ number_format($tax, 2) }}</span>
                                </div>
                                @endif
                                <hr class="my-2 border-dashed">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="fw-bold text-dark fs-6">Order Total</span>
                                    <span class="fw-bold text-success fs-5">₹{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Trust Cards -->
                    <div class="trust-cards-grid row g-2">
                        <div class="col-6">
                            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 text-center h-100">
                                <div class="text-success mb-2 fs-4"><i class="fas fa-shield-check"></i></div>
                                <h6 class="extra-small fw-bold mb-0">Safe Shipping</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 text-center h-100">
                                <div class="text-primary mb-2 fs-4"><i class="fas fa-truck-container"></i></div>
                                <h6 class="extra-small fw-bold mb-0">Fast Shipping</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #72a420;
        --secondary-color: #0d6efd;
        --light-bg: #f8f9fa;
        --border-dashed: #dee2e6;
        --primary-light: rgba(114, 164, 32, 0.1);
    }

    .checkout-page-wrapper {
        min-height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Steps */
    .checkout-steps .breadcrumb-item+.breadcrumb-item::before {
        content: "\f054";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 10px;
        color: #adb5bd;
    }

    .checkout-steps .breadcrumb-item {
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .checkout-steps .breadcrumb-item.active {
        color: var(--primary-color);
    }

    .checkout-steps .breadcrumb-item a {
        color: #adb5bd;
        text-decoration: none;
    }

    /* Saved Address Cards */
    .saved-address-card,
    .add-new-address-card {
        border: 2px solid transparent;
        background-color: #fff;
    }

    .saved-address-card:hover,
    .add-new-address-card:hover {
        border-color: var(--primary-light);
        transform: translateY(-3px);
    }

    .shadow-sm-hover:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .saved-address-card.selected {
        border-color: var(--primary-color) !important;
        background-color: var(--primary-light);
    }

    /* Selection Indicator styling */
    .selection-indicator {
        width: 20px;
        height: 20px;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .indicator-dot {
        width: 10px;
        height: 10px;
        background-color: #fff;
        border-radius: 50%;
        transform: scale(0);
        transition: transform 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
    }

    .saved-address-card.selected .selection-indicator {
        border-color: var(--primary-color);
        background-color: var(--primary-color);
    }

    .saved-address-card.selected .indicator-dot {
        transform: scale(1);
    }

    .border-primary-light {
        border-color: var(--primary-light) !important;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e9ecef;
        background-color: #fff;
        height: auto;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(114, 164, 32, 0.1);
        outline: none;
    }

    .form-label {
        margin-bottom: 0.5rem;
    }

    /* Product Thumbnail Sidebar */
    .product-thumb-sm-preview {
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        overflow: hidden;
    }

    /* Global Utils */
    .bg-primary-soft {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .extra-small {
        font-size: 11px;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .shadow-success-hover:hover {
        box-shadow: 0 8px 25px rgba(114, 164, 32, 0.4);
        transform: translateY(-2px);
        transition: all 0.3s;
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Buttons */
    .btn-success {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-success:hover {
        background-color: #5a821a;
        border-color: #5a821a;
    }

    /* Hide scrollbar but keep functionality */
    .hover-opacity-100:hover { opacity: 1 !important; transform: scale(1.1); transition: all 0.2s; }
    .border-dashed { border-top: 1px dashed #dee2e6 !important; background: transparent; }
    .summary-item-row:hover { background-color: #fcfcfc; }

    .cart-items-preview::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 10px;
    }

    @media (max-width: 991.98px) {
        .sticky-top {
            position: static !important;
            margin-top: 2rem;
        }
    }
</style>

<script>
    function resetAndFocusForm() {
        // Clear the hidden ID
        document.getElementById('address_id').value = '';

        // Reset all form inputs
        const form = document.getElementById('shipping-address-form');
        form.reset();

        // Manually clear if needed (sometimes reset doesn't hit everything in complex forms)
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.name !== '_token') input.value = '';
        });

        // Remove selection from all cards
        document.querySelectorAll('.saved-address-card').forEach(card => {
            card.classList.remove('selected', 'border-primary-light');
            card.classList.add('border-light');
        });

        // Scroll to form and focus first field
        const nameField = document.getElementById('name');
        nameField.scrollIntoView({
            behavior: 'smooth'
        });
        setTimeout(() => nameField.focus(), 500);
    }

    function fillAddressFormFromData(element) {
        const addr = JSON.parse(element.getAttribute('data-address'));

        document.getElementById('address_id').value = addr.id;
        document.getElementById('name').value = (addr.first_name || '') + ' ' + (addr.last_name || '');
        document.getElementById('door_number').value = addr.door_number || '';
        document.getElementById('address').value = addr.street || '';
        document.getElementById('city').value = addr.city || '';
        document.getElementById('state').value = addr.state || '';
        document.getElementById('pincode').value = addr.post_code || '';
        document.getElementById('phone').value = addr.phone_number || '';

        // Highlight selected card
        document.querySelectorAll('.saved-address-card').forEach(card => {
            card.classList.remove('selected', 'border-primary-light');
            card.classList.add('border-light');
        });

        element.classList.add('selected');
        element.classList.remove('border-light');

        // Scroll to form on mobile
        if (window.innerWidth < 768) {
            document.getElementById('shipping-address-form').scrollIntoView({
                behavior: 'smooth'
            });
        }
    }

    function removeFromCartSummary(itemId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to remove this item from your order?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#72a420",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, remove it!",
            cancelButtonText: "Keep it"
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a temporary form to send DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('cart/remove') }}/" + itemId + "?redirect=checkout";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<script>
async function removeFromCartSummary(itemId) {
    const { value: confirmed } = await Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#72a420',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    });

    if (confirmed) {
        try {
            const response = await fetch(`${window.APP_URL}/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            if (data.success) {
                window.location.reload();
            } else {
                Swal.fire('Error', data.message || 'Failed to remove item', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to communicate with server', 'error');
        }
    }
}
</script>
<style>
.btn-close-style {
    background: transparent;
    border: none;
    color: #adb5bd;
    font-size: 14px;
    padding: 5px;
    transition: all 0.2s;
    cursor: pointer;
}
.btn-close-style:hover {
    color: #dc3545;
}
.summary-item-row {
    transition: background 0.2s;
}
.summary-item-row:hover {
    background-color: #fcfcfc;
}
/* Ensure Inter font */
body { font-family: 'Inter', sans-serif !important; }
</style>
@include('view.layout.footer')