@extends('layouts.app')

@section('content')
    <div class="min-h-screen">

        @include('home.tagline')
        <!-- Banner -->
        @include('home.banner')

        <div class="container mx-auto px-4">
            <!-- Kategori Slider -->
            <div class="py-4" data-aos="fade-up">
                <div class="swiper kategoriSwiper">
                    <div class="swiper-wrapper">
                        <!-- Semua Kategori -->
                        <div class="swiper-slide w-auto">
                            <a href="{{ route('home.index') }}"
                                class="inline-block px-4 py-2 rounded-full {{ !request()->query('kategori') ? 'bg-custom text-white font-medium' : 'bg-white text-gray-700 border border-gray-200' }}">
                                Semua Kategori
                            </a>
                        </div>

                        <!-- Kategori Items -->
                        @foreach ($kategori as $kat)
                            <div class="swiper-slide w-auto">
                                <a href="{{ route('home.index', ['kategori' => $kat->slug]) }}"
                                    class="inline-block px-4 py-2 rounded-full {{ request()->query('kategori') == $kat->slug ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
                                    {{ $kat->nama_kategori }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Flash Sale Section -->
            @include('home.flash-sale')

            <!-- Recommended Products -->
            <div class="py-8" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Rekomendasi Untukmu</h2>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($recommendedProducts as $product)
                        <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <a href="{{ route('produk.detail', $product->slug) }}" class="block">
                                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <!-- Wishlist & Discount -->
                                    <div class="relative">
                                        @auth
                                            <form
                                                action="{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? route('wishlist.destroy', Auth::user()->wishlist->where('produk_id', $product->id)->first()) : route('wishlist.store') }}"
                                                method="POST" class="absolute top-2 right-2 z-10">
                                                @csrf
                                                @if (Auth::user()->wishlist->contains('produk_id', $product->id))
                                                    @method('DELETE')
                                                @endif
                                                <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                                <button type="submit"
                                                    class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow">
                                                    <i
                                                        class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? '-fill text-custom' : '' }}"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}"
                                                class="absolute top-2 right-2 z-10 p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow">
                                                <i class="bi bi-heart"></i>
                                            </a>
                                        @endauth

                                        @if ($product->diskon > 0)
                                            <span
                                                class="absolute top-2 left-2 bg-custom text-white px-2 py-1 rounded-md text-xs">
                                                {{ $product->diskon }}%
                                            </span>
                                        @endif

                                        <!-- Product Image -->
                                        <img src="{{ asset('storage/' . $product->gambar) }}"
                                            class="w-full h-48 object-cover rounded-t-lg" alt="{{ $product->nama_produk }}"
                                            loading="lazy">
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $product->nama_produk }}
                                        </h3>

                                        <!-- Price -->
                                        <div class="mt-2">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                                                <span class="text-lg font-bold text-custom">
                                                    Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                                                </span>
                                                @if ($product->diskon > 0)
                                                    <div class="flex items-center gap-1.5 mt-0.5 sm:mt-0">
                                                        <span class="text-xs sm:text-sm text-gray-500 line-through">
                                                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                                                        </span>
                                                        <span
                                                            class="text-xs sm:text-sm bg-red-100 text-custom px-1.5 py-0.5 rounded">
                                                            {{ $product->diskon }}%
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
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
            </div>
        </div>
    </div>

    {{-- @push('scripts')

@endpush --}}

    <link rel="stylesheet" href="{{ asset('css/swiper-kategori-items/kategori_items.css') }}">
@endsection
