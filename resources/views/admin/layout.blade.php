<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav-icon.png') }}">
    <title>@yield('title', 'Admin Panel') - Plantsware</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #134e5e, #71b280);
            color: white;
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .main-content {
            padding: 20px;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #134e5e !important;
            border-color: #134e5e !important;
            color: white !important;
        }

        .btn-primary:hover {
            background-color: #134e5e !important;
            border-color: #134e5e !important;
            color: white !important;
        }

        .btn-outline-primary {
            background-color: #f0872bff !important;
            --bs-btn-color: #fff !important;
            --bs-btn-border-color: #f0872bff !important;
            --bs-btn-hover-color: #fff !important;
            --bs-btn-hover-bg: #d47527 !important;
            --bs-btn-hover-border-color: #fd7d0dff !important;
            --bs-btn-focus-shadow-rgb: 13, 110, 253;
            --bs-btn-active-color: #fff !important;
            --bs-btn-active-bg: #fd7d0dff;
            --bs-btn-active-border-color: #fd7d0dff;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fd7d0dff;
            --bs-btn-disabled-bg: transparent;
            --bs-btn-disabled-border-color: #fd7d0dff;
            --bs-gradient: none;
        }

        .text-decoration-none {
            display: inline-block;
            background-color: #36383c;
            color: #fff !important;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 400;
            font-size: 12px;
            text-decoration: none !important;
            transition: 0.3s ease;
        }

        /* .text-decoration-none:hover {
            background-color: #0b5ed7;
            color: #fff !important;
        } */

        .btn {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 20px;
            font-weight: 400;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="text-center mb-4">
                    @php $headerFooter = \App\Models\HeaderFooter::first(); @endphp
                    <img src="{{ asset('assets/images/logo-1.png') }}" alt="Plantly Logo" style="background-color: #fff; border: 1px solid #bd1313ff; border-radius: 10px; margin-bottom: 15px;height: 15vh;width: 15vw;object-fit: cover;">
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.products.management') ? 'active' : '' }}" href="{{ route('admin.products.management') }}">
                        <i class="fas fa-boxes"></i> Product Management
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.combo-packs.*') ? 'active' : '' }}" href="{{ route('admin.combo-packs.index') }}">
                        <i class="fas fa-cubes"></i> Combo Packs
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fa-solid fa-truck"></i> Orders Management
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> <span>Customers</span>
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.shipping-rates.*') ? 'active' : '' }}" href="{{ route('admin.shipping-rates.index') }}">
                        <i class="fas fa-shipping-fast"></i> Shipping Cost
                    </a>

                    <!-- Informative Pages Dropdown -->
                    <a class="nav-link" data-bs-toggle="collapse" href="#informativePagesCollapse" role="button" aria-expanded="{{ request()->routeIs('admin.pages.*') ? 'true' : 'false' }}" aria-controls="informativePagesCollapse">
                        <i class="fas fa-file-alt"></i> Informative Pages <i class="fas fa-chevron-down float-end mt-1" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.pages.*') ? 'show' : '' }}" id="informativePagesCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/ad-banner/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'ad-banner') }}">
                                    <i class="fas fa-ad"></i> Ad Banner
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">
                                    <i class="fas fa-images"></i> Home Sliders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/about-us/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'about-us') }}">
                                    <i class="fas fa-lightbulb"></i> About Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/services/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'services') }}">
                                    <i class="fas fa-lightbulb"></i> Services
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/terms-conditions/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'terms-conditions') }}">
                                    <i class="fas fa-lightbulb"></i> Terms & Conditions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/privacy-policy/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'privacy-policy') }}">
                                    <i class="fas fa-lightbulb"></i> Privacy Policy
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/return-refund-policy/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'return-refund-policy') }}">
                                    <i class="fas fa-lightbulb"></i> Return & Refund Policy
                                </a>
                            </li>

                            <!--<li class="nav-item">
                                <hr style="border-color: rgba(255,255,255,0.2); margin: 5px 20px;">
                              </li> -->

                        </ul>
                    </div>

                    <a class="nav-link {{ request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blog-categories.index') }}">
                        <i class="fas fa-blog"></i> Blog Management
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                        <i class="fas fa-cog"></i> <span>GST & General Settings</span>
                    </a>
                    <hr style="border-color: rgba(255,255,255,0.3);">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <nav class="navbar navbar-light bg-white mb-3">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">@yield('title', 'Dashboard')</span>
                        <div class="dropdown ms-auto">
                            <button class="btn btn-light dropdown-toggle d-flex align-items-center rounded-pill px-3 shadow-sm border-0" type="button" id="adminUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-2 text-primary fs-5"></i>
                                <span class="fw-semibold text-dark">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 py-2" aria-labelledby="adminUserDropdown">
                                <li>
                                    <h6 class="dropdown-header small text-muted text-uppercase fw-bold pb-2">Admin Profile</h6>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 px-3" href="{{ route('admin.profile.edit') }}">
                                        <i class="fas fa-user-gear me-2 text-info opacity-75"></i> Manage Account
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider opacity-25">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 px-3 text-danger">
                                            <i class="fas fa-sign-out-alt me-2 opacity-75"></i> Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="main-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success Message
            const successMessage = "{{ session('success') }}";
            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: successMessage,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }

            // Error Message
            const errorMessage = "{{ session('error') }}";
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#134e5e'
                });
            }

            // Global confirm handler for delete buttons
            window.confirmDelete = function(message, form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message || "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#134e5e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                return false;
            };

            // Automatic Interceptor for standard confirm() calls in forms
            document.addEventListener('submit', function(e) {
                const form = e.target;
                const onsubmitAttr = form.getAttribute('onsubmit');

                if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    // Extract message
                    const match = onsubmitAttr.match(/confirm\(['"](.*)['"]\)/);
                    const message = match ? match[1] : 'Are you sure?';

                    Swal.fire({
                        title: 'Are you sure?',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#134e5e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.removeAttribute('onsubmit'); // Prevent loop
                            form.submit();
                        }
                    });
                }
            }, true);

            // Automatic Interceptor for onclick confirm() calls on buttons
            document.addEventListener('click', function(e) {
                const target = e.target.closest('[onclick*="confirm("]');
                if (target) {
                    const onclickAttr = target.getAttribute('onclick');
                    if (onclickAttr && onclickAttr.includes('confirm(')) {
                        e.preventDefault();
                        e.stopImmediatePropagation();

                        const match = onclickAttr.match(/confirm\(['"](.*)['"]\)/);
                        const message = match ? match[1] : 'Are you sure?';

                        Swal.fire({
                            title: 'Are you sure?',
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#134e5e',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Confirm',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                target.removeAttribute('onclick'); // Prevent loop
                                target.click();
                            }
                        });
                    }
                }
            }, true);
        });
    </script>
    <!--@yield('scripts')-->
    @stack('scripts')
</body>

</html>