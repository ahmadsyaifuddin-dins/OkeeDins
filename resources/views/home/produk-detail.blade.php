@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 mt-16">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-4">
            <ol class="list-none p-0 flex items-center space-x-2">
                <li>
                    <a href="{{ route('home.index') }}" class="text-gray-500 hover:text-red-500">Home</a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="#" class="text-gray-500 hover:text-red-500">{{ $product->kategori->nama_kategori }}</a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900">{{ $product->nama_produk }}</li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-8">
            <!-- Product Images -->
            <div class="mb-6 lg:mb-0" data-aos="fade-right">
                <div class="relative bg-white rounded-lg shadow-md p-4">
                    <!-- Main Image -->
                    <div class="relative aspect-square overflow-hidden rounded-lg mb-4">
                        <a href="{{ asset('storage/' . $product->gambar) }}" data-fancybox="gallery"
                            class="block w-full h-full">
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            @if ($product->diskon > 0)
                                <div
                                    class="absolute top-4 left-4 bg-custom text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    -{{ $product->diskon }}%
                                </div>
                            @endif
                        </a>
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="grid grid-cols-5 gap-2">
                        <!-- Main Image Thumbnail -->
                        <div class="aspect-square rounded-lg overflow-hidden cursor-pointer thumbnail-item active"
                            onclick="changeMainImage('{{ asset('storage/' . $product->gambar) }}', this)">
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="Main Thumbnail"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Additional Images -->
                        @foreach ($product->productImages as $image)
                            <div class="aspect-square rounded-lg overflow-hidden cursor-pointer thumbnail-item"
                                onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Additional Thumbnail"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div data-aos="fade-left">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->nama_produk }}</h1>
                        <div class="flex items-center space-x-4 text-sm">
                            <div class="flex items-center text-yellow-400">
                                <i class="bi bi-star-fill mr-1"></i>
                                <span class="text-gray-600">{{ number_format($product->rating, 1) }}</span>
                            </div>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-600">{{ $product->total_terjual }}+ terjual</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                            <span class="text-3xl font-bold text-custom mb-2 sm:mb-0">
                                Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                            </span>
                            @if ($product->diskon > 0)
                                <div class="flex items-center space-x-2">
                                    <span class="text-base md:text-lg text-gray-600 line-through">
                                        Rp{{ number_format($product->harga, 0, ',', '.') }}
                                    </span>
                                    <span class="px-2 py-1 bg-red-100 text-red-600 text-sm font-semibold rounded">
                                        {{ $product->diskon }}% OFF
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi Produk</h2>
                        <p class="text-gray-600 whitespace-pre-line">{!! $product->deskripsi !!}</p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $product->id }}">
                        <input type="hidden" name="price" value="{{ $product->harga_diskon }}">
                        <input type="hidden" name="amount" value="{{ $product->harga_diskon }}">

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-700">Jumlah</label>
                                <div class="flex items-center space-x-2">
                                    <i class="bi bi-box-seam text-gray-500"></i>
                                    <span class="text-sm text-gray-600">Stok: {{ $product->stok }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                    class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-100"
                                    onclick="decrementQuantity()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                    class="w-20 h-10 border border-gray-300 rounded-lg text-center focus:ring-red-500 focus:border-red-500">
                                <button type="button"
                                    class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-100"
                                    onclick="incrementQuantity()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <button type="button" id="btn-payNow" onclick="buyNow()"
                                class="flex-1 h-12 bg-custom text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center space-x-2">
                                <i class="bi bi-lightning-charge-fill"></i>
                                <span>Beli Sekarang</span>
                            </button>

                            <button type="submit" id="btn-addToCart"
                                class="flex-1 h-12 border border-custom text-custom rounded-lg hover:bg-red-50 transition-colors flex items-center justify-center space-x-2">
                                <i class="bi bi-cart-plus mr-2"></i>
                                <span>Keranjang</span>
                            </button>
                        </div>
                    </form>

                    <!-- Secondary Actions -->
                    <div class="grid grid-cols-3 gap-3 mt-4">
                        <button type="button"
                            class="h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="bi bi-chat-dots"></i>
                            <span class="text-sm">Chat</span>
                        </button>

                        @auth
                            @php
                                $wishlistItem = Auth::user()->wishlist->where('produk_id', $product->id)->first();
                            @endphp

                            @if ($wishlistItem)
                                <form action="{{ route('wishlist.destroy', $wishlistItem->id) }}" method="POST"
                                    class="wishlist-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-heart-fill text-custom"></i>
                                        <span class="text-sm">Wishlist</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('wishlist.store') }}" method="POST" class="wishlist-form">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                    <button type="submit"
                                        class="w-full h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-heart"></i>
                                        <span class="text-sm">Wishlist</span>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}">
                                <button type="button"
                                    class="w-full h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="bi bi-heart"></i>
                                    <span class="text-sm">Wishlist</span>
                                </button>
                            </a>
                        @endauth

                        <button type="button"
                            class="h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="bi bi-share"></i>
                            <span class="text-sm">Share</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    @push('styles')
        <style>
            .thumbnail-item {
                border: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .thumbnail-item:hover {
                border-color: #ef4444;
            }

            .thumbnail-item.active {
                border-color: #ef4444;
            }

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            // Initialize Fancybox
            Fancybox.bind("[data-fancybox]", {
                // Custom options
            });

            // Function to change main image
            function changeMainImage(imageSrc, thumbnail) {
                // Update main image
                const mainImage = document.querySelector('.aspect-square.mb-4 img');
                mainImage.src = imageSrc;

                // Update fancybox link
                const fancyboxLink = document.querySelector('.aspect-square.mb-4 a');
                fancyboxLink.href = imageSrc;

                // Update active state of thumbnails
                document.querySelectorAll('.thumbnail-item').forEach(item => {
                    item.classList.remove('active');
                });
                thumbnail.classList.add('active');
            }

            const quantityInput = document.getElementById('quantity');
            const amountInput = document.querySelector('input[name="amount"]');
            const price = {{ $product->harga_diskon }};
            const maxStock = {{ $product->stok }};

            function updateAmount() {
                const quantity = parseInt(quantityInput.value) || 1;
                if (quantity > maxStock) {
                    quantityInput.value = maxStock;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Jumlah melebihi stok yang tersedia!',
                        confirmButtonColor: '#EF4444'
                    });
                }
                amountInput.value = price * parseInt(quantityInput.value);
            }

            function incrementQuantity() {
                const input = document.getElementById('quantity');
                const currentValue = parseInt(input.value) || 0;
                if (currentValue < maxStock) {
                    input.value = currentValue + 1;
                    updateAmount();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Stok tidak mencukupi!',
                        confirmButtonColor: '#EF4444'
                    });
                }
            }

            function decrementQuantity() {
                const input = document.getElementById('quantity');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateAmount();
                }
            }

            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value) || 0;
                if (value > maxStock) {
                    this.value = maxStock;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Jumlah melebihi stok yang tersedia!',
                        confirmButtonColor: '#EF4444'
                    });
                }
                updateAmount();
            });

            quantityInput.addEventListener('change', updateAmount);

            // Fungsi untuk cart
            document.querySelector('form[action="{{ route('cart.add') }}"]').addEventListener('submit', function(event) {
                event.preventDefault();
                const button = document.getElementById('btn-addToCart');
                const originalContent = button.innerHTML;

                // Validasi input
                const quantity = document.getElementById('quantity').value;
                if (!quantity || quantity < 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah produk harus diisi',
                        confirmButtonColor: '#EF4444'
                    });
                    return;
                }

                // Update amount sebelum submit
                updateAmount();

                // Disable button & show loading
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> <span>Proses...</span>';

                fetch(this.action, {
                        method: this.method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(this)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }

                        // Update cart count jika ada
                        if (data.cartCount !== undefined) {
                            const cartCountElement = document.querySelector('.cart-count');
                            if (cartCountElement) {
                                cartCountElement.textContent = data.cartCount;
                            }
                        }

                        // Tampilkan konfirmasi dengan 2 tombol
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showCancelButton: true,
                            confirmButtonColor: '#EF4444',
                            cancelButtonColor: '#3B82F6',
                            confirmButtonText: 'Lihat Keranjang',
                            cancelButtonText: 'Lanjut Belanja',
                            reverseButtons: true // Membalik posisi tombol
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke halaman keranjang
                                window.location.href = "{{ route('cart.index') }}";
                            } else {
                                // Tetap di halaman, refresh untuk update data
                                // window.location.reload();
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message || 'Terjadi kesalahan saat menambahkan ke keranjang',
                            confirmButtonColor: '#EF4444'
                        });
                    })
                    .finally(() => {
                        button.disabled = false;
                        button.innerHTML = originalContent;
                    });
            });


            // Fungsi untuk buy now
            function buyNow() {
                @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            const qty = parseInt(document.getElementById('quantity').value);
            if (!qty || qty < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Jumlah produk tidak valid!',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }

            // Redirect langsung ke checkout dengan parameter produk
            window.location.href = "{{ route('checkout.index') }}?" + new URLSearchParams({
                direct_buy: 1,
                produk_id: {{ $product->id }},
                quantity: qty
            }).toString();
            }
        </script>

        <script src="{{ asset('js/wishlist.js') }}"></script>
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
