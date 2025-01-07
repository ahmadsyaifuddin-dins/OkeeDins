@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('orders.index') }}" class="text-decoration-none me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="mb-0">Detail Pesanan</h4>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-muted mb-1">Nomor Pesanan</p>
                                <h6 class="mb-0">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</h6>
                            </div>
                            <div class="text-end">
                                <p class="text-muted mb-1">Tanggal Pesanan</p>
                                <h6 class="mb-0">{{ $order->created_at->format('d M Y H:i') }}</h6>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Status Pesanan</span>
                                @switch(strtolower($order->status))
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu Pembayaran</span>
                                    @break

                                    @case('awaiting_payment')
                                        <span class="badge bg-info">Menunggu Konfirmasi</span>
                                    @break

                                    @case('processing')
                                        <span class="badge bg-primary">Diproses</span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @break

                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endswitch
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="border rounded-3 p-3 mb-4">
                            <h6 class="mb-3">Produk yang Dibeli</h6>
                            @foreach ($order->orderItems as $item)
                                <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                    <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                        alt="{{ $item->produk->nama_produk }}" class="rounded" width="60" height="60"
                                        style="object-fit: cover;">
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-1">{{ $item->produk->nama_produk }}</h6>
                                        <div class="text-muted small">
                                            {{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}
                                            @if ($item->discount > 0)
                                                <span class="text-danger">(Diskon {{ $item->discount }}%)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <span class="text-danger">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Payment Info -->
                        <div class="border rounded-3 p-3 mb-4">
                            <h6 class="mb-3">Informasi Pembayaran</h6>
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted mb-1">Metode Pembayaran</p>
                                    <p class="mb-3 text-capitalize">{{ $order->payment_method }}</p>

                                    <p class="text-muted mb-1">Status Pembayaran</p>
                                    <p class="mb-0">
                                        @if ($order->payment_method === 'Cash on Delivery')
                                            @if ($order->status === 'completed')
                                                <span class="text-success">Sudah Dibayar</span>
                                            @elseif($order->status === 'Processing')
                                                <span class="text-warning">Bayar Saat Terima</span>
                                            @else
                                                <span class="text-warning">Menunggu Pengiriman</span>
                                            @endif
                                        @else
                                            @if (strtolower($order->status) === 'pending')
                                                <span class="text-warning">Menunggu Pembayaran</span>
                                            @elseif(strtolower($order->status) === 'awaiting_payment')
                                                <span class="text-info">Menunggu Konfirmasi</span>
                                            @elseif(in_array(strtolower($order->status), ['Processing', 'completed']))
                                                <span class="text-success">Lunas</span>
                                            @else
                                                <span class="text-danger">Dibatalkan</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-1">Total Harga ({{ $order->qty }} Barang)</p>
                                    <p class="text-danger mb-0 fw-bold">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            @if ($order->payment_method === 'transfer')
                                @if ($order->payment_proof)
                                    <div class="mt-3">
                                        <p class="text-muted mb-2">Bukti Pembayaran</p>
                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                                            class="img-fluid rounded" style="max-height: 200px">
                                    </div>
                                @elseif(strtolower($order->status) === 'pending')
                                    <div class="mt-3">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Silakan lakukan pembayaran dan upload bukti transfer
                                        </div>
                                    </div>
                                @endif
                            @elseif($order->payment_method === 'cod')
                                <div class="mt-3">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Pembayaran akan dilakukan saat barang diterima
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Shipping Info -->
                        <div class="border rounded-3 p-3">
                            <h6 class="mb-3">Informasi Pengiriman</h6>
                            <p class="text-muted mb-1">Alamat Pengiriman</p>
                            <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                            <p class="mb-0">{{ $order->user->alamat }}</p>
                        </div>

                        @if (strtolower($order->status) === 'pending')
                            <div class="mt-4 text-center">
                                @if ($order->payment_method === 'transfer')
                                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                        data-bs-target="#uploadBuktiModal">
                                        Upload Bukti Transfer
                                    </button>
                                @endif
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Bukti Transfer -->
    @if ($order->payment_method === 'transfer' && strtolower($order->status) === 'pending')
        <div class="modal fade" id="uploadBuktiModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Bukti Transfer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('orders.upload-proof', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Bukti Transfer</label>
                                <input type="file" class="form-control" name="payment_proof" accept="image/*"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
