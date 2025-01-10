@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Produk Section -->
            <div class="col-lg-12">
                <!-- Flash Sale Banner -->
                @include('home.flash-sale')

                <!-- Kategori untuk Desktop dan Mobile secara horizontal -->
                <div class="col-12">
                    <div class="d-flex justify-content-start flex-wrap mb-4">
                        <!-- Add "All Categories" option -->
                        <a href="{{ route('home.index') }}"
                            class="btn {{ !request()->query('kategori') ? 'btn-custom' : 'btn-outline-custom' }} me-2 mb-2">
                            Semua Kategori
                        </a>

                        @foreach ($kategori as $kat)
                            <a href="{{ route('home.index', ['kategori' => $kat->slug]) }}"
                                class="btn {{ request()->query('kategori') == $kat->slug ? 'btn-custom' : 'btn-outline-custom' }} me-2 mb-2">
                                {{ $kat->nama_kategori }}
                            </a>
                        @endforeach
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
    </div>
@endsection
