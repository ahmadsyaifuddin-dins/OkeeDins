@extends('layouts.app')

@section('title', 'Lacak Pesanan')

@section('content')
    <div class="container mb-5">
        <div class="row">
            <div class="col-12">
                <!-- Back Button & Title -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ url()->previous() }}" class="btn btn-link text-dark p-0 me-3">
                        <i class="bi bi-arrow-left fs-5"></i>
                    </a>
                    <h4 class="mb-0 fw-bold">Lacak Pesanan #{{ $order->order_number }}</h4>
                </div>

                <!-- Order Status Timeline -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="timeline">
                            <!-- Pesanan Dibuat -->
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $order->created_at ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pesanan Dibuat</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pembayaran -->
                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pembayaran Diterima</h6>
                                    <p class="text-muted mb-0 small">
                                        @if ($order->transaction && $order->payment_status === 'paid')
                                            {{ \Carbon\Carbon::parse($order->transaction->payment_date)->format('d M Y, H:i') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Pesanan Diproses -->
                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ $order->status === 'processing' || $order->status === 'delivered' || $order->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pesanan Diproses</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $order->status === 'processing' || $order->status === 'delivered' || $order->status === 'completed' ? 'Pesanan sedang disiapkan' : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pesanan Dikirim -->
                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ $order->status === 'delivered' || $order->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pesanan Dikirim</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $order->status === 'delivered' || $order->status === 'completed' ? 'Pesanan dalam perjalanan' : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pesanan Selesai -->
                            <div class="timeline-item">
                                <div
                                    class="timeline-dot {{ $order->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-check-lg text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pesanan Selesai</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $order->status === 'completed' ? 'Pesanan telah diterima' : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Detail Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="mb-2">Informasi Pengiriman</h6>
                                <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                                <p class="mb-1">{{ $order->address->phone_number }}</p>
                                <p class="mb-0">{{ $order->address->full_address }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-2">Metode Pembayaran</h6>
                                <p class="mb-1 text-capitalize">{{ $order->payment_method }}</p>
                                <p class="mb-0">Status: <span
                                        class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ $order->payment_status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                                    </span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding: 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            width: 2px;
            background-color: #e9ecef;
            top: 0;
            bottom: 0;
            left: 15px;
        }

        .timeline-item {
            position: relative;
            padding-left: 40px;
            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #6c757d;
        }

        .timeline-dot.bg-success {
            background-color: var(--bs-success) !important;
        }

        .timeline-content {
            padding-top: 3px;
        }
    </style>
@endsection
