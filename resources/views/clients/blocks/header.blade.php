<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" href="{{ asset('assets/clients/img/favicon.png') }}" type="image/png" />
    <title>{{ $title }}</title>
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
                    <div class="col-lg-7">
                        <div class="float-left">
                            <p>Phone: +0967468703</p>
                            <p>email: minhdien678@gmail.com</p>
                        </div>
                    </div>
                    <div class="col-lg-5">

                    </div>
                </div>
            </div>
        </div>
        <div class="main_menu">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light w-100">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="{{ route('home') }}">
                        <img src="{{ asset('assets/clients/img/logo.png') }}" alt="" />
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
                                    <li class="nav-item {{ Request::url() == route('home') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                                    </li>
                                    <li
                                        class="nav-item submenu dropdown {{ Request::url() == route('product') ? 'active' : '' }}">
                                        <a href="{{ route('product') }}" class="nav-link dropdown-toggle"
                                            role="button" aria-haspopup="true" aria-expanded="false">Sản phẩm</a>
                                    </li>
                                    <li
                                        class="nav-item submenu dropdown {{ Request::url() == route('about') ? 'active' : '' }}">
                                        <a href="{{ route('about') }}" class="nav-link dropdown-toggle" role="button"
                                            aria-haspopup="true" aria-expanded="false">Giới thiệu</a>
                                    </li>
                                    <li class="nav-item {{ Request::url() == route('contact') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('contact') }}">Liên hệ</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-lg-5 pr-0">
                                <ul class="nav navbar-nav navbar-right right_nav pull-right">
                                    <li class="nav-item">
                                        <form class="icons" action="{{ route('search') }}" method="GET">
                                            <i class="ti-search new-search-icon" aria-hidden="true"></i>
                                            <i class="ti-search search-icon" aria-hidden="true"></i>
                                            <input type="text" name="search" class="input-search">
                                            <i class="ti-close close-icon" aria-hidden="true"></i>
                                            <i class="fa fa-microphone voice-search-icon" aria-hidden="true"></i>
                                        </form>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('carts') }}" class="icons">
                                            <i class="ti-shopping-cart"></i>
                                        </a>
                                    </li>

                                    <li class="nav-item submenu dropdown">
                                        <a class="icons">
                                            <i class="ti-user" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            @if (session('username'))
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('logout') }}">Đăng xuất</a>
                                                </li>
                                            @else
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('admin.login') }}">Admin</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                    <li></li>

                                    <li class="person nav-item submenu dropdown">
                                        @if (session('username'))
                                            <a class="nav-link ">Chào {{ session('username') }}</a>
                                            <ul class="dropdown-menu">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('information') }}">Cá Nhân</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('ordered') }}">Đơn Mua</a>
                                                </li>
                                            </ul>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!--================Header Menu Area =================-->
