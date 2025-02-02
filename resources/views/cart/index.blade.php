@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 mt-12">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2" data-aos="fade-right">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold">Keranjang Belanja</h2>
                            <span class="text-gray-500">{{ $cartItems->count() }} item</span>
                        </div>

                        @if ($cartItems->count() > 0)
                            <!-- Select All -->
                            <div class="flex items-center pb-4 border-b">
                                <input type="checkbox" id="selectAll"
                                    class="w-4 h-4 text-custom rounded border-gray-300 focus:ring-custom">
                                <label for="selectAll" class="ml-2 text-sm text-gray-700">Pilih Semua</label>
                            </div>

                            <!-- Cart Items List -->
                            <div class="divide-y">
                                @foreach ($cartItems as $item)
                                    <div class="py-6 cart-item" data-id="{{ $item->id }}">
                                        <!-- Checkbox -->
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox"
                                                    class="item-checkbox w-4 h-4 text-custom border-gray-300 rounded focus:ring-custom"
                                                    value="{{ $item->id }}" data-price="{{ $item->product->harga }}"
                                                    data-discount="{{ $item->product->diskon }}"
                                                    id="item-{{ $item->id }}">
                                            </div>
                                            <div class="flex flex-1 ml-4">
                                                <a href="{{ route('produk.detail', $item->product->slug) }}">
                                                    <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                                        alt="{{ $item->product->nama_produk }}"
                                                        class="w-20 h-20 object-cover rounded-lg hover:scale-105 transition-all duration-300">
                                                </a>

                                                <div class="ml-4 flex-1">
                                                    <h3 class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('produk.detail', $item->product->slug) }}"
                                                            class="hover:text-custom hover:scale-105 transform transition-all duration-300 ease-in-out inline-block">
                                                            {{ $item->product->nama_produk }}
                                                        </a>
                                                    </h3>

                                                    <div class="mt-1 flex items-center text-sm">
                                                        <span class="font-medium text-danger">
                                                            Rp{{ number_format($item->product->harga_diskon, 0, ',', '.') }}
                                                        </span>
                                                    </div>

                                                    <div class="mt-4 flex items-center justify-between">

                                                        <!-- Quantity Controls -->
                                                        <div class="flex items-center border rounded-lg">
                                                            <button type="button"
                                                                class="quantity-btn px-3 py-1 text-gray-600 hover:text-custom"
                                                                data-action="decrease">
                                                                <i class="bi bi-dash"></i>
                                                            </button>

                                                            <input type="number" value="{{ $item->quantity }}"
                                                                min="1" max="{{ $item->product->stok }}"
                                                                data-stock="{{ $item->product->stok }}"
                                                                class="quantity-input w-12 text-center border-x py-1 focus:outline-none">

                                                            <button type="button"
                                                                class="quantity-btn px-3 py-1 text-gray-600 hover:text-custom"
                                                                data-action="increase">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                            class="delete-item text-gray-400 hover:text-custom"
                                                            data-id="{{ $item->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty Cart -->
                            <div class="text-center py-12">
                                <i class="bi bi-cart-x text-gray-400 text-6xl"></i>
                                <p class="mt-4 text-gray-500">Keranjang belanja kamu masih kosong</p>
                                <a href="{{ route('home.index') }}"
                                    class="inline-block mt-4 px-6 py-2 bg-custom text-white rounded-lg hover:bg-red-700 transition-colors">
                                    Mulai Belanja
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mt-8 lg:mt-0" data-aos="fade-left">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-lg font-bold mb-4">Ringkasan Belanja</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm" id="total-harga">
                            <span>Total Harga (0 barang)</span>
                            <span>Rp0</span>
                        </div>
                        <div class="flex justify-between text-sm" id="total-diskon">
                            <span>Total Diskon</span>
                            <span class="text-danger">-Rp0</span>
                        </div>
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between" id="total-belanja">
                            <strong>Total Belanja</strong>
                            <strong class="text-danger">Rp0</strong>
                        </div>
                    </div>

                    <button type="button" id="btn-checkout" data-checkout-url="{{ route('checkout.index') }}"
                        class="w-full mt-6 bg-custom text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Checkout (0)
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/cart-old.js') }}"></script>
    @endpush

    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
@endsection
