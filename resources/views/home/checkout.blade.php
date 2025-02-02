@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 mt-12">
        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <input type="hidden" name="address_id" id="address_id_input">
            <input type="hidden" name="total_amount" id="total_amount_input" value="{{ $cartTotal }}">
            @if(isset($directBuy) && $directBuy)
                <input type="hidden" name="quantity" value="{{ $cartItems->first()->quantity }}">
            @endif
            @foreach ($cartItems as $item)
                <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
            @endforeach

            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- Main Content (Address, Items, Notes, Payment) -->
                <div class="lg:col-span-2">
                    <!-- Delivery Address -->
                    <div class="bg-white rounded-lg shadow-sm mb-6" data-aos="fade-right">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-sm md:text-xl font-medium text-gray-900">Alamat Pengiriman</h2>
                                <button type="button"
                                    class="inline-flex items-center px-1 py-1 md:px-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-custom hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom"
                                    onclick="toggleModal('addAddressModal', true)">
                                    <i class="bi bi-plus mr-0 text-lg"></i>Alamat Baru
                                </button>
                            </div>

                            <div class="checkout-address-container space-y-4">
                                @foreach (auth()->user()->addresses as $address)
                                    <div
                                        class="p-4 border rounded-lg {{ $address->is_primary ? 'border-custom' : 'border-gray-200' }}">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="radio" name="selected_address"
                                                    id="address_{{ $address->id }}" value="{{ $address->id }}"
                                                    {{ $address->is_primary ? 'checked' : '' }}
                                                    class="w-4 h-4 text-custom border-gray-300 focus:ring-custom">
                                            </div>
                                            <div class="ml-3">
                                                <label for="address_{{ $address->id }}" class="font-medium text-gray-900">
                                                    {{ $address->label }}
                                                    @if ($address->is_primary)
                                                        <span
                                                            class="ml-2 px-2 py-1 text-xs font-semibold text-custom bg-red-50 rounded-lg">
                                                            Utama
                                                        </span>
                                                    @endif
                                                </label>
                                                <div class="mt-2 text-sm text-gray-700">
                                                    <p class="font-medium">{{ $address->receiver_name }}</p>
                                                    <p>{{ $address->phone_number }}</p>
                                                    <p>{{ $address->full_address }}</p>
                                                </div>
                                                <div class="mt-2 space-x-4">
                                                    <button type="button" class="text-sm text-custom hover:text-red-600"
                                                        data-modal-target="#editAddressModal"
                                                        data-address-id="{{ $address->id }}">
                                                        Edit
                                                    </button>
                                                    @if (!$address->is_primary)
                                                        <button type="button"
                                                            class="text-sm text-gray-500 hover:text-gray-600"
                                                            onclick="deleteAddress({{ $address->id }})">
                                                            Hapus
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Details -->
                    <div class="bg-white rounded-lg shadow-sm mb-6" data-aos="fade-up">
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Detail Pembelian</h2>
                            <div class="space-y-6">
                                @if (isset($directBuyItem))
                                    <!-- Direct Buy Item -->
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 w-24 h-24">
                                            <img src="{{ asset('storage/' . $directBuyItem->product->gambar) }}"
                                                alt="{{ $directBuyItem->product->nama_produk }}"
                                                class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <div class="flex-grow">
                                            <h3 class="text-gray-900 font-medium">
                                                {{ $directBuyItem->product->nama_produk }}</h3>
                                            <p class="text-gray-500 text-sm">{{ $directBuyItem->quantity }} x Rp
                                                {{ number_format($directBuyItem->product->harga, 0, ',', '.') }}</p>
                                            @if ($directBuyItem->product->diskon > 0)
                                                <p class="text-custom text-sm">Diskon
                                                    {{ $directBuyItem->product->diskon }}%</p>
                                            @endif
                                            <p class="text-gray-900 font-medium mt-1">Rp
                                                {{ number_format($directBuyItem->final_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <input type="hidden" name="direct_buy" value="true">
                                    <input type="hidden" name="produk_id" value="{{ $directBuyItem->product->id }}">
                                    <input type="hidden" name="quantity" value="{{ $directBuyItem->quantity }}">
                                @else
                                    <!-- Cart Items -->
                                    @foreach ($cartItems as $item)
                                        <div class="flex items-start pb-6 border-b border-gray-200 last:border-0 last:pb-0">
                                            <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                                alt="{{ $item->product->nama_produk }}"
                                                class="w-24 h-24 object-cover rounded-lg">

                                            <div class="ml-4 flex-1">
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    {{ $item->product->nama_produk }}
                                                </h3>
                                                <div class="mt-2 flex justify-between items-start">
                                                    <div>
                                                        <p class="text-sm text-gray-700">
                                                            {{ $item->quantity }} x
                                                            Rp{{ number_format($item->product->harga, 0, ',', '.') }}
                                                        </p>
                                                        @if ($item->product->diskon > 0)
                                                            <p class="text-sm text-custom">
                                                                Diskon {{ $item->product->diskon }}%
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right hidden sm:block">
                                                        @php
                                                            $itemTotal = $item->product->harga * $item->quantity;
                                                            $discount = ($itemTotal * $item->product->diskon) / 100;
                                                            $finalPrice = $itemTotal - $discount;
                                                        @endphp
                                                        <span class="text-sm font-medium text-gray-900">
                                                            Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-left block sm:hidden mt-2">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="bg-white rounded-lg shadow-sm mb-6" data-aos="fade-up">
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Catatan</h2>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom"
                                placeholder="Tambahkan catatan untuk penjual (opsional)"></textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm mb-6" data-aos="fade-up">
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" id="transfer" value="transfer"
                                        class="w-4 h-4 text-custom border-gray-300 focus:ring-custom">
                                    <label for="transfer" class="ml-3 text-sm font-medium text-gray-900">
                                        Transfer Bank
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="payment_method" id="cod" value="Cash on Delivery"
                                        class="w-4 h-4 text-custom border-gray-300 focus:ring-custom focus:ring-offset-2 ">
                                    <label for="cod" class="ml-3 text-sm font-medium text-gray-900">
                                        Bayar di Tempat (COD)
                                    </label>
                                </div>

                                <!-- Transfer Info -->
                                <div id="transfer-info" class="mt-4 p-4 bg-red-50 rounded-lg hidden">
                                    <h3 class="text-sm font-medium text-red-800 mb-2">Informasi Transfer:</h3>
                                    <p class="text-sm text-custom">
                                        Setelah checkout, Kamu akan diarahkan ke halaman pembayaran untuk melihat
                                        nomor rekening dan mengunggah bukti transfer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6" data-aos="flip-up">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <!-- Voucher Section -->
                    <div class="mb-6">
                        <label for="voucher_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Voucher
                        </label>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <input type="text" id="voucher_code" name="voucher_code"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom"
                                placeholder="Masukkan kode voucher">
                            <button type="button" id="apply_voucher"
                                class="px-4 py-2 border border-custom text-custom rounded-lg hover:bg-red-50">
                                Terapkan
                            </button>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Harga ({{ count($cartItems) }} barang)</span>
                            <span>Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Diskon Barang</span>
                            <span class="text-custom">-Rp{{ number_format($totalDiscount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diskon Voucher</span>
                            <span class="text-custom" id="voucher_discount">-Rp0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Ongkos Kirim</span>
                            <span>Rp0</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-bold">Total Tagihan</span>
                                <span class="text-lg font-bold text-custom" id="final_total">
                                    Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submit-order-btn"
                        class="w-full mt-6 px-4 py-3 text-white bg-custom rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-custom focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                        <span id="button-text" class="inline-flex items-center">
                            <i class="bi bi-shield-lock mr-2"></i> Buat Pesanan
                        </span>
                        <span id="button-loading" class="hidden inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="ml-2">Proses...</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Address Modals -->
    @include('components.address-modals')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/checkout.js') }}"></script>
        <script src="{{ asset('js/address-new.js') }}"></script>

        <script>
            // Fungsi untuk toggle modal
            function toggleModal(modalId, show) {
                const modal = document.getElementById(modalId);
                if (show) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Event listener untuk tombol tambah alamat
                document.querySelector('[data-modal-target="#addAddressModal"]').addEventListener('click', function() {
                    toggleModal('addAddressModal', true);
                });

                // Event listener untuk tombol edit alamat
                document.querySelectorAll('[data-modal-target="#editAddressModal"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const addressId = this.getAttribute('data-address-id');
                        document.querySelector('#editAddressForm [name="address_id"]').value =
                            addressId;
                        toggleModal('editAddressModal', true);
                    });
                });

                // Event listener untuk menutup modal ketika mengklik overlay
                document.querySelectorAll('.fixed.inset-0.bg-gray-500').forEach(overlay => {
                    overlay.addEventListener('click', function() {
                        const modalId = this.closest('[role="dialog"]').id;
                        toggleModal(modalId, false);
                    });
                });

                // Event listener untuk tombol escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        document.querySelectorAll('[role="dialog"]:not(.hidden)').forEach(modal => {
                            toggleModal(modal.id, false);
                        });
                    }
                });
            });

            // Set address_id_input value when radio button is clicked
            const addressRadios = document.querySelectorAll('input[name="selected_address"]');
            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('address_id_input').value = this.value;
                });

                // Set initial value if radio is checked
                if (radio.checked) {
                    document.getElementById('address_id_input').value = radio.value;
                }
            });

            // Show/hide transfer info based on payment method
            const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
            const transferInfo = document.getElementById('transfer-info');

            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'transfer') {
                        transferInfo.classList.remove('hidden');
                    } else {
                        transferInfo.classList.add('hidden');
                    }
                });
            });

            // Validate form before submission
            function validateCheckoutForm() {
                const submitBtn = document.getElementById('submit-order-btn');
                const buttonText = document.getElementById('button-text');
                const buttonLoading = document.getElementById('button-loading');

                // Disable button and show loading
                function setLoading(isLoading) {
                    submitBtn.disabled = isLoading;
                    if (isLoading) {
                        buttonText.classList.add('hidden');
                        buttonLoading.classList.remove('hidden');
                    } else {
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                    }
                }

                // Check if address is selected
                const addressId = document.getElementById('address_id_input').value;
                if (!addressId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Alamat Belum Dipilih',
                        text: 'Silakan pilih alamat pengiriman terlebih dahulu',
                        confirmButtonColor: '#EF4444'
                    });
                    return false;
                }

                // Check if payment method is selected
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                if (!paymentMethod) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Metode Pembayaran Belum Dipilih',
                        text: 'Silakan pilih metode pembayaran terlebih dahulu',
                        confirmButtonColor: '#EF4444'
                    });
                    return false;
                }

                setLoading(true);
                return true;
            }

            // Add form submit handler
            document.getElementById('checkout-form').addEventListener('submit', function() {
                return validateCheckoutForm();
            });
        </script>
    @endpush

@endsection
