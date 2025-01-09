@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Keranjang Belanja</h4>

        <div class="row g-4">
            <!-- Main Cart Content -->
            <div class="col-lg-8">
                <!-- Select All Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
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
                                                <input class="form-check-input item-checkbox" type="checkbox"
                                                    data-price="{{ $item->product->harga }}"
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

@push('styles')
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
@endpush

<!--Perhitungan total belanja dan tombol beli dalam ringkasan belanja -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');

            // AJAX function for updating quantity
            async function updateQuantity(cartItemId, quantity) {
                try {
                    const response = await fetch(`/cart/${cartItemId}/quantity`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal mengupdate quantity');
                    }

                    return data;
                } catch (error) {
                    throw new Error(error.message || 'Gagal mengupdate quantity');
                }
            }

            // Update price display for individual items
            function updateItemPrice(cartItem) {
                const quantityInput = cartItem.querySelector('.quantity-input');
                const quantity = parseInt(quantityInput.value);
                const checkbox = cartItem.querySelector('.item-checkbox');
                const basePrice = parseFloat(checkbox.dataset.price);
                const priceDisplay = cartItem.querySelector('.text-danger');

                // Update individual item price display
                const totalItemPrice = basePrice * quantity;
                priceDisplay.textContent = `Rp${numberFormat(totalItemPrice)}`;
            }

            // Calculate and update summary with quantity consideration
            function calculateAndUpdateSummary() {
                let totalPrice = 0;
                let totalDiscount = 0;
                let selectedCount = 0;

                // Calculate totals for checked items
                document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                    const cartItem = checkbox.closest('.cart-item');
                    const quantity = parseInt(cartItem.querySelector('.quantity-input').value);
                    const price = parseFloat(checkbox.dataset.price);
                    const discount = parseFloat(checkbox.dataset.discount) || 0;

                    // Calculate total price and discount considering quantity
                    totalPrice += price * quantity;
                    totalDiscount += discount * quantity;
                    selectedCount += quantity;
                });

                // Update total price display
                const totalHargaElement = document.getElementById('total-harga');
                totalHargaElement.innerHTML = `
            <span>Total Harga (${selectedCount} barang)</span>
            <span>Rp${numberFormat(totalPrice)}</span>
        `;

                // Update total discount display
                const totalDiskonElement = document.getElementById('total-diskon');
                totalDiskonElement.innerHTML = `
            <span>Total Diskon</span>
            <span class="text-danger">${numberFormat(totalDiscount)}%</span>
        `;

                // Update grand total
                const grandTotal = totalPrice - totalDiscount;
                const totalBelanjaElement = document.getElementById('total-belanja');
                totalBelanjaElement.innerHTML = `
            <strong>Total Belanja</strong>
            <strong class="text-danger">Rp${numberFormat(grandTotal)}</strong>
        `;

                // Update buy button
                const buyButton = document.getElementById('btn-checkout');
                buyButton.disabled = selectedCount === 0;
                buyButton.textContent = `Checkout (${selectedCount})`;
            }

            // Helper function for number formatting
            function numberFormat(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            // Event listeners for quantity buttons
            document.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const cartItem = this.closest('.cart-item');
                    const input = cartItem.querySelector('.quantity-input');
                    const currentValue = parseInt(input.value);
                    const action = this.dataset.action;
                    const oldValue = currentValue; // Store old value in case of error

                    if (action === 'increase') {
                        input.value = currentValue + 1;
                    } else if (action === 'decrease' && currentValue > 1) {
                        input.value = currentValue - 1;
                    }

                    try {
                        // Update both individual item price and summary first for better UX
                        updateItemPrice(cartItem);
                        calculateAndUpdateSummary();

                        // Then make the AJAX call
                        const response = await updateQuantity(cartItem.dataset.id, input.value);
                        // alert(response.message); // Optional: show success message
                    } catch (error) {
                        // Revert changes if AJAX call fails
                        input.value = oldValue;
                        updateItemPrice(cartItem);
                        calculateAndUpdateSummary();
                        console.error('Error:', error);
                        alert(error.message);
                    }
                });
            });

            // Event listeners for manual quantity input
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', async function() {
                    const cartItem = this.closest('.cart-item');
                    const oldValue = this.defaultValue;
                    const newValue = Math.max(1, parseInt(this.value) || 1);
                    this.value = newValue;

                    try {
                        updateItemPrice(cartItem);
                        calculateAndUpdateSummary();

                        const response = await updateQuantity(cartItem.dataset.id, newValue);
                        this.defaultValue = newValue;
                        // alert(response.message); // Optional: show success message
                    } catch (error) {
                        this.value = oldValue;
                        updateItemPrice(cartItem);
                        calculateAndUpdateSummary();
                        console.error('Error:', error);
                        alert(error.message);
                    }
                });
            });

            // Event listeners for checkboxes
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    calculateAndUpdateSummary();
                });
            }

            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', calculateAndUpdateSummary);
            });

            // Initial calculation
            document.querySelectorAll('.cart-item').forEach(updateItemPrice);
            calculateAndUpdateSummary();

            // Event listeners for delete buttons
            document.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', async function() {
                    const cartItemId = this.dataset.id;
                    const cartItem = this.closest('.cart-item');

                    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                        try {
                            const response = await fetch(`/cart/${cartItemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            if (!response.ok) {
                                throw new Error('Gagal menghapus item');
                            }

                            // Hapus item dari DOM
                            cartItem.remove();
                            calculateAndUpdateSummary(); // Perbarui ringkasan belanja
                        } catch (error) {
                            console.error('Error:', error);
                            alert(error.message);
                        }
                    }
                });
            });
        });
    </script>
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


@push('scripts')
    <script>
        document.getElementById('btn-checkout').addEventListener('click', async function() {
            const selectedItems = [];
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');

            checkedBoxes.forEach(checkbox => {
                const cartItem = checkbox.closest('.cart-item');
                const productId = cartItem.dataset.id;
                const quantity = parseInt(cartItem.querySelector('.quantity-input').value);
                const price = parseFloat(checkbox.dataset.price);
                const discount = parseFloat(checkbox.dataset.discount) || 0;
                const productName = cartItem.querySelector('h6').textContent;
                const productImage = cartItem.querySelector('img').getAttribute('src');

                selectedItems.push({
                    id: productId,
                    name: productName,
                    quantity: quantity,
                    price: price,
                    discount: discount,
                    image: productImage,
                    subtotal: (price * quantity) - ((price * quantity * discount) / 100)
                });
            });

            if (selectedItems.length === 0) {
                alert('Pilih minimal satu produk untuk dibeli');
                return;
            }

            try {
                // Store selected items in session storage
                sessionStorage.setItem('checkoutItems', JSON.stringify(selectedItems));

                // Calculate totals
                const totalQuantity = selectedItems.reduce((sum, item) => sum + item.quantity, 0);
                const totalPrice = selectedItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const totalDiscount = selectedItems.reduce((sum, item) => sum + ((item.price * item.quantity *
                    item.discount) / 100), 0);
                const grandTotal = totalPrice - totalDiscount;

                // Store summary data
                sessionStorage.setItem('checkoutSummary', JSON.stringify({
                    totalQuantity,
                    totalPrice,
                    totalDiscount,
                    grandTotal
                }));

                // Redirect to checkout page
                window.location.href = '/checkout';
            } catch (error) {
                console.error('Error preparing checkout:', error);
                alert('Terjadi kesalahan saat memproses checkout. Silakan coba lagi.');
            }
        });
    </script>
@endpush
