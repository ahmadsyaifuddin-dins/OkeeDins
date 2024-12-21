@extends('layouts.market_head')


@section('scripts')
    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif
@endsection





<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <defs>
        <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
            <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
            <path fill="currentColor"
                d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
            <path fill="currentColor"
                d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
            <path fill="currentColor"
                d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
        </symbol>
    </defs>
</svg>

<div class="preloader-wrapper">
    <div class="preloader">
    </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="My Cart">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Your cart</span>
                <span class="badge bg-primary rounded-pill">3</span>
            </h4>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">Growers cider</h6>
                        <small class="text-body-secondary">Brief description</small>
                    </div>
                    <span class="text-body-secondary">$12</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">Fresh grapes</h6>
                        <small class="text-body-secondary">Brief description</small>
                    </div>
                    <span class="text-body-secondary">$8</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">Heinz tomato ketchup</h6>
                        <small class="text-body-secondary">Brief description</small>
                    </div>
                    <span class="text-body-secondary">$5</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong>$20</strong>
                </li>
            </ul>

            <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch"
    aria-labelledby="Search">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Search</span>
            </h4>
            <form role="search" action="#" method="get" class="d-flex mt-3 gap-0">
                <input class="form-control rounded-start rounded-0 bg-light" type="email"
                    placeholder="What are you looking for?" aria-label="What are you looking for?">
                <button class="btn btn-dark rounded-end rounded-0" type="submit">Search</button>
            </form>
        </div>
    </div>
</div>

<header>
    <div class="container-fluid">
        <div class="row py-3 border-bottom">

            <div class="col-sm-4 col-lg-3 text-center text-sm-start">
                <div class="main-logo">
                    <a href="#">
                        <img src="food_fusion/images/logo2.png" alt="logo" class="img-fluid">
                    </a>
                </div>
            </div>

            <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
                <div class="search-bar row bg-light p-2 my-2 rounded-4">
                    <div class="col-md-4 d-none d-md-block">
                        <select class="form-select border-0 bg-transparent">
                            <option>All Categories</option>
                            <option>Groceries</option>
                            <option>Drinks</option>
                            <option>Chocolates</option>
                        </select>
                    </div>
                    <div class="col-11 col-md-7">
                        <form id="search-form" class="text-center" action="#" method="post">
                            <input type="text" class="form-control border-0 bg-transparent"
                                placeholder="Search for more than 20,000 products" />
                        </form>
                    </div>
                    <div class="col-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
                <div class="support-box text-end d-none d-xl-block">
                    <span class="fs-6 text-muted">For Support?</span>
                    <h5 class="mb-0">+980-34984089</h5>
                </div>

                <ul class="d-flex justify-content-end list-unstyled m-0">
                    <li>
                        <a href="{{ route('register') }}" class="rounded-circle bg-light p-2 mx-1">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#user"></use>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="rounded-circle bg-light p-2 mx-1">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#heart"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#cart"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#search"></use>
                            </svg>
                        </a>
                    </li>
                </ul>

                <div class="cart text-end d-none d-lg-block dropdown">
                    <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                        <span class="fs-6 text-muted dropdown-toggle">Your Cart</span>
                        <span class="cart-total fs-5 fw-bold">$1290.00</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row py-3">
            <div class="d-flex  justify-content-center justify-content-sm-between align-items-center">
                <nav class="main-menu d-flex navbar navbar-expand-lg">

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">

                        <div class="offcanvas-header justify-content-center">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">

                            <select class="filter-categories border-0 mb-0 me-5">
                                <option>Shop by Departments</option>
                                <option>Groceries</option>
                                <option>Drinks</option>
                                <option>Chocolates</option>
                            </select>

                            <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                <li class="nav-item active">
                                    <a href="#women" class="nav-link">Women</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#men" class="nav-link">Men</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#kids" class="nav-link">Kids</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#accessories" class="nav-link">Accessories</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" id="pages"
                                        data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                                    <ul class="dropdown-menu" aria-labelledby="pages">
                                        <li><a href="#" class="dropdown-item">About Us </a></li>
                                        <li><a href="#" class="dropdown-item">Shop </a></li>
                                        <li><a href="#" class="dropdown-item">Single Product </a></li>
                                        <li><a href="#" class="dropdown-item">Cart </a></li>
                                        <li><a href="#" class="dropdown-item">Checkout </a></li>
                                        <li><a href="#" class="dropdown-item">Blog </a></li>
                                        <li><a href="#" class="dropdown-item">Single Post </a></li>
                                        <li><a href="#" class="dropdown-item">Styles </a></li>
                                        <li><a href="#" class="dropdown-item">Contact </a></li>
                                        <li><a href="#" class="dropdown-item">Thank You </a></li>
                                        <li><a href="#" class="dropdown-item">My Account </a></li>
                                        <li><a href="#" class="dropdown-item">404 Error </a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#brand" class="nav-link">Brand</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sale" class="nav-link">Sale</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#blog" class="nav-link">Blog</a>
                                </li>
                            </ul>

                        </div>

                    </div>
            </div>
        </div>
    </div>
