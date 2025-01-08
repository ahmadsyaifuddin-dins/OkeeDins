@extends('layouts.app')

@section('content')
    <div class="container mt-4">
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
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            Mulai Belanja
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
                                            @case('confirmed')
                                                <span class="badge bg-info">Di Konfirmasi</span>
                                            @break

                                            @case('pending')
                                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                            @break

                                            {{-- @case('awaiting_payment')
                                                <span class="badge bg-info">Menunggu Konfirmasi</span>
                                            @break --}}
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
                                    <div class="col">
                                        <span class="text-muted">Total Pembayaran</span>
                                        <br>
                                        <span class="text-danger fw-bold">
                                            Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="col text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-danger btn-sm">
                                            Detail Pesanan
                                        </a>
                                        @if ($order->status === 'pending')
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
