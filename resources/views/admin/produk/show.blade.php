@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Detail Produk</h6>
                        </div>
                        <a href="{{ route('admin.produk.index') }}" class="btn bg-gradient-dark btn-md me-3 mt-3">
                            <i class="material-symbols-rounded me-1" style="font-size: 20px; vertical-align: middle;">arrow_back</i> Kembali
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('storage/user.svg') }}"
                                    class="img-fluid rounded" alt="product photo">
                            </div>
                            <div class="col-md-8">
                                <h3 class="mb-3">{{ $produk->nama_produk }}</h3>
                                
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Kategori</h6>
                                    <p>{{ $produk->kategori->nama_kategori }}</p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Harga</h6>
                                    <p>Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                </div>

                                @if($produk->diskon > 0)
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Diskon</h6>
                                    <p>{{ $produk->diskon }}%</p>
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Harga Setelah Diskon</h6>
                                    <p>Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</p>
                                </div>
                                @endif

                                <div class="mb-3">
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Stok</h6>
                                    <p>{{ $produk->stok }}</p>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Deskripsi</h6>
                                    <div class="description-content">
                                        {!! $produk->deskripsi !!}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Ditambahkan Pada</h6>
                                    <p>{{ \Carbon\Carbon::parse($produk->created_at)->locale('id')->isoFormat('D MMMM Y HH:mm') }}</p>

                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn bg-gradient-info me-2">
                                        <i class="material-symbols-rounded me-1" style="font-size: 20px; vertical-align: middle;">edit</i> Edit Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
