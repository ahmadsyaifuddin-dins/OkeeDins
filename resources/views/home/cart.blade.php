@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <h4 class="mb-4">Keranjang Belanja</h4>
        <div class="row g-4">
            <!-- Main Cart Content -->
            <div class="col-lg-8">
                <!-- Select All Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input checkbox-custom" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                Pilih Semua
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Cart Items Group by Store -->
                <div class="card mb-3">

                    <div class="card-body">
                        <!-- Store Header -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="store1">
                            </div>
                            <div class="ms-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shop me-2"></i>
                                    <h6 class="mb-0">Food Fusion</h6>
                                    <span class="badge bg-custom ms-2">Official Store</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="cart-items">
                            @forelse($cartItems as $item)
                                <!-- Single Cart Item -->
                                <div class="cart-item border-top pt-3" data-id="{{ $item->id }}">
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-custom item-checkbox"
                                                    type="checkbox" data-price="{{ $item->product->harga }}"
                                                    data-discount="{{ $item->product->diskon ?? 0 }}"
                                                    id="item-{{ $item->id }}">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="{{ route('produk.detail', $item->product->slug) }}">

                                                <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                                    class="rounded img-fluid" alt="{{ $item->product->nama_produk }}"
                                                    width="100">
                                            </a>
                                        </div>

                                        <!-- Product Info & Quantity -->
                                        <div class="col">
                                            <div class="d-flex flex-column h-100">

                                                <!-- Product Name & Price -->
                                                <div class="mb-2">
                                                    <a href="{{ route('produk.detail', $item->product->slug) }}"
                                                        class="text-decoration-none text-custom">
                                                        <h6 class="mb-1">{{ $item->product->nama_produk }}</h6>
                                                    </a>
                                                    <div class="text-danger mt-1">
                                                        Rp{{ number_format($item->product->harga, 0, ',', '.') }}
                                                    </div>
                                                </div>

                                                <!-- Quantity Controls & Delete Button -->
                                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <div class="quantity-controls d-flex align-items-center">
                                                        <button class="btn btn-outline-custom btn-sm quantity-btn"
                                                            data-action="decrease">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <input type="number"
                                                            class="form-control form-control-sm mx-2 my-3 text-center quantity-input"
                                                            value="{{ $item->quantity }}" style="width: 45px;">
                                                        <button class="btn btn-outline-custom btn-sm quantity-btn"
                                                            data-action="increase">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Item Actions btn Hapus-->
                                                    <button
                                                        class="btn btn-link text-danger text-decoration-none btn-sm delete-item"
                                                        data-id="{{ $item->id }}">

                                                        <i class="bi bi-trash "></i> Hapus

                                                    </button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="container mt-4">

                                    <div class="text-center py-5">

                                        <i class="bi bi-cart-x display-1 text-muted"></i>

                                        <h4 class="mt-3">Wah, keranjang belanjamu kosong</h4>

                                        <p class="text-muted">Yuk, isi dengan barang-barang menarik</p>

                                        <a href="{{ route('home.index') }}" class="btn btn-custom">

                                            Mulai Belanja

                                        </a>

                                    </div>

                                </div>
                            @endforelse

                        </div>

                    </div>

                </div>

            </div>

            <!-- Shopping Summary -->
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title mb-4">Ringkasan Belanja</h5>

                        <div class="d-flex justify-content-between mb-3" id="total-harga">

                            <span>Total Harga (0 barang)</span>

                            <span>Rp0</span>

                        </div>

                        <div class="d-flex justify-content-between mb-3" id="total-diskon">

                            <span>Total Diskon</span>

                            <span class="text-danger">-Rp0</span>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4" id="total-belanja">

                            <strong>Total Belanja</strong>

                            <strong class="text-danger">Rp0</strong>

                        </div>

                        <button class="btn btn-custom w-100" id="btn-checkout" disabled>

                            Checkout (0)

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Empty Cart State -->
    <div class="container mt-4 d-none">

        <div class="text-center py-5">

            <i class="bi bi-cart-x display-1 text-muted"></i>

            <h4 class="mt-3">Wah, keranjang belanjamu kosong</h4>

            <p class="text-muted">Yuk, isi dengan barang-barang menarik</p>

            <a href="{{ route('home.index') }}" class="btn btn-custom">

                Mulai Belanja

            </a>

        </div>

    </div>
@endsection


<!--Perhitungan total belanja dan tombol beli dalam ringkasan belanja -->
@push('scripts')
   <script src="{{ asset('js/cart.js') }}"></script>
@endpush

<style>
    /* Menghilangkan tombol spinner untuk input number */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
        /* Firefox */
    }
</style>

    <style>
        /* Hide number input spinners */

        input[type="number"]::-webkit-inner-spin-button,

        input[type="number"]::-webkit-outer-spin-button {

            -webkit-appearance: none;

            margin: 0;

        }

        input[type="number"] {

            -moz-appearance: textfield;

        }

        .cart-item {

            padding: 1rem 0;

        }

        .cart-item:last-child {

            border-bottom: none;

            padding-bottom: 0;

            margin-bottom: 0;

        }

        .quantity-controls {

            min-width: 120px;

        }

        .quantity-controls .btn {

            padding: 0.25rem 0.5rem;

            line-height: 1;

        }

        .quantity-input {

            height: 28px;

            font-size: 0.875rem;

        }


        @media (max-width: 576px) {

            .cart-item .row {

                --bs-gutter-x: 0.5rem;

            }

            .quantity-controls {

                min-width: 110px;

            }


            .quantity-input {

                width: 40px !important;

                padding: 0.25rem;

            }

            .delete-item {

                padding: 0;

                font-size: 0.75rem;

            }

        }
    </style>
