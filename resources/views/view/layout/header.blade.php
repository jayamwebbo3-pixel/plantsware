<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $headerFooter->home_meta_title ?? 'Plantly' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.APP_URL = "{{ url('/') }}";
    </script>


    <!-- Blog sharing -->
    <!-- <meta property="og:title" content="{{ $blog->title ?? 'Plantly Blog' }}">-->
    <!--<meta property="og:description" content="{{ Str::limit(strip_tags($blog->excerpt ?? ''), 150) }}">-->
    <!--<meta property="og:image" content="{{ isset($blog->image) ? asset('storage/'.$blog->image) : asset('assets/images/logo-1.png') }}">-->
    <!--<meta property="og:url" content="{{ url()->current() }}">-->
    <!--<meta property="og:type" content="article">-->

    <!--<meta name="twitter:card" content="summary_large_image">-->

    <!-- Blog sharing end  -->

    <!--Blog sharing altered for img inclusion-->
    @php
    $blogImage = (isset($blog) && !empty($blog->image))
    ? asset('storage/' . $blog->image)
    : asset('assets/images/blog/default.jpg');

    $blogTitle = isset($blog)
    ? $blog->title
    : 'Plantly Blog';

    $blogDescription = isset($blog)
    ? \Illuminate\Support\Str::limit(strip_tags($blog->excerpt ?? $blog->content), 150)
    : 'Read the latest articles on Plantly';

    $blogUrl = url()->current();
    @endphp

    <meta property="og:site_name" content="Plantly">
    <meta property="og:title" content="{{ $blogTitle }}">
    <meta property="og:description" content="{{ $blogDescription }}">
    <meta property="og:image" content="{{ $blogImage }}">
    <meta property="og:url" content="{{ $blogUrl }}">
    <meta property="og:type" content="article">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blogTitle }}">
    <meta name="twitter:description" content="{{ $blogDescription }}">
    <meta name="twitter:image" content="{{ $blogImage }}">

    <!--end here-->

    <!-- Preconnect & DNS-Prefetch to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Preload Critical Above-The-Fold Assets -->
    <link rel="preload" as="image" href="{{ asset('assets/images/logo-1.png') }}">
    <link rel="preload" as="style" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="preload" as="style" href="{{ asset('assets/css/style.min.css') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav-icon.png') }}">

    <!-- Critical CSS (Render-blocking kept to minimum for speed) -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/media.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/customized.min.css') }}?v=1.5">

    <!-- Google Fonts (Preloaded & Swapped) -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="preload" href="{{ asset('assets/css/animate.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </noscript>
</head>

