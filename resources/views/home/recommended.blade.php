<div class="row">
    @foreach ($recommendedProducts as $recom)
        {{-- <div class="col-lg-3 col-md-2 col-sm-6 mb-4"> --}}
        <div class="col-lg-3 col-md-4 col-6 mb-4">
            <div class="card h-100 product-card shadow-sm position-relative">
                <!-- Tombol Wishlist -->
                <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 shadow-sm wishlist-btn">
                    <i class="bi bi-heart"></i>
                </button>
                <!-- Gambar Produk -->
                <img loading="lazy" src="{{ asset('storage/' . $recom->gambar) }}" class="card-img-top img-fluid"
                    alt="{{ $recom->nama_produk }}">
                <!-- Konten Produk -->
                <div class="card-body">
                    <h5 class="card-title h6 text-truncate">{{ $recom->nama_produk }}</h5>
                    <p class="card-text mt-1 mb-1 fw-bold text-custom">Rp{{ number_format($recom->harga, 0, ',', '.') }}
                    </p>
                    <!-- Rating dan Jumlah Terjual -->
                    <div class="d-flex align-items-center mb-2">
                        <div class="rating me-1">
                            <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                            <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                            <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                            <i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i>
                            <i class="bi bi-star-half text-warning" style="font-size: 0.8rem;"></i>
                        </div>
                        <span class="text-muted" style="font-size: 0.75rem;">| 100 terjual</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <button class="btn btn-sm btn-primary"><i class="bi bi-cart"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
