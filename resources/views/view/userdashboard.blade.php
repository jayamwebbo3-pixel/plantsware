@include('view.layout.header')


<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">Product</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="profile-container">
    <div class="container">
        <div class="profile-content">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <ul class="nav-menu">

                    <li class="nav-item">
                        <a href="#" class="nav-link active" data-tab="address-book">
                            <i class="fas fa-map-marker-alt nav-icon"></i>
                            <span>Address Book</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tab="order-history">
                            <i class="fas fa-box nav-icon"></i>
                            <span>Order History</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tab="wishlist">
                            <i class="fas fa-heart nav-icon"></i>
                            <span>Wishlist</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tab="settings">
                            <i class="fas fa-cog nav-icon"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li class="nav-item" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link" style="background-color: white;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>

                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="main-content">

                <!-- Address Book Section -->
                <div class="content-section active" id="address-book">
                    <h2 class="section-title">Address Book</h2>

                    <div class="tab-container">
                        <div class="tab-label active" onclick="toggleAddressForm()">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Saved Addresses</span>
                        </div>
                        <div class="tab-label" onclick="toggleAddressForm()">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add New Address</span>
                        </div>
                    </div>

                    <!-- Saved Addresses -->
                    <div id="saved-addresses" class="address-cards">
                        <div class="address-card default">
                            <span class="address-badge">DEFAULT</span>
                            <div class="address-name">Home</div>
                            <div class="address-detail">123 Green Street</div>
                            <div class="address-detail">Garden Lane, New Delhi 110001</div>
                            <div class="address-detail">Delhi, India</div>
                            <div class="address-phone">📱 +91 9876543210</div>
                            <div class="address-actions">
                                <button class="address-btn address-btn-edit">Edit</button>
                                <button class="address-btn address-btn-delete">Delete</button>
                            </div>
                        </div>

                        <div class="address-card">
                            <div class="address-name">Office</div>
                            <div class="address-detail">456 Business Plaza</div>
                            <div class="address-detail">Tech Park, Bangalore 560001</div>
                            <div class="address-detail">Karnataka, India</div>
                            <div class="address-phone">📱 +91 9876543211</div>
                            <div class="address-actions">
                                <button class="address-btn address-btn-edit">Edit</button>
                                <button class="address-btn address-btn-delete">Delete</button>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Address Form -->
                    <div id="new-address-form" style="display: none;">
                        <h3 style="margin-bottom: 1.5rem; color: var(--dark-color); font-weight: 600;">Add New Address</h3>
                        <form>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" class="form-control" placeholder="Enter your first name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" placeholder="Enter your last name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Door Number</label>
                                    <input type="text" class="form-control" placeholder="Door/Block No.">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Street</label>
                                    <input type="text" class="form-control" placeholder="Street Name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">District</label>
                                    <input type="text" class="form-control" placeholder="District">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">State</label>
                                    <select class="form-select">
                                        <option>Select a State</option>
                                        <option>Delhi</option>
                                        <option>Karnataka</option>
                                        <option>Maharashtra</option>
                                        <option>Tamil Nadu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">City *</label>
                                    <input type="text" class="form-control" placeholder="City">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Post Code</label>
                                    <input type="text" class="form-control" placeholder="Post Code" maxlength="6">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" placeholder="Phone Number" maxlength="10">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alternative Number</label>
                                    <input type="tel" class="form-control" placeholder="Alternative Number" maxlength="10">
                                </div>
                            </div>
                            <div class="form-row">
                                <button type="button" class="btn-primary">Add Address</button>
                                <button type="button" class="btn-secondary" onclick="toggleAddressForm()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order History Section -->
                <div class="content-section" id="order-history">
                    <h2 class="section-title">Order History</h2>
                    <div class="order-items">
                        @forelse($orders as $order)
                        <div class="order-item" onclick="window.location='{{ route('checkout.confirmation', $order->id) }}'" style="cursor: pointer;">
                            <div class="order-image">
                                <!-- Could render related product image if relationships exist, displaying a reliable icon fallback -->
                                <i class="fas fa-box" style="font-size: 2rem; color: var(--primary-color);"></i>
                            </div>
                            <div class="order-info">
                                <h4>Order {{ $order->order_number }}</h4>
                                <p>Date: {{ $order->created_at->format('d M Y') }}</p>

                                <span class="order-status {{ strtolower($order->status) }}" style="padding: 3px 8px; border-radius: 4px; color: white; background-color: {{ in_array(strtolower($order->status), ['cancelled', 'return_rejected']) ? '#dc3545' : (strtolower($order->status) === 'shipped' ? '#17a2b8' : (in_array(strtolower($order->status), ['delivered', 'completed']) ? '#28a745' : (strtolower($order->status) === 'returned' ? '#343a40' : '#ffc107'))) }};">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>

                                @if(!in_array(strtolower($order->status), ['shipped', 'delivered', 'cancelled', 'returned', 'return_requested', 'return_rejected', 'completed']))
                                <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" style="display:inline; margin-left:10px;" onclick="event.stopPropagation();" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    @csrf
                                    <button type="submit" onclick="event.stopPropagation();" style="background-color: transparent; border: 1px solid #dc3545; color: #dc3545; border-radius: 4px; padding: 2px 8px; cursor: pointer;">Cancel</button>
                                </form>
                                @endif

                                @if(strtolower($order->status) === 'delivered' && $order->delivered_at && $order->delivered_at->diffInDays(now()) <= 3)
                                    <button type="button" class="btn-return" onclick="event.stopPropagation(); openReturnModal({{ $order->id }}, '{{ $order->order_number }}')" style="background-color: transparent; border: 1px solid #ffc107; color: #ffc107; border-radius: 4px; padding: 2px 8px; margin-left:10px; cursor: pointer;">Request Return</button>
                                    @endif

                                    @if(in_array(strtolower($order->status), ['delivered', 'completed']))
                                    <button type="button" class="btn-review" onclick="event.stopPropagation(); openReviewModal({{ $order->id }}, '{{ $order->order_number }}')" style="background-color: var(--primary-color); border: none; color: white; border-radius: 4px; padding: 2px 8px; margin-left: 10px; cursor: pointer;">Review & Rating</button>
                                    @endif
                            </div>
                            <div class="order-price">₹{{ number_format($order->total ?? 0, 2) }}</div>
                        </div>
                        @empty
                        <div style="padding: 20px; color: #666; font-style: italic;">
                            You have no orders yet.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Wishlist Section -->
                <div class="content-section" id="wishlist">
                    <h2 class="section-title">Wishlist</h2>
                    <div class="wishlist-grid">
                        @forelse($wishlist as $item)
                        @if($item->product)
                        <div class="product-card">
                            <div class="product-image" style="position: relative;">
                                @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                <img src="{{ asset('assets/images/product/product1.jpg') }}" alt="{{ $item->product->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                                @endif
                                <form action="{{ route('wishlist.remove', $item->product->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="wishlist-remove" title="Remove from wishlist" style="background: white; border: none; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: red; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="product-info" style="padding: 15px;">
                                <div class="product-name" style="font-weight: 600; margin-bottom: 5px;">
                                    <a href="{{ route('product.show', $item->product->slug) }}" style="text-decoration: none; color: inherit;">
                                        {{ $item->product->name }}
                                    </a>
                                </div>
                                @php
                                $dashPriceToUse = ($item->product->sale_price && $item->product->sale_price > 0 && $item->product->sale_price < $item->product->price)
                                    ? $item->product->sale_price
                                    : $item->product->price;
                                    @endphp
                                    <div class="product-price" style="font-weight: bold; color: var(--primary-color); margin-bottom: 10px;">₹{{ number_format($dashPriceToUse, 2) }}</div>

                                    <form action="{{ route('cart.add', $item->product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="add-to-cart-btn" style="width: 100%; padding: 8px; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer; transition: background 0.3s;">Add to Cart</button>
                                    </form>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div style="grid-column: 1 / -1; padding: 20px; color: #666; font-style: italic;">
                            Your wishlist is empty.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="content-section" id="settings">
                    <h2 class="section-title">Settings</h2>
                    <form>
                        <h3 style="margin: 2rem 0 1.5rem 0; color: var(--dark-color); font-weight: 600;">Security</h3>
                        <div class="form-group">
                            <label class="form-label">Change Password</label>
                            <button type="button" class="btn-primary">Update Password</button>
                        </div>

                        <h3 style="margin: 2rem 0 1.5rem 0; color: var(--dark-color); font-weight: 600;">Account</h3>
                        <div class="form-group">
                            <label class="form-label">Delete Account</label>
                            <p style="color: #888; margin-bottom: 1rem; font-size: 0.9rem;">Once deleted, this action cannot be undone.</p>
                            <button type="button" class="btn-secondary">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 30px; border-radius: 12px; width: 50%; max-width: 600px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--dark-color); font-weight: 700;">Rate & Review Order <span id="modalOrderNumber"></span></h3>
            <span class="close-modal" onclick="closeReviewModal()" style="color: #666; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>

        <form id="reviewForm" action="{{ route('user.review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" id="modalOrderId">

            <div id="orderItemsList" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto; padding-right: 10px;">
                <!-- Order items will be loaded here via JS -->
                <p style="text-align: center; color: #888;">Loading items...</p>
            </div>

            <div style="text-align: right;">
                <button type="button" class="btn-secondary" onclick="closeReviewModal()" style="margin-right: 10px; padding: 10px 20px; border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn-primary" style="padding: 10px 20px; border-radius: 8px;">Submit Review</button>
            </div>
        </form>
    </div>
