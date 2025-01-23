@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Pesanan Saya</h4>
                </div>

                @if ($orders->isEmpty())
                    <div class="text-center py-5">
                        <img src="/images/empty-order.svg" alt="Empty Orders" class="mb-3" style="width: 150px">
                        <h5>Belum ada pesanan</h5>
                        <p class="text-muted">Anda belum memiliki pesanan apapun</p>
                        <a href="{{ route('home.index') }}" class="btn btn-custom">
                            <i class="bi bi-shop me-1"></i> Mulai Belanja
                        </a>
                    </div>
                @else
                    @foreach ($orders as $order)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-white">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="text-muted small">
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </span>
                                        <br>
                                        <span class="small">
                                            Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                    <div class="col text-end">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                            @break

                                            @case('confirmed')
                                                <span class="badge bg-success">
                                                    @if ($order->payment_method === 'transfer')
                                                        Pembayaran Dikonfirmasi
                                                    @else
                                                        Di Konfirmasi
                                                    @endif
                                                </span>
                                            @break

                                            @case('awaiting payment')
                                                <span class="badge bg-info">Menunggu Pembayaran Dikonfirmasi</span>
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
                                                <span class="badge bg-secondary">{{ $order->status }}</span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="overflow-auto" style="max-height: 150px;">
                                    @foreach ($order->orderItems as $item)
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                                                alt="{{ $item->produk->nama_produk }}" class="rounded" width="50"
                                                height="50" style="object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-1">{{ $item->produk->nama_produk }}</h6>
                                                <div class="text-muted small">
                                                    {{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- @if ($order->orderItems->count() > 2)
                                    <div class="text-muted small">
                                        dan {{ $order->orderItems->count() - 2 }} produk lainnya
                                    </div>
                                @endif --}}
                            </div>
                            <div class="card-footer bg-white">
                                <div class="row align-items-center">
                                    <div class="col align-self-center">
                                        <span class="text-muted">Total Pembayaran</span>
                                        <br>
                                        <span class="text-danger fw-bold">
                                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="col text-end">
                                        <div class="d-flex flex-column flex-sm-row justify-content-end">
                                            @if ($order->status === 'pending')
                                                <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-outline-custom btn-sm me-sm-2 px-2 mb-2">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="btn btn-danger btn-sm mb-2">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation" class="pagination-wrapper">
                            {{ $orders->links('vendor.pagination.custom') }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Custom Pagination Styles */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
            margin: 0;
            padding: 0;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            background-color: #fff;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease-in-out;
        }

        .pagination .page-item.active .page-link {
            background-color: #D32F2F;
            border-color: #D32F2F;
            color: #fff;
        }

        .pagination .page-link:hover:not(.active) {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #D32F2F;
        }

        .pagination .page-item.disabled .page-link {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 576px) {
            .pagination {
                gap: 3px;
            }

            .pagination .page-link {
                min-width: 35px;
                height: 35px;
                padding: 0 8px;
                font-size: 13px;
            }

            .pagination .page-item.mobile-hidden {
                display: none;
            }
        }
    </style>
@endsection
