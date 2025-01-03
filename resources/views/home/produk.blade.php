<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 product-card shadow-sm">
        <img loading="lazy" src="{{ asset('storage/' . $prod->gambar) }}" class="card-img-top"
            alt="{{ $prod->nama_produk }}">
        <div class="card-body">
            <h5 class="card-title text-truncate">{{ $prod->nama_produk }}</h5>
            <p class="card-text fw-bold text-custom">Rp{{ number_format($prod->harga, 0, ',', '.') }}</p>
            <div class="d-flex justify-content-between">
                {{-- <a href="{{ route('market.product-detail', $prod->id) }}" --}}
                <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                <button class="btn btn-sm btn-primary">Keranjang</button>
            </div>
        </div>
    </div>
</div>
