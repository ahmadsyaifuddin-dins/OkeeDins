@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Banner Blocks -->
        <div class="banner-blocks my-4">
            <div class="banner-ad large bg-info block-1">
                <div class="swiper main-swiper">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        <div class="swiper-slide">
                            <div class="row banner-content p-5">
                                <div class="content-wrapper col-md-7">
                                    <div class="categories my-3">Promo Spesial</div>
                                    <h3 class="display-4">Produk Pilihan Terbaik</h3>
                                    <p>Temukan berbagai produk berkualitas dengan harga terbaik untuk Anda.</p>
                                    <a href="#"
                                        class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Belanja
                                        Sekarang</a>
                                </div>
                                <div class="img-wrapper col-md-5">
                                    <img src="{{ asset('images/banner.jpg') }}" class="img-fluid" alt="Banner 1">
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide">
                            <div class="row banner-content p-5">
                                <div class="content-wrapper col-md-7">
                                    <div class="categories mb-3 pb-3">Diskon 20%</div>
                                    <h3 class="banner-title">Produk Unggulan</h3>
                                    <p>Dapatkan penawaran spesial untuk produk-produk pilihan kami.</p>
                                    <a href="#"
                                        class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Lihat Koleksi</a>
                                </div>
                                <div class="img-wrapper col-md-5">
                                    <img src="{{ asset('images/banner-kecap-bango.png') }}" class="img-fluid"
                                        alt="Banner 2">
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="swiper-slide">
                            <div class="row banner-content p-5">
                                <div class="content-wrapper col-md-7">
                                    <div class="categories mb-3 pb-3">Produk Baru</div>
                                    <h3 class="banner-title">Koleksi Terbaru</h3>
                                    <p>Jelajahi koleksi terbaru kami dengan kualitas terjamin.</p>
                                    <a href="#"
                                        class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Lihat Koleksi</a>
                                </div>
                                <div class="img-wrapper col-md-5">
                                    <img src="{{ asset('images/banner-default.jpg') }}" class="img-fluid" alt="Banner 3">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
        <!-- / Banner Blocks -->

        <!-- Rest of the content... -->

        <!-- Kategori -->
        <h2 class="mt-4">Kategori Produk</h2>
        <div class="row g-4"> <!-- Tambahkan 'g-4' untuk memberikan space antar kartu -->
            @foreach ($kategori as $kat)
                <div class="col-md-3">
                    <a href="{{ route('market.category', $kat->id) }}">
                        <div class="card h-100"> <!-- 'h-100' untuk memastikan card memiliki tinggi konsisten -->
                            <img src="{{ $kat->gambar_kategori ? asset('storage/' . $kat->gambar_kategori) : asset('images/default-kategori.png') }}"
                                class="card-img-top" alt="{{ $kat->nama_kategori }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $kat->nama_kategori }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- resources/views/components/product-card.blade.php --}}
        @props(['produk', 'showDiscount' => false, 'showWishlist' => true])


        <!-- Trending Products -->
        <div class="bootstrap-tabs product-tabs">
            <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                <h3>Produk Trending</h3>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a href="#" class="nav-link text-uppercase fs-6 active" id="nav-all-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-all">Semua</a>
                        @foreach ($kategori as $kat)
                            <a href="#" class="nav-link text-uppercase fs-6"
                                id="nav-{{ Str::slug($kat->nama_kategori) }}-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-{{ Str::slug($kat->nama_kategori) }}">
                                {{ $kat->nama_kategori }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            </div>

            <div class="tab-content" id="nav-tabContent">
                <!-- All Products Tab -->
                <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                    <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                        @foreach ($trendingProduk as $trend)
                            <div class="col">
                                <div class="product-item">
                                    {{-- Discount Badge --}}
                                    @if ($showDiscount && $trend->diskon > 0)
                                        <span class="badge bg-success position-absolute m-3">-{{ $trend->diskon }}%</span>
                                    @endif

                                    {{-- Wishlist Button --}}
                                    @if ($showWishlist)
                                        <a href="#" class="btn-wishlist">
                                            <svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Product Image --}}
                                    <figure>
                                        <a href="{{ route('market.product-detail', $trend->id) }}"
                                            title="{{ $trend->nama_produk }}">
                                            <img src="{{ asset('storage/' . $trend->gambar) }}" class="tab-image"
                                                alt="{{ $trend->nama_produk }}" loading="lazy">
                                        </a>
                                    </figure>

                                    {{-- Product Info --}}
                                    <div class="product-info">
                                        <h3>{{ $trend->nama_produk }}</h3>
                                        <span class="qty">1 Unit</span>
                                        <span class="rating">
                                            <svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            {{ $trend->rating ?? '4.5' }}
                                        </span>
                                        <span class="price">Rp {{ number_format($trend->harga, 0, ',', '.') }}</span>

                                        {{-- Product Actions --}}
                                        <div class="product-actions">

                                            <div class="product-qty">
                                                <button type="button" class="quantity-left-minus btn btn-danger btn-number"
                                                    data-type="minus">
                                                    <svg width="16" height="16">
                                                        <use xlink:href="#minus"></use>
                                                    </svg>
                                                </button>
                                                <input type="text" id="quantity_{{ $trend->id }}" name="quantity"
                                                    class="form-control input-number" value="1" min="1">
                                                <button type="button"
                                                    class="quantity-right-plus btn btn-success btn-number"
                                                    data-type="plus">
                                                    <svg width="16" height="16">
                                                        <use xlink:href="#plus"></use>
                                                    </svg>
                                                </button>
                                            </div>
                                            <a href="#" class="btn-add-cart">
                                                <span>Tambah ke Keranjang</span>
                                                <iconify-icon icon="uil:shopping-cart"></iconify-icon>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach ($trendingProduk as $tren)
                                <x-product-card :product="$tren" />
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- Category Tabs -->
                @foreach ($kategori as $kat)
                    <div class="tab-pane fade" id="nav-{{ Str::slug($kat->nama_kategori) }}" role="tabpanel"
                        aria-labelledby="nav-{{ Str::slug($kat->nama_kategori) }}-tab">
                        <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                            @foreach ($trendingProduk->where('kategori_id', $kat->id) as $trend)
                                <div class="col">
                                    <!-- Same product card structure as above -->
                                    <div class="product-item">
                                        @if ($trend->diskon > 0)
                                            <span
                                                class="badge bg-success position-absolute m-3">-{{ $trend->diskon }}%</span>
                                        @endif
                                        <a href="#" class="btn-wishlist">
                                            <svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg>
                                        </a>
                                        <figure>
                                            <a href="{{ route('market.product-detail', $trend->id) }}"
                                                title="{{ $trend->nama_produk }}">
                                                <img src="{{ asset('storage/' . $trend->gambar) }}" class="tab-image"
                                                    alt="{{ $trend->nama_produk }}">
                                            </a>
                                        </figure>

                                        <h3>{{ $trend->nama_produk }}</h3>
                                        <span class="qty">1 Unit</span>
                                        <span class="rating">
                                            <svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg>
                                            {{ $trend->rating ?? '4.5' }}
                                        </span>
                                        <span class="price">Rp {{ number_format($trend->harga, 0, ',', '.') }}</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button"
                                                        class="quantity-left-minus btn btn-danger btn-number"
                                                        data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity_{{ $trend->id }}" name="quantity"
                                                    class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button"
                                                        class="quantity-right-plus btn btn-success btn-number"
                                                        data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">
                                                Tambah ke Keranjang
                                                <iconify-icon icon="uil:shopping-cart"></iconify-icon>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
