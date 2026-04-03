@include('view.layout.header')

<div class="container py-5">
    <h1>Checkout</h1>
    <div class="row">
        <div class="col-md-8">
            <h3>Order Summary</h3>
            <table class="table">
                <thead>
                    <tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        @php
                            $isCombo = (bool) $item->combo_pack_id;
                            $p = $isCombo ? $item->comboPack : $item->product;
                        @endphp
                        <tr>
                            <td>
                                @php
                                    $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                @endphp
                                <div class="position-relative d-inline-block">
                                    <button type="button" class="btn btn-sm btn-danger p-0 rounded-circle position-absolute" 
                                            onclick="removeCheckoutItem({{ $item->id }})"
                                            style="top: -8px; left: -8px; width: 20px; height: 20px; line-height: 18px; font-size: 12px; z-index: 5;" 
                                            title="Remove item">
                                        &times;
                                    </button>
                                    <div class="d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: #fdfdfd; border-radius: 4px; overflow: hidden; margin-right: 15px; vertical-align: middle;">
                                        @if($isCombo && !$p->is_combo_only && is_array($imgData) && count($imgData) >= 2)
                                            <div class="checkout-dual-image d-flex align-items-center justify-content-center w-100 h-100 p-1">
                                                <img src="{{ asset('storage/' . $imgData[0]) }}" alt="{{ $p->name }}" style="width: 40%; height: auto; object-fit: contain;">
                                                <span style="font-size: 10px; font-weight: bold; color: #72a420; margin: 0 1px;">+</span>
                                                <img src="{{ asset('storage/' . $imgData[1]) }}" alt="{{ $p->name }}" style="width: 40%; height: auto; object-fit: contain;">
                                            </div>
                                        @else
                                            @php
                                                $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                            @endphp
                                            <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" 
                                                 alt="{{ $p->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>
                                {{ $p->name }}
                                <div class="small text-muted">
                                    <span>Weight: {{ $p->weight ?? 0 }} KG</span>
                                    @php
                                        $priceToUse = $item->calculated_price;
                                        $regularPrice = $isCombo ? $p->total_price : $p->price;
                                        $savings = max(0, $regularPrice - $priceToUse);
                                    @endphp
                                    @if($savings > 0)
                                        <span class="ms-2 text-success">Saved: ₹{{ number_format($savings, 2) }}</span>
                                    @endif
                                </div>
                                @if($item->options)
                                    @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                    @if(is_array($options) && isset($options['size']))
                                        <br><small class="text-muted">Size: {{ $options['size'] }}</small>
                                    @elseif(is_string($options) && !empty($options))
                                        <br><small class="text-muted">Size: {{ $options }}</small>
                                    @endif
                                @endif
                                @if($isCombo)
                                    <span class="badge badge-danger ms-2">COMBO</span>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            @php
                                $priceToUse = $item->calculated_price;
                            @endphp
                            <td>₹{{ number_format($priceToUse, 2) }}</td>
                            <td>₹{{ number_format($priceToUse * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end">
                <p>Subtotal: ₹{{ number_format($subtotal, 2) }}</p>
                <p>Total Weight: {{ $total_weight ?? $totalWeight ?? 0 }} KG</p>
                <p>Shipping: <span class="fw-bold">₹{{ number_format($shipping, 2) }}</span></p>
                @if(isset($gstSettings) && $gstSettings->gst_status)
                    <p>GST ({{ number_format($gstSettings->gst_percentage, 1) }}%): <span class="fw-bold text-danger">+₹{{ number_format($tax, 2) }}</span></p>
                @else
                    <p>Tax: <span class="fw-bold">₹{{ number_format($tax, 2) }}</span></p>
                @endif
                <p>Discount: <span style="color: #72a420;" class="fw-bold">-₹{{ number_format($discount ?? 0, 2) }}</span></p>
                <h4>Total: ₹{{ number_format($total, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Shipping Address</h3>
            <p>{{ $shippingAddress['name'] }}<br>
            @if(!empty($shippingAddress['door_number'])){{ $shippingAddress['door_number'] }}, @endif{{ $shippingAddress['address'] }}<br>
            {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} - {{ $shippingAddress['pincode'] }}<br>
            Phone: {{ $shippingAddress['phone'] }}</p>
            <a href="{{ route('checkout.address') }}" class="btn btn-secondary">Edit Address</a>
        </div>
    </div>
    <form action="{{ route('checkout.placeOrder') }}" method="POST" class="mt-4">
        @csrf
        <div class="payment-selection mb-4">
            <label class="font-weight-bold text-dark mb-2" style="font-size: 16px;">
                <i class="fas fa-wallet text-success me-1"></i> Choose Payment Method
            </label>
            <div class="custom-select-wrapper position-relative">
                <select name="payment_method" id="payment_method" class="form-control form-select py-3 px-4 shadow-sm" 
                        style="border: 2px solid #e0e0e0; border-radius: 12px; font-weight: 600; cursor: pointer; background-color: #fff !important; height: auto !important; position: relative; z-index: 5;">
                    <option value="online" selected>💳 Online Payment (Zero Transaction Fee)</option>
                    <option value="cod">🚚 Cash on Delivery (COD)</option>
                </select>
            </div>
            
            <!-- Accepted Icons -->
            <div id="payment-icons-list" class="mt-3 p-3 bg-light rounded d-flex flex-wrap align-items-center justify-content-center gap-2 border shadow-sm">
                <span class="small text-muted w-100 text-center mb-2">We Accept:</span>
                <img src="https://christianbooksworld.com/home/img/hero-bg/visa.png" alt="Visa" style="height: 20px;">
                <img src="https://christianbooksworld.com/home/img/hero-bg/rupay.png" alt="RuPay" style="height: 20px;">
                <img src="https://christianbooksworld.com/home/img/hero-bg/gpay.png" alt="GPay" style="height: 20px;">
                <img src="https://christianbooksworld.com/home/img/hero-bg/paytm.png" alt="Paytm" style="height: 20px;">
                <img src="https://christianbooksworld.com/home/img/hero-bg/upi.webp" alt="UPI" style="height: 20px;">
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 py-3 rounded-pill font-weight-bold shadow transition-all hover-scale" 
                style="font-size: 18px; letter-spacing: 0.5px; background-color: #28a745 !important; border-color: #28a745 !important; color: #ffffff !important; display: block !important; border-width: 0;">
            SECURE CHECKOUT <i class="fas fa-lock ms-2"></i>
        </button>
    </form>

    <style>
        .custom-select-wrapper {
            position: relative;
        }
        .form-select:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.15) !important;
        }
        .hover-scale:active {
            transform: scale(0.97);
        }
        @media (max-width: 576px) {
            #payment-icons-list img {
                height: 16px !important;
            }
            .form-select {
                font-size: 14px !important;
                padding: 10px 15px !important;
            }
        }
    </style>
</div>

<script>
    function removeCheckoutItem(cartId) {
        Swal.fire({
            title: 'Remove Item?',
            text: "Are you sure you want to remove this item from your order?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#72a420',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('cart/remove') }}/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); 
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error removing item'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.'
                    });
                });
            }
        });
    }
</script>

@include('view.layout.footer')