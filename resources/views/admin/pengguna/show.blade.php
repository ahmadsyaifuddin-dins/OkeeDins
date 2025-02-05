@extends('layouts.app-admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Profil Pengguna -->
        <div class="col-lg-4">
            <div class="card card-profile">
                <div class="position-relative">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg p-3">
                        <img src="{{ asset('storage/' . ($pengguna->photo ?? 'user.svg')) }}" 
                             class="img-fluid shadow border-radius-lg" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="mb-0">{{ $pengguna->name }}</h4>
                    <p class="text-sm text-dark mb-0">{{ $pengguna->email }}</p>
                    <div class="mt-3">
                        <span class="badge bg-gradient-dark">{{ $pengguna->role }}</span>
                        <span class="badge {{ $pengguna->type_char === 'Hero' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                            {{ $pengguna->type_char }}
                        </span>
                    </div>
                    <div class="text-center my-2">
                        <h6 class="text-sm mb-1">Bergabung Sejak</h6>
                        <p class="text-sm text-dark mb-0">
                            {{ \Carbon\Carbon::parse($pengguna->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                            <small class="d-block text-muted">
                                {{ \Carbon\Carbon::parse($pengguna->created_at)->locale('id')->diffForHumans() }}
                            </small>
                        </p>
                    </div>
                    <hr class="horizontal gray-light my-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6 class="text-sm">Telepon</h6>
                            <p class="text-sm mb-0">{{ $pengguna->telepon }}</p>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="text-sm">Tanggal Lahir</h6>
                            <p class="text-sm mb-0">{{ \Carbon\Carbon::parse($pengguna->tgl_lahir)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6">
                            <h6 class="text-sm">Jenis Kelamin</h6>
                            <p class="text-sm mb-0">{{ $pengguna->jenis_kelamin }}</p>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="text-sm">Makanan Favorit</h6>
                            <p class="text-sm mb-0">{{ $pengguna->makanan_fav }}</p>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <h6 class="text-sm">Alamat</h6>
                            <p class="text-sm mb-0">{{ $pengguna->alamat }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Total Transaksi -->
                <div class="col-xl-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Transaksi</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $totalTransaksi }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10" aria-hidden="true">receipt_long</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pembelian -->
                <div class="col-xl-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pembelian</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            Rp{{ number_format($totalPembelian, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10" aria-hidden="true">payments</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Transaksi -->
                <div class="col-xl-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata-rata Transaksi</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10" aria-hidden="true">trending_up</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Favorit -->
            <div class="card mb-4">
                <div class="card-header p-3">
                    <h6 class="mb-0">Produk Favorit</h6>
                </div>
                <div class="card-body p-3">
                    @if($produkFavorit->count() > 0)
                        <ul class="list-group">
                            @foreach($produkFavorit as $produk)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                            <i class="material-symbols-rounded opacity-10">lunch_dining</i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $produk->nama_produk }}</h6>
                                            <span class="text-xs">Dibeli {{ $produk->total_beli }} kali</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm mb-0">Belum ada produk favorit</p>
                    @endif
                </div>
            </div>

            <!-- Voucher yang Digunakan -->
            <div class="card mb-4">
                <div class="card-header p-3">
                    <h6 class="mb-0">Voucher yang Digunakan</h6>
                </div>
                <div class="card-body p-3">
                    @if($voucherDigunakan->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diskon</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Digunakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($voucherDigunakan as $voucher)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $voucher->code }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $voucher->type === 'percentage' ? $voucher->value . '%' : 'Rp' . number_format($voucher->value, 0, ',', '.') }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ \Carbon\Carbon::parse($voucher->tanggal_pakai)->format('d M Y H:i') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm mb-0">Belum pernah menggunakan voucher</p>
                    @endif
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0">Riwayat Transaksi Terakhir</h6>
                </div>
                <div class="card-body p-3">
                    @if($riwayatTransaksi->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatTransaksi as $transaksi)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $transaksi->order_number }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Rp{{ number_format($transaksi->total_amount, 0, ',', '.') }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm {{ $transaksi->status === 'completed' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                    {{ $transaksi->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y H:i') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm mb-0">Belum ada riwayat transaksi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
