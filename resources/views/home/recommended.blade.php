<div class="row">
    @foreach ($recommendedProducts as $recom)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card h-100 product-card shadow-sm position-relative">
                <!-- Tombol Wishlist -->
                <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn">
                    <i class="bi bi-heart"></i>
                </button>
                <!-- Gambar Produk -->
                <img src="{{ asset('storage/' . $recom->gambar) }}" class="card-img-top" alt="{{ $recom->nama_produk }}">
                <!-- Konten Produk -->
                <div class="card-body">
                    <h5 class="card-title h6 text-truncate">{{ $recom->nama_produk }}</h5>
                    <p class="card-text mt-1 fw-bold text-primary">Rp{{ number_format($recom->harga, 0, ',', '.') }}</p>
                    <!-- Rating dan Jumlah Terjual -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="rating me-1">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </div>
                        <span class="text-muted small">| 100 terjual</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('market.product-detail', $recom->id) }}"
                            class="btn btn-sm btn-outline-primary">Lihat</a>
                        <button class="btn btn-sm btn-primary">Keranjang</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
