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
                        @foreach ($kategori as $kat)
                            <a href="{{ route('market.kategori', $kat->id) }}" class="btn btn-outline-primary me-2 mb-2">
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
                    @foreach ($recommendedProducts as $prod)
                        @include('home.produk', ['produk' => $prod]) <!-- Pastikan produk dikirim dengan benar -->
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
