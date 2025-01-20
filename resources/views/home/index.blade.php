@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row">
        <!-- Produk Section -->
        <div class="col-lg-12">
            <!-- Flash Sale Banner -->
            @include('home.flash-sale')

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

            <!-- Produk Rekomendasi -->
            <h3 class="fw-bold mb-4">Produk Rekomendasi</h3>
            <div class="row">
                @forelse ($recommendedProducts as $prod)
                    @include('home.produk', ['produk' => $prod])
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada produk tersedia
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
