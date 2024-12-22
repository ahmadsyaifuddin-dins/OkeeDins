@extends('layouts.market_head')
@extends('components.svg')

@section('scripts')
    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-start",
                showConfirmButton: false,
                timer: 3000,
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
                    <span class="text-body-secondary">Rp. 50.000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">Fresh grapes</h6>
                        <small class="text-body-secondary">Brief description</small>
                    </div>
                    <span class="text-body-secondary">Rp. 50.000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">Heinz tomato ketchup</h6>
                        <small class="text-body-secondary">Brief description</small>
                    </div>
                    <span class="text-body-secondary">Rp. 100.000</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (IDR)</span>
                    <strong>Rp. 200.000</strong>
                </li>
            </ul>

            <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch" aria-labelledby="Search">
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


                <ul class="d-flex justify-content-end list-unstyled m-0">


                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#search"></use>
                            </svg>
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    @if (Auth::check())
                        <div class="text-end me-3 dropdown">
                            <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false"
                                style="cursor: pointer;">
                                <div>
                                    <span class="fs-6 text-muted">Welcome</span>
                                    <span class="fs-5 fw-bold d-block">{{ Auth::user()->name }}</span>
                                </div>
                                <!-- Custom dropdown arrow -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="ms-1">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </div>
                            <ul class="dropdown-menu shadow-sm border-0 rounded-3" style="min-width: 200px;">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2"
                                        href="{{ route('pelanggan.profile.show') }}">
                                        <svg width="18" height="18" class="me-2" viewBox="0 0 24 24">
                                            <use xlink:href="#user"></use>
                                        </svg>
                                        My Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" {{-- href="{{ route('wishlist.index') }}"> --}} <svg
                                        width="18" height="18" class="me-2" viewBox="0 0 24 24">
                                        <use xlink:href="#heart"></use>
                                        </svg>
                                        Wishlist
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center py-2"
                                            style="color: #dc3545 !important;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" class="me-2" fill="none" stroke="#dc3545"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                <polyline points="16 17 21 12 16 7"></polyline>
                                                <line x1="21" y1="12" x2="9" y2="12">
                                                </line>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="d-flex align-items-center text-decoration-none text-dark">
                            <div class="rounded-circle bg-light p-2 d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <use xlink:href="#user"></use>
                                </svg>
                            </div>
                            <span class="ms-2">Login/Register</span>
                        </a>
                    @endif
                </div>

                <!-- Add this floating cart button -->
                <div class="floating-cart" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
                    aria-controls="offcanvasCart">
                    <div class="cart-badge">3</div>
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <use xlink:href="#cart"></use>
                    </svg>
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
