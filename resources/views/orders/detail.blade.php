@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            <!-- Back Button & Title -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-link text-dark p-0 me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <h4 class="mb-0 fw-bold">Detail Pesanan #{{ $order->order_number }}</h4>
            </div>

            <!-- Order Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Status Pesanan</p>
                            @if($order->status === 'delivered')
                                <span class="badge bg-{{ $order->status_color }} fs-6">
                                    {{ $order->status_label }} <i class="bi bi-truck ms-1"></i>
                                </span>
                            @else
                                <span class="badge bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
                            @endif
                        </div>
                        @if(in_array($order->status, ['processing', 'delivered', 'completed']))
                            <a href="{{ route('orders.track', $order->id) }}" class="btn btn-custom">
                                <i class="bi bi-truck me-2"></i>Lacak Pesanan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Produk yang Dibeli</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->orderItems as $item)
                    <div class="p-3 border-bottom">
                        <div class="d-flex">
                            <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama_produk }}" 
                                class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1">{{ $item->produk->nama_produk }}</h6>
                                <p class="text-muted mb-1 small">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                <p class="mb-0 fw-bold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informasi Pesanan</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Nomor Pesanan</p>
                            <h6 class="mb-0">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</h6>
                        </div>
                        <div class="text-end">
                            <p class="text-muted mb-1">Tanggal Pesanan</p>
                            <h6 class="mb-0">{{ $order->created_at->format('d M Y H:i') }}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="mb-2">Alamat Pengiriman</h6>
                            <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                            <p class="mb-1">{{ $order->address->phone_number }}</p>
                            <p class="mb-0">{{ $order->address->full_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Metode Pembayaran</h6>
                            <span class="badge text-capitalize bg-dark">{{ $order->payment_method }}</span>
                            
                            <p class="mb-0 mt-2">Status: <br><span class=" fw-bold {{ $order->payment_status === 'paid' ? 'text-success' : 'text-danger' }}">
                                {{ $order->payment_status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                            </span></p>
                            
                            @if ($order->payment_method === 'Cash on Delivery' && in_array($order->status, ['processing', 'delivered']))
                            <div class="mt-3">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Pembayaran akan dilakukan saat barang diterima.
                                </div>
                            </div>
                        @endif

                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Rincian Pembayaran</h5>
                </div>
                <div class="card-body">
                    <!-- Rincian per item -->
                    @foreach($order->orderItems as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item->produk->nama_produk }} ({{ $item->quantity }}x)</span>
                        <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach

                    <hr>

                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp{{ number_format($order->orderItems->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>

                    <!-- Diskon voucher -->
                    @if($order->voucher_discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon Voucher</span>
                        <span class="text-success">-Rp{{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <!-- Total -->
                    <div class="d-flex justify-content-between pt-2 border-top">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold text-decoration-underline">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Konfirmasi Penerimaan untuk COD -->
            @if ($order->payment_method === 'Cash on Delivery' && strtolower($order->status) === 'delivered')
            <div class="mt-4 text-center">
                    <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal"
                        data-bs-target="#ratingModalCOD">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Barang Diterima
                    </button>
                </div>
            @endif
            
            <!-- Konfirmasi Penerimaan untuk Tranfer -->
            @if ($order->payment_method === 'transfer' && in_array(strtolower($order->status), ['delivered', 'processing']))
                <div class="mt-4 text-center">
                    <button type="button" class="btn btn-success mb-2" data-bs-toggle="modal"
                        data-bs-target="#ratingModalTransfer">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Barang Diterima
                    </button>
                </div>
            @endif

            <!-- Modal Konfirmasi dan Rating Metode COD-->
            <div class="modal fade" id="ratingModalCOD" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Penerimaan & Beri Rating</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('orders.confirm-cod', $order) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Dengan mengkonfirmasi penerimaan, Anda menyatakan bahwa barang telah diterima dalam kondisi baik.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Rating Produk</label>
                                    <div class="star-rating">
                                        <div class="stars">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="starCOD{{ $i }}" name="rating"
                                                    value="{{ $i }}" required>
                                                <label for="starCOD{{ $i }}"><i
                                                        class="bi bi-star-fill"></i></label>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ulasanCOD" class="form-label">Ulasan Anda</label>
                                    <textarea class="form-control" id="ulasanCOD" name="ulasan" rows="3" required
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

            <!-- Modal Konfirmasi dan Rating Metode Transfer-->
            <div class="modal fade" id="ratingModalTransfer" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Penerimaan & Beri Rating</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('orders.confirm-transfer', $order) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Dengan mengkonfirmasi penerimaan, Anda menyatakan bahwa barang telah diterima dalam kondisi baik.
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rating Produk</label>
                                    <div class="star-rating">
                                        <div class="stars">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="starTransfer{{ $i }}" name="rating"
                                                    value="{{ $i }}" required>
                                                <label for="starTransfer{{ $i }}"><i
                                                        class="bi bi-star-fill"></i></label>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ulasanTransfer" class="form-label">Ulasan Anda</label>
                                    <textarea class="form-control" id="ulasanTransfer" name="ulasan" rows="3" required
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

            {{-- @if($order->status === 'completed')
            <!-- Review Button -->
            <div class="text-center mt-4">
                <a href="{{ route('reviews.create', $order->id) }}" class="btn btn-custom">
                    <i class="bi bi-star me-2"></i>Beri Ulasan
                </a>
            </div>
            @endif --}}

        </div>
    </div>
</div>


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
        font-size: 20px;
        color: #ddd;
        cursor: pointer;
    }

    .stars label:hover,
    .stars label:hover~label,
    .stars input[type="radio"]:checked~label {
        color: #ffd700;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        h6 {
            font-size: 1rem;
        }
        p {
            font-size: 0.9rem;
        }
        .badge {
            font-size: 0.8rem;
            white-space:
        }
    }
</style>
@endsection
