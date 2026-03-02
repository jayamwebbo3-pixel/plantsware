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
                            <button type="submit" class="nav-link">
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
                                <span class="order-status {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="order-price">₹{{ number_format($order->total_amount, 2) }}</div>
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
                                <div class="product-price" style="font-weight: bold; color: var(--primary-color); margin-bottom: 10px;">₹{{ number_format($item->product->price, 2) }}</div>
                                
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
