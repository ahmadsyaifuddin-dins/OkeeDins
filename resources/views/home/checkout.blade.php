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

                <!-- Product List -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Produk yang Dibeli</h5>
                        <div id="checkout-items">
                            <div class="product-list">
                                <!-- Template for product items -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Metode Pembayaran</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                value="cod" checked>
                            <label class="form-check-label" for="cod">
                                COD (Cash on Delivery)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                value="transfer">
                            <label class="form-check-label" for="transfer">
                                Transfer Bank
                            </label>
                        </div>

                        <!-- Transfer proof upload (initially hidden) -->
                        <div id="transfer-proof" class="mt-3 d-none">
                            <label class="form-label">Upload Bukti Transfer</label>
                            <input type="file" class="form-control" id="proof_of_payment" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ringkasan Pesanan</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Harga (<span id="total-items">0</span> barang)</span>
                            <span id="total-price">Rp0</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Total Diskon</span>
                            <span id="total-discount">-Rp0</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total Tagihan</strong>
                            <strong class="text-danger" id="grand-total">Rp0</strong>
                        </div>

                        <button class="btn btn-primary w-100" id="btn-pay">
                            Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Retrieve stored checkout items
                const checkoutItems = JSON.parse(sessionStorage.getItem('checkoutItems') || '[]');
                const checkoutSummary = JSON.parse(sessionStorage.getItem('checkoutSummary') || '{}');

                if (checkoutItems.length === 0) {
                    window.location.href = '/cart';
                    return;
                }

                // Populate the checkout items
                const checkoutItemsContainer = document.getElementById('checkout-items');
                checkoutItems.forEach(item => {
                    const itemHtml = `
                <div class="checkout-item border-bottom py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="${item.image}" alt="${item.name}" class="rounded" width="80">
                        </div>
                        <div class="col">
                            <h6 class="mb-1">${item.name}</h6>
                            <div class="text-muted small">
                                ${item.quantity} x Rp${numberFormat(item.price)}
                                ${item.discount > 0 ? `<span class="text-danger">(Diskon ${item.discount}%)</span>` : ''}
                            </div>
                            <div class="text-danger mt-1">
                                Rp${numberFormat(item.subtotal)}
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    checkoutItemsContainer.innerHTML += itemHtml;
                });

                // Update summary section
                document.getElementById('total-items').textContent = checkoutSummary.totalQuantity;
                document.getElementById('total-price').textContent =
                    `Rp${numberFormat(checkoutSummary.totalPrice)}`;
                document.getElementById('total-discount').textContent =
                    `-Rp${numberFormat(checkoutSummary.totalDiscount)}`;
                document.getElementById('grand-total').textContent =
                    `Rp${numberFormat(checkoutSummary.grandTotal)}`;

                // Handle payment method selection
                const transferProofDiv = document.getElementById('transfer-proof');
                const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
                paymentMethods.forEach(method => {
                    method.addEventListener('change', function() {
                        transferProofDiv.classList.toggle('d-none', this.value !== 'transfer');
                    });
                });


                // Handle checkout form submission
                const btnPay = document.getElementById('btn-pay');
                btnPay.addEventListener('click', async function(e) {
                    e.preventDefault();

                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked')
                        .value;
                    let formData = new FormData();

                    // Pastikan setiap item memiliki ID yang benar
                    const itemsToSend = checkoutItems.map(item => ({
                        id: parseInt(item.id), // Pastikan ID dalam bentuk integer
                        price: item.price,
                        quantity: item.quantity,
                        discount: item.discount
                    }));

                    // Add payment method and items to form data
                    formData.append('payment_method', paymentMethod);
                    formData.append('items', JSON.stringify(itemsToSend));
                    // formData.append('items', JSON.stringify(checkoutItems));


                    // If transfer method is selected, add proof of payment
                    if (paymentMethod === 'transfer') {
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
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Clear cart session storage
                            sessionStorage.removeItem('checkoutItems');
                            sessionStorage.removeItem('checkoutSummary');

                            // Redirect to order success page or show success message
                            alert('Pesanan berhasil dibuat!');
                            window.location.href = '/order'; // Adjust the redirect URL as needed
                        } else {
                            throw new Error(result.message ||
                                'Terjadi kesalahan saat memproses pesanan');
                        }
                    } catch (error) {
                        console.error('Error processing checkout:', error);
                        alert(error.message);
                    }
                });

            } catch (error) {
                console.error('Error loading checkout data:', error);
                alert('Terjadi kesalahan saat memuat data checkout');
                window.location.href = '/cart';
            }
        });

        // Helper function for number formatting
        function numberFormat(number) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }
    </script>
@endsection
