@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Delivery Address -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Alamat Pengiriman</h5>
                            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addAddressModal">
                                <i class="bi bi-plus"></i> Tambah Alamat Baru
                            </button>
                        </div>

                        <div class="addresses-container">
                            @foreach (auth()->user()->addresses as $address)
                                <div class="address-item mb-3 p-3 border rounded @if ($address->is_primary) border-primary @endif">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="selected_address"
                                            id="address_{{ $address->id }}" value="{{ $address->id }}"
                                            @if ($address->is_primary) checked @endif>
                                        <label class="form-check-label" for="address_{{ $address->id }}">
                                            <strong>{{ $address->label }}</strong>
                                            @if ($address->is_primary)
                                                <span class="badge bg-primary ms-2">Utama</span>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="ms-4 mt-2">
                                        <p class="mb-1"><strong>{{ $address->receiver_name }}</strong></p>
                                        <p class="mb-1">{{ $address->phone_number }}</p>
                                        <p class="mb-0">{{ $address->full_address }}</p>
                                    </div>
                                    <div class="mt-2 ms-4">
                                        <button type="button" class="btn btn-link btn-sm p-0 text-primary me-3"
                                            data-bs-toggle="modal" data-bs-target="#editAddressModal"
                                            data-address-id="{{ $address->id }}">
                                            Edit
                                        </button>
                                        @if (!$address->is_primary)
                                            <button type="button" class="btn btn-link btn-sm p-0 text-danger"
                                                onclick="deleteAddress({{ $address->id }})">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Address Modals -->
                @include('components.address-modals')

                <!-- Purchase Details -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Detail Pembelian</h5>
                        <div id="checkout-items">
                            @foreach ($cartItems as $item)
                                <div class="d-flex mb-3 align-items-center">
                                    <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                        alt="{{ $item->product->nama_produk }}" class="img-thumbnail me-3"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product->nama_produk }}</h6>
                                        <p class="mb-1">{{ $item->quantity }} x Rp
                                            {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                        @if ($item->product->diskon > 0)
                                            <p class="mb-0 text-danger">
                                                Diskon {{ $item->product->diskon }}%
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <h6 class="mb-0">
                                            Rp
                                            {{ number_format($item->product->harga * $item->quantity - ($item->product->harga * $item->quantity * $item->product->diskon) / 100, 0, ',', '.') }}
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Catatan</h5>
                        <textarea class="form-control" id="notes" rows="3" placeholder="Tambahkan catatan untuk penjual (opsional)"></textarea>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Metode Pembayaran</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                value="transfer">
                            <label class="form-check-label" for="transfer">
                                Transfer Bank
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                value="Cash on Delivery">
                            <label class="form-check-label" for="cod">
                                Bayar di Tempat (COD)
                            </label>
                        </div>

                        <!-- Transfer Info -->
                        <div id="transfer-info" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Informasi Transfer:</h6>
                                <p class="mb-0">Setelah checkout, Anda akan diarahkan ke halaman pembayaran untuk melihat
                                    nomor rekening dan mengunggah bukti transfer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ringkasan Pesanan</h5>

                        <!-- Voucher Section -->
                        <div class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="voucher-code"
                                    placeholder="Masukkan kode voucher">
                                <button class="btn btn-custom" type="button" id="apply-voucher">
                                    Terapkan
                                </button>
                            </div>
                            <div id="voucher-message" class="mt-2"></div>
                        </div>

                        <!-- Order Summary Details -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp <span id="subtotal">{{ number_format($totalPrice, 0, ',', '.') }}</span></span>
                            </div>
                            @if ($totalDiscount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Diskon Produk</span>
                                    <span>-Rp <span
                                            id="product-discount">{{ number_format($totalDiscount, 0, ',', '.') }}</span></span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2" id="discount-row"
                                style="display: none !important;">
                                <span>Diskon Voucher</span>
                                <span>-Rp <span id="discount-amount">0</span></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span><strong>Total</strong></span>
                                <strong>Rp <span
                                        id="final-total">{{ number_format($grandTotal, 0, ',', '.') }}</span></strong>
                            </div>
                        </div>

                        <button id="btn-pay" class="btn btn-custom w-100"><i class="bi bi-shield-lock-fill me-2"></i>
                            Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/address.js') }}"></script>
        <script src="{{ asset('js/checkout.js') }}"></script>
    @endpush