</div>

<!-- Return Request Modal -->
<div id="returnModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 30px; border-radius: 12px; width: 50%; max-width: 600px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--dark-color); font-weight: 700;">Request Return for Order <span id="returnModalOrderNumber"></span></h3>
            <span class="close-modal" onclick="closeReturnModal()" style="color: #666; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>

        <form id="returnForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label">Reason for Return *</label>
                <textarea name="reason" class="form-control" placeholder="Please explain why you want to return the items..." rows="4" required></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Product Images (Optional)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <small class="text-muted">You can upload multiple images to help us understand the issue.</small>
            </div>

            <div style="text-align: right;">
                <button type="button" class="btn-secondary" onclick="closeReturnModal()" style="margin-right: 10px; padding: 10px 20px; border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn-primary" style="padding: 10px 20px; border-radius: 8px; background-color: #ffc107; border-color: #ffc107; color: #000;">Submit Return Request</button>
            </div>
        </form>
    </div>
</div>

<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
        margin-top: 5px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ddd;
        font-size: 24px;
        padding: 0;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-rating label:hover,
    .star-rating label:hover~label,
    .star-rating input:checked~label {
        color: #ffc107;
    }

    .star-rating.readonly label {
        cursor: default;
    }

    .star-rating.readonly label:hover,
    .star-rating.readonly label:hover~label {
        color: #ddd;
    }

    .star-rating.readonly input:checked~label {
        color: #ffc107;
    }

    .order-item-review {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #eee;
    }

    .order-item-review h5 {
        margin: 0 0 10px 0;
        font-size: 1rem;
        color: #333;
    }
