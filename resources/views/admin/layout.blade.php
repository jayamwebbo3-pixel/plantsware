<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Plantsware</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
           background: linear-gradient(135deg, #134e5e, #71b280);
            color: white;
            padding: 20px 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .main-content {
            padding: 20px;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="text-center mb-4">
                    <h4><i class="fas fa-leaf"></i> Plantsware</h4>
                    <small>Admin Panel</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.products.management') ? 'active' : '' }}" href="{{ route('admin.products.management') }}">
                        <i class="fas fa-boxes"></i> Product Management
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fa-solid fa-truck"></i> Orders Management
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> <span>Users</span>
                    </a>

                    <!-- Informative Pages Dropdown -->
                    <a class="nav-link" data-bs-toggle="collapse" href="#informativePagesCollapse" role="button" aria-expanded="{{ request()->routeIs('admin.pages.*') ? 'true' : 'false' }}" aria-controls="informativePagesCollapse">
                        <i class="fas fa-file-alt"></i> Informative Pages <i class="fas fa-chevron-down float-end mt-1" style="font-size: 0.8rem;"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.pages.*') ? 'show' : '' }}" id="informativePagesCollapse">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/about-us/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'about-us') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> About Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/contact-us/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'contact-us') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Contact Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/services/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'services') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Services
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/management-team/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'management-team') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Management Team
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/terms-conditions/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'terms-conditions') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Terms & Conditions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/privacy-policy/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'privacy-policy') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Privacy Policy
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/return-refund-policy/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'return-refund-policy') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Return & Refund Policy
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/pages/shipping-policy/edit') ? 'active' : '' }}" href="{{ route('admin.pages.edit', 'shipping-policy') }}">
                                    <i class="fas fa-circle" style="font-size: 0.4rem; vertical-align: middle;"></i> Shipping Policy
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box"></i> Products
                    </a> --}}
                    {{-- <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-tags"></i> Categories
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}" href="{{ route('admin.subcategories.index') }}">
                        <i class="fas fa-list"></i> Subcategories
                    </a> --}}
                    {{-- <a class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
                        <i class="fa-solid fa-cog"></i> Attributes
                    </a> --}}
                    <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                        <i class="fas fa-blog"></i> Blogs
                    </a>
                    <!--<a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">-->
                    <!--    <i class="fas fa-images"></i> Sliders-->
                    <!--</a>-->
                    <!--<a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">-->
                    <!--    <i class="fas fa-star"></i> Testimonials-->
                    <!--</a>-->
                    <!--<a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">-->
                    <!--    <i class="fas fa-cog"></i> Settings-->
                    <!--</a>-->
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
                        <span class="text-muted">Welcome, {{ auth()->guard('admin')->user()->name ?? 'Admin' }}</span>
                    </div>
                </nav>

                <div class="main-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!--@yield('scripts')-->
    @stack('scripts')
</body>
</html>
