@if(isset($cartItems) && $cartItems->count() > 0)
    <div class="cart-drawer-items">
        @php
        $groupedCartItems = $cartItems->groupBy(function($item) {
            return $item->custom_combo_id ?? 'single';
        });
        @endphp
        
        @foreach($groupedCartItems as $comboId => $groupItems)
            @if($comboId === 'single')
                @foreach($groupItems as $item)
                    @php
                    $isCombo = (bool) $item->combo_pack_id;
                    $p = $isCombo ? $item->comboPack : $item->product;
                    @endphp
                    @if($p)
                        @php
                            $stock = 0;
                            if ($isCombo) {
                                $stock = $p->stock_quantity ?? 0;
                            } else {
                                $stock = $p->stock_quantity ?? 0;
                                if ($item->options) {
                                    $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                                    if (is_array($options) && isset($options['size']) && $p->size) {
                                        $sizes = is_string($p->size) ? json_decode($p->size, true) : $p->size;
                                        if (is_array($sizes) && isset($sizes[$options['size']])) {
                                            $sizeData = $sizes[$options['size']];
                                            $sizeStock = is_array($sizeData) ? ($sizeData['stock'] ?? null) : null;
                                            if ($sizeStock !== null) {
                                                $stock = $sizeStock;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        <div class="cart-drawer-item" id="drawerItem_{{ $item->id }}">
                            <div class="drawer-item-img">
                                @php
                                $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                @endphp
                                @if($isCombo && !$p->is_combo_only && is_array($imgData) && count($imgData) >= 2)
                                    <div class="dual-image-drawer">
                                        <img src="{{ asset('storage/' . $imgData[0]) }}" alt="{{ $p->name }}">
                                        <img src="{{ asset('storage/' . $imgData[1]) }}" alt="{{ $p->name }}">
                                    </div>
                                @else
                                    @php
                                    $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                    @endphp
                                    <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}">
                                @endif
                            </div>
                            <div class="drawer-item-details">
                                <a href="{{ route('product.show', $p->slug) }}" class="drawer-item-name">{{ $p->name }}</a>
                                @if($item->options)
                                    @php $options = is_string($item->options) && is_array(json_decode($item->options, true)) ? json_decode($item->options, true) : $item->options; @endphp
                                    @if(is_array($options) && isset($options['size']))
                                        <span class="drawer-item-meta">Size: {{ $options['size'] }}</span>
                                    @elseif(is_string($options) && !empty($options))
                                        <span class="drawer-item-meta">Size: {{ $options }}</span>
                                    @endif
                                @endif
                                <span class="drawer-item-price">₹{{ number_format($item->calculated_price, 2) }}</span>
                                
                                <div class="drawer-qty-control mt-2">
                                    <div class="qty-pill-control drawer-qty-pill">
                                        <button type="button" class="qty-btn-inline drawer-qty-dec" data-item-id="{{ $item->id }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" class="qty-number-input drawer-qty-input" value="{{ $item->quantity }}" readonly>
                                        <button type="button" class="qty-btn-inline drawer-qty-inc" data-item-id="{{ $item->id }}" data-stock="{{ $stock }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="drawer-item-remove" onclick="removeDrawerItem('{{ $item->id }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                @php
                 $comboSubtotal = $groupItems->sum(function($item) {
                     return $item->calculated_price * $item->quantity;
                 });
                 $slabs = \App\Models\ComboPackDiscountSlab::where('status', true)->orderBy('min_amount', 'asc')->get();
                 $pct = 0;
                 foreach($slabs as $slab) {
                     if ($comboSubtotal >= $slab->min_amount) {
                         $pct = (float)$slab->discount_percentage;
                     }
                 }
                 $comboDiscount = $comboSubtotal * ($pct / 100);
                 $firstItem = $groupItems->first();
                @endphp
                <div class="cart-drawer-item custom-combo-drawer-block" id="drawerItem_{{ $firstItem->id }}">
                    <div class="drawer-combo-header">
                        <span class="badge bg-success text-white">Custom Combo Bundle</span>
                        <button type="button" class="drawer-combo-remove" onclick="removeDrawerItem('{{ $firstItem->id }}')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="drawer-combo-body">
                        @foreach($groupItems as $item)
                            @php $p = $item->product; @endphp
                            @if($p)
                                <div class="drawer-combo-subitem">
                                    <div class="drawer-item-img-sm">
                                        @php
                                        $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                                        $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                                        @endphp
                                        <img src="{{ $firstImg ? asset('storage/' . $firstImg) : asset('assets/images/product/product1.jpg') }}" alt="{{ $p->name }}">
                                    </div>
                                    <div class="drawer-item-details-sm">
                                        <span class="drawer-item-name-sm">{{ $p->name }}</span>
                                        <span class="drawer-item-price-sm">₹{{ number_format($item->calculated_price, 2) }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="drawer-combo-footer">
                        <span>Bundle Price:</span>
                        <strong class="text-success">₹{{ number_format($comboSubtotal - $comboDiscount, 2) }}</strong>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    
    <div class="cart-drawer-footer">
        <div class="drawer-summary-row">
            <span>Subtotal</span>
            <strong>₹{{ number_format($subtotal, 2) }}</strong>
        </div>
        @if(isset($discount) && $discount > 0)
        <div class="drawer-summary-row">
            <span>Discount</span>
            <span class="text-success">-₹{{ number_format($discount, 2) }}</span>
        </div>
        @endif
        @if(isset($couponDiscount) && $couponDiscount > 0)
        <div class="drawer-summary-row">
            <span>Coupon Discount</span>
            <span class="text-success">-₹{{ number_format($couponDiscount, 2) }}</span>
        </div>
        @endif
        <div class="drawer-summary-row drawer-total-row">
            <span>Total</span>
            <strong>₹{{ number_format($total ?? $subtotal, 2) }}</strong>
        </div>
        
        <div class="drawer-actions">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-success w-100 mb-2 py-2">
                <span class="d-none d-lg-inline">View Cart</span>
                <span class="d-inline d-lg-none">Cart</span>
            </a>
            <a href="{{ route('checkout.address') }}" class="btn btn-success w-100 py-2 btn-checkout-drawer">
                <span class="d-none d-lg-inline">Proceed to Checkout</span>
                <span class="d-inline d-lg-none">Checkout</span>
            </a>
        </div>
    </div>
@else
    <div class="empty-drawer-container">
        <div class="empty-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <h4>Your cart is empty</h4>
        <p>Start adding some fresh greens to it!</p>
        <button type="button" class="btn btn-success px-4 py-2 mt-3" id="drawerContinueShoppingBtn">Shop Now</button>
    </div>
@endif
