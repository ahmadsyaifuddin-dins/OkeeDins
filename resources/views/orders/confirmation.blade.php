@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <!-- Status Banner -->
                        <div class="text-center mb-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-pill py-2 px-4 d-inline-block mb-3">
                                <i class="bi bi-check-circle me-2"></i>Pesanan Berhasil Dibuat
                            </div>
                            <h4 class="mb-1">Terima Kasih, {{ $order->user->name }}!</h4>
                            <p class="text-muted mb-0">Nomor Pesanan: #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <!-- Order Details -->
                        <div class="border rounded-3 p-3 mb-4">
                            <h5 class="mb-3">Detail Pesanan</h5>

                            <!-- Order Items -->
                            @foreach ($order->orderItems as $item)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                        alt="{{ $item->produk->nama_produk }}" class="rounded" width="100" height="100"
                                        style="object-fit: cover;">
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1">{{ $item->produk->nama_produk }}</h6>
                                        <div class="text-muted small">
                                            {{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}
                                            @if ($item->discount > 0)
                                                <span class="text-danger">(Diskon {{ intval($item->discount) }}%)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <span class="text-danger fw-medium">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Order Summary -->
                            <div class="bg-light rounded p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Item</span>
                                    <span>{{ $order->qty }} barang</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Harga</span>
                                    <span>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Total Pembayaran</strong>
                                    <strong
                                        class="text-danger">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="border rounded-3 p-3 mb-4">
                            <h5 class="mb-3">Informasi Pembayaran</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Metode Pembayaran</span>
                                <span class="text-capitalize">{{ $order->payment_method }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Status Pembayaran</span>
                                <span class="badge {{ $order->status === 'pending' ? 'bg-warning' : 'bg-info' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            @if ($order->payment_method === 'Transfer' && $order->payment_proof)
                                <div class="mt-3">
                                    <label class="form-label">Bukti Transfer</label>
                                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer"
                                        class="img-fluid rounded" style="max-height: 200px">
                                </div>
                            @endif
                        </div>

                        <!-- Customer Info -->
                        <div class="border rounded-3 p-3">
                            <h5 class="mb-3">Informasi Penerima</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Nama</span>
                                <span>{{ $order->user->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Alamat</span>
                                <span class="text-end" style="max-width: 60%">{{ $order->address->full_address }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4 me-3 mb-3 d-flex flex-column flex-md-row">
                            <a href="{{ route('home.index') }}" class="btn btn-outline-custom w-100 mb-2 mb-md-0 me-md-2">
                                Kembali ke Beranda
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-custom w-100">
                                Lihat Pesanan Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
