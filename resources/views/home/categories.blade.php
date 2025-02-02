@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Categories Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kategori</h1>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
            <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('kategori.show', $category->slug) }}" 
                   class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                    <div class="aspect-square relative">
                        <img src="{{ asset('storage/' . $category->gambar) }}" 
                             alt="{{ $category->nama }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <h3 class="absolute bottom-0 left-0 right-0 text-white text-center p-3 text-sm font-medium">
                            {{ $category->nama }}
                        </h3>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Category Products -->
    @if(isset($products) && $products->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                {{ $currentCategory->nama ?? 'Semua Produk' }}
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                    <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('produk.detail', $product->slug) }}" class="block">
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="relative">
                                    <!-- Wishlist Button -->
                                    @auth
                                        <form action="{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? route('wishlist.destroy', Auth::user()->wishlist->where('produk_id', $product->id)->first()) : route('wishlist.store') }}"
                                            method="POST" class="absolute top-2 right-2 z-10">
                                            @csrf
                                            @if(Auth::user()->wishlist->contains('produk_id', $product->id))
                                                @method('DELETE')
                                            @endif
                                            <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                            <button type="submit" class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow">
                                                <i class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? '-fill text-red-500' : '' }}"></i>
                                            </button>
                                        </form>
                                    @endauth

                                    <!-- Discount Badge -->
                                    @if($product->diskon > 0)
                                        <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs">
                                            {{ $product->diskon }}%
                                        </span>
                                    @endif

                                    <!-- Product Image -->
                                    <img src="{{ asset('storage/' . $product->gambar) }}"
                                         class="w-full h-48 object-cover rounded-t-lg"
                                         alt="{{ $product->nama_produk }}">
                                </div>

                                <div class="p-4">
                                    <h3 class="text-sm font-medium text-gray-900 line-clamp-2">
                                        {{ $product->nama_produk }}
                                    </h3>

                                    <!-- Price -->
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-lg font-bold text-red-500">
                                            Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                                        </span>
                                        @if($product->diskon > 0)
                                            <span class="text-sm text-gray-500 line-through">
                                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Rating & Sales -->
                                    <div class="mt-2 flex items-center text-sm text-gray-500">
                                        <i class="bi bi-star-fill text-yellow-400 mr-1"></i>
                                        <span>4.8</span>
                                        <span class="mx-1">•</span>
                                        <span>1rb+ terjual</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
