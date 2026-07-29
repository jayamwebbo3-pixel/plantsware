@include('view.layout.header')

<style>
/* Dashboard Base Layout */
.profile-container {
    padding: 60px 0;
    background-color: #f8fafc;
    min-height: calc(100vh - 200px);
}
.profile-content {
    display: flex;
    flex-wrap: nowrap;
    gap: 30px;
    align-items: flex-start;
}

@media (max-width: 991.98px) {
    .profile-content {
        flex-wrap: wrap;
    }
}

/* Sidebar Styles */
.profile-sidebar {
    width: 280px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 30px 20px;
    flex: 0 0 280px;
}
@media (max-width: 991.98px) {
    .profile-sidebar {
        width: 100%;
        margin-bottom: 20px;
    }
}

/* User Info Styling */
.user-info {
    text-align: center;
    margin-bottom: 30px;
}
.user-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto 15px auto;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--primary-color, #1b8744);
    box-shadow: 0 4px 10px rgba(27, 135, 68, 0.2);
}
.user-name {
    font-weight: 700;
    font-size: 1.2rem;
    color: #1e293b;
}

/* Navigation Menu Styling */
.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}
.nav-item {
    margin-bottom: 5px;
}
.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #475569;
    font-weight: 600;
    font-size: 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    width: 100%;
    text-align: left;
    background: transparent;
    cursor: pointer;
}
.nav-link:hover {
    background-color: #f1f5f9;
    color: var(--primary-color, #1b8744);
    text-decoration: none;
}
.nav-link.active {
    background-color: #eaf5ec; /* Light green tint */
    color: var(--primary-color, #1b8744);
    border-left: 4px solid var(--primary-color, #1b8744);
    border-radius: 0 8px 8px 0;
}
.nav-link i.nav-icon, .nav-link i.fa-sign-out-alt {
    width: 24px;
    margin-right: 12px;
    font-size: 1.1rem;
    text-align: center;
}

/* Main Content Area */
.main-content {
    flex: 1 1 0%;
    min-width: 0; /* Prevents overflow in flexbox */
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 30px;
}

/* Hide content sections by default */
.content-section {
    display: none;
    animation: fadeIn 0.4s ease;
}
.content-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-title {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 25px;
    font-size: 1.5rem;
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 15px;
}

/* Tab Container (Address Book) */
.tab-container {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    border-bottom: 1px solid #e2e8f0;
}
.tab-label {
    padding: 10px 20px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
}
.tab-label:hover {
    color: var(--primary-color, #1b8744);
}
.tab-label.active {
    color: var(--primary-color, #1b8744);
    border-bottom-color: var(--primary-color, #1b8744);
}

/* Address Cards */
.address-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}
.address-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    position: relative;
    transition: all 0.3s ease;
    background: #fff;
    cursor: pointer;
}
.address-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}
.address-card.selected.default {
    border: 2px solid var(--primary-color, #1b8744);
    background-color: #fafffa;
}
.address-badge {
    position: absolute;
    top: 15px;
    right: 45px;
    background: var(--primary-color, #1b8744);
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 4px;
    letter-spacing: 0.5px;
}
.address-name {
    font-weight: 700;
    font-size: 1.1rem;
    color: #1e293b;
    margin-bottom: 10px;
    padding-right: 60px; /* space for badge */
}
.address-detail {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 4px;
    line-height: 1.4;
}
.address-phone {
    margin-top: 10px;
    font-weight: 600;
    color: #475569;
    font-size: 0.9rem;
}
.address-actions {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px dashed #e2e8f0;
    display: flex;
    gap: 10px;
}
.address-btn {
    padding: 6px 12px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}
.address-btn-edit {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.address-btn-edit:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.address-btn-delete {
    background: #fff;
    color: #ef4444;
    border: 1px solid #fecaca;
}
.address-btn-delete:hover {
    background: #fef2f2;
}

/* Order History */
.order-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: center;
    transition: all 0.3s ease;
}
.order-item:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    border-color: #cbd5e1;
}
.order-info {
    flex-grow: 1;
}
.order-info h4 {
    font-weight: 700;
    color: #1e293b;
    font-size: 1.1rem;
    margin-bottom: 5px;
}
.order-info p {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 10px;
}
.order-price {
    font-weight: 700;
    font-size: 1.2rem;
    color: #1e293b;
}

/* Wishlist Grid */
.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
}
.product-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    background: #fff;
}
.product-card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    transform: translateY(-3px);
}
.product-info {
    padding: 15px;
}
.product-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* General Form Styles */
.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}
.form-group {
    flex: 1;
    min-width: 250px;
}
.form-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    display: block;
}
.form-control, .form-select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}
.form-control:focus, .form-select:focus {
    border-color: var(--primary-color, #1b8744);
    outline: none;
    box-shadow: 0 0 0 3px rgba(27, 135, 68, 0.1);
}
.btn-primary {
    background: var(--primary-color, #1b8744);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-primary:hover {
    background: #146833;
    transform: translateY(-2px);
}
.btn-secondary {
    background: #f1f5f9;
    color: #475569;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-secondary:hover {
    background: #e2e8f0;
    color: #1e293b;
}

/* Logout button fix */
.nav-item form {
    margin: 0;
}
.nav-item form button {
    outline: none;
}
</style>

<div class="profile-container">
    <div class="container">
        <div class="profile-content">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="user-info">
                    <div class="user-avatar" style="overflow: hidden; border: 4px solid var(--primary-color);">
                        @php
                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=76a713&color=fff&size=150';
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="flex-grow: 1; min-width: 0; overflow: hidden; position: relative;">
                        <div id="userNameDisplayContainer" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <div class="user-name" id="userNameLabel">{{ auth()->user()->name }}</div>
                            <i class="fas fa-pen" id="editNameIcon" style="cursor: pointer; font-size: 0.8rem; color: var(--primary-color);" onclick="showEditNameForm()"></i>
                        </div>
                        <div id="userNameEditContainer" style="display: none; align-items: center; justify-content: center; gap: 5px;">
                            <input type="text" id="userNameInput" class="form-control form-control-sm" value="{{ auth()->user()->name }}" style="max-width: 150px; font-size: 0.9rem;">
                            <button class="btn btn-sm btn-success" onclick="saveName()">✓</button>
                            <button class="btn btn-sm btn-danger" onclick="hideEditNameForm()">✗</button>
                        </div>
                        <div class="user-email" id="userEmailDisplay" style="word-break: break-all; overflow-wrap: break-word; font-size: 0.78rem; color: #444; line-height: 1.3; display: block;" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <ul class="nav-menu">

                    <li class="nav-item">
                        <a href="#address-book" class="nav-link active" data-tab="address-book">
                            <i class="fas fa-map-marker-alt nav-icon"></i>
                            <span>Address Book</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#order-history" class="nav-link" data-tab="order-history">
                            <i class="fas fa-box nav-icon"></i>
                            <span>Order History</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#wishlist" class="nav-link" data-tab="wishlist">
                            <i class="fas fa-heart nav-icon"></i>
                            <span>Wishlist</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#my-coupons" class="nav-link" data-tab="my-coupons">
                            <i class="fas fa-ticket-alt nav-icon"></i>
                            <span>My Coupons</span>
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
                        @forelse($addresses as $address)
                        <div class="address-card {{ $address->is_default ? 'selected' : '' }}" id="address-card-{{ $address->id }}">
                            @if($address->is_default)
                            <span class="address-badge">DEFAULT</span>
                            @endif
                            <div class="address-name">{{ $address->first_name }} {{ $address->last_name }}</div>
                            @if($address->door_number)
                            <div class="address-detail">{{ $address->door_number }}</div>
                            @endif
                            <div class="address-detail">{{ $address->street }}, {{ $address->city }} {{ $address->post_code }}</div>
                            <div class="address-detail">{{ $address->district }} {{ $address->district ? ',' : '' }} {{ $address->state }}</div>
                            <div class="address-phone">📱 {{ $address->phone_number }}</div>
                            <div class="address-actions">
                                @if(!$address->is_default)
                                <button type="button" class="address-btn address-btn-edit btn-set-default" onclick="setAddressDefault(this, {{ $address->id }})" style="background-color: var(--primary-color); color: white; border: none;">Set Default</button>
                                @endif
                                <button type="button" class="address-btn address-btn-edit" onclick="editAddress({{ json_encode($address) }})">Edit</button>
                                <form action="{{ route('user.address.delete', $address->id) }}" method="POST" style="display: inline;" class="delete-address-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="address-btn address-btn-delete" onclick="confirmDeleteAddress(this.form)">Delete</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div style="padding: 20px; color: #666; font-style: italic;">
                            No addresses saved yet.
                        </div>
                        @endforelse
                    </div>

                    <!-- Add/Edit Address Form -->
                    <div id="new-address-form" style="display: none;">
                        <h3 id="address-form-title" style="margin-bottom: 1.5rem; color: var(--dark-color); font-weight: 600;">Add New Address</h3>
                        <form id="address-form" action="{{ route('user.address.store') }}" method="POST">
                            @csrf
                            <div id="method-field"></div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Door Number</label>
                                    <input type="text" name="door_number" id="door_number" class="form-control" placeholder="Door/Block No.">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Street</label>
                                    <input type="text" name="street" id="street" class="form-control" placeholder="Street Name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">District</label>
                                    <input type="text" name="district" id="district" class="form-control" placeholder="District">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">State *</label>
                                    <select name="state" id="state" class="form-select" required>
                                        <option value="">Select a State</option>
                                        @php
                                            $shippingStates = \App\Models\ShippingRate::orderBy('state_name')->pluck('state_name');
                                        @endphp
                                        @foreach($shippingStates as $sState)
                                            <option value="{{ $sState }}">{{ $sState }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">City *</label>
                                    <input type="text" name="city" id="city" class="form-control" placeholder="City" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Post Code *</label>
                                    <input type="text" name="post_code" id="post_code" class="form-control" placeholder="Post Code" maxlength="6" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Phone Number" maxlength="10" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alternative Number</label>
                                    <input type="tel" name="alternative_number" id="alternative_number" class="form-control" placeholder="Alternative Number" maxlength="10">
                                </div>
                            </div>
                            <div class="form-row">
                                <button type="submit" id="submit-btn" class="btn-primary">Add Address</button>
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
                        <div class="order-item">
                            <div class="order-image">
                                @php
                                $hasImage = false;
                                $imgSrc = '';
                                if ($order->items && $order->items->count() > 0) {
                                $pImg = $order->items->first()->product_image;
                                if (!$pImg && $order->items->first()->product) {
                                $pImg = $order->items->first()->product->image;
                                }
                                if ($pImg) {
                                $hasImage = true;
                                if (is_string($pImg) && str_starts_with($pImg, '[')) {
                                $decoded = json_decode($pImg, true);
                                $pImg = is_array($decoded) && count($decoded) > 0 ? $decoded[0] : $pImg;
                                }
                                if (is_string($pImg) && (str_starts_with($pImg, 'http') || str_starts_with($pImg, 'assets/'))) {
                                $imgSrc = asset($pImg);
                                } else {
                                $imgSrc = asset('storage/' . $pImg);
                                }
                                }
                                }
                                @endphp
                                @if($hasImage)
                                <img src="{{ $imgSrc }}" alt="{{ $order->items->first()->product_name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;" onerror="this.onerror=null; this.src='{{ asset('assets/images/product/product1.jpg') }}';">
                                @else
                                <i class="fas fa-box" style="font-size: 2rem; color: var(--primary-color);"></i>
                                @endif
                            </div>
                            <div class="order-info">
                                <h4>
                                    @if($order->items && $order->items->count() > 0)
                                    @php
                                        $firstItem = $order->items->first();
                                        $optionsStr = '';
                                        if (!empty($firstItem->options)) {
                                            $options = is_string($firstItem->options) ? json_decode($firstItem->options, true) : $firstItem->options;
                                            if (is_array($options) && isset($options['size'])) {
                                                $optionsStr = ' (Size: ' . $options['size'] . ')';
                                            } elseif (is_string($options)) {
                                                $optionsStr = ' (Size: ' . $options . ')';
                                            }
                                        }
                                    @endphp
                                    {{ $firstItem->product_name }}{{ $optionsStr }}
                                    @if($order->items->count() > 1)
                                    <span style="font-size: 0.8em; color: gray; display: block; margin-top: 4px; font-weight: normal;">+ {{ $order->items->count() - 1 }} more item(s)</span>
                                    @endif
                                    @else
                                    Order {{ $order->order_number }}
                                    @endif
                                </h4>
                                <p>Date: {{ $order->created_at->format('d M Y') }}</p>

                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 8px;">
                                    <span class="order-status {{ strtolower($order->status) }}" style="padding: 3px 8px; border-radius: 4px; color: white; background-color: {{ in_array(strtolower($order->status), ['cancelled', 'return_rejected']) ? '#dc3545' : (strtolower($order->status) === 'shipped' ? '#17a2b8' : (in_array(strtolower($order->status), ['delivered', 'completed']) ? '#28a745' : (strtolower($order->status) === 'returned' ? '#343a40' : '#ffc107'))) }};">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>

                                    @if(!in_array(strtolower($order->status), ['shipped', 'delivered', 'cancelled', 'returned', 'return_requested', 'return_rejected', 'completed']))
                                    <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" style="margin: 0;" class="cancel-order-form">
                                        @csrf
                                        <button type="button" onclick="event.stopPropagation(); confirmCancelOrder(this.form);" style="background-color: transparent; border: 1px solid #dc3545; color: #dc3545; border-radius: 4px; padding: 2px 8px; cursor: pointer;">Cancel</button>
                                    </form>
                                    @endif

                                    @if(strtolower($order->status) === 'shipped')
                                        <button type="button" 
                                            data-tracking-id="{{ $order->tracking_number }}" 
                                            data-tracking-link="{{ $order->tracking_link }}"
                                            onclick="event.stopPropagation(); openTrackOrderModal(this);" 
                                            style="background-color: transparent; border: 1px solid #17a2b8; color: #17a2b8; border-radius: 4px; padding: 2px 8px; cursor: pointer; text-decoration: none; font-size: 0.9em;">
                                            Track Order
                                        </button>
                                    @endif

                                    @if(strtolower($order->status) === 'delivered' && $order->delivered_at && $order->delivered_at->diffInDays(now()) <= 3)
                                        <button type="button" class="btn-return" onclick="event.stopPropagation(); openReturnModal({{ $order->id }}, '{{ $order->order_number }}')" style="background-color: transparent; border: 1px solid #ffc107; color: #ffc107; border-radius: 4px; padding: 2px 8px; cursor: pointer;">Request Return</button>
                                    @endif

                                    @if(in_array(strtolower($order->status), ['delivered', 'completed']))
                                        <button type="button" class="btn-review" onclick="event.stopPropagation(); openReviewModal({{ $order->id }}, '{{ $order->order_number }}')" style="background-color: var(--primary-color); border: none; color: white; border-radius: 4px; padding: 2px 8px; cursor: pointer;">Review & Rating</button>
                                    @endif
                                    
                                    <a href="{{ route('user.order.invoice', $order->id) }}" class="btn-invoice" onclick="event.stopPropagation();" style="background-color: transparent; border: 1px solid var(--primary-color); color: var(--primary-color); border-radius: 4px; padding: 2px 8px; cursor: pointer; text-decoration: none; font-size: 0.85rem;">Invoice</a>
                                </div>
                            </div>
                            <div class="order-price" style="display: flex; flex-direction: column; align-items: flex-end; justify-content: flex-start; gap: 10px;">
                                <div>₹{{ number_format($order->total ?? 0, 2) }}</div>
                                <a href="{{ route('user.order.show', $order->id) }}" class="btn-view" style="background-color: var(--primary-color); color: white; padding: 4px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; white-space: nowrap;">
                                    <span class="d-none d-lg-inline">View Details</span>
                                    <span class="d-inline d-lg-none">Details</span>
                                </a>
                            </div>
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
                            <div class="product-image-container" style="background: #ffffff; position: relative;">
                                <a href="{{ route('product.show', $item->product->slug) }}">
                                    @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="product-image main-image w-100 h-100" style="object-fit: contain; background: #ffffff;">
                                    @else
                                    <img src="{{ asset('assets/images/product/product1.jpg') }}" alt="{{ $item->product->name }}" class="product-image main-image w-100 h-100" style="object-fit: contain; background: #ffffff;">
                                    @endif
                                </a>
                                <form action="{{ route('wishlist.remove', $item->product->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="wishlist-remove" title="Remove from wishlist" style="background: white; border: none; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: red; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                        {{ $item->product->name }}
                                    </a>
                                </h3>
                                @php
                                $dashPriceToUse = ($item->product->sale_price > 0 && $item->product->sale_price < $item->product->price)
                                    ? $item->product->sale_price
                                    : $item->product->price;
                                @endphp
                                <div class="product-price">
                                    @if($item->product->sale_price > 0 && $item->product->sale_price < $item->product->price)
                                        <span class="original-price text-muted text-decoration-line-through">₹{{ number_format($item->product->price, 2) }}</span>
                                        <span class="current-price ms-2 fw-bold">₹{{ number_format($item->product->sale_price, 2) }}</span>
                                    @else
                                        <span class="current-price fw-bold">₹{{ number_format($item->product->price, 2) }}</span>
                                    @endif
                                </div>

                                @php
                                $hasAttributes = false;
                                if (!empty($item->product->size)) {
                                    if (is_array($item->product->size)) {
                                        $hasAttributes = count($item->product->size) > 0;
                                    } else {
                                        $decoded = json_decode($item->product->size, true);
                                        if (is_array($decoded)) {
                                            $hasAttributes = count($decoded) > 0;
                                        } else {
                                            $parts = array_filter(array_map('trim', explode(',', $item->product->size)));
                                            $hasAttributes = count($parts) > 0;
                                        }
                                    }
                                }
                                @endphp

                                <div class="product-actions mt-3 d-flex align-items-stretch">
                                    @if($item->product->stock_quantity > 0)
                                        @if($hasAttributes)
                                            <a href="{{ route('product.show', $item->product->slug) }}" class="btn btn-primary w-100 text-nowrap d-flex align-items-center justify-content-center" style="font-weight: 700;">
                                                Add To Cart
                                            </a>
                                        @else
                                            <form action="{{ route('cart.add', $item->product->id) }}" method="POST" class="w-100">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100" style="font-weight: 700;">
                                                    Add To Cart
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>Out of Stock</button>
                                    @endif
                                </div>
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

                <!-- My Coupons Section -->
                <div class="content-section" id="my-coupons">
                    <h2 class="section-title">My Coupons</h2>
                    <div class="row g-3">
                        @forelse($coupons as $coupon)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-2 {{ $coupon->is_used ? 'border-light bg-light' : 'border-success' }} shadow-sm" style="border-style: dashed; border-radius: 12px; overflow: hidden; position: relative;">
                                @if($coupon->is_used)
                                    <div style="position: absolute; top: 10px; right: 10px; background-color: #6c757d; color: white; padding: 2px 8px; font-size: 0.75rem; border-radius: 4px; font-weight: bold; z-index: 2;">USED</div>
                                @else
                                    <div style="position: absolute; top: 10px; right: 10px; background-color: var(--primary-color); color: white; padding: 2px 8px; font-size: 0.75rem; border-radius: 4px; font-weight: bold; z-index: 2;">ACTIVE</div>
                                @endif
                                
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-ticket-alt {{ $coupon->is_used ? 'text-secondary' : 'text-success' }} fs-4 me-2"></i>
                                        <h4 class="mb-0 mx-1 fw-bold text-uppercase {{ $coupon->is_used ? 'text-secondary' : 'text-dark' }}" style="font-family: monospace; letter-spacing: 1px;">{{ $coupon->coupon_code }}</h4>
                                    </div>
                                    
                                    <p class="mb-2 text-muted small">
                                        @if($coupon->discount_type === 'percentage')
                                            <strong>{{ number_format($coupon->discount_value, 0) }}% Off</strong> your order.
                                            @if($coupon->max_discount)
                                                <span class="d-block">(Max discount up to ₹{{ number_format($coupon->max_discount, 2) }})</span>
                                            @endif
                                        @else
                                            <strong>Flat ₹{{ number_format($coupon->discount_value, 2) }} Off</strong> your order.
                                        @endif
                                    </p>
                                    
                                    <div class="border-top pt-2 mt-2">
                                        <div class="d-flex justify-content-between extra-small text-muted mb-1">
                                            <span>Min. Order Value:</span>
                                            <span class="fw-bold text-dark">₹{{ number_format($coupon->minimum_order_amount, 2) }}</span>
                                        </div>
                                        @if($coupon->valid_to)
                                        <div class="d-flex justify-content-between extra-small text-muted">
                                            <span>Expires On:</span>
                                            <span class="fw-bold text-danger">{{ $coupon->valid_to->format('d M Y') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 p-3 pt-0">
                                    @if(!$coupon->is_used)
                                        <button type="button" class="btn btn-sm btn-success w-100 copy-coupon-btn" data-code="{{ $coupon->coupon_code }}" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                                            <i class="fas fa-copy me-1"></i> Copy Code
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-secondary w-100" disabled>
                                            Already Used
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-4 text-muted bg-white border rounded-3">
                                <i class="fas fa-ticket-alt fs-1 text-muted mb-3 opacity-50"></i>
                                <p class="mb-0">No active coupons currently available for you.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border-radius: 12px; width: 95%; max-width: 600px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
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
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border-radius: 12px; width: 95%; max-width: 600px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--dark-color); font-weight: 700;">Request Return for Order <span id="returnModalOrderNumber"></span></h3>
            <span class="close-modal" onclick="closeReturnModal()" style="color: #666; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>

        <form id="returnForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label">Select Items to Return *</label>
                <div id="returnOrderItemsList" style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px;">
                    <!-- Order items will be loaded here via JS -->
                    <p style="text-align: center; color: #888; margin: 0;">Loading items...</p>
                </div>
                <small class="text-danger" id="returnItemsError" style="display: none;">Please select at least one item to return.</small>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label">Reason for Return *</label>
                <textarea name="reason" class="form-control" placeholder="Please explain why you want to return the items..." rows="4" required></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;" id="dynamicImageContainer">
                <label class="form-label">Product Images *</label>
                <div id="dynamicImageFields">
                    <input type="file" name="images[]" class="form-control mb-2" accept="image/*" required>
                </div>
                <small class="text-muted">You must upload at least 1 image (Max: 1MB per image). Others are optional. Images will be automatically converted to WebP.</small>
            </div>

            <div style="text-align: right;">
                <button type="button" class="btn-secondary" onclick="closeReturnModal()" style="margin-right: 10px; padding: 10px 20px; border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn-primary" style="padding: 10px 20px; border-radius: 8px; background-color: #ffc107; border-color: #ffc107; color: #000;">Submit Return Request</button>
            </div>
        </form>
    </div>
</div>




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

            // Update URL hash
            window.history.pushState(null, null, '#' + tabId);
        });
    });

    // Check hash on load to open correct tab
    document.addEventListener('DOMContentLoaded', () => {
        if (window.location.hash) {
            const tabId = window.location.hash.substring(1);
            const link = document.querySelector(`[data-tab="${tabId}"]`);
            if (link) {
                document.querySelectorAll('[data-tab]').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
                link.classList.add('active');
                const targetSection = document.getElementById(tabId);
                if (targetSection) targetSection.classList.add('active');
            }
        }
    });

    // Toggle Address Form
    function toggleAddressForm() {
        const savedAddresses = document.getElementById('saved-addresses');
        const newAddressForm = document.getElementById('new-address-form');
        const addressForm = document.getElementById('address-form');
        const formTitle = document.getElementById('address-form-title');
        const submitBtn = document.getElementById('submit-btn');
        const methodField = document.getElementById('method-field');

        if (newAddressForm.style.display === 'none') {
            // Switch to Add Address mode
            addressForm.reset();
            addressForm.action = "{{ route('user.address.store') }}";
            formTitle.innerText = "Add New Address";
            submitBtn.innerText = "Add Address";
            methodField.innerHTML = "";

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

    function setAddressDefault(btn, id) {
        const originalText = btn.innerText;
        btn.innerText = "Setting...";
        btn.disabled = true;

        fetch("{{ url('user/address') }}/" + id + "/default", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        }).then(response => {
            if(response.ok) {
                // Remove 'selected' class and 'DEFAULT' badge from all cards
                document.querySelectorAll('.address-card').forEach(card => {
                    card.classList.remove('selected');
                    const badge = card.querySelector('.address-badge');
                    if(badge) badge.remove();
                    
                    // If it doesn't have a Set Default button, it was the old default, so add one
                    const actions = card.querySelector('.address-actions');
                    if(!actions.querySelector('.btn-set-default')) {
                        const cardId = card.id.replace('address-card-', '');
                        const newBtn = document.createElement('button');
                        newBtn.type = 'button';
                        newBtn.className = 'address-btn address-btn-edit btn-set-default';
                        newBtn.style = 'background-color: var(--primary-color); color: white; border: none;';
                        newBtn.innerText = 'Set Default';
                        newBtn.onclick = function() { setAddressDefault(this, cardId) };
                        actions.insertBefore(newBtn, actions.firstChild);
                    }
                });

                // Set current card as default
                const currentCard = document.getElementById('address-card-' + id);
                currentCard.classList.add('selected');
                const badge = document.createElement('span');
                badge.className = 'address-badge';
                badge.innerText = 'DEFAULT';
                currentCard.insertBefore(badge, currentCard.firstChild);
                
                // Remove the Set Default button from current card
                btn.remove();
            } else {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }).catch(error => {
            btn.innerText = originalText;
            btn.disabled = false;
            console.error('Error:', error);
        });
    }

    function editAddress(address) {
        const savedAddresses = document.getElementById('saved-addresses');
        const newAddressForm = document.getElementById('new-address-form');
        const addressForm = document.getElementById('address-form');
        const formTitle = document.getElementById('address-form-title');
        const submitBtn = document.getElementById('submit-btn');
        const methodField = document.getElementById('method-field');

        // Populate form
        document.getElementById('first_name').value = address.first_name;
        document.getElementById('last_name').value = address.last_name;
        document.getElementById('door_number').value = address.door_number || '';
        document.getElementById('street').value = address.street || '';
        document.getElementById('district').value = address.district || '';
        document.getElementById('state').value = address.state;
        document.getElementById('city').value = address.city;
        document.getElementById('post_code').value = address.post_code;
        document.getElementById('phone_number').value = address.phone_number;
        document.getElementById('alternative_number').value = address.alternative_number || '';

        // Switch to Edit Address mode
        addressForm.action = "{{ url('user/address') }}/" + address.id;
        formTitle.innerText = "Edit Address";
        submitBtn.innerText = "Update Address";
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        savedAddresses.style.display = 'none';
        newAddressForm.style.display = 'block';
        document.querySelectorAll('.tab-label').forEach((label, index) => {
            if (index === 1) label.classList.add('active');
            else label.classList.remove('active');
        });
    }

    // Track Order Modal
    function openTrackOrderModal(btn) {
        const trackingId = btn.getAttribute('data-tracking-id');
        const trackingLink = btn.getAttribute('data-tracking-link');

        let htmlContent = `
            <div style="text-align: left; margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid var(--primary-color, #1b8744);">
                <p style="margin-bottom: 10px; font-size: 1.1em;"><strong>Tracking ID:</strong> <span style="font-family: monospace; font-size: 1.1em; color: #333;">${trackingId || 'Not provided'}</span></p>
        `;
        
        if (trackingLink) {
            htmlContent += `<p style="margin-bottom: 0;"><strong>Tracking Link:</strong> <br> <a href="${trackingLink}" target="_blank" style="color: var(--primary-color, #1b8744); word-break: break-all; display: inline-block; margin-top: 5px; text-decoration: underline;">${trackingLink}</a></p>`;
        } else {
            htmlContent += `<p style="margin-bottom: 0; color: #6c757d; font-size: 0.9em;">No tracking link available.</p>`;
        }
        htmlContent += `</div>`;

        Swal.fire({
            title: '<i class="fas fa-shipping-fast" style="color: var(--primary-color, #1b8744); margin-right: 10px;"></i> Order Tracking',
            html: htmlContent,
            showCloseButton: true,
            showConfirmButton: trackingLink ? true : true,
            confirmButtonText: trackingLink ? 'Track Now <i class="fas fa-external-link-alt ms-1"></i>' : 'Close',
            confirmButtonColor: trackingLink ? 'var(--primary-color, #1b8744)' : '#6c757d',
            customClass: {
                title: 'text-start'
            }
        }).then((result) => {
            if (result.isConfirmed && trackingLink) {
                window.open(trackingLink, '_blank');
            }
        });
    }


    // Profile Name Editing
    function showEditNameForm() {
        document.getElementById('userNameDisplayContainer').style.display = 'none';
        document.getElementById('userNameEditContainer').style.display = 'flex';
        document.getElementById('userNameInput').focus();
    }

    function hideEditNameForm() {
        document.getElementById('userNameDisplayContainer').style.display = 'flex';
        document.getElementById('userNameEditContainer').style.display = 'none';
    }

    function saveName() {
        const newName = document.getElementById('userNameInput').value;
        if (!newName.trim()) {
            alert('Name cannot be empty');
            return;
        }

        const submitBtn = event.target;
        const originalText = submitBtn.innerText;
        submitBtn.innerText = '...';
        submitBtn.disabled = true;

        fetch("{{ route('user.profile.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: newName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('userNameLabel').innerText = data.name;
                    hideEditNameForm();
                } else {
                    alert(data.message || 'Error updating name');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update name. Please try again.');
            })
            .finally(() => {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
    }

    // Review Modal Functions
    function openReviewModal(orderId, orderNumber) {
        document.getElementById('modalOrderNumber').innerText = '#' + orderNumber;
        document.getElementById('modalOrderId').value = orderId;
        document.getElementById('reviewModal').style.display = 'block';

        // Fetch order items using Laravel route helper to ensure compatibility with all hosting environments (like cPanel)
        const itemsList = document.getElementById('orderItemsList');
        itemsList.innerHTML = '<p style="text-align: center; color: #888;">Loading items...</p>';

        const fetchUrl = "{{ route('user.order.items', ':id') }}".replace(':id', orderId);

        fetch(fetchUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
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
        form.action = "{{ route('user.order.return', ':id') }}".replace(':id', orderId);
        
        // Fetch order items for return selection
        const itemsList = document.getElementById('returnOrderItemsList');
        itemsList.innerHTML = '<p style="text-align: center; color: #888; margin: 0;">Loading items...</p>';

        const fetchUrl = "{{ route('user.order.items', ':id') }}".replace(':id', orderId);

        fetch(fetchUrl)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.items && data.items.length > 0) {
                    let html = '';
                    data.items.forEach((item, index) => {
                        const itemId = item.id; // Using order_item id
                        // Using a compound value or just name for simplicity
                        html += `
                            <div style="display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                                <input type="checkbox" name="returned_items[]" value="${item.id}" id="return_item_${index}" style="margin-right: 10px;" class="return-item-checkbox" onchange="updateImageFields()">
                                <label for="return_item_${index}" style="margin: 0; cursor: pointer; font-size: 0.95rem; flex: 1;">${item.name}</label>
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <label style="font-size: 0.85rem; color: #666; margin: 0;">Qty:</label>
                                    <input type="number" name="return_quantities[${item.id}]" value="${item.quantity}" min="1" max="${item.quantity}" class="form-control" style="width: 70px; padding: 2px 5px; height: auto;" onchange="updateImageFields()">
                                </div>
                            </div>
                        `;
                    });
                    itemsList.innerHTML = html;
                    updateImageFields();
                } else {
                    itemsList.innerHTML = '<p style="text-align: center; color: #888; margin: 0;">No items found for this order.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching items:', error);
                itemsList.innerHTML = '<p style="text-align: center; color: #dc3545; margin: 0;">Error loading items.</p>';
            });

        // Form validation to ensure at least one item is checked
        form.onsubmit = function(e) {
            const checkboxes = document.querySelectorAll('.return-item-checkbox');
            let isChecked = false;
            checkboxes.forEach(cb => {
                if(cb.checked) isChecked = true;
            });
            if(!isChecked && checkboxes.length > 0) {
                e.preventDefault();
                document.getElementById('returnItemsError').style.display = 'block';
            } else {
                document.getElementById('returnItemsError').style.display = 'none';
            }
        };

        modal.style.display = 'block';
    }

    function closeReturnModal() {
        document.getElementById('returnModal').style.display = 'none';
        document.getElementById('returnForm').reset();
        const container = document.getElementById('dynamicImageFields');
        if(container) {
            container.innerHTML = '<input type="file" name="images[]" class="form-control mb-2" accept="image/*" required>';
        }
    }

    function updateImageFields() {
        const checkboxes = document.querySelectorAll('.return-item-checkbox');
        let totalQty = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const qtyInput = document.querySelector(`input[name="return_quantities[${cb.value}]"]`);
                if (qtyInput) {
                    totalQty += parseInt(qtyInput.value) || 0;
                }
            }
        });

        if (totalQty < 1) totalQty = 1;

        const container = document.getElementById('dynamicImageFields');
        const currentFields = container.querySelectorAll('input[type="file"]').length;

        if (totalQty > currentFields) {
            for (let i = currentFields; i < totalQty; i++) {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'images[]';
                input.className = 'form-control mb-2';
                input.accept = 'image/*';
                container.appendChild(input);
            }
        } else if (totalQty < currentFields) {
            const inputs = container.querySelectorAll('input[type="file"]');
            for (let i = currentFields - 1; i >= totalQty; i--) {
                if (i > 0) {
                    inputs[i].remove();
                }
            }
        }
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
        btn.addEventListener('click', (e) => {
            // Let the form submit naturally if it's in a form, otherwise show toast
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            toast.fire({
                icon: 'success',
                title: 'Product added to cart!'
            });
        });
    });

    // Confirmation functions
    function confirmDeleteAddress(form) {
        Swal.fire({
            title: 'Delete Address?',
            text: "Are you sure you want to delete this address? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function confirmCancelOrder(form) {
        Swal.fire({
            title: 'Cancel Order?',
            text: "Are you sure you want to cancel this order?",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Reason for cancellation...',
            inputAttributes: {
                'aria-label': 'Reason for cancellation',
                'required': 'true'
            },
            inputValidator: (value) => {
                if (!value || value.trim() === '') {
                    return 'You need to write a reason!'
                }
            },
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cancel_reason';
                input.value = result.value;
                form.appendChild(input);
                form.submit();
            }
        });
    }

    // Address Selection UI implementation
    document.addEventListener('DOMContentLoaded', function() {
        const addressCards = document.querySelectorAll('.address-cards .address-card');
        addressCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Ignore clicks on action buttons
                if (e.target.closest('.address-actions') || e.target.closest('.address-btn')) return;

                // Remove selected class from all cards
                addressCards.forEach(c => {
                    c.classList.remove('selected', 'default');
                    const badge = c.querySelector('.address-badge');
                    if (badge) badge.style.display = 'none';
                });

                // Add selected class to the clicked card
                this.classList.add('selected', 'default');
                const myBadge = this.querySelector('.address-badge');
                if (myBadge) myBadge.style.display = 'inline-block';
            });
        });

        // Initialize first address as selected logic has been completely removed as per request hook.
        
        // Copy coupon code functionality
        document.querySelectorAll('.copy-coupon-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: `Coupon code ${code} copied to clipboard.`,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            });
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