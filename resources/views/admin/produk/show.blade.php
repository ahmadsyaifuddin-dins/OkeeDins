@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card card-body my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div
                            class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 px-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white text-capitalize mb-0">Detail Produk</h6>
                                <p class="text-white text-sm mb-0 opacity-8">{{ $produk->nama_produk }}</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-info btn-sm me-2">
                                    <i class="material-symbols-rounded"
                                        style="font-size: 15px; vertical-align: middle;">edit</i> Edit
                                </a>
                                <a href="{{ route('admin.produk.index') }}" class="btn btn-light btn-sm">
                                    <span class="material-symbols-rounded">arrow_back</span> Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-5">
                        <div class="row">
                            <div class="col-lg-4">
                                <!-- Gambar Utama -->
                                <div class="card shadow-sm">
                                    <div class="card-body p-2">
                                        <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('storage/user.svg') }}"
                                            class="img-fluid rounded" alt="product photo"
                                            style="width: 100%; height: 300px; object-fit: cover;">
                                    </div>
                                </div>

                                <!-- Galeri Gambar -->
                                <div class="card mt-4 shadow-sm">
                                    <div class="card-header p-3 pb-1">
                                        <h6 class="mb-0">Galeri Produk</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-2">
                                            @forelse($produk->productImages as $image)
                                                <div class="col-6">
                                                    <a href="{{ asset('storage/' . $image->image_path) }}"
                                                        data-fancybox="gallery" class="d-block image-hover">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                            class="img-fluid rounded shadow-sm"
                                                            style="width: 100%; height: 120px; object-fit: cover;"
                                                            alt="additional product photo">
                                                    </a>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-info text-sm text-white mb-0" role="alert">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Belum ada gambar tambahan untuk produk ini
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <div class="info-group">
                                                    <label
                                                        class="text-uppercase text-dark text-xs font-weight-bolder">Kategori</label>
                                                    <p class="mb-0 text-lg">{{ $produk->kategori->nama_kategori }}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="info-group">
                                                    <label
                                                        class="text-uppercase text-dark text-xs font-weight-bolder">Stok</label>
                                                    <p class="mb-0 text-lg">
                                                        <span
                                                            class="badge bg-{{ $produk->stok <= 10 ? 'danger' : 'success' }}">
                                                            {{ $produk->stok }} unit
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="info-group">
                                                    <label class="text-uppercase text-dark text-xs font-weight-bolder">Harga
                                                        Normal</label>
                                                    <p
                                                        class="mb-0 text-lg {{ $produk->diskon > 0 ? 'text-decoration-line-through text-muted' : '' }}">
                                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if ($produk->diskon > 0)
                                                <div class="col-md-6 mb-4">
                                                    <div class="info-group">
                                                        <label class="text-uppercase text-dark text-xs font-weight-bolder">
                                                            Harga Promo
                                                            <span
                                                                class="badge bg-danger ms-2">-{{ $produk->diskon }}%</span>
                                                        </label>
                                                        <p class="mb-0 text-lg text-danger">
                                                            Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-12">
                                                <div class="info-group">
                                                    <label
                                                        class="text-uppercase text-dark text-xs font-weight-bolder">Deskripsi
                                                        Produk</label>
                                                    <div class="card bg-gray-100 border-0 mt-2">
                                                        <div class="card-body">
                                                            <p class="mb-0">{!! $produk->deskripsi !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@push('styles')
    <style>
        .info-group label {
            margin-bottom: 0.5rem;
            display: block;
        }

        .image-hover {
            transition: transform 0.2s ease;
            display: block;
        }

        .image-hover:hover {
            transform: scale(1.05);
        }

        .card {
            border: none;
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .1);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            // Custom options
        });
    </script>
@endpush
