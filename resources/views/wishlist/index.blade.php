@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 mt-12">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Wishlist Saya</h1>
        <a href="{{ route('home.index') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="bi bi-arrow-left mr-2"></i> Lanjut Belanja
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-700" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-700" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($wishlist->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($wishlist as $wishlist)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="relative">

                        <!-- Product Image -->
                        <a href="{{ route('produk.detail', $wishlist->produk->slug) }}">
                            <img src="{{ asset('storage/' . $wishlist->produk->gambar) }}"
                                 alt="{{ $wishlist->produk->nama_produk }}" 
                                 class="w-full h-48 object-cover rounded-t-lg">
                        </a>

                        <!-- Discount Badge -->
                        @if ($wishlist->produk->diskon > 0)
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-custom rounded-lg">
                                    {{ $wishlist->produk->diskon }}% OFF
                                </span>
                            </div>
                        @endif

                        <!-- Remove from Wishlist Button -->
                        <form action="{{ route('wishlist.destroy', $wishlist->id) }}" 
                              method="POST"
                              class="absolute top-2 right-2"
                              onsubmit="event.preventDefault(); Swal.fire({
                                title: 'Konfirmasi',
                                text: 'Apakah kamu yakin menghapus produk ini dari favorit?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, hapus!',
                                cancelButtonText: 'Tidak, batalkan!',
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.submit();
                                }
                            });">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-2 text-custom bg-white rounded-lg shadow-sm hover:bg-gray-50">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </form>
                    </div>

                    <div class="p-4">
                        <!-- Product Title -->
                        <a href="{{ route('produk.detail', $wishlist->produk->slug) }}" class="group">
                            <h5 class="text-sm font-medium text-gray-900 mb-2 transition-all duration-300 ease-in-out group-hover:text-custom group-hover:translate-x-1">
                                {{ $wishlist->produk->nama_produk }}
                            </h5>
                        </a>

                        <div class="flex flex-col space-y-2 mb-3">
                            <!-- Price and Original Price -->
                            <div class="flex sm:flex-row sm:items-center space-x-2 sm:space-x-2">
                                <span class="text-lg font-semibold text-custom">
                                    Rp{{ number_format($wishlist->produk->harga_diskon, 0, ',', '.') }}
                                </span>
                                @if ($wishlist->produk->diskon > 0)
                                    <span class="text-sm text-gray-600 line-through mt-1 sm:mt-0">
                                        Rp{{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-star-fill text-yellow-400 mr-1"></i>
                                <span>{{ number_format($wishlist->produk->rating, 1) }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $wishlist->produk->total_terjual }}+ terjual</span>
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        <form onsubmit="return addToCart(event, this, {{ $wishlist->produk->id }}, {{ $wishlist->produk->harga_diskon }})">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" 
                                    class="w-full py-2 px-4 bg-custom hover:bg-red-600 text-white rounded-lg transition-colors">
                                <i class="bi bi-cart-plus mr-2"></i>
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <i class="bi bi-heart text-gray-400 text-6xl"></i>
            <p class="mt-4 text-gray-600">Wishlist kamu masih kosong</p>
            <a href="{{ route('home.index') }}" 
               class="inline-block mt-4 px-6 py-2 bg-custom text-white rounded-lg hover:bg-red-600 transition-colors">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function addToCart(event, form, productId, price) {
        event.preventDefault();
        
        const quantity = form.querySelector('input[name="quantity"]').value;
        const amount = price * quantity;
        
        $.ajax({
            url: '{{ route('cart.add') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                produk_id: productId,
                quantity: quantity,
                price: price,
                amount: amount
            },
            success: function(response) {
                // Update cart badge
                const cartBadges = document.querySelectorAll('.cart-badge');
                cartBadges.forEach(badge => {
                    badge.textContent = response.cartCount;
                    badge.classList.remove('hidden');
                });

                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Produk berhasil ditambahkan ke keranjang',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Lihat Keranjang',
                    cancelButtonText: 'Lanjut Belanja',
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('cart.index') }}';
                    }
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menambahkan ke keranjang',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            }
        });

        return false;
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
</style>
@endsection
