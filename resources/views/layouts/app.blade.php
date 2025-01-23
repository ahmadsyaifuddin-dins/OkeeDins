<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Food Fusion' }}</title>
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

        @livewireStyles

    <style>
        /* Atur di file style-market.css atau CSS lainnya */

        /* Desktop */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Tablet dan Mobile */
        @media (max-width: 768px) {
            .container .px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        /* Mobile */
        @media (max-width: 576px) {
            .container .px-5 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }
    </style>


</head>

<body>
    @livewireScripts
    @include('layouts.navbar')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 col-lg-12 mx-auto"> <!-- Mengatur lebar konten berdasarkan ukuran layar -->
                <div class="px-5 px-md-5 mt-4">
                    @yield('content')
                    @stack('scripts')
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    </div>
    @include('layouts.bottom-nav')

    <!-- Tempatkan untuk script tambahan -->
    @yield('scripts')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tambahkan pustaka toastr -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/market.js') }}"></script>
    <script src="{{ asset('js/wishlist.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @if (session('success'))
        <meta name="success-message" content="{{ session('success') }}">
    @endif


    @if ($errors->any())
        <meta name="error-message" content="{{ $errors->first() }}">
    @endif


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
