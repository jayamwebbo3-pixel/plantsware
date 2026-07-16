@include('view.layout.header')

<div class="checkout-page-wrapper bg-light py-4 py-md-5">
    <div class="container">
        <!-- Modern Step Header -->
        <div class="d-flex align-items-center mb-4 pb-3 border-bottom d-md-none">
            <a href="{{ route('cart.index') }}" class="text-dark text-decoration-none me-3">
                <i class="fas fa-arrow-left fs-4"></i>
            </a>
            <div>
                <div class="text-muted extra-small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Step 2 of 3</div>
                <h5 class="mb-0 fw-bold text-dark">Delivery Details</h5>
            </div>
        </div>

        <!-- Desktop Steps Indicator -->
        <div class="checkout-steps-container d-none d-md-flex justify-content-between align-items-center mb-4 mt-2" style="border-bottom: none; padding-bottom: 0;">
            <div class="d-flex align-items-center">
                <div class="step-item active fw-semibold" style="color: #388e3c; font-size: 15px;">
                    Address
                </div>
                <div class="step-divider mx-3" style="color: #adb5bd;"><i class="fas fa-chevron-right" style="font-size: 11px;"></i></div>
                
                <div class="step-item pending fw-semibold" style="color: #adb5bd; font-size: 15px;">
                    Payment & Review
                </div>
            </div>
            <div class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.85rem;">
                Step 1 of 2
            </div>
        </div>

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
                            <h5 class="mb-0 fw-bold d-flex align-items-center" style="font-size: 18px; color: #0f172a;">
                                <i class="fas fa-address-book fa-fw me-3" style="color: #388e3c;"></i> Saved Addresses
                            </h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="saved-addresses-list">
                                @foreach($userAddresses as $addr)
                                @php
                                $isSelected = (isset($savedAddress['address_id']) && $savedAddress['address_id'] == $addr->id);
                                @endphp
                                <div class="saved-address-card d-flex flex-column p-3 rounded shadow-sm border transition-all cursor-pointer mb-3 {{ $isSelected ? 'bg-white border-success selected' : 'bg-white border-light' }}"
                                    data-address="{{ json_encode($addr) }}"
                                    onclick="fillAddressFormFromData(this)"
                                    style="border-color: {{ $isSelected ? '#388e3c' : '#e2e8f0' }} !important;">
                                    
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="fw-bold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px; color: #64748b;">
                                            <i class="fas fa-address-card me-2"></i>Saved Address
                                        </div>
                                        @if($isSelected)
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm" style="font-size: 12px;"><i class="fas fa-check-circle me-1"></i>Selected</span>
                                        @else
                                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill" style="font-size: 12px;">Select</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                                        <i class="fas fa-map-marker-alt fs-5 me-3" style="color: #1e293b;"></i>
                                        <div class="text-truncate" style="font-size: 14.5px;">
                                            <span class="fw-bold" style="color: #0f172a;">{{ explode(' ', trim($addr->first_name))[0] }}</span> 
                                            <span class="text-muted mx-1">|</span>
                                            <span style="color: #334155;">{{ $addr->street }}, {{ $addr->city }}, {{ $addr->state }} - {{ $addr->post_code }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <!-- Add New Address Button -->
                                <div class="d-flex align-items-center p-3 rounded border border-dashed transition-all cursor-pointer bg-light shadow-sm-hover add-new-address-card mb-3"
                                    onclick="resetAndFocusForm()" style="border-color: #cbd5e1 !important;">
                                    <i class="fas fa-plus-circle fs-5 me-3" style="color: #388e3c;"></i>
                                    <div class="text-truncate" style="font-size: 14.5px;">
                                        <span class="fw-bold" style="color: #0f172a;">Add New Address</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Shipping Form Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center" style="font-size: 18px; color: #0f172a;">
                                <i class="fas fa-map-marker-alt fa-fw me-3" style="color: #388e3c;"></i> Shipping Details
                            </h5>
                            <p class="text-muted extra-small mb-0 mt-1 ms-4 ps-2">Please enter your accurate delivery information</p>
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

                                    <!-- Billing address same as shipping checkbox -->
                                    <div class="col-12 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="billing_same" id="billing_same" value="1" {{ old('billing_same', $billingSame ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-bold small" for="billing_same">
                                                Billing address is same as shipping address
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Collapsible Billing Details -->
                                    <div id="billing-address-section" style="display: none;" class="col-12 mt-3 pt-3 border-top">
                                        <h5 class="mb-3 fw-bold d-flex align-items-center text-dark">
                                            <i class="fas fa-file-invoice text-success me-2"></i> Billing Details
                                        </h5>
                                        <div class="row g-3">
                                            <!-- Billing Full Name -->
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="billing_name" class="form-label text-dark fw-bold small">Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="billing_name" id="billing_name" class="form-control @error('billing_name') is-invalid @enderror"
                                                        placeholder="Enter billing full name" value="{{ old('billing_name', $savedAddress['billing_name'] ?? '') }}">
                                                </div>
                                                @error('billing_name') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Billing Door Number & Street -->
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label for="billing_door_number" class="form-label text-dark fw-bold small">Door / Block No.</label>
                                                    <input type="text" name="billing_door_number" id="billing_door_number" class="form-control @error('billing_door_number') is-invalid @enderror"
                                                        placeholder="e.g. 12A" value="{{ old('billing_door_number', $savedAddress['billing_door_number'] ?? '') }}">
                                                </div>
                                                @error('billing_door_number') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-7">
                                                <div class="mb-3">
                                                    <label for="billing_address" class="form-label text-dark fw-bold small">Street / Road Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="billing_address" id="billing_address" class="form-control @error('billing_address') is-invalid @enderror"
                                                        placeholder="Enter street or area name" value="{{ old('billing_address', $savedAddress['billing_address'] ?? '') }}">
                                                </div>
                                                @error('billing_address') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Billing City, State, Pincode -->
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="billing_city" class="form-label text-dark fw-bold small">City / Town <span class="text-danger">*</span></label>
                                                    <input type="text" name="billing_city" id="billing_city" class="form-control @error('billing_city') is-invalid @enderror"
                                                        placeholder="City" value="{{ old('billing_city', $savedAddress['billing_city'] ?? '') }}">
                                                </div>
                                                @error('billing_city') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="billing_state" class="form-label text-dark fw-bold small">State <span class="text-danger">*</span></label>
                                                    <input type="text" name="billing_state" id="billing_state" class="form-control @error('billing_state') is-invalid @enderror"
                                                        placeholder="State name" value="{{ old('billing_state', $savedAddress['billing_state'] ?? '') }}">
                                                </div>
                                                @error('billing_state') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="billing_pincode" class="form-label text-dark fw-bold small">Pincode <span class="text-danger">*</span></label>
                                                    <input type="text" name="billing_pincode" id="billing_pincode" class="form-control @error('billing_pincode') is-invalid @enderror"
                                                        placeholder="6-digit PIN code" value="{{ old('billing_pincode', $savedAddress['billing_pincode'] ?? '') }}">
                                                </div>
                                                @error('billing_pincode') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Billing Phone Number -->
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="billing_phone" class="form-label text-dark fw-bold small">Phone Number <span class="text-danger">*</span></label>
                                                    <input type="tel" name="billing_phone" id="billing_phone" class="form-control @error('billing_phone') is-invalid @enderror"
                                                        placeholder="10-digit mobile number" value="{{ old('billing_phone', $savedAddress['billing_phone'] ?? '') }}">
                                                </div>
                                                @error('billing_phone') <div class="text-danger extra-small ms-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="checkout-actions d-none d-md-flex flex-column flex-md-row justify-content-between mt-4 border-top pt-4 gap-3 px-3 px-md-4">
                                    <a href="{{ route('cart.index') }}" class="btn-checkout-secondary order-2 order-md-1">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Back to Cart
                                    </a>
                                    <button type="submit" class="btn-checkout-primary order-1 order-md-2 " style="font-size: 15px;">
                                        CONTINUE TO PAYMENT <i class="fas fa-arrow-right mx-1"></i>
                                    </button>
                                </div>
                                
                                <!-- Mobile Fixed Bottom Bar -->
                                <div class="fixed-bottom bg-white border-top p-3 d-flex justify-content-between align-items-center shadow-lg d-md-none" style="z-index: 1050;">
                                    <div>
                                        <div class="fw-bold text-dark fs-5">₹{{ number_format($total, 2) }}</div>
                                        <div class="text-primary small fw-medium" data-bs-toggle="modal" data-bs-target="#orderSummaryModal">View details</div>
                                    </div>
                                    <button type="submit" form="shipping-address-form" class="btn btn-dark px-4 py-2 fw-bold" style="border-radius: 6px;">
                                        Proceed to Payment
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Mobile Order Summary Modal -->
            <div class="modal fade" id="orderSummaryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-bottom modal-fullscreen-sm-down">
                    <div class="modal-content border-0 rounded-top-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Order Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- We will render the same summary items here via JS or duplicate blade -->
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bag Total</span>
                                <span class="text-dark">₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bag Savings</span>
                                <span class="text-success fw-bold">-₹{{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee</span>
                                <span class="text-dark">@if($shipping > 0) ₹{{ number_format($shipping, 2) }} @else <span class="text-success">Free</span> @endif</span>
                            </div>
                            <hr class="border-dashed">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Amount Payable</span>
                                <span>₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Simplified Summary Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    @if($cartItems && $cartItems->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header py-3" style="background-color: #388e3c !important; border-bottom: none;">
                            <h6 class="mb-0 fw-bold text-center text-white" style="color: #ffffff !important;">ORDER SUMMARY</h6>
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
                                        @if(!$item->custom_combo_id)
                                        <button type="button" class="btn-close-style" 
                                                onclick="removeFromCartSummary('{{ $item->id }}')" title="Remove Item">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
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
                                    <span class="fw-bold extra-small text-dark">{{ number_format($totalWeight, 2) }} grams</span>
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
                                @if(isset($couponDiscount) && $couponDiscount > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">Coupon Discount ({{ $coupon->coupon_code ?? '' }})</span>
                                    <span class="text-success extra-small fw-bold">-₹{{ number_format($couponDiscount, 2) }}</span>
                                </div>
                                @endif
                                @if(isset($cgst) && $cgst > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">CGST</span>
                                    <span class="text-danger extra-small fw-bold">+₹{{ number_format($cgst, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">SGST</span>
                                    <span class="text-danger extra-small fw-bold">+₹{{ number_format($sgst, 2) }}</span>
                                </div>
                                @elseif(isset($igst) && $igst > 0)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted extra-small">IGST</span>
                                    <span class="text-danger extra-small fw-bold">+₹{{ number_format($igst, 2) }}</span>
                                </div>
                                @elseif($tax > 0)
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
                                <div class="text-success mb-2 fs-4"><i class="fas fa-shield-alt"></i></div>
                                <h6 class="extra-small fw-bold mb-0">Safe Shipping</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 shadow-sm rounded-4 bg-white p-3 text-center h-100">
                                <div class="text-success mb-2 fs-4"><i class="fas fa-shipping-fast"></i></div>
                                <h6 class="extra-small fw-bold mb-0">Fast Shipping</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const billingSame = document.getElementById('billing_same');
        const billingSection = document.getElementById('billing-address-section');
        const billingInputs = billingSection.querySelectorAll('input:not(#billing_door_number)');

        function toggleBillingSection() {
            if (billingSame.checked) {
                billingSection.style.display = 'none';
                billingInputs.forEach(input => input.removeAttribute('required'));
            } else {
                billingSection.style.display = 'block';
                billingInputs.forEach(input => input.setAttribute('required', 'required'));
            }
        }

        if (billingSame) {
            billingSame.addEventListener('change', toggleBillingSection);
            toggleBillingSection(); // Run on load
        }
    });
    function resetAndFocusForm() {
        // Clear the hidden ID
        document.getElementById('address_id').value = '';

        // Reset all form inputs
        const form = document.getElementById('shipping-address-form');
        form.reset();

        // Manually clear if needed (sometimes reset doesn't hit everything in complex forms)
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.name !== '_token' && input.name !== 'billing_same') {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            }
        });

        // Set billing_same checkbox back to true and trigger change event to hide section
        const billingSame = document.getElementById('billing_same');
        if (billingSame) {
            billingSame.checked = true;
            billingSame.dispatchEvent(new Event('change'));
        }

        // Remove selection from all cards
        document.querySelectorAll('.saved-address-card').forEach(card => {
            card.classList.remove('selected', 'border-success', 'border-primary-light');
            card.classList.add('border-light');
            card.style.borderColor = '#e2e8f0';
            
            const badge = card.querySelector('.badge');
            if (badge) {
                badge.className = 'badge bg-light text-dark border px-3 py-2 rounded-pill';
                badge.innerHTML = 'Select';
            }
        });

        // Scroll to form and focus first field
        const nameField = document.getElementById('name');
        nameField.scrollIntoView({
            behavior: 'smooth'
        });
        setTimeout(() => nameField.focus(), 500);
    }

    function fillAddressFormFromData(element) {
        const addrStr = element.getAttribute('data-address');
        const addr = JSON.parse(addrStr);

        document.getElementById('address_id').value = addr.id || '';
        document.getElementById('name').value = (addr.first_name || '') + ' ' + (addr.last_name || '');
        document.getElementById('door_number').value = addr.door_number || '';
        document.getElementById('address').value = addr.street || '';
        document.getElementById('city').value = addr.city || '';
        document.getElementById('state').value = addr.state || '';
        document.getElementById('pincode').value = addr.post_code || '';
        document.getElementById('phone').value = addr.phone_number || '';

        // Highlight selected card
        document.querySelectorAll('.saved-address-card').forEach(card => {
            card.classList.remove('selected', 'border-success', 'border-primary-light');
            card.classList.add('border-light');
            card.style.borderColor = '#e2e8f0';
            
            const badge = card.querySelector('.badge');
            if (badge) {
                badge.className = 'badge bg-light text-dark border px-3 py-2 rounded-pill';
                badge.innerHTML = 'Select';
            }
        });

        element.classList.add('selected', 'border-success');
        element.classList.remove('border-light');
        element.style.borderColor = '#388e3c';
        
        const currBadge = element.querySelector('.badge');
        if (currBadge) {
            currBadge.className = 'badge bg-success text-white px-3 py-2 rounded-pill shadow-sm';
            currBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Selected';
        }

        // Scroll to form on mobile
        if (window.innerWidth < 768) {
            document.getElementById('shipping-address-form').scrollIntoView({
                behavior: 'smooth'
            });
        }
    }

async function removeFromCartSummary(itemId) {
    const result = await Swal.fire({
        title: 'Remove Item?',
        text: 'Are you sure you want to remove this item from your order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#72a420',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    });

    if (result.isConfirmed) {
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
                // If cart is now empty, redirect to cart page
                if (data.cart_count === 0) {
                    window.location.href = "{{ route('cart.index') }}";
                    return;
                }

                // Fetch the updated page in the background
                const pageResponse = await fetch(window.location.href);
                const pageHtml = await pageResponse.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(pageHtml, 'text/html');

                // Swap the order summary card body (sticky summary)
                const oldSummary = document.querySelector('.sticky-top');
                const newSummary = doc.querySelector('.sticky-top');
                if (oldSummary && newSummary) {
                    oldSummary.innerHTML = newSummary.innerHTML;
                }

                // Update cart count badge in header
                document.querySelectorAll('.cart-icon-link .price_cart').forEach(el => el.textContent = data.cart_count);
                document.querySelectorAll('.cart-icon-link .sticky-badge').forEach(el => el.textContent = data.cart_count);

                // Show success toast
                Swal.fire({
                    icon: 'success',
                    title: data.message || 'Item removed',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
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
    font-size: clamp(12px, 1.2vw, 14px);
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
</style>
@include('view.layout.footer')