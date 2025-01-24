@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row">
        <!-- Produk Section -->
        <div class="col-lg-12">
            <!-- Banner -->
            @include('home.banner')

            <!-- Kategori Slider -->
            <div class="col-12">
                <div class="swiper kategoriSwiper mb-4">
                    <div class="swiper-wrapper">
                        <!-- Semua Kategori -->
                        <div class="swiper-slide">
                            <a href="{{ route('home.index') }}"
                                class="btn {{ !request()->query('kategori') ? 'btn-custom' : 'btn-outline-custom' }} w-100">
                                Semua Kategori
                            </a>
                        </div>

                        <!-- Kategori Items -->
                        @foreach ($kategori as $kat)
                            <div class="swiper-slide">
                                <a href="{{ route('home.index', ['kategori' => $kat->slug]) }}"
                                    class="btn {{ request()->query('kategori') == $kat->slug ? 'btn-custom' : 'btn-outline-custom' }} w-100">
                                    {{ $kat->nama_kategori }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <!-- Add Navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <!-- Produk Rekomendasi -->
            @include('home.recommended')

        </div>
    </div>
@endsection
