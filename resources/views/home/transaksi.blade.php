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
                    <div class="col-12 col-sm-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-wallet2 text-custom me-2"></i>
                                    <h6 class="mb-0 fs-mobile">Total Pengeluaran (Cash on Delivery)</h6>
                                </div>
                                <h4 class="mb-0 fw-bold fs-mobile-lg">Rp{{ number_format($totalCODSpent, 0, ',', '.') }}</h4>
                                <small class="text-muted">Bulan ini</small>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="col-12 col-sm-6">
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
                                <div class="mt-2 small d-flex flex-wrap gap-2">
                                    <span class="text-success">{{ $totalSuccessTransferOrders }} Berhasil</span>
                                    <span class="text-danger">{{ $totalPendingTransferOrders }} Menunggu Pembayaran</span>
                                    <span class="text-primary">{{ $totalAwaitingConfirmPaymentTransferOrders }} Menunggu Konfirmasi</span>
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
                                <button class="btn btn-sm btn-outline-custom dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
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
                        @if ($transactions->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-receipt-cutoff display-1 text-muted mb-3"></i>
                                <h5 class="fw-bold mb-2">Belum Ada Transaksi</h5>
                                <p class="text-muted mb-4">Anda belum memiliki riwayat transaksi</p>
                                <a href="{{ route('home.index') }}" class="btn btn-custom">Mulai Belanja</a>
                            </div>
                        @else
                            @foreach ($transactions as $transaction)
                                <!-- Transaction Item -->
                                <div class="p-3 border-bottom transaction-item">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div class="transaction-info">
                                                <h6 class="mb-0 transaction-title">Pembayaran Pesanan #{{ $transaction->order->order_number }}</h6>
                                                <h6 class="mb-1 text-muted transaction-number">Nomor Pesanan #{{ str_pad($transaction->order_id, 6, '0', STR_PAD_LEFT) }}</h6>
                                                <p class="text-muted mb-2 transaction-date">
                                                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}
                                                </p>
                                            </div>
                                            <span class="text-danger fw-bold transaction-amount d-none d-sm-block">-Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                        </div>
                                        <span class="text-danger fw-bold transaction-amount d-block d-sm-none">-Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                        <br>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if ($transaction->order->payment_method === 'Cash on Delivery')
                                            @if ($transaction->order->status === 'completed')
                                                <span class="badge bg-success me-2">Berhasil</span>
                                            @else
                                                <span class="badge bg-danger me-2">Belum Bayar</span>
                                            @endif
                                        @else
                                            @if ($transaction->order->status === 'completed')
                                                <span class="badge bg-success me-2">Berhasil</span>
                                            @elseif($transaction->order->status === 'awaiting payment')
                                                <span class="badge bg-primary me-2">Menunggu Konfirmasi</span>
                                            @elseif($transaction->order->status === 'payment_rejected')
                                                <span class="badge bg-danger me-2">Pembayaran Ditolak</span>
                                            @elseif($transaction->order->status === 'processing')
                                                <span class="badge bg-info me-2">Sedang Diproses</span>
                                            @elseif($transaction->order->status === 'delivered')
                                                <span class="badge bg-info me-2">Dalam Pengantaran</span>
                                            @elseif($transaction->order->status === 'pending')
                                            <span class="badge bg-danger me-2">Menunggu Pembayaran</span>

                                            @endif
                                        @endif

                                        <small class="text-muted">
                                            {{ $transaction->order->payment_method === 'transfer' ? 'Transfer Bank' : 'Cash on Delivery' }}
                                        </small>
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
                padding: 0.75rem;
            }

            .fs-mobile {
                font-size: 0.85rem !important;
            }

            .fs-mobile-lg {
                font-size: 1.25rem !important;
            }

            .col-6 {
                width: 100%;
            }

            .row.g-3 {
                --bs-gutter-y: 0.75rem;
            }

            .mb-4 {
                margin-bottom: 1rem !important;
            }

            .card-subtitle {
                font-size: 0.85rem;
            }

            .card-title {
                font-size: 1.25rem;
            }

            .badge {
                font-size: 0.7rem;
            }

            .transaction-amount {
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .small {
                font-size: 0.75rem !important;
            }

            .gap-2 {
                gap: 0.5rem !important;
            }

            .transaction-item {
                padding: 0.875rem !important;
            }

            .transaction-title {
                font-size: 0.9rem;
                line-height: 1.3;
                flex: 1;
            }

            .transaction-number {
                font-size: 0.8rem !important;
            }

            .transaction-date {
                font-size: 0.75rem !important;
                margin-bottom: 0.5rem !important;
            }

            .badge {
                padding: 0.35em 0.65em;
                font-size: 0.7rem;
            }
        }
    </style>
@endpush
