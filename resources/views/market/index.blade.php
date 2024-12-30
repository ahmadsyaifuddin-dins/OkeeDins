@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Banner -->
        <div class="banner">
            <img src="{{ asset('images/banner.jpg') }}" alt="Promosi" class="img-fluid">
        </div>
        <!-- Kategori -->
        <h2 class="mt-4">Kategori Produk</h2>
        <div class="row">
            @foreach ($kategori as $kat)
                <div class="col-md-3">
                    <a href="{{ route('market.category', $kat->kategori_id) }}">
                        <div class="card">
                            <img src="{{ $kat->image }}" class="card-img-top" alt="{{ $kat->nama_kategori }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $kat->nama_kategori }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <!-- Produk Trending -->
        <h2 class="mt-4">Produk Trending</h2>
        <div class="row">
            @foreach ($trendingProduk as $trend)
                <div class="card" style="width: 18rem;">
                    <img src="{{ asset('storage/' . $trend->gambar) }}" class="card-img-top"
                        alt="{{ $trend->nama_produk }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $trend->nama_produk }}</h5>
                        <p class="card-text">{{ $trend->deskripsi }}</p>
                        <p class="card-text">Harga: Rp {{ number_format($trend->harga, 0, ',', '.') }}</p>
                        <a href="#" class="btn btn-primary">Beli Sekarang</a>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
