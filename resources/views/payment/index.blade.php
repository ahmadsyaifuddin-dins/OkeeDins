@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Button & Title -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <i class="bi bi-arrow-left text-xl mr-2"></i>
                <span class="text-sm font-medium">Kembali</span>
            </a>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Daftar Pembayaran</h1>
    </div>

    <!-- Payment List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            @if ($orders->isEmpty())
                <div class="text-center py-12">
                    <i class="bi bi-credit-card text-6xl text-gray-400 mb-4"></i>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Pembayaran</h2>
                    <p class="text-gray-500">Tidak ada pembayaran yang menunggu saat ini</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="bg-white border border-gray-100 rounded-lg p-6 hover:border-gray-200 transition-colors duration-200">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <!-- Order Info -->
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <i class="bi bi-receipt text-custom"></i>
                                        <h6 class="font-semibold text-gray-900">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h6>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900 mb-1">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="bi bi-clock mr-1"></i>
                                        <span>Batas waktu: {{ $order->created_at->addDays(1)->format('d M Y H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('payment.show', $order->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-custom text-white font-medium rounded-lg hover:bg-red-700 transition duration-200">
                                    <i class="bi bi-credit-card mr-2"></i>
                                    Bayar Sekarang
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-custom {
        background-color: #D32F2F;
    }
    .text-custom {
        color: #D32F2F;
    }
</style>
@endpush
@endsection
