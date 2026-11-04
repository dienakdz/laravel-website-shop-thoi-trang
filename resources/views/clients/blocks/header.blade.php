<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="{{ asset('assets/clients/img/favicon.png') }}" type="image/png" />
    <title>{{ $title ?? 'Shop Thời Trang' }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/vendors/linericon/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/vendors/owl-carousel/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/vendors/lightbox/simpleLightbox.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/vendors/nice-select/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/vendors/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/vendors/jquery-ui/jquery-ui.css') }}" />
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/clients/css/custom-style.css') }}" />

    @if (request()->routeIs('login') === true)
        <link rel="stylesheet" href="{{ asset('assets/clients/css/login.css') }}" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css"
            integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
    @endif
    @if (request()->routeIs('admin.login') === true)
        <link rel="stylesheet" href="{{ asset('assets/clients/css/login.css') }}" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css"
            integrity="sha384-3AB7yXWz4OeoZcPbieVW64vVXEwADiYyAEhwilzWsLw+9FgqpyjjStpPnpBO8o8S" crossorigin="anonymous">
    @endif
    @if (request()->routeIs('about') === true)
        <link href='https://fonts.googleapis.com/css?family=Josefin Sans' rel='stylesheet'>
    @endif
    @yield('css')
</head>

<body>
    <!--================Header Menu Area =================-->
    <header class="header_area">
        <div class="top_menu">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-7">
                        <div class="float-left">
                            <p><i class="fa fa-phone"></i> Hotline: <strong>0967.468.703</strong></p>
                            <p><i class="fa fa-envelope"></i> Email: support@shopthoitrang.vn</p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <div class="float-right text-right d-none d-md-block">
                            <p><i class="fa fa-truck"></i> Miễn phí giao hàng cho đơn từ 500k</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main_menu">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light w-100">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="{{ route('home') }}">
                        <img src="{{ asset('assets/clients/img/logo.png') }}" alt="Shop Thời Trang" />
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset w-100" id="navbarSupportedContent">
                        <div class="row w-100 mr-0">
                            <div class="col-lg-7 pr-0">
                                <ul class="nav navbar-nav center_nav pull-right">
                                    <li class="nav-item {{ Request::routeIs('home') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                                    </li>
                                    <li class="nav-item submenu dropdown {{ Request::routeIs('product') ? 'active' : '' }}">
                                        <a href="{{ route('product') }}" class="nav-link dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false">
                                            Sản phẩm <i class="fa fa-angle-down ml-1"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="nav-item">
                                                <a class="nav-link font-weight-bold" href="{{ route('product') }}">Tất cả sản phẩm</a>
                                            </li>
                                            @php
                                                $headerCategories = function_exists('getAllCategories') ? getAllCategories() : collect();
                                            @endphp
                                            @foreach ($headerCategories as $cat)
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('product', ['category' => $cat->CategoryID]) }}">
                                                        {{ $cat->CategoryName }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="nav-item {{ Request::routeIs('about') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('about') }}">Giới thiệu</a>
                                    </li>
                                    <li class="nav-item {{ Request::routeIs('contact') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('contact') }}">Liên hệ</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-5 pr-0">
                                <ul class="nav navbar-nav navbar-right right_nav pull-right">
                                    <!-- Thanh tìm kiếm -->
                                    <li class="nav-item">
                                        <form class="icons" action="{{ route('search') }}" method="GET">
                                            <i class="ti-search new-search-icon" aria-hidden="true"></i>
                                            <i class="ti-search search-icon" aria-hidden="true"></i>
                                            <input type="text" name="search" class="input-search" placeholder="Tìm sản phẩm..." value="{{ request('search') }}">
                                            <i class="ti-close close-icon" aria-hidden="true"></i>
                                            <i class="fa fa-microphone voice-search-icon" aria-hidden="true"></i>
                                        </form>
                                    </li>

                                    <!-- Giỏ hàng -->
                                    <li class="nav-item">
                                        <a href="{{ route('carts') }}" class="icons" title="Giỏ hàng">
                                            <span class="cart-icon-box">
                                                <i class="ti-shopping-cart"></i>
                                                @php
                                                    $cartQty = function_exists('getCartQuantity') ? getCartQuantity() : 0;
                                                @endphp
                                                @if ($cartQty > 0)
                                                    <span class="cart-badge">{{ $cartQty }}</span>
                                                @endif
                                            </span>
                                        </a>
                                    </li>

                                    <!-- Menu Tài khoản -->
                                    @if (session('username'))
                                        <li class="nav-item submenu dropdown user-nav-item">
                                            <a href="#" class="icons user-nav-link" role="button" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti-user mr-1"></i>
                                                <span class="user-name-text">{{ session('username') }}</span>
                                                <i class="fa fa-angle-down ml-1"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('information') }}">
                                                        <i class="fa fa-user-circle mr-2 text-primary"></i> Thông tin cá nhân
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('ordered') }}">
                                                        <i class="fa fa-shopping-bag mr-2 text-success"></i> Đơn mua của tôi
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link text-danger" href="{{ route('logout') }}">
                                                        <i class="fa fa-sign-out mr-2"></i> Đăng xuất
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @else
                                        <li class="nav-item submenu dropdown user-nav-item">
                                            <a href="#" class="icons user-nav-link" title="Tài khoản" role="button" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti-user"></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('login') }}">
                                                        <i class="fa fa-sign-in mr-2 text-primary"></i> Đăng nhập / Đăng ký
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('admin.login') }}">
                                                        <i class="fa fa-shield mr-2 text-warning"></i> Quản trị viên
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!--================Header Menu Area =================-->
