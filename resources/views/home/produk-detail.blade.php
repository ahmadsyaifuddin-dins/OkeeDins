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

    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <!-- Product Images -->
        <div class="lg:col-span-5 mb-6 lg:mb-0">
            <div class="relative bg-white rounded-lg shadow-md p-4 mb-6">
                <!-- Main Image -->
                <div class="relative overflow-hidden rounded-lg mb-4">
                    <a href="{{ asset('storage/' . $product->gambar) }}" data-fancybox="gallery"
                        data-caption="{{ $product->nama_produk }}" class="block w-full h-full main-image-link">
                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                            class="w-full h-auto object-cover hover:scale-105 transition-transform duration-300 main-image">
                        @if ($product->diskon > 0)
                        <div
                            class="absolute top-4 left-4 bg-custom text-white px-3 py-1 rounded-full text-sm font-semibold">
                            -{{ $product->diskon }}%
                        </div>
                        @endif
                    </a>
                </div>

                <!-- Thumbnail Images Container -->
                <div class="relative">
                    <!-- Previous Button -->
                    <button type="button" id="prev-btn"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white rounded-full p-1 shadow-md hidden">
                        <i class="bi bi-chevron-left text-gray-600"></i>
                    </button>

                    <!-- Next Button -->
                    <button type="button" id="next-btn"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white rounded-full p-1 shadow-md hidden">
                        <i class="bi bi-chevron-right text-gray-600"></i>
                    </button>

                    <!-- Thumbnails Scroll Container -->
                    <div class="overflow-hidden">
                        <div class="flex gap-2 transition-transform duration-300 thumbnails-wrapper">
                            <!-- Main Image Thumbnail -->
                            <div class="flex-none w-20 aspect-square rounded-lg overflow-hidden cursor-pointer thumbnail-item active"
                                data-image="{{ asset('storage/' . $product->gambar) }}">
                                <img src="{{ asset('storage/' . $product->gambar) }}" data-fancybox="gallery"
                                    alt="Main Thumbnail" class="w-full h-full object-cover">
                            </div>

                            <!-- Additional Images -->
                            @foreach ($product->productImages as $image)
                            <div class="flex-none w-20 aspect-square rounded-lg overflow-hidden cursor-pointer thumbnail-item"
                                data-image="{{ asset('storage/' . $image->image_path) }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" data-fancybox="gallery"
                                    alt="Additional Thumbnail" class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambahkan Produk Terkait -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-lg font-semibold mb-4">Produk Terkait</h3>
                <div class="grid grid-cols-2 gap-4">
                    @if(count($product->kategori->produk->where('id', '!=', $product->id)) > 0)
                    @foreach($product->kategori->produk->where('id', '!=', $product->id)->take(4) as $relatedProduct)
                    <a href="{{ route('produk.detail', $relatedProduct->slug) }}" class="group">
                        <div class="relative aspect-square rounded-lg overflow-hidden mb-2">
                            <img src="{{ asset('storage/' . $relatedProduct->gambar) }}"
                                alt="{{ $relatedProduct->nama_produk }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @if($relatedProduct->diskon > 0)
                            <div
                                class="absolute top-2 left-2 bg-custom text-white px-2 py-1 rounded-full text-xs font-semibold">
                                -{{ $relatedProduct->diskon }}%
                            </div>
                            @endif
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 truncate mb-1">
                            {{ $relatedProduct->nama_produk }}
                        </h4>
                        <div class="flex-row items-center space-x-2">
                            <span class="text-sm font-bold text-custom">
                                Rp{{ number_format($relatedProduct->harga_diskon, 0, ',', '.') }}
                            </span>
                            @if($relatedProduct->diskon > 0)
                            <span class="text-xs text-gray-500 line-through">
                                Rp{{ number_format($relatedProduct->harga, 0, ',', '.') }}
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <i class="bi bi-star-fill text-yellow-400 mr-1"></i>
                            <span>{{ number_format($relatedProduct->rating, 1) }}</span>
                            <span class="mx-1">•</span>
                            <span>{{ $relatedProduct->total_terjual }}+ terjual</span>
                        </div>
                    </a>
                    @endforeach
                    @else
                    <div class="flex items-center justify-center text-gray-600">
                        <i class="bi bi-exclamation-triangle-fill text-2xl mr-2 text-custom"></i>
                        <p class="text-custom">Belum ada produk terkait.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="lg:col-span-7 lg:sticky lg:top-24">
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

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Spesifikasi Produk</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <p class="text-sm text-gray-500">Kategori</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-900">{{ $product->kategori->nama_kategori }}</p>
                        </div>
                        <div class="col-span-1">
                            <p class="text-sm text-gray-500">Berat</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-900">{{ $product->berat ?? '500' }} gram</p>
                        </div>
                        <div class="col-span-1">
                            <p class="text-sm text-gray-500">Kondisi</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-900">Baru</p>
                        </div>
                        <!-- Tambahkan spesifikasi lainnya sesuai kebutuhan -->
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
                        onclick="window.location.href='https://wa.me/6285849910396?text=Hai%2C+saya+{{ Auth::check() ? Auth::user()->name : '' }}+tertarik+dengan+produk+{{ $product->nama_produk }}'"
                        class="h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-whatsapp text-green-500"></i>
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

                    <div class="relative" x-data="{ shareOpen: false }">
                        <button type="button" @click="shareOpen = !shareOpen"
                            class="w-full h-10 flex items-center justify-center space-x-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="bi bi-share"></i>
                            <span class="text-sm">Share</span>
                        </button>
                        <!-- Share dropdown -->
                        <div x-show="shareOpen" @click.away="shareOpen = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                            <div class="py-1">
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-facebook mr-2 text-blue-600"></i> Facebook
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-instagram mr-2 text-blue-600"></i> Instagram
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-twitter mr-2 text-blue-400"></i> Twitter
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-whatsapp mr-2 text-green-500"></i> WhatsApp
                                </a>
                                <button @click="navigator.clipboard.writeText(window.location.href)"
                                    class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="bi bi-link-45deg mr-2"></i> Salin Link
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Reviews Section -->
                <div class="mt-12">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-6">Ulasan Pembeli</h2>

                        <!-- Rating Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                            <!-- Left Column - Rating Overview -->
                            <div class="md:col-span-3 flex flex-col items-center justify-center">
                                <div class="text-4xl font-bold text-gray-900 mb-2">4.9<span class="text-xl">/5</span>
                                </div>
                                <div class="flex items-center text-yellow-400 mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <p class="text-sm text-gray-500">200 ulasan</p>
                            </div>

                            <!-- Right Column - Rating Bars -->
                            <div class="md:col-span-9">
                                @foreach (range(5, 1) as $star)
                                <div class="flex items-center mb-2 last:mb-0">
                                    <div class="flex items-center w-16">
                                        <span class="text-sm font-medium text-gray-600 mr-2">{{ $star }}</span>
                                        <i class="bi bi-star-fill text-yellow-400"></i>
                                    </div>
                                    <div class="flex-1 h-2.5 bg-gray-200 rounded-full mx-2">
                                        <div class="h-2.5 bg-yellow-400 rounded-full"
                                            style="width: {{ rand(60, 95) }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-12 text-right">{{ rand(40, 150) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Review Filter -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            <button
                                class="px-4 py-1.5 text-sm bg-red-50 text-custom rounded-full hover:bg-red-100 font-medium">
                                Semua
                            </button>
                            @foreach (range(5, 1) as $star)
                            <button class="px-4 py-1.5 text-sm border border-gray-300 rounded-full hover:bg-gray-50">
                                {{ $star }} Bintang
                            </button>
                            @endforeach
                            <button class="px-4 py-1.5 text-sm border border-gray-300 rounded-full hover:bg-gray-50">
                                Dengan Foto
                            </button>
                        </div>

                        <!-- Review List -->
                        <div class="space-y-6">
                            @foreach (range(1, 2) as $review)
                            <div class="border-b border-gray-200 pb-6 last:border-0">
                                <div class="flex items-center mb-2">
                                    <img src="{{ asset('images/user.svg') }}" alt="User Avatar"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">User {{ $review }}</p>
                                        <div class="flex items-center text-yellow-400">
                                            @foreach (range(1, 5) as $star)
                                            <i class="bi bi-star-fill text-sm"></i>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mb-2">
                                    {{ now()->subDays(rand(1, 30))->format('d M Y') }}
                                </p>
                                <p class="text-gray-600 mb-4">
                                    Produk bagus, pengiriman cepat, packing aman. Recommended seller!
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (range(1, 2) as $img)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="Review Image"
                                        class="w-24 h-24 rounded-lg object-cover hover:opacity-90 transition-opacity cursor-pointer">
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Load More Button -->
                        <div class="text-center mt-8">
                            <button
                                class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                                Lihat Lebih Banyak
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
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

    .thumbnails-wrapper {
        display: flex;
        transition: transform 0.3s ease;
    }

    #prev-btn,
    #next-btn {
        transition: opacity 0.3s ease;
    }

    #prev-btn:disabled,
    #next-btn:disabled {
        cursor: not-allowed;
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        const mainImage = document.querySelector('.main-image');
        const mainImageLink = document.querySelector('.main-image-link');
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        const wrapper = document.querySelector('.thumbnails-wrapper');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        // Initialize Fancybox
        Fancybox.bind("[data-fancybox]", {
            Carousel: {
                infinite: false,
            },
            Thumbs: {
                autoStart: true,
            },
            Images: {
                zoom: true,
            }
        });
        // Thumbnail click handler
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent default action
                // Update main image src
                const newImageSrc = thumbnail.dataset.image;
                mainImage.src = newImageSrc;
                mainImageLink.href = newImageSrc;
                // Update active state
                thumbnails.forEach(t => t.classList.remove('active'));
                thumbnail.classList.add('active');
            });
        });
        // Scroll functionality
        let scrollPosition = 0;
        const scrollAmount = 88; // width of thumbnail (80px) + gap (8px)
        const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;

        function updateNavigationVisibility() {
            if (wrapper.scrollWidth > wrapper.clientWidth) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
            }
            prevBtn.disabled = scrollPosition <= 0;
            nextBtn.disabled = scrollPosition >= maxScroll;
            prevBtn.style.opacity = prevBtn.disabled ? '0.5' : '1';
            nextBtn.style.opacity = nextBtn.disabled ? '0.5' : '1';
        }
        // Initialize visibility
        updateNavigationVisibility();
        // Previous button click handler
        prevBtn.addEventListener('click', () => {
            scrollPosition = Math.max(scrollPosition - scrollAmount, 0);
            wrapper.style.transform = `translateX(-${scrollPosition}px)`;
            updateNavigationVisibility();
        });
        // Next button click handler
        nextBtn.addEventListener('click', () => {
            scrollPosition = Math.min(scrollPosition + scrollAmount, maxScroll);
            wrapper.style.transform = `translateX(-${scrollPosition}px)`;
            updateNavigationVisibility();
        });
        // Update on window resize
        window.addEventListener('resize', updateNavigationVisibility);
    });
    // Function to change main image
    function changeMainImage(imageSrc, thumbnail) {
        // Update main image
        const mainImageContainer = document.querySelector('.relative.overflow-hidden.rounded-lg.mb-4');
        if (mainImageContainer) {
            const mainImage = mainImageContainer.querySelector('img');
            const fancyboxLink = mainImageContainer.querySelector('a');
            if (mainImage) {
                mainImage.src = imageSrc;
            }
            if (fancyboxLink) {
                fancyboxLink.href = imageSrc;
            }
        }
        // Update active state of thumbnails
        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });
        thumbnail.closest('.thumbnail-item').classList.add('active');
    }
    const quantityInput = document.getElementById('quantity');
    const amountInput = document.querySelector('input[name="amount"]');
    const price = {
        {
            $product - > harga_diskon
        }
    };
    const maxStock = {
        {
            $product - > stok
        }
    };

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
    document.querySelector('form[action="{{ route('
        cart.add ') }}"]').addEventListener('submit', function(event) {
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
                if (error.message === 'Unauthenticated.') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.',
                        confirmButtonColor: '#EF4444'
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Terjadi kesalahan saat menambahkan ke keranjang',
                        confirmButtonColor: '#EF4444'
                    });
                }
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
            produk_id: {
                {
                    $product - > id
                }
            },
            quantity: qty
        }).toString();
    }
</script>

<script src="{{ asset('js/wishlist.js') }}"></script>
@endpush
@endsection