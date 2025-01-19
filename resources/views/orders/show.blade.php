@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="row ">
            <div class="col-12">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('orders.index') }}" class="text-decoration-none me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h4 class="mb-0">Detail</h4>
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

                        <!-- Status Pesanan -->
                        <div class="bg-light rounded p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Status Pesanan</span>
                                @switch(strtolower($order->status))
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                    @break

                                    @case('confirmed')
                                        <span class="badge bg-info">Dikonfirmasi</span>
                                    @break

                                    @case('processing')
                                        <span class="badge bg-primary">Sedang Dikemas</span>
                                    @break

                                    @case('delivered')
                                        <span class="badge bg-primary">Dalam Pengiriman <i class="bi bi-truck"></i></span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @break

                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </div>
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
                                            @if ($order->status === 'cancelled')
                                                <span class="text-danger">Dibatalkan</span>
                                            @elseif ($order->status === 'completed')
                                                <span class="text-success">Sudah Dibayar</span>
                                            @elseif ($order->status === 'processing')
                                                <span class="text-primary">Menunggu Pengiriman</span>
                                            @elseif ($order->status === 'delivered')
                                                <span class="text-warning">Bayar Saat Terima</span>
                                            @elseif (in_array($order->status, ['pending']))
                                                <span class="text-warning">Menunggu Konfirmasi Penjual</span>
                                            @else
                                                <span class="text-warning">Menunggu Pengiriman</span>
                                            @endif
                                        @else
                                            @if (strtolower($order->status) === 'cancelled')
                                                <span class="text-danger">Dibatalkan</span>
                                            @elseif (strtolower($order->status) === 'pending')
                                                <span class="text-warning">Menunggu Pembayaran</span>
                                            @elseif (strtolower($order->status) === 'awaiting_payment')
                                                <span class="text-info">Menunggu Konfirmasi Pembayaran</span>
                                            @elseif (in_array(strtolower($order->status), ['processing', 'completed']))
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
                        </div>

                        @if ($order->payment_method === 'Cash on Delivery' && in_array($order->status, ['processing', 'delivered']))
                            <div class="mt-3">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Pembayaran akan dilakukan saat barang diterima.
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- Shipping Info -->
                    <div class="border rounded-3 p-3">
                        <h6 class="mb-3">Informasi Pengiriman</h6>
                        <p class="text-muted mb-1">Alamat Pengiriman</p>
                        <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                        <p class="mb-0">{{ $order->address->full_address }}</p>
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

                    <!-- Konfirmasi Penerimaan untuk COD -->
                    <!-- Add this right after the confirm receipt form -->
                    @if ($order->payment_method === 'Cash on Delivery' && strtolower($order->status) === 'delivered')
                        <div class="mt-4 text-center">
                            <!-- Ganti dengan button biasa untuk trigger modal -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#ratingModal">
                                <i class="bi bi-check-circle me-2"></i>Konfirmasi Barang Diterima
                            </button>
                        </div>
                    @endif
                    <!-- Modal Rating -->
                    <div class="modal fade" id="ratingModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Beri Rating dan Ulasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Ubah form untuk menggabungkan konfirmasi penerimaan dan rating -->
                                <form action="{{ route('orders.confirm-receipt', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <!-- Star Rating -->
                                        <div class="mb-3">
                                            <label class="form-label">Rating Produk</label>
                                            <div class="star-rating">
                                                <div class="stars">
                                                    @for ($i = 5; $i >= 1; $i--)
                                                        <input type="radio" id="star{{ $i }}" name="rating"
                                                            value="{{ $i }}" required>
                                                        <label for="star{{ $i }}"><i
                                                                class="bi bi-star-fill"></i></label>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Review Text -->
                                        <div class="mb-3">
                                            <label for="ulasan" class="form-label">Ulasan Anda</label>
                                            <textarea class="form-control" id="ulasan" name="ulasan" rows="3" required
                                                placeholder="Bagikan pengalaman Anda menggunakan produk ini..."></textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Konfirmasi & Kirim Ulasan</button>
                                    </div>
                                </form>
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
                            <form action="{{ route('orders.upload-proof', $order) }}" method="POST"
                                enctype="multipart/form-data">
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endsection

        <!-- Add this CSS to your stylesheet or in a style tag -->
        <style>
            .star-rating {
                display: flex;
                justify-content: center;
                direction: rtl;
            }

            .stars {
                display: inline-block;
            }

            .stars input[type="radio"] {
                display: none;
            }

            .stars label {
                float: right;
                padding: 0 2px;
                font-size: 24px;
                color: #ddd;
                cursor: pointer;
            }

            .stars label:hover,
            .stars label:hover~label,
            .stars input[type="radio"]:checked~label {
                color: #ffd700;
            }
        </style>
