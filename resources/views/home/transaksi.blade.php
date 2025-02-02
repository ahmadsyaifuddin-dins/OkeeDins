@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button & Title -->
        <div class="flex items-center mb-8">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <i class="bi bi-arrow-left text-xl mr-2"></i>
                <span class="text-sm font-medium">Kembali</span>
            </a>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 ml-4">Riwayat Transaksi</h1>
        </div>

        <!-- Transaction Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Total Pengeluaran COD -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center mb-2">
                    <i class="bi bi-wallet2 text-custom text-xl mr-2"></i>
                    <h6 class="text-gray-700 font-medium">Total Pengeluaran (COD)</h6>
                </div>
                <div class="mt-2">
                    <h4 class="text-2xl font-bold text-gray-900">Rp{{ number_format($totalCODSpent, 0, ',', '.') }}</h4>
                    <span class="text-sm text-gray-500">Bulan ini</span>
                </div>
            </div>

            <!-- Total Pesanan -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center mb-2">
                    <i class="bi bi-box-seam text-custom text-xl mr-2"></i>
                    <h6 class="text-gray-700 font-medium">Total Pesanan COD</h6>
                </div>
                <div class="mt-2">
                    <h4 class="text-2xl font-bold text-gray-900">{{ $totalCompletedOrders }}</h4>
                    <span class="text-sm text-gray-500">Pesanan selesai</span>
                </div>
            </div>

            <!-- Total Pengeluaran Transfer -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center mb-2">
                    <i class="bi bi-credit-card text-custom text-xl mr-2"></i>
                    <h6 class="text-gray-700 font-medium">Total Pengeluaran (Transfer)</h6>
                </div>
                <div class="mt-2">
                    <h4 class="text-2xl font-bold text-gray-900">Rp{{ number_format($totalTransferSpent, 0, ',', '.') }}
                    </h4>
                    <span class="text-sm text-gray-500">Bulan ini</span>
                </div>
            </div>

            <!-- Total Pesanan Transfer -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <div class="flex items-center mb-3">
                    <i class="bi bi-arrow-left-right text-custom text-xl sm:text-2xl mr-2"></i>
                    <h6 class="text-gray-700 font-medium text-sm sm:text-base">Total Pesanan Transfer</h6>
                </div>
                <div class="mt-2">
                    <h4 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totalTransferOrders }}</h4>
                    <div class="mt-3 flex flex-wrap gap-2 text-xs sm:text-sm">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">{{ $totalSuccessTransferOrders }}
                            Berhasil</span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">{{ $totalPendingTransferOrders }}
                            Menunggu</span>
                        <span
                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">{{ $totalAwaitingConfirmPaymentTransferOrders }}
                            Konfirmasi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Status transaksi-->
        <div class="mb-6">
            <div class="flex overflow-x-auto pb-2 gap-2 md:gap-3 no-scrollbar">
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex items-center justify-center min-w-[200px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ !request('status') ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Semua
                </a>
                <a href="{{ route('transactions.index', ['status' => 'pending']) }}"
                    class="inline-flex items-center justify-center min-w-[200px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'pending' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Menunggu Pembayaran
                </a>
                <a href="{{ route('transactions.index', ['status' => 'awaiting payment']) }}"
                    class="inline-flex items-center justify-center min-w-[200px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'awaiting payment' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Menunggu Konfirmasi
                </a>
                <a href="{{ route('transactions.index', ['status' => 'completed']) }}"
                    class="inline-flex items-center justify-center min-w-[200px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'completed' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
                    Berhasil
                </a>
                <a href="{{ route('transactions.index', ['status' => 'cancelled']) }}"
                    class="inline-flex items-center justify-center min-w-[200px] h-10 px-4 rounded-full text-sm font-medium whitespace-nowrap {{ request('status') === 'cancelled' ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} transition duration-200">
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
        @if ($transactions->isEmpty())
            <div class="text-center py-16">
                <i class="bi bi-receipt text-6xl text-gray-400 mb-4"></i>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Transaksi</h2>
                <p class="text-gray-500 mb-8">Anda belum memiliki riwayat transaksi</p>
                <a href="{{ route('home.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-custom text-white font-medium rounded-lg hover:bg-red-700 transition duration-200">
                    <i class="bi bi-cart-plus mr-2"></i>
                    Mulai Belanja
                </a>
            </div>
        @else
            <!-- Transaction List -->
            <div class="space-y-4">
                @foreach ($transactions as $transaction)
                    <!-- Transaction Item -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <!-- Transaction Header -->
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden p-4 sm:p-6">
                                <div class="flex flex-col space-y-4">
                                    <!-- Transaction Header -->
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                        <div class="mb-2 sm:mb-0">
                                            <div class="flex items-center text-gray-500 text-sm mb-1">
                                                <i class="bi bi-calendar2 mr-2"></i>
                                                {{ $transaction->created_at->format('d M Y H:i') }}
                                            </div>
                                            <div
                                                class="flex flex-col sm:flex-row items-start sm:items-center text-gray-900">
                                                <span class="font-medium mb-1 sm:mb-0">
                                                    Transaksi #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                                                </span>
                                                <span class="hidden sm:inline mx-2">•</span>
                                                <span class="text-blue-950 font-medium flex items-center">
                                                    <i
                                                        class="{{ $transaction->payment_method === 'Cash on Delivery' ? 'bi-wallet2' : 'bi-credit-card' }} mr-1"></i>
                                                    {{ ucfirst($transaction->payment_method) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:mt-0">
                                            @if ($transaction->payment_method === 'transfer')
                                                <span
                                                    class="px-3 py-1 rounded-full text-sm font-medium inline-block
                                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($transaction->status === 'awaiting payment' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ $transaction->status === 'completed'
                                                        ? 'Berhasil'
                                                        : ($transaction->status === 'pending'
                                                            ? 'Menunggu Pembayaran'
                                                            : ($transaction->status === 'awaiting payment'
                                                                ? 'Menunggu Konfirmasi'
                                                                : 'Dibatalkan')) }}
                                                </span>
                                            @else
                                                @if (in_array($transaction->status, ['completed', 'cancelled']))
                                                    <span
                                                        class="px-3 py-1 rounded-full text-sm font-medium inline-block
                                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $transaction->status === 'completed' ? 'Berhasil' : 'Dibatalkan' }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Transaction Details -->
                                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                            <span class="text-sm text-gray-600 mb-1 sm:mb-0">Total Pembayaran</span>
                                            <span
                                                class="text-base md:text-lg font-semibold text-gray-900">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                        </div>
                                        @if ($transaction->payment_date)
                                            <div
                                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                                <span class="text-sm text-gray-600 mb-1 sm:mb-0">Tanggal Pembayaran</span>
                                                <span
                                                    class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaction->payment_date)->format('d M Y H:i') }}</span>
                                            </div>
                                        @endif
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                            <span class="text-sm text-gray-600 mb-1 sm:mb-0">Metode Pembayaran</span>
                                            <span
                                                class="text-sm text-gray-900">{{ ucfirst($transaction->payment_method) }}</span>
                                        </div>
                                        @if ($transaction->payment_proof)
                                            <div
                                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                                <span class="text-sm text-gray-600 mb-1 sm:mb-0">Bukti Pembayaran</span>
                                                <a href="{{ asset('storage/' . $transaction->payment_proof) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center text-sm text-custom hover:text-red-700 transition-colors duration-200">
                                                    <i class="bi bi-image mr-1"></i>
                                                    Lihat Bukti
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Transaction Footer -->
                                    <div
                                        class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-4 pt-4 border-t border-gray-200">
                                        @if ($transaction->status === 'pending' && $transaction->payment_method === 'transfer')
                                            <a href="{{ route('payment.show', $transaction->order_id) }}"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-custom text-white font-medium rounded-lg hover:bg-red-700 transition duration-200">
                                                <i class="bi bi-credit-card mr-2"></i>
                                                Bayar Sekarang
                                            </a>
                                        @endif
                                        <a href="{{ route('orders.detail', $transaction->order_id) }}"
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-custom text-custom font-medium rounded-lg hover:bg-red-50 transition duration-200">
                                            <i class="bi bi-eye mr-2"></i>
                                            Detail Pesanan
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if (method_exists($transactions, 'links'))
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>

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

@endsection