</header>

<section class="py-3"
    style="background-image: url('food_fusion/images/background-pattern.jpg');background-repeat: no-repeat;background-size: cover;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="banner-blocks">

                    <div class="banner-ad large bg-info block-1">

                        <div class="swiper main-swiper">
                            <div class="swiper-wrapper">

                                <div class="swiper-slide">
                                    <div class="row banner-content p-5">
                                        <div class="content-wrapper col-md-7">
                                            <div class="categories my-3">100% natural</div>
                                            <h3 class="display-4">Fresh Smoothie & Summer Juice</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim
                                                massa diam elementum.</p>
                                            <a href="#"
                                                class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Shop
                                                Now</a>
                                        </div>
                                        <div class="img-wrapper col-md-5">
                                            <img src="food_fusion/images/product-thumb-1.png" class="img-fluid">
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="row banner-content p-5">
                                        <div class="content-wrapper col-md-7">
                                            <div class="categories mb-3 pb-3">100% natural</div>
                                            <h3 class="banner-title">Fresh Smoothie & Summer Juice</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim
                                                massa diam elementum.</p>
                                            <a href="#"
                                                class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop
                                                Collection</a>
                                        </div>
                                        <div class="img-wrapper col-md-5">
                                            <img src="food_fusion/images/product-thumb-1.png" class="img-fluid">
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="row banner-content p-5">
                                        <div class="content-wrapper col-md-7">
                                            <div class="categories mb-3 pb-3">100% natural</div>
                                            <h3 class="banner-title">Heinz Tomato Ketchup</h3>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dignissim
                                                massa diam elementum.</p>
                                            <a href="#"
                                                class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Shop
                                                Collection</a>
                                        </div>
                                        <div class="img-wrapper col-md-5">
                                            <img src="food_fusion/images/product-thumb-2.png" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="swiper-pagination"></div>

                        </div>
                    </div>

                    <div class="banner-ad bg-success-subtle block-2"
                        style="background:url('food_fusion/images/ad-image-1.png') no-repeat;background-position: right bottom">
                        <div class="row banner-content p-5">

                            <div class="content-wrapper col-md-7">
                                <div class="categories sale mb-3 pb-3">20% off</div>
                                <h3 class="banner-title">Fruits & Vegetables</h3>
                                <a href="#" class="d-flex align-items-center nav-link">Shop Collection <svg
                                        width="24" height="24">
                                        <use xlink:href="#arrow-right"></use>
                                    </svg></a>
                            </div>

                        </div>
                    </div>

                    <div class="banner-ad bg-danger block-3"
                        style="background:url('food_fusion/images/ad-image-2.png') no-repeat;background-position: right bottom">
                        <div class="row banner-content p-5">

                            <div class="content-wrapper col-md-7">
                                <div class="categories sale mb-3 pb-3">15% off</div>
                                <h3 class="item-title">Baked Products</h3>
                                <a href="#" class="d-flex align-items-center nav-link">Shop Collection <svg
                                        width="24" height="24">
                                        <use xlink:href="#arrow-right"></use>
                                    </svg></a>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- / Banner Blocks -->

            </div>
        </div>
    </div>
