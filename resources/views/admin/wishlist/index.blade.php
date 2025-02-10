@extends('layouts.app-admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-dark">Visualisasi Produk Favorit</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Produk Terfavorit</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-dark">Produk</th>
                                                    <th class="text-dark">Jumlah Wishlist</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topProducts as $product)
                                                <tr>
                                                    <td>{{ $product->produk->nama_produk }}</td>
                                                    <td>{{ $product->total }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>User dengan Wishlist Terbanyak</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-dark">User</th>
                                                    <th class="text-dark">Jumlah Wishlist</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($userWishlists as $userWishlist)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('admin.wishlist.show', $userWishlist->user_id) }}">
                                                            {{ $userWishlist->user->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $userWishlist->total }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Total Wishlist</span>
                                    <span class="info-box-number text-dark fw-bolder">{{ $totalWishlists }}</span>
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