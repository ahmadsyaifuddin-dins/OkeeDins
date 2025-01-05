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
                                <template id="product-template">
                                    <div class="cart-item border-bottom py-3">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <img src="" alt="Product Image" class="product-image rounded"
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>
                                            <div class="col">
                                                <h6 class="product-name mb-1"></h6>
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="text-danger product-price me-2"></span>
                                                    <span
                                                        class="text-decoration-line-through text-muted original-price"></span>
                                                </div>
                                                <div class="text-muted">Jumlah: <span class="product-quantity"></span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="text-end">
                                                    <div class="text-danger product-subtotal"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
        document.addEventListener('DOMContentLoaded', () => {
            const productList = document.querySelector('.product-list');
            const productTemplate = document.getElementById('product-template').content;
            const totalItems = document.getElementById('total-items');
            const totalPrice = document.getElementById('total-price');
            const totalDiscount = document.getElementById('total-discount');
            const grandTotal = document.getElementById('grand-total');

            // Fetch data produk dari server
            fetch('{{ route('api.cart.items') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let totalItemCount = 0;
                        let totalItemPrice = 0;
                        let totalItemDiscount = 0;

                        data.items.forEach(item => {
                            const productClone = productTemplate.cloneNode(true);

                            const productImage = productClone.querySelector('.product-image');
                            const productName = productClone.querySelector('.product-name');
                            const productPrice = productClone.querySelector('.product-price');
                            const originalPrice = productClone.querySelector('.original-price');
                            const productQuantity = productClone.querySelector('.product-quantity');
                            const productSubtotal = productClone.querySelector('.product-subtotal');

                            productImage.src = item.image_url;
                            productName.textContent = item.name;
                            productPrice.textContent = `Rp${item.price.toLocaleString()}`;
                            originalPrice.textContent = item.original_price ?
                                `Rp${item.original_price.toLocaleString()}` :
                                '';
                            productQuantity.textContent = item.quantity;
                            productSubtotal.textContent =
                                `Rp${(item.price * item.quantity).toLocaleString()}`;

                            productList.appendChild(productClone);

                            totalItemCount += item.quantity;
                            totalItemPrice += item.price * item.quantity;
                            totalItemDiscount += item.original_price ?
                                (item.original_price - item.price) * item.quantity :
                                0;
                        });

                        // Update total summary
                        totalItems.textContent = totalItemCount;
                        totalPrice.textContent = `Rp${totalItemPrice.toLocaleString()}`;
                        totalDiscount.textContent = `-Rp${totalItemDiscount.toLocaleString()}`;
                        grandTotal.textContent = `Rp${(totalItemPrice - totalItemDiscount).toLocaleString()}`;
                    } else {
                        alert('Gagal memuat data produk. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching cart items:', error);
                    alert('Terjadi kesalahan saat memuat data produk.');
                });
        });
    </script>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
            const transferProofDiv = document.getElementById('transfer-proof');
            const btnPay = document.getElementById('btn-pay');

            // Toggle visibility of proof of payment upload
            paymentMethodInputs.forEach(input => {
                input.addEventListener('change', () => {
                    if (input.value === 'transfer') {
                        transferProofDiv.classList.remove('d-none');
                    } else {
                        transferProofDiv.classList.add('d-none');
                    }
                });
            });

            // Handle "Bayar Sekarang" button click
            btnPay.addEventListener('click', () => {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                const proof = document.getElementById('proof_of_payment').files[0];

                if (paymentMethod === 'transfer' && !proof) {
                    alert('Harap upload bukti transfer!');
                    return;
                }

                // Gather data and send to server
                const data = {
                    paymentMethod,
                    // Include other necessary data, e.g., cart items, total price, etc.
                };

                if (paymentMethod === 'transfer') {
                    const formData = new FormData();
                    formData.append('proof', proof);
                    formData.append('data', JSON.stringify(data));

                    fetch('{{ route('checkout.process') }}', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Pembayaran berhasil diproses');
                                window.location.href = `{{ url('orders') }}/${data.order.id}/complete`;
                            } else {
                                alert('Terjadi kesalahan saat memproses pembayaran');
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghubungi server.');
                        });
                } else {
                    // Handle COD
                    alert('Pesanan COD berhasil dibuat');
                    window.location.href = `{{ url('orders') }}/${data.order.id}/complete`;
                }
            });
        });
    </script>
@endsection
