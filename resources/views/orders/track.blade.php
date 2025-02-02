@extends('layouts.app')

@section('title', 'Lacak Pesanan')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Button & Title -->
    <div class="flex items-center mb-8">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <i class="bi bi-arrow-left text-xl mr-2"></i>
            <span class="text-sm font-medium">Kembali</span>
        </a>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 ml-4">Lacak Pesanan #{{ $order->order_number }}</h1>
    </div>

    <!-- Order Status Timeline -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="relative">
            <!-- Vertical Line -->
            <div class="absolute left-[21px] top-8 bottom-8 w-0.5 bg-gray-200"></div>

            <!-- Timeline Items -->
            <div class="space-y-8">
                <!-- Pesanan Dibuat -->
                <div class="relative flex items-start">
                    <div class="flex items-center justify-center w-11 h-11 rounded-full {{ $order->created_at ? 'bg-green-500' : 'bg-gray-300' }} shrink-0">
                        <i class="bi bi-check-lg text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Dibuat</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="relative flex items-start">
                    <div class="flex items-center justify-center w-11 h-11 rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-gray-300' }} shrink-0">
                        <i class="bi bi-credit-card text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pembayaran Diterima</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($order->transaction && $order->payment_status === 'paid')
                                {{ \Carbon\Carbon::parse($order->transaction->payment_date)->format('d M Y, H:i') }}
                            @else
                                Menunggu Pembayaran
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Pesanan Diproses -->
                <div class="relative flex items-start">
                    <div class="flex items-center justify-center w-11 h-11 rounded-full {{ $order->status === 'processing' || $order->status === 'delivered' || $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} shrink-0">
                        <i class="bi bi-gear text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Diproses</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $order->status === 'processing' || $order->status === 'delivered' || $order->status === 'completed' ? 'Pesanan sedang disiapkan' : 'Menunggu diproses' }}
                        </p>
                    </div>
                </div>

                <!-- Pesanan Dikirim -->
                <div class="relative flex items-start">
                    <div class="flex items-center justify-center w-11 h-11 rounded-full {{ $order->status === 'delivered' || $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} shrink-0">
                        <i class="bi bi-truck text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Dikirim</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $order->status === 'delivered' || $order->status === 'completed' ? 'Pesanan dalam perjalanan' : 'Menunggu pengiriman' }}
                        </p>
                    </div>
                </div>

                <!-- Pesanan Selesai -->
                <div class="relative flex items-start">
                    <div class="flex items-center justify-center w-11 h-11 rounded-full {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-gray-300' }} shrink-0">
                        <i class="bi bi-box-seam text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pesanan Selesai</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $order->status === 'completed' ? 'Pesanan telah diterima' : 'Menunggu konfirmasi penerimaan' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="bg-white rounded-xl shadow-sm p-6 mt-8">
        <div class="flex items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Detail Pesanan</h2>
        </div>
        <div class="flex flex-wrap -mx-4">
            <div class="w-full lg:w-1/2 xl:w-1/2 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Informasi Pengiriman</h3>
                <p class="text-sm text-gray-500 mb-1"><strong>{{ $order->user->name }}</strong></p>
                <p class="text-sm text-gray-500 mb-1">{{ $order->address->phone_number }}</p>
                <p class="text-sm text-gray-500">{{ $order->address->full_address }}</p>
            </div>
            <div class="w-full lg:w-1/2 xl:w-1/2 p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Metode Pembayaran</h3>
                <p class="text-sm text-blue-950 mb-1 font-medium capitalize">{{ $order->payment_method }}</p>
                <p class="text-sm text-gray-500 mb-0">Status: <span class="badge bg-{{ $order->payment_status === 'paid' ? 'green' : 'yellow' }} text-red-600 font-medium">{{ $order->payment_status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
