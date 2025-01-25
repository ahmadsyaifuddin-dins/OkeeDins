@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            <!-- Back Button & Title -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-link text-dark p-0 me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <h4 class="mb-0 fw-bold">Transaksi</h4>
            </div>

            <!-- Transaction Summary Cards -->
            <div class="row g-3 mb-4">
                <!-- Total Spent -->
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-wallet2 text-custom me-2"></i>
                                <h6 class="mb-0">Total Pengeluaran (Cash on Delivery)</h6>
                            </div>
                            <h4 class="mb-0 fw-bold">Rp{{ number_format($totalSpentThisMonth, 0, ',', '.') }}</h4>
                            <small class="text-muted">Bulan ini</small>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-box-seam text-custom me-2"></i>
                                <h6 class="mb-0">Total Pesanan</h6>
                            </div>
                            <h4 class="mb-0 fw-bold">{{ $totalCompletedOrders }}</h4>
                            <small class="text-muted">Pesanan selesai</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Total Pengeluaran Transfer -->
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Pengeluaran (Transfer)</h6>
                            <h3 class="card-title mb-0">Rp{{ number_format($totalTransferSpent, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Pesanan Transfer -->
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Total Pesanan Transfer</h6>
                            <h3 class="card-title mb-0">{{ $totalTransferOrders }}</h3>
                            <div class="mt-2 small">
                                <span class="text-success me-2">{{ $totalSuccessTransferOrders }} Berhasil</span>
                                <span class="text-warning">{{ $totalPendingTransferOrders }} Menunggu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Transaksi</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-custom dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Bulan Ini
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                                <li><a class="dropdown-item" href="#">3 Bulan Terakhir</a></li>
                                <li><a class="dropdown-item" href="#">6 Bulan Terakhir</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Empty State -->
                    @if($transactions->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-receipt-cutoff display-1 text-muted mb-3"></i>
                        <h5 class="fw-bold mb-2">Belum Ada Transaksi</h5>
                        <p class="text-muted mb-4">Anda belum memiliki riwayat transaksi</p>
                        <a href="{{ route('home.index') }}" class="btn btn-custom">Mulai Belanja</a>
                    </div>
                    @else
                        @foreach($transactions as $transaction)
                        <!-- Transaction Item -->
                        <div class="p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-1">Pembayaran Pesanan #{{ $transaction->order->order_number }}</h6>
                                    <p class="text-muted mb-0 small">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}</p>
                                </div>
                                <span class="text-danger fw-bold">-Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($transaction->order->payment_method === 'transfer')
                                    @if($transaction->order->status === 'processing' || $transaction->order->status === 'completed')
                                        <span class="badge bg-success me-2">Berhasil</span>
                                    @elseif($transaction->order->status === 'awaiting payment')
                                        <span class="badge bg-warning me-2">Menunggu Konfirmasi</span>
                                    @elseif($transaction->order->status === 'payment_rejected')
                                        <span class="badge bg-danger me-2">Pembayaran Ditolak</span>
                                    @else
                                        <span class="badge bg-danger me-2">Belum Bayar</span>
                                    @endif
                                @else
                                    <span class="badge bg-{{ $transaction->payment_status === 'paid' ? 'success' : 'danger' }} me-2">
                                        {{ $transaction->payment_status === 'paid' ? 'Berhasil' : 'Belum Bayar' }}
                                    </span>
                                @endif
                                <small class="text-muted text-capitalize">{{ $transaction->order->payment_method }}</small>
                                @if($transaction->order->payment_method === 'transfer' && $transaction->order->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-primary me-2 ms-2" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $transaction->order->id }}">
                                        Upload Bukti Transfer
                                    </button>

                                    <!-- Modal Upload Bukti Transfer -->
                                    <div class="modal fade" id="uploadModal{{ $transaction->order->id }}" tabindex="-1" aria-labelledby="uploadModalLabel{{ $transaction->order->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadModalLabel{{ $transaction->order->id }}">Upload Bukti Transfer</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('orders.upload-payment', $transaction->order->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="payment_proof" class="form-label">Bukti Transfer</label>
                                                            <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                                            <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
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
                               
                                @elseif($transaction->order->payment_method === 'transfer' && $transaction->order->status === 'payment_rejected')
                                    <span class="badge bg-danger">Pembayaran Ditolak</span>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $transaction->order->id }}">
                                        Upload Ulang Bukti Transfer
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-custom {
        color: #D32F2F;
    }
    .btn-custom {
        background-color: #D32F2F;
        color: white;
    }
    .btn-outline-custom {
        border-color: #D32F2F;
        color: #D32F2F;
    }
    .btn-outline-custom:hover {
        background-color: #D32F2F;
        color: white;
    }
    .card {
        border-radius: 10px;
    }
    .badge.bg-success {
        background-color: #4CAF50 !important;
    }
    .btn-link:hover {
        color: #D32F2F !important;
    }
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem;
        }
        h6 {
            font-size: 0.9rem;
        }
        .small {
            font-size: 0.75rem;
        }
    }
</style>
@endpush