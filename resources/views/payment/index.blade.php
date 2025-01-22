@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center mb-4">Daftar Pembayaran</h4>
                    
                    @if($orders->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-receipt fs-1 text-secondary"></i>
                            <p class="mt-3 text-secondary">Tidak ada pembayaran yang menunggu</p>
                        </div>
                    @else
                        @foreach($orders as $order)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                            <p class="mb-1">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                            <small class="text-muted">
                                                Batas waktu: {{ $order->created_at->addDays(1)->format('d M Y H:i') }}
                                            </small>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('payment.show', $order->id) }}" 
                                               class="btn btn-custom btn-sm">
                                                Bayar Sekarang
                                            </a>
                                        </div>
                                    </div>
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
