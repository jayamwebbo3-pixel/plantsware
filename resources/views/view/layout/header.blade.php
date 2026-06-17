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

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav-icon.png') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/media.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CDN Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/customized.css') }}">
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
                                <div class="between-header border border-danger rounded mb-0 head-left" style="position:relative;">
                                    <div class="d-flex align-items-stretch w-100" style="gap: 0;">
                                        <input type="text"
                                            id="liveSearchInput"
                                            placeholder="Search Products"
                                            class="form-control flex-grow-1"
                                            aria-label="search"
                                            aria-describedby="button-addon2"
                                            autocomplete="off">
                                        <button type="button"
                                            id="liveSearchBtn"
                                            class="btn btn-danger text-uppercase font-weight-normal flex-shrink-0">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
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
                            <li><a href="{{ url('/') }}">Home</a></li>

                            @php
                            $gardenCategory = $headerCategories->where('id', 4)->first();
                            $aquariumCategory = $headerCategories->where('id', 5)->first();
                            $naturalCategory = $headerCategories->where('id', 11)->first();
                            @endphp

                            <!-- Garden Products Dropdown -->
                            <li class="dropdown mega_menu m1 level-1 font-weight-bolderer">
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
                                                        <i class="fas fa-chevron-right ml-2" style="font-size: 10px; opacity: 0.5;"></i>
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
                            <li class="dropdown mega_menu m1 level-1 font-weight-bolderer">
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
                                                        <i class="fas fa-chevron-right ml-2" style="font-size: 10px; opacity: 0.5;"></i>
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
                            <li class="dropdown mega_menu mega-menu-2 m1 level-1 font-weight-bolderer">
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
                                                        <i class="fas fa-chevron-right ml-2" style="font-size: 10px; opacity: 0.5;"></i>
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

                            <li><a href="{{ route('combo_packs.frontend_index') }}">Combo Packs</a></li>
                            @php
                            $customComboSetting = \App\Models\ComboPackSetting::first();
                            @endphp
                            @if($customComboSetting && $customComboSetting->is_enabled)
                            <li><a href="{{ route('combo-builder.index') }}">Build a Combo</a></li>
                            @endif
                            <li><a href="{{ url('about') }}">About Plantsware</a></li>
                            <li><a href="{{ route('blog.categories') }}">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <div class="menu-overlay" id="menuOverlay"></div>
    </header>
    <!-- header area end -->

    <div class="container-fluid mt-2">
        <!-- Alerts moved to SweetAlert2 for better UX -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const closeMenu = document.getElementById('closeMenu');
            const mainMenu = document.querySelector('.main-menu');
            const menuOverlay = document.getElementById('menuOverlay');

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

            if (menuToggle && mainMenu) {
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openMobileMenu();
                });
            }

            if (closeMenu && mainMenu) {
                closeMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobileMenu();
                });
            }

            if (menuOverlay) {
                menuOverlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    closeMobileMenu();
                });
            }

            // Mobile Dropdown Toggle
            const dropdownToggles = document.querySelectorAll('.main-menu .dropdown > a');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    if (window.innerWidth < 1200) {
                        e.preventDefault();
                        e.stopPropagation();
                        const parent = this.parentElement;
                        parent.classList.toggle('active');
                    }
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mainMenu && mainMenu.classList.contains('show')) {
                    if (!mainMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                        closeMobileMenu();
                    }
                }
            });
        });
    </script>

    <!-- Live Search Autocomplete -->
    <script>
        (function() {
            const input = document.getElementById('liveSearchInput');
            const dropdown = document.getElementById('searchDropdown');
            const btn = document.getElementById('liveSearchBtn');

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
        })();
    </script>
</body>

</html>