<div class="row">
    @foreach ($recommendedProducts as $recom)
        <div class="col-lg-3 col-md-4 col-6 mb-4">
            <a href="{{ route('produk.detail', $recom->slug) }}" class="text-decoration-none">
                {{-- <a href="#" class="text-decoration-none"> --}}
                <div class="card h-100 product-card shadow-sm position-relative">
                    <!-- Wishlist Button -->
                    @auth
                        @if(Auth::user()->wishlists->contains('produk_id', $recom->id))
                            <form action="{{ route('wishlist.destroy', Auth::user()->wishlists->where('produk_id', $recom->id)->first()) }}" 
                                  method="POST" class="d-inline" onsubmit="confirmAddToWishlist(event, this)">
                            @csrf
                            @method('DELETE')
                        @else
                            <form action="{{ route('wishlist.store') }}" method="POST" class="d-inline" 
                                  onsubmit="confirmAddToWishlist(event, this)">
                            @csrf
                        @endif
                        <input type="hidden" name="produk_id" value="{{ $recom->id }}">
                        <button type="submit" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn"
                            onclick="event.stopPropagation();" style="min-width: 32px;">
                            <i class="bi bi-heart{{ Auth::user()->wishlists->contains('produk_id', $recom->id) ? '-fill text-danger' : '' }}"
                               style="{{ Auth::user()->wishlists->contains('produk_id', $recom->id) ? 'font-size: 1rem;' : '' }}"></i>
                        </button>
                    </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn"
                           onclick="event.stopPropagation();">
                            <i class="bi bi-heart"></i>
                        </a>
                    @endauth

                    <!-- Discount Badge -->
                    @if ($recom->diskon > 0)
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-danger">{{ $recom->diskon }}%</span>
                        </div>
                    @endif

                    <!-- Product Image -->
                    <img loading="lazy" src="{{ asset('storage/' . $recom->gambar) }}" class="card-img-top img-fluslug"
                        alt="{{ $recom->nama_produk }}">

                    <!-- Product Content -->
                    <div class="card-body">
                        <h5 class="card-title h6 text-dark">{{ $recom->nama_produk }}</h5>

                        <!-- Price Section -->
                        <div class="price-section mt-1 mb-1">
                            <div class="d-flex flex-wrap align-items-center">
                                <p class="card-text fw-bold text-custom mb-0 me-2" style="font-size: 0.95rem;">
                                    Rp{{ number_format($recom->harga_diskon, 0, ',', '.') }}
                                </p>

                                @if ($recom->diskon > 0)
                                    <p class="card-text text-muted mb-0"
                                        style="font-size: 0.75rem; text-decoration: line-through; white-space: nowrap;">
                                        Rp{{ number_format($recom->harga, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Rating and Sales -->
                        <div class="d-flex align-items-center">
                            <div class="rating me-1">
                                <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                                <span class="ms-1" style="font-size: 0.75rem;">4.8</span>
                                <span class="text-muted" style="font-size: 0.75rem;">• 1rb+ terjual</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
