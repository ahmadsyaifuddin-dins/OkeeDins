@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button & Title -->
        <div class="flex items-center mb-8">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <i class="bi bi-arrow-left text-xl mr-2"></i>
                <span class="text-sm font-medium">Kembali</span>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 ml-4">Riwayat Pesanan</h1>
        </div>

        <!-- Filter Status pesanan-->
        <div class="mb-6">
            <div class="flex overflow-x-auto pb-2 gap-2 md:gap-3 no-scrollbar">
                <a href="{{ route('orders.history') }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ !request('status') ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Semua
                </a>
                <a href="{{ route('orders.history', ['status' => 'pending']) }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'pending' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Menunggu
                </a>
                <a href="{{ route('orders.history', ['status' => 'awaiting payment']) }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'awaiting payment' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Belum Bayar
                </a>
                <a href="{{ route('orders.history', ['status' => 'processing']) }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'processing' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Diproses
                </a>
                <a href="{{ route('orders.history', ['status' => 'delivered']) }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'delivered' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Dikirim
                </a>
                <a href="{{ route('orders.history', ['status' => 'completed']) }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'completed' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Selesai
                </a>
                <a href="{{ route('orders.history', ['status' => 'cancelled']) }}"
                    class="inline-flex items-center justify-center min-w-[100px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'cancelled' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Dibatalkan
                </a>
            </div>
        </div>

        <style>
            /* Hide scrollbar for Chrome, Safari and Opera */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            /* Hide scrollbar for IE, Edge and Firefox */
            .no-scrollbar {
                -ms-overflow-style: none;
                /* IE and Edge */
                scrollbar-width: none;
                /* Firefox */
            }
        </style>

        <!-- Empty State -->
        @if ($orders->isEmpty())
            <div class="text-center py-16">
                <i class="bi bi-bag-x text-6xl text-gray-400 mb-4"></i>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h2>
                <p class="text-gray-500 mb-8">Anda belum memiliki riwayat pesanan</p>
                <a href="{{ route('home.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-custom text-white font-medium rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="bi bi-cart-plus mr-2"></i>
                    Mulai Belanja
                </a>
            </div>
        @else
            <!-- Order List -->
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <!-- Order Item -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <!-- Order Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                                <div class="mb-2 sm:mb-0">
                                    <div class="flex items-center text-gray-500 text-sm mb-1">
                                        <i class="bi bi-calendar2 mr-2"></i>
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </div>
                                    <div class="flex items-center text-gray-900">
                                        <span class="font-medium">Order
                                            ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="mx-2">•</span>
                                        <span class="text-blue-950 font-medium"><i
                                                class="bi bi-shop mr-1"></i>OkeeDins</span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>

                            @php
                                $firstItem = $order->orderItems->first();
                                $otherItemsCount = $order->orderItems->count() - 1;
                            @endphp

                            <!-- Product Item -->
                            <div class="flex items-start space-x-4">
                                <img src="{{ asset('storage/' . $firstItem->produk->gambar) }}"
                                    alt="{{ $firstItem->produk->nama_produk }}"
                                    class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-medium text-gray-900 mb-1">
                                        {{ $firstItem->produk->nama_produk }}</h3>
                                    <p class="text-sm text-gray-500 mb-1">{{ $firstItem->quantity }} x
                                        Rp{{ number_format($firstItem->price, 0, ',', '.') }}</p>
                                    @if ($otherItemsCount > 0)
                                        <p class="text-sm text-gray-500">+ {{ $otherItemsCount }} produk lainnya</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Footer -->
                            <div
                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-4 pt-4 border-t border-gray-200">
                                <div class="mb-4 sm:mb-0">
                                    <p class="text-sm text-gray-500 mb-1">Total Pesanan</p>
                                    <p class="text-lg font-semibold text-gray-900">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex space-x-3">
                                    @if (in_array($order->status, ['processing', 'delivered']))
                                        <a href="{{ route('orders.track', $order->id) }}"
                                            class="inline-flex items-center px-4 py-2 border border-custom text-custom font-medium rounded-lg hover:bg-red-50 transition duration-200">
                                            <i class="bi bi-truck mr-2"></i>Lacak
                                        </a>
                                    @endif
                                    @if ($order->status === 'pending')
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200">
                                                <i class="bi bi-x-circle mr-2"></i>Batalkan
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('orders.detail', $order->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-custom text-white font-medium rounded-lg hover:bg-red-700 transition duration-200">
                                        <i class="bi bi-eye mr-2"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .bg-custom {
            background-color: #D32F2F;
        }

        .text-custom {
            color: #D32F2F;
        }

        .border-custom {
            border-color: #D32F2F;
        }
    </style>
@endpush