<body>
    <div class="preloader"></div>
    <!-- header area -->
    <header>
        <!-- <div class="topbar-outer py-2 d-lg-block d-none">
            <div>
                <div class="row">
                    <div class="col-lg-12 col-md-4 col-sm-4 topbar_left">
                        <ul>
                            <li class="text-center">
                                <span class="fw-bold">{{ $headerFooter->header_title ?? 'Rooted in Nature, Grown with Love' }}</span>
                            </li>
                        </ul>
                    </div> -->

                    <!--
                    <div class="col-lg-7 col-md-8 col-sm-8 text-xs-right topbar_right text-right">
                        ...
                    </div>
                    -->
                <!-- </div>
            </div>
        </div> -->
        <!-- header-top -->
        <div class="header-top py-2 border-bottom shadow-sm">
            <div class="header-top-container">
                <div class="row header_row">
                    <div class="col-xl-3 col-lg-3 col-6 head-logo pl-md-0">
                        <div class="text-left header-top-left pt-2">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/images/logo-1.png') }}" class="img-responsive img" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-6 head-search">
                        <div class="d-flex navbar">
                            <!-- Search Bar -->
                            <div class="input-class text-left col-12 col-md-12 col-lg-7 order-2 order-lg-1">
                                <div class="between-header mb-0 head-left" style="position:relative;">
                                    <input type="text"
                                        id="liveSearchInput"
                                        placeholder="Search Products"
                                        class="form-control"
                                        autocomplete="off">
                                    <button type="button"
                                        id="liveSearchBtn"
                                        class="btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <!-- Autocomplete Dropdown -->
                                    <div id="searchDropdown" style="
                                        display:none;
                                        position:absolute;
                                        top:100%;
                                        left:0;
                                        right:0;
                                        background:#fff;
                                        border:1px solid #e0e0e0;
                                        border-top:none;
                                        border-radius:0 0 10px 10px;
                                        box-shadow:0 8px 24px rgba(0,0,0,0.12);
                                        z-index:99999;
                                        max-height:420px;
                                        overflow-y:auto;
                                    "></div>
                                </div>
                            </div>
                            <!-- Account / Cart / Wishlist -->
                            <div class="col-xl-5 col-lg-5 head-right text-right order-1 order-lg-2">
                                <ul class="top_cart">
                                    <!-- Mobile Search Icon (1st Icon on Mobile) -->
                                    <li class="d-lg-none md_acco pr-2">
                                        <a href="javascript:void(0)" onclick="document.querySelector('.input-class').classList.toggle('mobile-active')" class="cart-qty d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-search" style="font-size: 22px; color: #333;"></i>
                                        </a>
                                    </li>
                                    <!-- Wishlist -->
                                    <li class="d-inline-block my-cart md_acco">
                                        <a href="{{ url('wishlist') }}" class="cart-qty wishlist-icon-link">
                                            <span class="price_cart d-md-inline-block align-middle font-weight-bolder">
                                                {{ $wishlistCount ?? 0 }}
                                            </span>
                                            <span class="Price-amount font-weight-bolderer">
                                                Wishlist
                                            </span>
                                        </a>
                                    </li>
                                    <!-- Cart -->
                                    <li class="d-inline-block my-cart md_acco">
                                        <a href="{{ url('cart') }}" class="cart-qty cart-icon-link">
                                            <span class="price_cart d-md-inline-block align-middle font-weight-bolder">
                                                {{ $cartCount ?? 0 }}
                                            </span>
                                            <span class="Price-amount font-weight-bolderer">
                                                Cart
                                            </span>
                                        </a>
                                    </li>
                                    <!-- My Account -->
                                    <li class="dropdown right1 md_acc md_acco">
                                        <span class="account-block"></span>
                                        <a href="{{ Auth::check() ? url('user/dashboard') : url('login') }}" class="my_account_link" style="text-decoration: none;">
                                            <span class="dropdown-toggle my_account" role="menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                My account
                                            </span>
                                        </a>
                                        <div class="dropdown-menu r_menu dropdown-menu-right">
                                            @auth
                                            <a class="dropdown-item font-weight-bolderer" href="{{ url('user/dashboard') }}">Dashboard</a>
                                            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                                @csrf
                                                <button type="submit" class="dropdown-item font-weight-bolderer">Logout</button>
                                            </form>
                                            @else
                                            <a class="dropdown-item font-weight-bolderer" href="{{ url('login') }}">Login</a>
                                            {{-- <a class="dropdown-item font-weight-bolderer" href="{{ url('register') }}">Register</a> --}}
                                            @endauth
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- head-search -->
                </div>
            </div>
        </div>
        <!-- header-top py-2 border-bottom shadow-sm -->
        <div class="header_bottom shadow-sm rounded d-md-block d-sm-block d-lg-block">
            <div>
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Toggler for mobile/tablet -->
                        <button class="navbar-toggler d-xl-none" type="button" id="menuToggle" aria-label="Open Menu">
                            <span class="navbar-toggler-icon">☰</span>
                        </button>
                        <ul class="main-menu navbar">
                            <button class="close-menu" id="closeMenu" aria-label="Close Menu">×</button>
                            <!-- Hamburger Logo -->
                            <li class="hamburger-logo-item">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('assets/images/logo-1.png') }}" alt="Plantsware Logo">
                                </a>
                            </li>
                            <!-- Home -->
                            <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>

                            @php
                            $gardenCategory = $headerCategories->where('id', 4)->first();
                            $aquariumCategory = $headerCategories->where('id', 5)->first();
                            $naturalCategory = $headerCategories->where('id', 11)->first();
                            @endphp

                            <!-- Garden Products Dropdown -->
                            <li class="dropdown mega_menu m1 level-1 font-weight-bolderer {{ $gardenCategory && Request::is('category/' . $gardenCategory->slug) ? 'active' : '' }}">
                                <a class="dropdown-toggle" href="{{ $gardenCategory ? route('category.show', $gardenCategory->slug) : '#' }}" role="button" data-toggle="dropdown" aria-expanded="false">
                                    Garden Products&nbsp;<span class="ml-1"><i class="fa fa-angle-down"></i></span>
                                </a>
                                <ul class="dropdown-menu p-3">
                                    <div class="row">
                                        @if($gardenCategory && $gardenCategory->subcategories->count() > 0)
                                        @php $chunks = $gardenCategory->subcategories->chunk(ceil($gardenCategory->subcategories->count() / 3)); @endphp
                                        @foreach($chunks as $chunk)
                                        <div class="col-lg-3 col-md-6 mb-3">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $sub)
                                                <li class="h_title text-uppercase">
                                                    <a href="{{ route('subcategory.show', $sub->slug) }}">
                                                        {{ $sub->name }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="col-12">
                                            <p class="text-muted small">No subcategories available.</p>
                                        </div>
                                        @endif
                                    </div>
                                </ul>
                            </li>

                            <!-- Planted Aquarium Products Dropdown -->
                            <li class="dropdown mega_menu m1 level-1 font-weight-bolderer {{ $aquariumCategory && Request::is('category/' . $aquariumCategory->slug) ? 'active' : '' }}">
                                <a class="dropdown-toggle" href="{{ $aquariumCategory ? route('category.show', $aquariumCategory->slug) : '#' }}" role="button" data-toggle="dropdown" aria-expanded="false">
                                    Planted Aquarium Products&nbsp;<span class="ml-1"><i class="fa fa-angle-down"></i></span>
                                </a>
                                <ul class="dropdown-menu p-3">
                                    <div class="row">
                                        @if($aquariumCategory && $aquariumCategory->subcategories->count() > 0)
                                        @php $chunks = $aquariumCategory->subcategories->chunk(ceil($aquariumCategory->subcategories->count() / 2)); @endphp
                                        @foreach($chunks as $chunk)
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $sub)
                                                <li class="h_title text-uppercase">
                                                    <a href="{{ route('subcategory.show', $sub->slug) }}">
                                                        {{ $sub->name }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="col-12">
                                            <p class="text-muted small">No subcategories available.</p>
                                        </div>
                                        @endif
                                    </div>
                                </ul>
                            </li>

                            <!-- Natural Products Dropdown -->
                            <li class="dropdown mega_menu mega-menu-2 m1 level-1 font-weight-bolderer {{ $naturalCategory && Request::is('category/' . $naturalCategory->slug) ? 'active' : '' }}">
                                <a class="dropdown-toggle" href="{{ $naturalCategory ? route('category.show', $naturalCategory->slug) : '#' }}" role="button" data-toggle="dropdown" aria-expanded="false">
                                    Natural Products&nbsp;<span class="ml-1"><i class="fa fa-angle-down"></i></span>
                                </a>
                                <ul class="dropdown-menu p-3">
                                    <div class="row">
                                        @if($naturalCategory && $naturalCategory->subcategories->count() > 0)
                                        @php $chunks = $naturalCategory->subcategories->chunk(ceil($naturalCategory->subcategories->count() / 2)); @endphp
                                        @foreach($chunks as $chunk)
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $sub)
                                                <li class="h_title text-uppercase">
                                                    <a href="{{ route('subcategory.show', $sub->slug) }}">
                                                        {{ $sub->name }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="col-12">
                                            <p class="text-muted small">No subcategories available.</p>
                                        </div>
                                        @endif
                                    </div>
                                </ul>
                            </li>

                            <li class="{{ Request::routeIs('combo_packs.frontend_index') ? 'active' : '' }}"><a href="{{ route('combo_packs.frontend_index') }}">Combo Packs</a></li>
                            @php
                            $customComboSetting = \App\Models\ComboPackSetting::first();
                            @endphp
                            @if($customComboSetting && $customComboSetting->is_enabled)
                            <li class="{{ Request::routeIs('combo-builder.index') ? 'active' : '' }}"><a href="{{ route('combo-builder.index') }}">Build a Combo</a></li>
                            @endif
                            <li class="{{ Request::is('about') ? 'active' : '' }}"><a href="{{ url('about') }}">About Us</a></li>
                            <li class="{{ Request::routeIs('blog.categories') || Request::is('blog*') ? 'active' : '' }}"><a href="{{ route('blog.categories') }}">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <!-- Sticky Header Bar -->
        <div class="sticky-header-bar">
            <div class="container-fluid px-3">
                <div class="sticky-header-row">
                    <!-- Left: Logo -->
                    <div class="sticky-nav-col">
                        <a href="{{ url('/') }}" class="sticky-logo-link">
                            <img src="{{ asset('assets/images/logo-1.png') }}" alt="logo" class="sticky-logo-img">
                        </a>
                    </div>
                    
                    <!-- Center: Search -->
                    <div class="sticky-search-col">
                        <button type="button" id="stickySearchMobileToggle" class="btn sticky-search-mobile-toggle">
                            <i class="fas fa-search"></i>
                        </button>
                        <div class="sticky-search-box">
                            <input type="text"
                                id="stickySearchInput"
                                placeholder="Search Products"
                                class="form-control"
                                autocomplete="off">
                            <button type="button" id="stickySearchBtn" class="btn">
                                <i class="fas fa-search"></i>
                            </button>
                            <button type="button" id="stickySearchCloseBtn" class="sticky-search-close">
                                &times;
                            </button>
                            <!-- Autocomplete Dropdown -->
                            <div id="stickySearchDropdown" style="
                                display:none;
                                position:absolute;
                                top:100%;
                                left:0;
                                right:0;
                                background:#fff;
                                border:1px solid #e0e0e0;
                                border-top:none;
                                border-radius:0 0 10px 10px;
                                box-shadow:0 8px 24px rgba(0,0,0,0.12);
                                z-index:99999;
                                max-height:420px;
                                overflow-y:auto;
                            "></div>
                        </div>
                    </div>
                    
                    <!-- Right: Actions & Menu -->
                    <div class="sticky-actions-col">
                        <ul class="sticky-actions-list">
                            <!-- Wishlist -->
                            <!-- Wishlist -->
                            <li class="sticky-action-item">
                                <a href="{{ url('wishlist') }}" class="sticky-action-link wishlist-icon-link" title="Wishlist">
                                    <span class="price_cart sticky-badge">{{ $wishlistCount ?? 0 }}</span>
                                </a>
                            </li>
                            <!-- Cart -->
                            <li class="sticky-action-item">
                                <a href="{{ url('cart') }}" class="sticky-action-link cart-icon-link" title="Cart">
                                    <span class="price_cart sticky-badge">{{ $cartCount ?? 0 }}</span>
                                </a>
                            </li>
                            <!-- User Login / Account -->
                            <li class="sticky-action-item dropdown">
                                <a href="{{ Auth::check() ? url('user/dashboard') : url('login') }}" class="sticky-action-link my_account_link" style="text-decoration: none;" id="stickyAccountToggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="My Account">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="stickyAccountToggle">
                                    @auth
                                    <a class="dropdown-item font-weight-bolderer" href="{{ url('user/dashboard') }}">Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item font-weight-bolderer">Logout</button>
                                    </form>
                                    @else
                                    <a class="dropdown-item font-weight-bolderer" href="{{ url('login') }}">Login</a>
                                    @endauth
                                </div>
                            </li>
                            <!-- Hamburger Menu Toggle -->
                            <li class="sticky-action-item">
                                <button class="sticky-menu-toggle" type="button" aria-label="Open Menu">
                                    <span>☰</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="menu-overlay" id="menuOverlay"></div>
    </header>
    <!-- header area end -->

    <!-- Alerts moved to SweetAlert2 for better UX -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const stickyMenuToggle = document.querySelector('.sticky-menu-toggle');
            const closeMenu = document.getElementById('closeMenu');
            const mainMenu = document.querySelector('.main-menu');
            const menuOverlay = document.getElementById('menuOverlay');
            const stickyHeader = document.querySelector('.sticky-header-bar');

            function openMobileMenu() {
                if (mainMenu) mainMenu.classList.add('show');
                if (menuOverlay) menuOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                if (mainMenu) mainMenu.classList.remove('show');
                if (menuOverlay) menuOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            // Delegated click handler for menu toggle and closing elements
            document.addEventListener('click', function(e) {
                // Check for open triggers (normal menu toggle or sticky menu toggle)
                const openTrigger = e.target.closest('#menuToggle') || e.target.closest('.sticky-menu-toggle');
                if (openTrigger && mainMenu) {
                    e.preventDefault();
                    e.stopPropagation();
                    openMobileMenu();
                    return;
                }

                // Check for close triggers (close button or overlay)
                const closeTrigger = e.target.closest('#closeMenu') || e.target.closest('#menuOverlay');
                if (closeTrigger) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeMobileMenu();
                    return;
                }

                // Close when clicking outside the menu container
                if (mainMenu && mainMenu.classList.contains('show')) {
                    if (!mainMenu.contains(e.target)) {
                        closeMobileMenu();
                    }
                }
            });

            // Mobile & Scrolled Drawer Dropdown Toggle
            const dropdownToggles = document.querySelectorAll('.main-menu .dropdown > a');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    if (window.innerWidth < 1200 || (mainMenu && mainMenu.classList.contains('show'))) {
                        e.preventDefault();
                        e.stopPropagation();
                        const parent = this.parentElement;
                        parent.classList.toggle('active');
                    }
                });
            });

            // Sticky Scroll Logic
            if (stickyHeader) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 150) {
                        stickyHeader.classList.add('sticky-visible');
                    } else {
                        stickyHeader.classList.remove('sticky-visible');
                    }
                });
            }
        });
    </script>

    <!-- Live Search Autocomplete -->
    <script>
        (function() {
            function initAutocomplete(inputId, dropdownId, btnId) {
                const input = document.getElementById(inputId);
                const dropdown = document.getElementById(dropdownId);
                const btn = document.getElementById(btnId);

                if (!input || !dropdown || !btn) return;

                const AUTOCOMPLETE_URL = '{{ route("search.autocomplete") }}';
                const PRODUCTS_URL = '{{ url("products") }}';

                let debounceTimer = null;
                let activeIndex = -1;
                let lastResults = [];

                function fetchSuggestions(q) {
                    fetch(AUTOCOMPLETE_URL + '?q=' + encodeURIComponent(q))
                        .then(r => r.json())
                        .then(data => {
                            lastResults = data;
                            renderDropdown(data, q);
                        })
                        .catch(() => closeDropdown());
                }

                function renderDropdown(items, q) {
                    activeIndex = -1;
                    if (!items.length) {
                        dropdown.innerHTML = '<div style="padding:18px 16px;color:#888;font-size:14px;text-align:center;"><i class="fas fa-search" style="margin-right:6px;"></i>No products found for <strong>' + escHtml(q) + '</strong></div>';
                        dropdown.style.display = 'block';
                        return;
                    }
                    let html = '';
                    items.forEach(function(p, i) {
                        var price = '';
                        if (p.stock_quantity > 0) {
                            price = (p.sale_price && p.sale_price < p.price) ?
                                '<s style="color:#aaa;font-size:11px;">&#8377;' + fmt(p.price) + '</s>&nbsp;<span style="color:#6EA820;font-weight:700;">&#8377;' + fmt(p.sale_price) + '</span>' :
                                '<span style="color:#6EA820;font-weight:700;">&#8377;' + fmt(p.price) + '</span>';
                        } else {
                            price = '<span style="color:#dc3545;font-weight:700;font-size:11px;">OUT OF STOCK</span>';
                        }
                        var highlighted = highlightMatch(escHtml(p.name), q);
                        html += '<a href="' + escHtml(p.url) + '" class="search-suggestion-item"' +
                            ' style="display:flex;align-items:center;gap:12px;padding:10px 14px;text-decoration:none;color:#333;border-bottom:1px solid #f4f4f4;">' +
                            '<img src="' + escHtml(p.image) + '" alt="' + escHtml(p.name) + '"' +
                            ' style="width:52px;height:52px;object-fit:contain;border-radius:8px;border:1px solid #eee;background:#fafafa;flex-shrink:0;">' +
                            '<div style="flex:1;min-width:0;">' +
                            '<div style="font-size:13px;font-weight:600;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + highlighted + '</div>' +
                            '<div style="font-size:12px;margin-top:3px;">' + price + '</div>' +
                            '</div>' +
                            '<i class="fas fa-chevron-right" style="color:#ccc;font-size:11px;flex-shrink:0;"></i>' +
                            '</a>';
                    });
                    html += '<a href="' + PRODUCTS_URL + '?q=' + encodeURIComponent(q) + '"' +
                        ' style="display:block;text-align:center;padding:11px;font-size:13px;color:#6EA820;font-weight:600;border-top:2px solid #f0f0f0;text-decoration:none;background:#fafff4;">' +
                        '<i class="fas fa-search" style="margin-right:5px;"></i>See all results for <strong>' + escHtml(q) + '</strong></a>';
                    dropdown.innerHTML = html;
                    dropdown.style.display = 'block';
                    dropdown.querySelectorAll('.search-suggestion-item').forEach(function(el) {
                        el.addEventListener('mouseenter', function() {
                            el.style.background = '#f5fbe8';
                        });
                        el.addEventListener('mouseleave', function() {
                            el.style.background = '';
                        });
                    });
                }

                function closeDropdown() {
                    dropdown.style.display = 'none';
                    activeIndex = -1;
                }

                function fmt(n) {
                    return parseFloat(n).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                function escHtml(s) {
                    var d = document.createElement('div');
                    d.textContent = String(s);
                    return d.innerHTML;
                }

                function highlightMatch(name, q) {
                    var re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                    return name.replace(re, '<mark style="background:#d4f0a0;padding:0 1px;border-radius:3px;">$1</mark>');
                }

                input.addEventListener('keydown', function(e) {
                    var items = dropdown.querySelectorAll('.search-suggestion-item');
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        activeIndex = Math.min(activeIndex + 1, items.length - 1);
                        items.forEach(function(el, i) {
                            el.style.background = i === activeIndex ? '#f5fbe8' : '';
                        });
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        activeIndex = Math.max(activeIndex - 1, -1);
                        items.forEach(function(el, i) {
                            el.style.background = i === activeIndex ? '#f5fbe8' : '';
                        });
                    } else if (e.key === 'Enter') {
                        if (activeIndex >= 0 && items[activeIndex]) {
                            e.preventDefault();
                            items[activeIndex].click();
                        } else {
                            goSearch();
                        }
                    } else if (e.key === 'Escape') {
                        closeDropdown();
                    }
                });

                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    var q = this.value.trim();
                    if (q.length < 2) {
                        closeDropdown();
                        return;
                    }
                    debounceTimer = setTimeout(function() {
                        fetchSuggestions(q);
                    }, 280);
                });

                function goSearch() {
                    var q = input.value.trim();
                    if (q) window.location.href = PRODUCTS_URL + '?q=' + encodeURIComponent(q);
                }
                btn.addEventListener('click', goSearch);

                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) closeDropdown();
                });
                input.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2 && lastResults.length) dropdown.style.display = 'block';
                });
            }

            initAutocomplete('liveSearchInput', 'searchDropdown', 'liveSearchBtn');
            initAutocomplete('stickySearchInput', 'stickySearchDropdown', 'stickySearchBtn');

            // Typewriter / Scrolling Placeholder Animation
            function initPlaceholderTyping(inputId) {
                var input = document.getElementById(inputId);
                if (!input) return;

                var phrases = [
                    "Search Products"
                ];

                var currentPhraseIndex = 0;
                var currentCharIndex = 0;
                var isDeleting = false;
                var typingSpeed = 120;
                var pauseBetweenPhrases = 3000;
                var deletingSpeed = 60;

                function tick() {
                    if (!input) return;

                    // If input is focused or has user input, pause animation and show default placeholder
                    if (document.activeElement === input || input.value.length > 0) {
                        input.setAttribute('placeholder', 'Search Products');
                        setTimeout(tick, 1000);
                        return;
                    }

                    var currentPhrase = phrases[currentPhraseIndex];
                    var displayedText = "";

                    if (isDeleting) {
                        displayedText = currentPhrase.substring(0, currentCharIndex - 1);
                        currentCharIndex--;
                    } else {
                        displayedText = currentPhrase.substring(0, currentCharIndex + 1);
                        currentCharIndex++;
                    }

                    input.setAttribute('placeholder', displayedText + " |");

                    var nextDelay = typingSpeed;

                    if (isDeleting) {
                        nextDelay = deletingSpeed;
                    }

                    if (!isDeleting && currentCharIndex === currentPhrase.length) {
                        // Pause at full phrase, and remove cursor before deleting
                        input.setAttribute('placeholder', currentPhrase);
                        nextDelay = pauseBetweenPhrases;
                        isDeleting = true;
                    } else if (isDeleting && currentCharIndex === 0) {
                        isDeleting = false;
                        currentPhraseIndex = (currentPhraseIndex + 1) % phrases.length;
                        nextDelay = 800; // Time gap when empty before typing starts again
                    }

                    setTimeout(tick, nextDelay);
                }

                // Start the typing animation after a small delay
                setTimeout(tick, 500);
            }

            initPlaceholderTyping('liveSearchInput');
            initPlaceholderTyping('stickySearchInput');
        })();
    </script>

    <!-- Sticky Categories & Mobile Search Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const catBtn = document.getElementById('stickyCategoriesBtn');
            const catDropdown = document.getElementById('stickyCategoriesDropdown');
            const searchToggle = document.getElementById('stickySearchMobileToggle');
            const searchClose = document.getElementById('stickySearchCloseBtn');
            const searchCol = document.querySelector('.sticky-search-col');
            const searchInput = document.getElementById('stickySearchInput');

            if (catBtn && catDropdown) {
                catBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close search overlay if open
                    if (searchCol && searchCol.classList.contains('search-active')) {
                        searchCol.classList.remove('search-active');
                    }
                    
                    catDropdown.classList.toggle('show');
                });

                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!catDropdown.contains(e.target) && e.target !== catBtn) {
                        catDropdown.classList.remove('show');
                    }
                });
            }

            if (searchToggle && searchCol) {
                searchToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close categories dropdown if open
                    if (catDropdown && catDropdown.classList.contains('show')) {
                        catDropdown.classList.remove('show');
                    }
                    
                    searchCol.classList.add('search-active');
                    if (searchInput) searchInput.focus();
                });
            }

            if (searchClose && searchCol) {
                searchClose.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    searchCol.classList.remove('search-active');
                });
            }

            // Mutual close: close categories dropdown when interacting with search input
            if (searchInput && catDropdown) {
                searchInput.addEventListener('focus', function() {
                    catDropdown.classList.remove('show');
                });
            }
            if (searchCol && catDropdown) {
                searchCol.addEventListener('click', function() {
                    catDropdown.classList.remove('show');
                });
            }

            // Close search when clicking outside search box on mobile
            document.addEventListener('click', function(e) {
                if (searchCol && searchCol.classList.contains('search-active')) {
                    if (!searchCol.contains(e.target) && e.target !== searchToggle) {
                        searchCol.classList.remove('search-active');
                    }
                }
            });
        });
    </script>
</body>

</html>