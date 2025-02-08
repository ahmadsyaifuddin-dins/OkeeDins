@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="text-2xl font-semibold text-gray-800">Hasil Pencarian</h4>
                @if ($searchQuery)
                    <p class="text-gray-600 mt-1">Menampilkan hasil untuk "{{ $searchQuery }}"</p>
                @endif
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="text-center py-12">
                <i class="bi bi-search text-gray-400 text-6xl mb-4"></i>
                <h5 class="text-xl font-medium text-gray-800 mb-2">Tidak ada hasil</h5>
                <p class="text-gray-600 mb-4">Coba kata kunci lain atau periksa ejaan</p>
                <a href="{{ route('home.index') }}"
                    class="inline-block px-6 py-2.5 bg-custom text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <!-- Wishlist Button -->
                        @auth
                            @if (Auth::user()->wishlist->contains('produk_id', $product->id))
                                <form
                                    action="{{ route('wishlist.destroy', Auth::user()->wishlist->where('produk_id', $product->id)->first()) }}"
                                    method="POST" class="inline" onsubmit="confirmAddToWishlist(event, this)">
                                    @csrf
                                    @method('DELETE')
                                @else
                                    <form action="{{ route('wishlist.store') }}" method="POST" class="inline">
                                        @csrf
                            @endif
                            <input type="hidden" name="produk_id" value="{{ $product->id }}">
                            <button type="submit"
                                class="absolute top-2 right-2 p-2 bg-white rounded-full shadow-sm hover:bg-gray-100"
                                onclick="event.stopPropagation();">
                                <i
                                    class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? '-fill text-red-500' : ' text-gray-600' }}
                                    text-xl"></i>
                            </button>
                            </form>
                        @endauth

                        <!-- Product Image -->
                        <div class="relative">
                            <a href="{{ route('produk.detail', $product->slug) }}" class="block">
                                <div class="w-full aspect-square flex items-center justify-center bg-gray-100">
                                    <img src="{{ asset('storage/' . $product->gambar) }}"
                                        class="w-full h-full object-contain p-2 rounded-lg"
                                        alt="{{ $product->nama_produk }}" loading="lazy">
                                </div>
                            </a>
                            @if ($product->diskon > 0)
                                <div class="absolute top-2 left-2 z-10">
                                    <span
                                        class="bg-custom text-white px-1.5 md:px-2.5 py-1.5 md:py-1.5 rounded-md text-xs md:text-sm">
                                        {{ $product->diskon }}%
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h6 class="text-lg font-medium text-gray-800 truncate">
                                <a href="{{ route('produk.detail', $product->slug) }}" class="hover:text-custom">
                                    {{ $product->nama_produk }}
                                </a>
                            </h6>
                            <p class="text-sm text-gray-600 mb-2">{{ $product->kategori->nama_kategori }}</p>

                            @if ($product->diskon > 0)
                                <div class="space-y-1">
                                    <p class="flex-row items-center gap-2">
                                        <span class="text-lg font-semibold text-red-600">
                                            Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                                        </span>
                                        <span class="text-sm text-gray-500 line-through">
                                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                                        </span>
                                    </p>
                                </div>
                            @else
                                <p class="text-lg font-semibold text-red-600">
                                    Rp{{ number_format($product->harga, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-8">
                {{ $products->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
@endsection
