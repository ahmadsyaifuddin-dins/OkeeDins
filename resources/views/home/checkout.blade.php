@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Checkout</h4>

        <div class="row">
            <!-- Delivery Address -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Alamat Pengiriman</h5>
                        <div class="mb-3">
                            <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                            <p class="mb-0">{{ auth()->user()->alamat }}</p>
                        </div>
                    </div>
                </div>

                <!-- Purchase Details -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Detail Pembelian</h5>
                        <div id="checkout-items">
                            @if (isset($cartItems))
                                @if ($isFromCart)
                                    {{-- Cart checkout items --}}
                                    @foreach ($cartItems as $item)
                                        @php
                                            $originalPrice = $item->product->harga * $item->quantity;
                                            $discountAmount = ($originalPrice * $item->product->diskon) / 100;
                                            $subtotal = $originalPrice - $discountAmount;
                                        @endphp
                                        <div class="checkout-item border-bottom py-3"
                                            data-item-id="{{ $item->product->id }}" data-price="{{ $item->product->harga }}"
                                            data-quantity="{{ $item->quantity }}"
                                            data-discount="{{ $item->product->diskon }}">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                                        alt="{{ $item->product->nama_produk }}" class="rounded"
                                                        width="80">
                                                </div>
                                                <div class="col">
                                                    <h6 class="mb-1">{{ $item->product->nama_produk }}</h6>
                                                    <div class="text-muted small">
                                                        {{ $item->quantity }} x
                                                        Rp{{ number_format($item->product->harga, 0, ',', '.') }}
                                                        @if ($item->product->diskon > 0)
                                                            <span class="text-danger">(Diskon
                                                                {{ $item->product->diskon }}%)</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-danger mt-1">
                                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Direct purchase items --}}
                                    @foreach ($cartItems as $item)
                                        @php
                                            $originalPrice = $item['price'] * $item['quantity'];
                                            $discountAmount = ($originalPrice * $item['discount']) / 100;
                                            $subtotal = $originalPrice - $discountAmount;
                                        @endphp
                                        <div class="checkout-item border-bottom py-3"
                                            data-item-id="{{ $item['product']->id }}" data-price="{{ $item['price'] }}"
                                            data-quantity="{{ $item['quantity'] }}"
                                            data-discount="{{ $item['discount'] }}">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img src="{{ asset('storage/' . $item['product']->gambar) }}"
                                                        alt="{{ $item['product']->nama_produk }}" class="rounded"
                                                        width="80">
                                                </div>
                                                <div class="col">
                                                    <h6 class="mb-1">{{ $item['product']->nama_produk }}</h6>
                                                    <div class="text-muted small">
                                                        {{ $item['quantity'] }} x
                                                        Rp{{ number_format($item['price'], 0, ',', '.') }}
                                                        @if ($item['discount'] > 0)
                                                            <span class="text-danger">(Diskon
                                                                {{ $item['discount'] }}%)</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-danger mt-1">
                                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Metode Pembayaran</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                value="Cash on Delivery" checked>
                            <label class="form-check-label" for="cod">
                                COD (Cash on Delivery)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                value="Transfer">
                            <label class="form-check-label" for="transfer">
                                Transfer Bank
                            </label>
                        </div>

                        <div id="transfer-proof" class="mt-3 d-none">
                            <label class="form-label">Upload Bukti Transfer</label>
                            <input type="file" class="form-control" id="proof_of_payment" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Section -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ringkasan Pesanan</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga (@if ($isFromCart)
                                    {{ $cartItems->count() }}
                                @else
                                    1
                                @endif barang)</span>
                            <span id="total-price">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Total Diskon</span>
                            <span id="total-discount">-Rp{{ number_format($totalDiscount, 0, ',', '.') }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total Tagihan</strong>
                            <strong class="text-danger" id="grand-total">
                                Rp{{ number_format($grandTotal, 0, ',', '.') }}
                            </strong>
                        </div>

                        <button class="btn btn-primary w-100" id="btn-pay">
                            <i class="bi bi-shield-lock"></i> Bayar Sekarang
                        </button>

                        <input type="hidden" id="is-from-cart" value="{{ $isFromCart ? 'true' : 'false' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle payment method selection
            const transferProofDiv = document.getElementById('transfer-proof');
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    transferProofDiv.classList.toggle('d-none', this.value !== 'Transfer');
                });
            });

            // Handle checkout form submission
            const btnPay = document.getElementById('btn-pay');
            btnPay.addEventListener('click', async function(e) {
                e.preventDefault();

                const paymentMethod = document.querySelector('input[name="payment_method"]:checked')
                    .value;
                let formData = new FormData();

                // Collect items data from the DOM
                const items = [];
                document.querySelectorAll('.checkout-item').forEach(item => {
                    items.push({
                        id: parseInt(item.dataset.itemId),
                        price: parseFloat(item.dataset.price),
                        quantity: parseInt(item.dataset.quantity),
                        discount: parseFloat(item.dataset.discount)
                    });
                });

                // Add payment method and items to form data
                formData.append('payment_method', paymentMethod);
                formData.append('items', JSON.stringify(items));

                // If transfer method is selected, add proof of payment
                if (paymentMethod === 'Transfer') {
                    const proofFile = document.getElementById('proof_of_payment').files[0];
                    if (!proofFile) {
                        alert('Silakan upload bukti transfer terlebih dahulu');
                        return;
                    }
                    formData.append('proof_of_payment', proofFile);
                }

                try {
                    const response = await fetch('/checkout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errorResult = await response
                            .text(); // Jika respons bukan JSON, ambil sebagai teks
                        console.error('Error response:', errorResult);
                        throw new Error('Terjadi kesalahan saat memproses pesanan');
                    }

                    const result = await response.json();

                    if (response.ok) {
                        // Clear cart session storage if it exists
                        if (sessionStorage.getItem('checkoutItems')) {
                            sessionStorage.removeItem('checkoutItems');
                            sessionStorage.removeItem('checkoutSummary');
                        }

                        alert('Pesanan berhasil dibuat!');
                        window.location.href = result.redirect_url;
                    } else {
                        throw new Error(result.message || 'Terjadi kesalahan saat memproses pesanan');
                    }
                } catch (error) {
                    console.error('Error processing checkout:', error);
                    alert(error.message);
                }
            });
        });
    </script>
@endsection
