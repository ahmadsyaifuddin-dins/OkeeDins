@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="lg:grid lg:grid-cols-2 lg:gap-8">
        <!-- Product Images -->
        <div data-aos="fade-right">
            <div class="swiper-container product-slider rounded-lg overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" 
                             alt="{{ $produk->nama_produk }}"
                             class="w-full h-[300px] md:h-[400px] object-cover">
                    </div>
                    @foreach($produk->gambar_tambahan as $gambar)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $gambar) }}" 
                                 alt="{{ $produk->nama_produk }}"
                                 class="w-full h-[300px] md:h-[400px] object-cover">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>

            <!-- Thumbnail Navigation -->
            <div class="mt-4 grid grid-cols-4 gap-2">
                <div class="swiper-slide cursor-pointer rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $produk->gambar) }}" 
                         alt="{{ $produk->nama_produk }}"
                         class="w-full h-20 object-cover">
                </div>
                @foreach($produk->gambar_tambahan as $gambar)
                    <div class="swiper-slide cursor-pointer rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $gambar) }}" 
                             alt="{{ $produk->nama_produk }}"
                             class="w-full h-20 object-cover">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div class="mt-8 lg:mt-0" data-aos="fade-left">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-red-500">Home</a></li>
                    <li class="flex items-center">
                        <i class="bi bi-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('kategori.show', $produk->kategori->slug) }}" class="text-gray-500 hover:text-red-500">
                            {{ $produk->kategori->nama }}
                        </a>
                    </li>
                </ol>
            </nav>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $produk->nama_produk }}</h1>

            <!-- Price Section -->
            <div class="flex items-center gap-3 mb-4">
                <span class="text-3xl font-bold text-red-500">
                    Rp{{ number_format($produk->harga_diskon, 0, ',', '.') }}
                </span>
                @if($produk->diskon > 0)
                    <span class="text-lg text-gray-500 line-through">
                        Rp{{ number_format($produk->harga, 0, ',', '.') }}
                    </span>
                    <span class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                        {{ $produk->diskon }}% OFF
                    </span>
                @endif
            </div>

            <!-- Rating & Sales -->
            <div class="flex items-center text-sm text-gray-500 mb-6">
                <div class="flex items-center">
                    <i class="bi bi-star-fill text-yellow-400"></i>
                    <span class="ml-1">4.8</span>
                </div>
                <span class="mx-2">•</span>
                <span>1rb+ terjual</span>
            </div>

            <!-- Description -->
            <div class="prose prose-sm max-w-none mb-6">
                <h3 class="text-lg font-semibold mb-2">Deskripsi Produk</h3>
                <p class="text-gray-600">{{ $produk->deskripsi }}</p>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                
                <div class="flex items-center gap-4">
                    <label class="text-gray-700">Jumlah:</label>
                    <div class="flex items-center border rounded-lg">
                        <button type="button" class="px-3 py-2 text-gray-600 hover:text-red-500 decrease-qty">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" name="quantity" value="1" min="1"
                               class="w-16 text-center border-x py-2 focus:outline-none">
                        <button type="button" class="px-3 py-2 text-gray-600 hover:text-red-500 increase-qty">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" name="checkout" value="true"
                            class="flex-1 bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
                        Beli Sekarang
                    </button>
                    <button type="submit"
                            class="flex-1 border border-red-500 text-red-500 px-6 py-3 rounded-lg hover:bg-red-50 transition-colors">
                        + Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-16" data-aos="fade-up">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relatedProducts as $related)
                <a href="{{ route('produk.detail', $related->slug) }}" class="block">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="relative">
                            @if($related->diskon > 0)
                                <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs">
                                    {{ $related->diskon }}%
                                </span>
                            @endif
                            <img src="{{ asset('storage/' . $related->gambar) }}"
                                 class="w-full h-48 object-cover rounded-t-lg"
                                 alt="{{ $related->nama_produk }}">
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2">
                                {{ $related->nama_produk }}
                            </h3>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-lg font-bold text-red-500">
                                    Rp{{ number_format($related->harga_diskon, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize product slider
    new Swiper('.product-slider', {
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });

    // Quantity handlers
    document.querySelector('.decrease-qty').addEventListener('click', function() {
        let input = this.parentNode.querySelector('input[name="quantity"]');
        let value = parseInt(input.value);
        if (value > 1) input.value = value - 1;
    });

    document.querySelector('.increase-qty').addEventListener('click', function() {
        let input = this.parentNode.querySelector('input[name="quantity"]');
        let value = parseInt(input.value);
        input.value = value + 1;
    });
</script>
@endpush
@endsection
