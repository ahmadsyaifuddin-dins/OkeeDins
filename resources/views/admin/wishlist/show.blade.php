@extends('layouts.app-admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-gradient-dark">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="text-white">
                                <i class="fas fa-user-circle mr-2"></i>
                                Favorit dari {{ $user->name }}
                            </h2>
                            <p class="text-white mb-0">
                                <i class="fas fa-envelope mr-1"></i> {{ $user->email }}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="h1 text-white mb-0">
                                <i class="fas fa-heart mr-2"></i>
                                {{ $totalWishlists }}
                            </div>
                            <small class="text-white">Total Produk Difavoritkan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-dark">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Produk yang Difavoritkan
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.wishlist.index') }}" class="btn btn-sm btn-dark">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-dark">No</th>
                                    <th class="text-dark">Gambar</th>
                                    <th class="text-dark">Nama Produk</th>
                                    <th class="text-dark">Kategori</th>
                                    <th class="text-dark">Harga</th>
                                    <th class="text-dark">Tanggal Difavoritkan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userWishlists as $index => $wishlist)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($wishlist->produk->gambar)
                                        <img src="{{ asset('storage/' . $wishlist->produk->gambar) }}"
                                            class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                        <div class="bg-secondary text-white p-2"
                                            style="width: 50px; height: 50px; text-align: center;">
                                            No Image
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $wishlist->produk->nama_produk }}</td>
                                    <td>
                                        <span>
                                            {{ $wishlist->produk->kategori->nama_kategori }}
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            Rp {{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $wishlist->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection