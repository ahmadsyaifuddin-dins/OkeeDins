@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Hasil Pencarian</h4>
                @if ($searchQuery)
                    <p class="text-muted mb-0">Menampilkan hasil untuk "{{ $searchQuery }}"</p>
                @endif
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="text-center py-5">
                <img src="/images/empty-search.svg" alt="No Results" class="mb-3" style="width: 150px">
                <h5>Tidak ada hasil</h5>
                <p class="text-muted">Coba kata kunci lain atau periksa ejaan</p>
                <a href="{{ route('home.index') }}" class="btn btn-custom">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100 product-card shadow-sm position-relative">
                            <!-- Wishlist Button -->
                            @auth
                                @if (Auth::user()->wishlist->contains('produk_id', $product->id))
                                    <form
                                        action="{{ route('wishlist.destroy',Auth::user()->wishlist->where('produk_id', $product->id)->first()) }}"
                                        method="POST" class="d-inline" onsubmit="confirmAddToWishlist(event, this)">
                                        @csrf
                                        @method('DELETE')
                                    @else
                                        <form action="{{ route('wishlist.store') }}" method="POST" class="d-inline">
                                            @csrf
                                @endif
                                <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                <button type="submit"
                                    class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn"
                                    onclick="event.stopPropagation();" style="min-width: 32px;">
                                    <i class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? '-fill text-danger' : '' }}"
                                        style="{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? 'font-size: 1rem;' : '' }}"></i>
                                </button>
                                </form>
                            @endauth

                            <!-- Product Image -->
                            <a href="{{ route('produk.detail', $product->slug) }}" class="text-decoration-none">
                                <img src="{{ asset('storage/' . $product->gambar) }}" class="card-img-top"
                                    alt="{{ $product->nama_produk }}" style="height: 200px; object-fit: cover;">
                            </a>

                            <div class="card-body p-3">
                                <h6 class="card-title mb-1 text-truncate">
                                    <a href="{{ route('produk.detail', $product->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $product->nama_produk }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-2">{{ $product->kategori->nama_kategori }}</p>

                                @if ($product->diskon > 0)
                                    <p class="card-text mb-1">
                                        <span class="text-danger">
                                            Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                                        </span>
                                        <small class="text-decoration-line-through text-muted">
                                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                                        </small>
                                    </p>
                                    <small class="text-danger">{{ $product->diskon }}% OFF</small>
                                @else
                                    <p class="card-text text-danger mb-0">
                                        Rp{{ number_format($product->harga, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation" class="pagination-wrapper">
                    {{ $products->links('vendor.pagination.custom') }}
                </nav>
            </div>
        @endif
    </div>
@endsection