</style>


<script>
    // Tab Navigation
    document.querySelectorAll('[data-tab]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();

            // Remove active class from all links and sections
            document.querySelectorAll('[data-tab]').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));

            // Add active class to clicked link and corresponding section
            link.classList.add('active');
            const tabId = link.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Toggle Address Form
    function toggleAddressForm() {
        const savedAddresses = document.getElementById('saved-addresses');
        const newAddressForm = document.getElementById('new-address-form');

        if (newAddressForm.style.display === 'none') {
            savedAddresses.style.display = 'none';
            newAddressForm.style.display = 'block';
            document.querySelectorAll('.tab-label').forEach((label, index) => {
                if (index === 1) label.classList.add('active');
                else label.classList.remove('active');
            });
        } else {
            savedAddresses.style.display = 'grid';
            newAddressForm.style.display = 'none';
            document.querySelectorAll('.tab-label').forEach((label, index) => {
                if (index === 0) label.classList.add('active');
                else label.classList.remove('active');
            });
        }
    }



    // Review Modal Functions
    function openReviewModal(orderId, orderNumber) {
        document.getElementById('modalOrderNumber').innerText = '#' + orderNumber;
        document.getElementById('modalOrderId').value = orderId;
        document.getElementById('reviewModal').style.display = 'block';

        // Fetch order items (this would ideally be an API call, but for simplicity we'll fetch from a route)
        const itemsList = document.getElementById('orderItemsList');
        itemsList.innerHTML = '<p style="text-align: center; color: #888;">Loading items...</p>';

        fetch(`/user/order/${orderId}/items`)
            .then(response => response.json())
            .then(data => {
                if (data.items && data.items.length > 0) {
                    let html = '';
                    data.items.forEach((item, index) => {
                        const uniqueId = `item_${item.id}`;
                        const isCombo = item.combo_pack_id ? true : false;
                        const itemId = isCombo ? item.combo_pack_id : item.product_id;
                        const itemType = isCombo ? 'combo' : 'product';

                        const existingRating = item.existing_rating;
                        const existingReview = item.existing_review || '';

                        const isEditable = item.is_editable;

                        html += `
                            <div class="order-item-review" style="${!isEditable ? 'opacity: 0.8;' : ''}">
                                <h5>${item.name}</h5>
                                <input type="hidden" name="items[${index}][id]" value="${itemId}">
                                <input type="hidden" name="items[${index}][type]" value="${itemType}">
                                
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 0.85rem;">Rating * ${!isEditable ? '<span class="text-danger small">(Editing limit expired)</span>' : ''}</label>
                                    <div class="star-rating ${!isEditable ? 'readonly' : ''}">
                                        <input type="radio" id="star5_${index}" name="items[${index}][rating]" value="5" required ${existingRating == 5 ? 'checked' : ''} ${!isEditable ? 'disabled' : ''} />
                                        <label for="star5_${index}" title="5 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star4_${index}" name="items[${index}][rating]" value="4" ${existingRating == 4 ? 'checked' : ''} ${!isEditable ? 'disabled' : ''} />
                                        <label for="star4_${index}" title="4 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star3_${index}" name="items[${index}][rating]" value="3" ${existingRating == 3 ? 'checked' : ''} ${!isEditable ? 'disabled' : ''} />
                                        <label for="star3_${index}" title="3 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star2_${index}" name="items[${index}][rating]" value="2" ${existingRating == 2 ? 'checked' : ''} ${!isEditable ? 'disabled' : ''} />
                                        <label for="star2_${index}" title="2 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star1_${index}" name="items[${index}][rating]" value="1" ${existingRating == 1 ? 'checked' : ''} ${!isEditable ? 'disabled' : ''} />
                                        <label for="star1_${index}" title="1 star"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 10px;">
                                    <label class="form-label" style="font-size: 0.85rem;">Review (Optional)</label>
                                    <textarea name="items[${index}][review]" class="form-control" placeholder="Write your experience..." rows="2" ${!isEditable ? 'disabled' : ''}>${existingReview}</textarea>
                                </div>
                            </div>
                        `;
                    });
                    itemsList.innerHTML = html;
                } else {
                    itemsList.innerHTML = '<p style="text-align: center; color: #888;">No items found for this order.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching items:', error);
                itemsList.innerHTML = '<p style="text-align: center; color: #dc3545;">Error loading items. Please try again.</p>';
            });
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
        document.getElementById('reviewForm').reset();
    }

    // Return Modal Functions
    function openReturnModal(orderId, orderNumber) {
        const modal = document.getElementById('returnModal');
        const form = document.getElementById('returnForm');
        const orderNumberSpan = document.getElementById('returnModalOrderNumber');

        orderNumberSpan.innerText = '#' + orderNumber;
        form.action = `/user/order/${orderId}/return`;
        modal.style.display = 'block';
    }

    function closeReturnModal() {
        document.getElementById('returnModal').style.display = 'none';
        document.getElementById('returnForm').reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const reviewModal = document.getElementById('reviewModal');
        const returnModal = document.getElementById('returnModal');

        if (event.target == reviewModal) {
            closeReviewModal();
        }
        if (event.target == returnModal) {
            closeReturnModal();
        }
    }

    // Add to Cart
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Product added to cart!');
        });
    });
</script>

@include('view.layout.footer')




{{-- @extends('layout.app')

@section('content')

    <!--<h2>Welcome {{ auth()->user()->name }}</h2>-->
<!--<p>Email: {{ auth()->user()->email }}</p>-->

<!--<form method="POST" action="{{ route('logout') }}">-->
<!--    @csrf-->
<!--    <button type="submit">Logout</button>-->
<!--</form>-->

@endsection --}}