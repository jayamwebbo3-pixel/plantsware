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
                                {{ $p->name }}
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
                <p>Shipping: ₹{{ number_format($shipping, 2) }}</p>
                <p>Tax: ₹{{ number_format($tax, 2) }}</p>
                <h4>Total: ₹{{ number_format($total, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Shipping Address</h3>
            <p>{{ $shippingAddress['name'] }}<br>
            {{ $shippingAddress['address'] }}<br>
            {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} - {{ $shippingAddress['pincode'] }}<br>
            Phone: {{ $shippingAddress['phone'] }}</p>
            <a href="{{ route('checkout.address') }}" class="btn btn-secondary">Edit Address</a>
        </div>
    </div>
    <form action="{{ route('checkout.placeOrder') }}" method="POST">
        @csrf
        <div class="form-group mb-4">
            <label class="font-weight-bold">Payment Method</label>
            <select name="payment_method" class="form-control" readonly>
                <option value="online">Online Payment (Cards/UPI/Netbanking)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-100 py-3 font-weight-bold">Proceed to Payment -> </button>
    </form>
</div>

@include('view.layout.footer')