<div class="row">
    @foreach ($recommendedProducts as $recom)
        <div class="col-lg-3 col-md-4 col-6 mb-4">
            <a href="{{ route('produk.detail', $recom->slug) }}" class="text-decoration-none">
                {{-- <a href="#" class="text-decoration-none"> --}}
                <div class="card h-100 product-card shadow-sm position-relative">
                    <!-- Wishlist Button -->
                    <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn"
                        onclick="event.stopPropagation();">
                        <i class="bi bi-heart"></i>
                    </button>

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
                        <h5 class="card-title h6 text-truncate text-dark">{{ $recom->nama_produk }}</h5>

                        <!-- Price Section -->
                        <div class="price-section mt-1 mb-1">
                            <p class="card-text fw-bold text-custom mb-0">
                                Rp{{ number_format($recom->harga_diskon, 0, ',', '.') }}
                            </p>

                            @if ($recom->diskon > 0)
                                <p class="card-text text-muted mb-1"
                                    style="text-decoration: line-through; font-size: 0.9rem;">
                                    Rp{{ number_format($recom->harga, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>

                        <!-- Rating and Sales -->
                        <div class="d-flex align-items-center">
                            <div class="rating me-1">
                                <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                                <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                                <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                                <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                                <i class="bi bi-star-half text-warning" style="font-size: 0.8rem;"></i>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem;">| 100+ terjual</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
