@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Product Images Section -->
            <div class="col-md-5">
                <div class="card">
                    <img src="{{ asset('storage/' . $product->gambar) }}" class="img-fluid" alt="{{ $product->nama_produk }}">
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="col-md-7">
                <h1 class="h2 mb-2">{{ $product->nama_produk }}</h1>

                <div class="d-flex align-items-center mb-3">
                    <div class="rating me-2">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-half text-warning"></i>
                        <span class="ms-1">4.5</span>
                    </div>
                    <div class="vr mx-2"></div>
                    <span class="text-muted">100+ Terjual</span>
                </div>

                <!-- Price Section -->
                <div class="mb-3">
                    <h2 class="h3 text-danger mb-1">
                        Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                    </h2>
                    @if ($product->diskon > 0)
                        <div class="d-flex align-items-center">
                            <span class="text-muted text-decoration-line-through me-2">
                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                            </span>
                            <span class="badge bg-danger">{{ $product->diskon }}% OFF</span>
                        </div>
                    @endif
                </div>

                <!-- Quantity Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <button id="btn-minus" class="btn btn-outline-primary">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" id="quantity" class="form-control text-center mx-2" value="1"
                            max="{{ $product->stok }}" style="width: 80px;">
                        <button id="btn-plus" class="btn btn-outline-primary">
                            <i class="bi bi-plus"></i>
                        </button>
                        <span class="ms-3 text-muted">Stok: Sisa {{ $product->stok }}</span>
                    </div>
                    <small id="qtyError" class="text-danger d-none">Jumlah tidak boleh kurang dari 1 atau melebihi
                        stok</small>
                </div>

                <!-- Subtotal -->
                <div class="mb-3">
                    <h5 class="text-muted">Subtotal:</h5>
                    <h3 id="subtotal" class="text-danger">
                        Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                    </h3>
                </div>


                <!-- Buttons Qty -->
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-primary flex-grow-1" onclick="addToCart()">
                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>

                <button class="btn btn-outline-primary w-100">
                    <i class="bi bi-cash"></i> Beli Sekarang
                </button>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="description-tab" data-bs-toggle="tab" href="#description">
                            Spesifikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews">
                            Ulasan
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-4 border border-top-0" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description">
                        <!-- Add the description section first -->
                        <div class="mb-4">
                            <h5 class="mb-3">Deskripsi Produk</h5>
                            <div class="description-content">
                                {{ $product->deskripsi }}
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 1rem">Kategori</td>
                                    <td>: {{ $product->kategori->nama_kategori }}</td>
                                </tr>
                                <tr>
                                    <td>Berat</td>
                                    <td>: {{ $product->berat }} gram</td>
                                </tr>
                                <tr>
                                    <td>Stok</td>
                                    <td>: {{ $product->stok }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="reviews">
                        <p>Belum ada ulasan untuk produk ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const hargaProduk = {{ $product->harga_diskon }};
        const stokProduk = {{ $product->stok }};
        const qtyInput = document.getElementById('quantity');
        const minusBtn = document.getElementById('btn-minus');
        const plusBtn = document.getElementById('btn-plus');
        const subtotalElement = document.getElementById('subtotal');
        const errorText = document.getElementById('qtyError');

        function validateAndUpdateQty(value) {
            // Jika input kosong, set subtotal ke 0 dan return
            if (value === '' || value === null) {
                subtotalElement.textContent = 'Rp0';
                errorText.classList.add('d-none');
                return;
            }

            let qty = parseInt(value);

            // Jika input bukan angka, kosongkan input
            if (isNaN(qty)) {
                qtyInput.value = '';
                subtotalElement.textContent = 'Rp0';
                errorText.classList.remove('d-none');
                return;
            }

            // Validasi range angka
            if (qty > stokProduk) {
                qty = stokProduk;
                qtyInput.value = qty;
                errorText.classList.remove('d-none');
            } else if (qty < 1) {
                qtyInput.value = '';
                subtotalElement.textContent = 'Rp0';
                errorText.classList.remove('d-none');
                return;
            } else {
                errorText.classList.add('d-none');
            }

            updateSubtotal(qty);
        }

        function updateSubtotal(qty) {
            const total = hargaProduk * qty;
            subtotalElement.textContent = `Rp${total.toLocaleString('id-ID')}`;
        }

        minusBtn.addEventListener('click', () => {
            let currentQty = parseInt(qtyInput.value) || 0;
            let newQty = currentQty - 1;
            if (newQty < 1) newQty = 1;
            qtyInput.value = newQty;
            validateAndUpdateQty(newQty);
        });

        plusBtn.addEventListener('click', () => {
            let currentQty = parseInt(qtyInput.value) || 0;
            let newQty = currentQty + 1;
            if (newQty > stokProduk) newQty = stokProduk;
            qtyInput.value = newQty;
            validateAndUpdateQty(newQty);
        });

        qtyInput.addEventListener('input', (e) => {
            validateAndUpdateQty(e.target.value);
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
        function addToCart() {
            // Ambil nilai quantity dan notes
            const quantity = document.getElementById('quantity').value;

            // Validasi quantity
            if (quantity < 1) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Jumlah produk minimal 1',
                    icon: 'warning'
                });
                return;
            }

            // Disable button selama proses
            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menambahkan...';

            // Kirim request AJAX
            $.ajax({
                url: '{{ route('cart.add') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    produk_id: {{ $product->id }},
                    quantity: quantity,
                    price: {{ $product->harga_diskon }},
                    amount: {{ $product->harga_diskon }} * quantity
                },
                success: function(response) {
                    // Reset button state
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-cart-plus"></i> Tambah ke Keranjang';

                    // Update cart badge di navbar
                    const cartBadges = document.querySelectorAll('.cart-badge');
                    cartBadges.forEach(badge => {
                        badge.textContent = response.cartCount;
                        badge.classList.remove('d-none');
                    });

                    // Tampilkan notifikasi sukses
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Produk berhasil ditambahkan ke keranjang',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Lihat Keranjang',
                        cancelButtonText: 'Lanjut Belanja',
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('cart.index') }}';
                        }
                    });
                },
                error: function(xhr) {
                    // Reset button state
                    btn.disabled = false;

                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menambahkan ke keranjang',
                        icon: 'error'
                    });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Animasi loading saat menambah ke keranjang */
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
@endpush
