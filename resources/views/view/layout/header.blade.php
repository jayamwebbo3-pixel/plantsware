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

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav-icon.png') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.fancybox.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/media.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CDN Libraries -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        .mega_menu {
            position: static !important;
        }

        .mega_menu .dropdown-menu {
            background-color: #76a713;
            /* Primary green color */
            border: none;
            border-radius: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100% !important;
            left: 0 !important;
            right: 0 !important;
            margin-top: 0;
            padding: 30px 5% !important;
            /* Align with header padding */
        }

        .mega_menu .h_title {
            color: #fff !important;
            font-weight: 800 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3) !important;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: block;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .mega_menu .list-unstyled li a {
            color: #fff !important;
            padding: 6px 0;
            display: block;
            font-size: 13px;
            transition: all 0.3s;
            white-space: normal;
            /* Allow product names to wrap if needed */
        }

        .mega_menu .list-unstyled li a:hover {
            padding-left: 10px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff !important;
        }

        .mega_menu .dropdown-menu .row {
            margin: 0;
            width: 100%;
        }

        @media (max-width: 991px) {
            .mega_menu .dropdown-menu {
                width: auto !important;
                position: relative !important;
                left: 0 !important;
                right: 0 !important;
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>
    <div class="preloader"></div>
    <!-- header area -->
    <header>
        <div class="topbar-outer py-2 d-lg-block d-none">
            <div>
                <div class="row">
                    <div class="col-lg-12 col-md-4 col-sm-4 topbar_left">
                        <ul>
                            <li class="text-center">
                                <span class="fw-bold">{{ $headerFooter->header_title ?? 'Rooted in Nature, Grown with Love' }}</span>
                            </li>
                        </ul>
                    </div>

                    <!--
                    <div class="col-lg-7 col-md-8 col-sm-8 text-xs-right topbar_right text-right">
                        ...
                    </div>
                    -->
                </div>
            </div>
        </div>
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
                                <div class="between-header border border-danger rounded mb-0 head-left">
                                    <div class="d-flex align-items-stretch w-100" style="gap: 0;">
                                        <input type="text" placeholder="Search Products" class="form-control flex-grow-1" aria-label="search" aria-describedby="button-addon2">
                                        <button type="button" class="btn btn-danger text-uppercase font-weight-normal flex-shrink-0">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Account / Cart / Wishlist -->
                            <div class="col-xl-5 col-lg-5 head-right text-right order-1 order-lg-2">
                                <ul class="top_cart">
                                    <!-- Wishlist -->
                                    <li class="dropdown d-inline-block my-cart md_acco">
                                        <a href="{{ url('wishlist') }}" class="cart-qty">
                                            <span class="price_cart d-md-inline-block align-middle font-weight-bolder">
                                                {{ session('wishlist_count', 0) }}
                                            </span>
                                            <span class="dropdown-toggle Price-amount" role="menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Wishlist
                                            </span>
                                        </a>
                                    </li>
                                    <!-- Cart -->
                                    <li class="dropdown d-inline-block my-cart md_acco">
                                        <a href="{{ url('cart') }}" class="cart-qty">
                                            <span class="price_cart d-md-inline-block align-middle font-weight-bolder">
                                                {{ session('cart_count', 0) }}
                                            </span>
                                            <span class="dropdown-toggle Price-amount" role="menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Cart
                                            </span>
                                        </a>
                                    </li>
                                    <!-- My Account -->
                                    <li class="dropdown right1 md_acc md_acco">
                                        <span class="account-block"></span>
                                        <span class="dropdown-toggle my_account" role="menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            My account
                                        </span>
                                        <span class="dropdown-menu r_menu dropdown-menu-right">
                                            @auth
                                            <a class="dropdown-item font-weight-bolderer" href="{{ url('user/dashboard') }}">Dashboard</a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item font-weight-bolderer">Logout</button>
                                            </form>
                                            @else
                                            <a class="dropdown-item font-weight-bolderer" href="{{ url('login') }}">Login</a>
                                            {{-- <a class="dropdown-item font-weight-bolderer" href="{{ url('register') }}">Register</a> --}}
                                            @endauth
                                        </span>
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
                        <button class="navbar-toggler d-lg-none d-md-block d-sm-block" type="button" id="menuToggle" aria-label="Open Menu">
                            <span class="navbar-toggler-icon">☰</span>
                        </button>
                        <ul class="main-menu navbar">
                            <button class="close-menu" id="closeMenu" aria-label="Close Menu">×</button>
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
                                        <div class="col-lg-4 col-md-6">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $sub)
                                                <li class="h_title text-uppercase">{{ $sub->name }}</li>
                                                @foreach($sub->products as $product)
                                                <li><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></li>
                                                @endforeach
                                                <hr class="border-light opacity-25 my-2">
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
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $sub)
                                                <li class="h_title text-uppercase">{{ $sub->name }}</li>
                                                @foreach($sub->products as $product)
                                                <li><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></li>
                                                @endforeach
                                                <hr class="border-light opacity-25 my-2">
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
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                @foreach($chunk as $sub)
                                                <li class="h_title text-uppercase">{{ $sub->name }}</li>
                                                @foreach($sub->products as $product)
                                                <li><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></li>
                                                @endforeach
                                                <hr class="border-light opacity-25 my-2">
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
                            <li><a href="{{ url('about') }}">About Plantsware</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header area end -->

    <div class="container-fluid mt-2">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // TOGGLE MENU OPEN
            const menuToggle = document.getElementById('menuToggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelector(".main-menu").classList.add("show");
                });
            }

            // TOGGLE MENU CLOSE
            const closeMenu = document.getElementById('closeMenu');
            if (closeMenu) {
                closeMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelector(".main-menu").classList.remove("show");
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const mainMenu = document.querySelector('.main-menu');
                if (mainMenu && !event.target.closest('.navbar-toggler') &&
                    !event.target.closest('.main-menu') &&
                    mainMenu.classList.contains('show')) {
                    mainMenu.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>