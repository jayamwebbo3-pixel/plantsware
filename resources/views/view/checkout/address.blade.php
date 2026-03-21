@include('view.layout.header')

<div class="container py-5">
    <h1>Shipping Address</h1>
    
    <!-- Debug: Check if data is coming -->
    @php
        // Debug: Check what data is available
        // Remove this after debugging
        // dd(compact('savedAddress', 'cartItems'));
    @endphp
    
    @if(!$cartItems || $cartItems->isEmpty())
        <div class="alert alert-warning">
            Your cart is empty. <a href="{{ route('products.index') }}">Continue Shopping</a>
        </div>
    @else
        @if(auth()->check() && $userAddresses->isNotEmpty())
            <div class="mb-4">
                <h3 class="mb-3">Select from Saved Addresses</h3>
                <div class="row">
                    @foreach($userAddresses as $addr)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-{{ $addr->is_default ? 'primary' : 'secondary' }} saved-address-card" 
                                 style="cursor: pointer;"
                                 onclick="fillAddressForm({{ json_encode($addr) }})">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $addr->first_name }} {{ $addr->last_name }} 
                                        @if($addr->is_default) <span class="badge bg-primary">Default</span> @endif
                                    </h5>
                                    <p class="card-text mb-1">{{ $addr->door_number ? $addr->door_number . ', ' : '' }}{{ $addr->street }}</p>
                                    <p class="card-text mb-1">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->post_code }}</p>
                                    <p class="card-text mb-0"><small class="text-muted">📱 {{ $addr->phone_number }}</small></p>
                                </div>
                                <div class="card-footer bg-transparent border-0 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary">Select</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr class="my-4">
        @endif

        <h3 class="mb-3">Shipping Details</h3>
        <form action="{{ route('checkout.saveAddress') }}" method="POST" id="shipping-address-form">
            @csrf
            <input type="hidden" name="address_id" id="address_id" value="{{ old('address_id', session('shipping_address')['address_id'] ?? '') }}">
            
            <div class="form-group mb-3">
                <label for="name" class="form-label">Full Name *</label>
                <input type="text" name="name" id="name" class="form-control" 
                       required value="{{ old('name', $savedAddress['name'] ?? '') }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <label for="address" class="form-label">Full Address *</label>
                <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address', $savedAddress['address'] ?? '') }}</textarea>
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="city" class="form-label">City *</label>
                    <input type="text" name="city" id="city" class="form-control" 
                           required value="{{ old('city', $savedAddress['city'] ?? '') }}">
                    @error('city')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="state" class="form-label">State *</label>
                    <input type="text" name="state" id="state" class="form-control" 
                           required value="{{ old('state', $savedAddress['state'] ?? '') }}">
                    @error('state')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="pincode" class="form-label">Pincode *</label>
                    <input type="text" name="pincode" id="pincode" class="form-control" 
                           required value="{{ old('pincode', $savedAddress['pincode'] ?? '') }}">
                    @error('pincode')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group mb-4">
                <label for="phone" class="form-label">Phone Number *</label>
                <input type="tel" name="phone" id="phone" class="form-control" 
                       required value="{{ old('phone', $savedAddress['phone'] ?? '') }}">
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
                <button type="submit" class="btn btn-primary">Save & Proceed to Checkout</button>
            </div>
        </form>
    @endif
</div>

<script>
function fillAddressForm(addr) {
    document.getElementById('address_id').value = addr.id;
    document.getElementById('name').value = addr.first_name + ' ' + addr.last_name;
    document.getElementById('address').value = (addr.door_number ? addr.door_number + ', ' : '') + addr.street;
    document.getElementById('city').value = addr.city;
    document.getElementById('state').value = addr.state;
    document.getElementById('pincode').value = addr.post_code;
    document.getElementById('phone').value = addr.phone_number;
    
    // Highlight selected card
    document.querySelectorAll('.saved-address-card').forEach(card => {
        card.classList.remove('border-primary', 'shadow');
        card.classList.add('border-secondary');
    });
    event.currentTarget.classList.add('border-primary', 'shadow');
    event.currentTarget.classList.remove('border-secondary');
}
</script>

<style>
.saved-address-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
.saved-address-card {
    transition: all 0.3s ease;
}
</style>
</div>

@include('view.layout.footer')