</section>

<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                    <h2 class="section-title">Category</h2>

                    <div class="d-flex align-items-center">
                        <a href="#" class="btn-link text-decoration-none">View All Categories →</a>
                        <div class="swiper-buttons">
                            <button class="swiper-prev category-carousel-prev btn btn-yellow">❮</button>
                            <button class="swiper-next category-carousel-next btn btn-yellow">❯</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="category-carousel swiper">
                    <div class="swiper-wrapper">
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-bread-baguette.png" alt="Category Thumbnail">
                            <h3 class="category-title">Breads & Sweets</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-soft-drinks-bottle.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-wine-glass-bottle.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-animal-products-drumsticks.png"
                                alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-bread-herb-flour.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>
                        <a href="#" class="nav-link category-item swiper-slide">
                            <img src="food_fusion/images/icon-vegetables-broccoli.png" alt="Category Thumbnail">
                            <h3 class="category-title">Fruits & Veges</h3>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="section-header d-flex flex-wrap flex-wrap justify-content-between mb-5">

                    <h2 class="section-title">Newly Arrived Brands</h2>

                    <div class="d-flex align-items-center">
                        <a href="#" class="btn-link text-decoration-none">View All Categories →</a>
                        <div class="swiper-buttons">
                            <button class="swiper-prev brand-carousel-prev btn btn-yellow">❮</button>
                            <button class="swiper-next brand-carousel-next btn btn-yellow">❯</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="brand-carousel swiper">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="food_fusion/images/product-thumb-11.jpg" class="img-fluid rounded"
                                            alt="Card title">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body py-0">
                                            <p class="text-muted mb-0">Amber Jar</p>
                                            <h5 class="card-title">Honey best nectar you wish to get</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="food_fusion/images/product-thumb-12.jpg" class="img-fluid rounded"
                                            alt="Card title">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body py-0">
                                            <p class="text-muted mb-0">Amber Jar</p>
                                            <h5 class="card-title">Honey best nectar you wish to get</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="food_fusion/images/product-thumb-13.jpg" class="img-fluid rounded"
                                            alt="Card title">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body py-0">
                                            <p class="text-muted mb-0">Amber Jar</p>
                                            <h5 class="card-title">Honey best nectar you wish to get</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="food_fusion/images/product-thumb-14.jpg" class="img-fluid rounded"
                                            alt="Card title">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body py-0">
                                            <p class="text-muted mb-0">Amber Jar</p>
                                            <h5 class="card-title">Honey best nectar you wish to get</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="food_fusion/images/product-thumb-11.jpg" class="img-fluid rounded"
                                            alt="Card title">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body py-0">
                                            <p class="text-muted mb-0">Amber Jar</p>
                                            <h5 class="card-title">Honey best nectar you wish to get</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card mb-3 p-3 rounded-4 shadow border-0">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <img src="food_fusion/images/product-thumb-12.jpg" class="img-fluid rounded"
                                            alt="Card title">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body py-0">
                                            <p class="text-muted mb-0">Amber Jar</p>
                                            <h5 class="card-title">Honey best nectar you wish to get</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@extends('layouts.market_footer')
@extends('layouts.trending_products')
@extends('layouts.most_popular_products')
@extends('layouts.best_selling_products')









<script src="{{ asset('food_fusion/js/jquery-1.11.0.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>
<script src="{{ asset('food_fusion/js/plugins.js') }}"></script>
<script src="{{ asset('food_fusion/js/script.js') }}"></script>

</body>

</html>
