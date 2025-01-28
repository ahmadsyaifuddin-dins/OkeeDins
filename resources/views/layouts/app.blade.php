<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Food Fusion' }}</title>

    @include('layouts.styles')

</head>

<body>
    
    <div id="content">
    {{-- @include('layouts.loader') --}}
        @include('layouts.navbar')
        <div class="container-fluid px-0 px-lg-5">
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

        @include('layouts.flash-messages')
    </div>

    @include('layouts.scripts')

    <script>
        window.addEventListener('load', function() {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        });

        var swiper = new Swiper(".kategoriSwiper", {
            slidesPerView: 'auto',
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 2
                },
                480: {
                    slidesPerView: 3
                },
                768: {
                    slidesPerView: 4
                },
                1024: {
                    slidesPerView: 6
                },
            }
        });
    </script>
</body>

</html>
