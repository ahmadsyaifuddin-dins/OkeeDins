@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            <!-- Back Button & Title -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-link text-dark p-0 me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <h4 class="mb-0 fw-bold">Riwayat Pesanan</h4>
            </div>
            
            <!-- Filter Status -->
            <div class="status-filter mb-4">
                <div class="d-flex overflow-auto pb-2">
                    <a href="{{ route('orders.history') }}" class="btn btn-sm {{ !request('status') ? 'btn-custom' : 'btn-outline-custom' }} me-2">Semua</a>
                    <a href="{{ route('orders.history', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-custom' : 'btn-outline-custom' }} me-2">Menunggu Konfirmasi</a>
                    <a href="{{ route('orders.history', ['status' => 'awaiting payment']) }}" class="btn btn-sm {{ request('status') === 'awaiting payment' ? 'btn-custom' : 'btn-outline-custom' }} me-2">Belum Bayar</a>
                    <a href="{{ route('orders.history', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') === 'processing' ? 'btn-custom' : 'btn-outline-custom' }} me-2">Diproses</a>
                    <a href="{{ route('orders.history', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-custom' : 'btn-outline-custom' }} me-2">Dikirim</a>
                    <a href="{{ route('orders.history', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') === 'completed' ? 'btn-custom' : 'btn-outline-custom' }} me-2">Selesai</a>
                    <a href="{{ route('orders.history', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-custom' : 'btn-outline-custom' }}">Dibatalkan</a>
                </div>
            </div>

            <!-- Empty State -->
            @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bag-x display-1 text-muted mb-3"></i>
                <h5 class="fw-bold mb-2">Belum Ada Pesanan</h5>
                <p class="text-muted mb-4">Anda belum memiliki riwayat pesanan</p>
                <a href="{{ route('home.index') }}" class="btn btn-custom">Mulai Belanja</a>
            </div>
            @else
            <!-- Order List -->
            <div class="order-list">
                @foreach($orders as $order)
                <!-- Order Item -->
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="row align-items-center">
                                <span class="text-muted small">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </span>
                                <br>
                                <span class="small">
                                    Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="fw-bold"><i class="bi bi-shop me-2"></i>Food Fusion</span>
                            </div>
                            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </div>
                        
                        @php
                            $firstItem = $order->orderItems->first();
                            $otherItemsCount = $order->orderItems->count() - 1;
                        @endphp
                        
                        <!-- Product Item -->
                        <div class="d-flex mb-3">
                            <img src="{{ asset('storage/' . $firstItem->produk->gambar) }}" alt="{{ $firstItem->produk->nama_produk }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1">{{ $firstItem->produk->nama_produk }}</h6>
                                <p class="text-muted mb-1 small">{{ $firstItem->quantity }} x Rp{{ number_format($firstItem->price, 0, ',', '.') }}</p>
                                @if($otherItemsCount > 0)
                                    <p class="mb-0 small">+ {{ $otherItemsCount }} produk lainnya</p>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <p class="text-muted mb-0 small">Total Pesanan</p>
                                <p class="fw-bold mb-0">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="d-flex">
                                @if(in_array($order->status, ['processing', 'delivered']))
                                    <a href="{{ route('orders.track', $order->id) }}" class="btn btn-sm btn-outline-custom me-2">Lacak</a>
                                @endif
                                @if ($order->status === 'pending')
                                                <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-outline-custom btn-sm me-2">
                                                        Batalkan
                                                    </button>
                                                </form>
                                @endif
                                <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-sm btn-custom">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .status-filter {
        -webkit-overflow-scrolling: touch;
    }
    .status-filter::-webkit-scrollbar {
        display: none;
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
    .badge.bg-warning {
        background-color: #FFA726 !important;
        color: white;
    }
    .btn-link:hover {
        color: #D32F2F !important;
    }
    @media (max-width: 576px) {
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush