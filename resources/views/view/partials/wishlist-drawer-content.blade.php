@if(isset($wishlistItems) && $wishlistItems->count() > 0)
    <div class="cart-drawer-items">
        @foreach($wishlistItems as $item)
            @php
            $isCombo = (bool) $item->combo_pack_id;
            $p = $isCombo ? $item->comboPack : $item->product;
            @endphp
            @if($p)
                <div class="cart-drawer-item" id="drawerWishlistItem_{{ $item->id }}">
                    <div class="drawer-item-img">
                        @php
                        $imgData = is_string($p->image) ? json_decode($p->image, true) : $p->image;
                        $firstImg = is_array($imgData) && count($imgData) > 0 ? $imgData[0] : $p->image;
                        @endphp
                        <img src="{{ $firstImg ? asset("storage/" . $firstImg) : asset("assets/images/product/product1.jpg") }}" alt="{{ $p->name }}">
                    </div>
                    <div class="drawer-item-details">
                        <a href="{{ route("product.show", $p->slug) }}" class="drawer-item-name">{{ $p->name }}</a>
                        <span class="drawer-item-price">₹{{ number_format($p->offer_price ?? $p->price, 2) }}</span>
                        
                        <div class="drawer-qty-control mt-2" style="justify-content: flex-end;">
                            <form action="{{ $isCombo ? route("cart.add_combo", $p->id) : route("cart.add", $p->id) }}" method="POST" style="margin: 0; padding: 0;">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm btn-success" style="padding: 4px 10px; font-size: 11px; border-radius: 4px; border:none; margin-right: 5px;">Add to Cart</button>
                            </form>
                            <form action="{{ route($isCombo ? "wishlist.remove_combo" : "wishlist.remove", $p->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="drawer-item-remove" style="width: 26px; height: 26px;">
                                    <i class="fas fa-trash-alt" style="font-size: 11px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    
    <div class="cart-drawer-footer" style="padding: 15px; text-align: center;">
        <a href="{{ route("wishlist.index") }}" class="btn btn-outline-success w-100" style="border-radius: 50px; font-weight: 600;">View Full Wishlist</a>
    </div>
@else
    <div class="empty-drawer-container">
        <div class="empty-icon" style="color: #dc3545; background: #fff5f5;">
            <i class="fas fa-heart"></i>
        </div>
        <h4>Your wishlist is empty</h4>
        <p>Save your favorite plants for later!</p>
        <button type="button" class="btn btn-success px-4 py-2 mt-3" id="drawerContinueShoppingBtn">Shop Now</button>
    </div>
@endif
