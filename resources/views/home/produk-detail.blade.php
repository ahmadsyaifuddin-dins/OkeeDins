@extends('layouts.app')

@section('content')
    <div class="mt-4">
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
                        <span class="ms-1">4.8</span>
                    </div>
                    <div class="vr mx-2"></div>
                    <span class="text-muted">1rb+ terjual</span>
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
                        <button id="btn-minus" class="btn btn-outline-custom">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" id="quantity" class="form-control text-center mx-2" value="1"
                            max="{{ $product->stok }}" style="width: 80px;">
                        <button id="btn-plus" class="btn btn-outline-custom">
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
                    <button class="btn btn-custom flex-grow-1" onclick="addToCart()">
                        <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                    </button>

                    @auth
                        @if (Auth::user()->wishlist->contains('produk_id', $product->id))
                            <form
                                action="{{ route('wishlist.destroy',Auth::user()->wishlist->where('produk_id', $product->id)->first()) }}"
                                method="POST" class="d-inline" onsubmit="confirmAddToWishlist(event, this)">
                                @csrf
                                @method('DELETE')
                            @else
                                <form action="{{ route('wishlist.store') }}" method="POST" class="d-inline"
                                    onsubmit="confirmAddToWishlist(event, this)">
                                    @csrf
                        @endif
                        <input type="hidden" name="produk_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-outline-custom">
                            <i
                                class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? '-fill text-danger' : '' }} wishlist-icon"></i>
                        </button>
                        <style>
                            .wishlist-icon:hover {
                                color: white !important;
                            }
                        </style>
                        <script>
                            document.querySelector('.wishlist-icon').addEventListener('mouseleave', function() {
                                this.style.color = this.classList.contains('text-danger') ? '#dc3545' : '';
                            });
                        </script>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-custom">
                            <i class="bi bi-heart"></i>
                        </a>
                    @endauth
                </div>

                <button type="button" class="btn btn-outline-custom w-100" id="btn-payNow" onclick="buyNow()">
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
                        <!-- Modified description section -->
                        <div class="mb-4">
                            <h5 class="mb-3">Deskripsi Produk</h5>
                            <div class="description-content">
                                {!! $product->deskripsi !!}
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
        const payNowQuantity = document.getElementById('payNowQuantity');

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

            // Update subtotal dan quantity untuk beli sekarang
            const subtotal = qty * hargaProduk;
            subtotalElement.textContent = `Rp${new Intl.NumberFormat('id-ID').format(subtotal)}`;
            payNowQuantity.value = qty;
        }

        // Event listener untuk input quantity
        qtyInput.addEventListener('input', (e) => validateAndUpdateQty(e.target.value));

        // Event listener untuk tombol minus
        minusBtn.addEventListener('click', () => {
            const currentValue = parseInt(qtyInput.value) || 0;
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
                validateAndUpdateQty(qtyInput.value);
            }
        });

        // Event listener untuk tombol plus
        plusBtn.addEventListener('click', () => {
            const currentValue = parseInt(qtyInput.value) || 0;
            if (currentValue < stokProduk) {
                qtyInput.value = currentValue + 1;
                validateAndUpdateQty(qtyInput.value);
            }
        });

        // Set initial value
        validateAndUpdateQty(qtyInput.value);
    </script>
@endpush

@push('scripts')
    <script>
        function addToCart() {
            @guest
            Swal.fire({
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#C62828'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
            return;
        @endguest

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
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#C62828'
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

@push('scripts')
    <script>
        function buyNow() {
            @guest
            window.location.href = "{{ route('login') }}";
            return;
        @endguest

        const qty = parseInt(document.getElementById('quantity').value);
        if (!qty || qty < 1 || qty > stokProduk) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jumlah produk tidak valid!'
            });
            return;
        }

        // Redirect langsung ke checkout dengan parameter direct buy
        window.location.href = "{{ route('checkout.show') }}?" + new URLSearchParams({
            direct_buy: 1,
            produk_id: {{ $product->id }},
            quantity: qty
        }).toString();
        }
    </script>
@endpush

@push('scripts')
    <script>
        function confirmAddToWishlist(event, form) {
            event.preventDefault();

            const isDelete = form.method.toLowerCase() === 'post' && form.innerHTML.includes('DELETE');
            const message = isDelete ? 'Hapus dari wishlist?' : 'Tambahkan ke wishlist?';

            Swal.fire({
                title: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#C62828'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
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

    /* Animasi loading saat menambah ke keranjang */
    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>

<style>
    /* Styling untuk konten deskripsi dari TinyMCE */
    .description-content {
        font-size: 14px;
        line-height: 1.6;
        color: #333;
    }

    .description-content ul,
    .description-content ol {
        padding-left: 20px;
        margin-bottom: 1rem;
    }

    .description-content li {
        margin-bottom: 0.5rem;
    }

    .description-content p {
        margin-bottom: 1rem;
    }

    .description-content img {
        max-width: 100%;
        height: auto;
        margin: 1rem 0;
    }

    .description-content table {
        width: 100%;
        margin-bottom: 1rem;
        border-collapse: collapse;
    }

    .description-content table td,
    .description-content table th {
        padding: 0.5rem;
        border: 1px solid #dee2e6;
    }

    /* Menambahkan spacing yang lebih baik untuk lists */
    .description-content ul li,
    .description-content ol li {
        padding: 0.25rem 0;
    }

    /* Style untuk blockquote jika ada */
    .description-content blockquote {
        margin: 1rem 0;
        padding: 1rem;
        border-left: 4px solid #007bff;
        background-color: #f8f9fa;
    }
</style>
