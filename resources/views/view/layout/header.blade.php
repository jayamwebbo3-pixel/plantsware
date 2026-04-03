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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Global override to reliably locate Wishlist and Cart icons, bypassing style.css nth-child issues entirely */
        .wishlist-icon-link .Price-amount::before {
            background: url("{{ asset('uploads/img/svg/heart.svg') }}") no-repeat center !important;
            background-size: 100% !important;
        }

        .cart-icon-link .Price-amount::before {
            background: url("{{ asset('uploads/img/svg/cart.svg') }}") no-repeat center !important;
            background-size: 100% !important;
        }

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
                background-color: #fdfdfd !important;
                /* Force light background for mobile menu */
            }

            /* Mobile Side Menu Fixes */
            header {
                position: relative;
                z-index: 1050;
            }

            .header_row {
                position: relative !important;
            }

            .header-top {
                padding: 10px 0 !important;
                background: #fff;
            }

            .head-logo {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                text-align: left !important;
            }

            .head-logo img {
                max-height: 45px;
                width: auto;
            }

            .head-search {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                position: static !important;
            }

            .input-class {
                display: none !important;
            }

            .input-class.mobile-active {
                display: block !important;
                position: absolute !important;
                top: 60px !important;
                right: 15px !important;
                width: 350px !important;
                max-width: calc(100vw - 30px) !important;
                z-index: 100000 !important;
                background: #fff !important;
                padding: 15px !important;
                border-radius: 8px !important;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2) !important;
                border: 1px solid #eaeaea !important;
            }

            .input-class.mobile-active .between-header {
                box-shadow: none !important;
                border-radius: 6px !important;
                overflow: hidden !important;
                background: #fff !important;
                height: auto !important;
                border: 2px solid #6EA820 !important;
            }

            .input-class.mobile-active input {
                height: 45px !important;
                font-size: 14px !important;
                border: none !important;
            }

            .input-class.mobile-active .btn-danger {
                width: 55px !important;
                height: 45px !important;
                font-size: 0 !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 0 !important;
            }

            .input-class.mobile-active .btn-danger::before {
                content: '\f002' !important;
                font-family: "Font Awesome 5 Free" !important;
                font-weight: 900 !important;
                font-size: 18px !important;
                visibility: visible !important;
                color: #fff !important;
            }

            .head-right {
                display: block !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .top_cart {
                display: flex !important;
                justify-content: flex-end !important;
                align-items: center !important;
                margin: 0 !important;
                height: 45px !important;
            }

            .top_cart li {
                padding: 0 5px !important;
                position: relative !important;
                min-width: 45px !important;
                display: flex !important;
                align-items: center !important;
                height: 100% !important;
            }

            .Price-amount {
                font-size: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                min-height: 35px !important;
                min-width: 35px !important;
                position: relative !important;
            }

            .Price-amount::before {
                display: block !important;
                visibility: visible !important;
                width: 28px !important;
                height: 28px !important;
                position: absolute !important;
                left: 50% !important;
                top: 50% !important;
                transform: translate(-50%, -50%) !important;
                z-index: 1 !important;
            }

            .my_account {
                font-size: 0 !important;
                padding-left: 28px !important;
                display: flex !important;
                align-items: center !important;
                height: 35px !important;
                position: relative !important;
            }

            .my_account::before {
                display: block !important;
                visibility: visible !important;
                width: 28px !important;
                height: 28px !important;
                position: absolute !important;
                left: 0 !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                z-index: 1 !important;
            }

            .price_cart {
                font-size: 11px !important;
                background: #6EA820;
                color: #fff !important;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                display: flex !important;
                align-items: center;
                justify-content: center;
                position: absolute !important;
                top: -5px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                z-index: 10;
                padding: 0 !important;
            }

            .dropdown-menu.r_menu {
                padding: 5px 0 !important;
                min-height: auto !important;
                top: 40px !important;
            }

            .header_bottom {
                background: #6EA820 !important;
                min-height: 45px;
                display: flex !important;
                align-items: center;
                justify-content: flex-end;
                position: relative;
            }

            #menuToggle {
                background: transparent;
                border: none;
                color: #fff;
                font-size: 28px;
                padding: 5px 15px;
                cursor: pointer;
                display: block !important;
            }

            .main-menu {
                position: fixed !important;
                top: 0;
                left: -110% !important;
                width: 280px !important;
                height: 100vh !important;
                background: #fff !important;
                z-index: 9999 !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                padding: 70px 0 20px 0 !important;
                transition: all 0.4s ease-in-out !important;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
                display: block !important;
                overflow-y: auto;
                opacity: 1 !important;
                transform: none !important;
            }

            .main-menu.show {
                left: 0 !important;
            }

            .main-menu li {
                width: 100%;
                display: block !important;
                border-bottom: 1px solid #f0f0f0;
            }

            .main-menu li a {
                color: #333 !important;
                padding: 12px 20px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                display: flex;
                justify-content: space-between;
            }

            .main-menu>li.dropdown .dropdown-menu {
                position: static !important;
                display: none !important;
                width: 100% !important;
                opacity: 0 !important;
                transform: scale(1, 0) !important;
                transform-origin: top !important;
                transition: all 0.3s !important;
                visibility: visible !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            .main-menu li.dropdown.active>.dropdown-menu {
                display: block !important;
                opacity: 1 !important;
                transform: scale(1, 1) !important;
            }

            .main-menu li.mega_menu .dropdown-menu .h_title {
                color: #6EA820 !important;
                font-size: 12px !important;
                font-weight: 800 !important;
                margin-top: 10px !important;
                padding-bottom: 5px !important;
                border-bottom: 1px solid #f0f0f0 !important;
                display: block !important;
            }

            .main-menu li.mega_menu .dropdown-menu ul.list-unstyled li a {
                color: #666 !important;
                padding: 8px 0 !important;
                font-size: 13px !important;
                display: block !important;
                background: transparent !important;
            }

            #closeMenu {
                display: flex !important;
                position: absolute;
                top: 15px;
                right: 15px;
                background: #6EA820;
                color: #fff;
                border: none;
                width: 35px;
                height: 35px;
                border-radius: 50%;
                font-size: 20px;
                justify-content: center;
                align-items: center;
                z-index: 10001;
            }
        }

        /* Ultra small screen optimization (320px - 375px) */
        @media (max-width: 375px) {
            .head-logo {
                flex: 0 0 35% !important;
                max-width: 35% !important;
            }

            .head-search {
                flex: 0 0 65% !important;
                max-width: 65% !important;
            }

            .top_cart li {
                padding: 0 1px !important;
                min-width: 35px !important;
            }

            .head-logo img {
                max-height: 32px;
            }

            .price_cart {
                left: 50% !important;
                top: -8px !important;
                width: 16px !important;
                height: 16px !important;
                font-size: 10px !important;
                transform: translateX(-50%) !important;
            }

            .Price-amount,
            .my_account {
                padding-left: 20px !important;
            }
        }

        /* 1024px Layout Optimization (Handles 110-125% zoom) */
        @media (min-width: 992px) and (max-width: 1250px) {
            .header_row {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                flex-wrap: nowrap !important;
                margin: 0 !important;
            }

            /* Logo (25%) */
            .head-logo {
                flex: 0 0 25% !important;
                max-width: 25% !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
            }

            .head-logo img {
                max-width: 100%;
                height: auto;
                display: block !important;
            }

            /* Search Container (75%) */
            .head-search {
                flex: 0 0 75% !important;
                max-width: 75% !important;
                padding: 0 !important;
            }

            /* Internal Split to pull Icons closer to Search */
            .input-class {
                flex: 0 0 55% !important;
                max-width: 55% !important;
                padding-right: 20px !important;
                display: flex !important;
                align-items: center !important;
            }

            .head-right {
                flex: 0 0 45% !important;
                max-width: 45% !important;
                text-align: right !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-end !important;
            }

            .between-header {
                min-width: auto !important;
                width: 100% !important;
                margin: 0 !important;
                display: flex !important;
                align-items: center !important;
            }

            .top_cart {
                display: flex !important;
                justify-content: flex-end !important;
                align-items: center !important;
                flex-wrap: nowrap !important;
                margin: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }

            .top_cart li:not(.d-lg-none) {
                margin-left: 10px !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                height: 100% !important;
                position: relative !important;
            }

            .top_cart li.d-lg-none {
                display: none !important;
            }

            /* Precise Centering for Wishlist, Cart, and My Account */
            a.Price-amount,
            span.my_account {
                display: inline-flex !important;
                align-items: center !important;
                height: 40px !important;
                font-size: 11px !important;
                font-weight: 600 !important;
                padding-left: 30px !important;
                white-space: nowrap !important;
                line-height: normal !important;
                color: #333 !important;
                text-decoration: none !important;
                vertical-align: middle !important;
            }

            .my_account_link {
                padding: 0 !important;
                margin: 0 !important;
                text-decoration: none !important;
                height: 100% !important;
                display: flex !important;
                align-items: center !important;
            }

            .price_cart {
                top: -8px !important;
                left: 14px !important;
                transform: translateX(-50%) scale(0.9) !important;
                position: absolute !important;
            }

            /* Fixed Search Button and Input Alignment */
            .between-header .btn.btn-danger {
                width: auto !important;
                min-width: 80px !important;
                font-size: 11px !important;
                height: 38px !important;
                padding: 0 15px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 0 4px 4px 0 !important;
            }

            .between-header input {
                height: 38px !important;
                font-size: 11px !important;
                width: calc(100% - auto) !important;
                flex-grow: 1 !important;
                padding-left: 8px !important;
                border-radius: 4px 0 0 4px !important;
            }

            .main-menu li a {
                padding: 12px 3px !important;
                font-size: 11px !important;
            }

            .header_bottom {
                padding: 0 1.5% !important;
            }

            /* Account Dropdown for 1024px */
            .dropdown-menu.r_menu {
                top: 40px !important;
                right: 0 !important;
                left: auto !important;
                min-width: 180px !important;
                height: auto !important;
                display: none;
                overflow: visible !important;
                padding-bottom: 15px !important;
                z-index: 100000;
            }

            /* Mega Menu column adjustment for 1024px */
            .mega_menu .dropdown-menu .col-lg-4 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }
        }

        /* Main Menu Aesthetic Refinement (Professional Center) */
        @media (min-width: 992px) {
            .main-menu {
                justify-content: center !important;
                flex-wrap: wrap !important;
                gap: 6px !important;
                /* User requested 6px gap */
            }

            .main-menu li {
                margin: 0 !important;
            }

            .main-menu li a {
                padding: 15px 15px !important;
                font-size: 14px !important;
                letter-spacing: 0.2px;
            }

            .header_bottom {
                padding: 0 3% !important;
            }
        }

        @media (max-width: 1400px) and (min-width: 992px) {
            .main-menu {
                gap: 4px !important;
            }

            .main-menu li a {
                padding: 15px 12px !important;
                font-size: 13px !important;
            }
        }

        .owl-theme .owl-dots {
            display: block !important;
            visibility: visible !important;
            z-index: 10;
        }

        /* Global Account Dropdown Fix */
        .dropdown-menu.r_menu {
            height: auto !important;
            min-height: auto !important;
            /* Removed 100px forced height to reduce empty space */
            overflow: visible !important;
            padding: 10px 0 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            min-width: 180px !important;
            background-color: #fff !important;
            z-index: 99999 !important;
            display: none;
        }

        button.dropdown-item {
            width: 100% !important;
            text-align: left !important;
            background: none !important;
            border: none !important;
            padding: 8px 20px !important;
            outline: none !important;
            cursor: pointer !important;
            display: block !important;
        }

        .r_menu .dropdown-item {
            padding: 8px 20px !important;
            display: block !important;
        }

        .r_menu form {
            margin: 0 !important;
            padding: 0 !important;
            display: block !important;
        }

        button.dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #6EA820 !important;
        }

        .right1.dropdown {
            overflow: visible !important;
            height: auto !important;
        }

        /* My Account / Utility Hover logic for Desktop */
        @media (min-width: 992px) {
            .top_cart .dropdown:hover>.dropdown-menu {
                display: block !important;
                margin-top: 0;
            }

            .main-menu>li.dropdown:hover>.dropdown-menu {
                display: block !important;
            }

            #closeMenu,
            #menuToggle {
                display: none !important;
            }
        }

        .my_account_link {
            color: inherit !important;
            display: inline-block;
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
                                            Search
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
                            <li><a href="{{ route('blog.categories') }}">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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

            if (menuToggle && mainMenu) {
                menuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mainMenu.classList.add('show');
                    document.body.style.overflow = 'hidden';
                });
            }

            if (closeMenu && mainMenu) {
                closeMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mainMenu.classList.remove('show');
                    document.body.style.overflow = '';
                });
            }

            // Mobile Dropdown Toggle
            const dropdownToggles = document.querySelectorAll('.main-menu .dropdown > a');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    if (window.innerWidth < 992) {
                        e.preventDefault();
                        e.stopPropagation();
                        const parent = this.parentElement;
                        parent.classList.toggle('active');

                        // Optional: close other dropdowns
                        // const allDropdowns = document.querySelectorAll('.main-menu .dropdown');
                        // allDropdowns.forEach(d => { if(d !== parent) d.classList.remove('active'); });
                    }
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (mainMenu && mainMenu.classList.contains('show')) {
                    if (!mainMenu.contains(event.target) && !menuToggle.contains(event.target)) {
                        mainMenu.classList.remove('show');
                        document.body.style.overflow = '';
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