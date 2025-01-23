<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Food Fusion' }}</title>
    
    <style>
        /* Loading styles */
        #loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #D32F2F;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #content {
            display: none;
        }

        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
            width: 100%;
        }

        /* Swiper container fix */
        .swiper-container {
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Content container */
        .content-wrapper {
            padding: 1rem;
            overflow-x: hidden;
            max-width: 100%;
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 0.5rem;
            }
            
            /* Adjust container padding */
            .container-fluid {
                padding-left: 8px;
                padding-right: 8px;
            }

            /* Fix row margins */
            .row {
                margin-left: -4px;
                margin-right: -4px;
            }
            
            .col-6, .col-12 {
                padding-left: 4px;
                padding-right: 4px;
            }
        }

        /* Product Card Styling */
        .product-card {
            transition: transform 0.2s ease;
            height: 100%;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
        }

        .product-card .card-img-top {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .product-card .card-body {
            padding: 0.8rem;
        }

        .product-card .card-title {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 0.5rem;
            }

            .product-card .card-img-top {
                height: 130px;
            }
            
            .product-card .card-body {
                padding: 0.5rem;
            }
            
            .product-card .card-title {
                font-size: 0.8rem;
                margin-bottom: 0.3rem;
                -webkit-line-clamp: 2;
            }
            
            .product-card .price-section {
                margin: 0.2rem 0;
            }

            .product-card .price-section .fw-bold {
                font-size: 0.9rem !important;
            }
            
            .product-card .price-section .text-muted {
                font-size: 0.7rem !important;
            }
            
            .col-6 {
                padding-left: 4px;
                padding-right: 4px;
            }
            
            .row {
                margin-left: -4px;
                margin-right: -4px;
            }

            .mb-4 {
                margin-bottom: 0.5rem !important;
            }
        }

        /* Container max-width override for different screens */
        @media (min-width: 576px) {
            .container {
                max-width: 540px;
                padding: 0 15px;
            }
        }
        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }
        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }
        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }
    </style>
</head>

<body>
    <div id="loading">
        <div class="spinner"></div>
    </div>

    <div id="content">
        @include('layouts.navbar')
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-12">
                    <div class="content-wrapper">
                        @yield('content')
                        @stack('scripts')
                        @include('layouts.footer')
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.bottom-nav')

        @yield('scripts')

        @if (session('success'))
            <meta name="success-message" content="{{ session('success') }}">
        @endif

        @if ($errors->any())
            <meta name="error-message" content="{{ $errors->first() }}">
        @endif
    </div>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        });
    </script>

    <link rel="stylesheet" href="{{ asset('css/style-market.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar-market.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bottom-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-pelanggan.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/market.js') }}"></script>
    <script src="{{ asset('js/wishlist.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".kategoriSwiper", {
            slidesPerView: 'auto',
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 2,
                },
                480: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 4,
                },
                1024: {
                    slidesPerView: 6,
                },
            }
        });
    </script>
</body>

</html>
