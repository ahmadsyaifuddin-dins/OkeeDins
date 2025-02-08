@extends('layouts.app')

@section('content')
<section class="py-8">
    <div class="container mx-auto px-4">
        <h2 class="text-xl font-bold mb-4">Rekomendasi Untukmu</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
            @if ($recommendedProducts->count() > 0)
                @foreach ($recommendedProducts as $recom)
                    <div  data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('produk.detail', $recom->slug) }}" class="block">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <!-- Wishlist & Discount -->
                                <div class="relative">
                                    @auth
                                        <form action="{{ Auth::user()->wishlist->contains('produk_id', $recom->id) ? route('wishlist.destroy', Auth::user()->wishlist->where('produk_id', $recom->id)->first()) : route('wishlist.store') }}"
                                            method="POST" class="absolute top-2 right-2 z-10">
                                            @csrf
                                            @if(Auth::user()->wishlist->contains('produk_id', $recom->id))
                                                @method('DELETE')
                                            @endif
                                            <input type="hidden" name="produk_id" value="{{ $recom->id }}">
                                            <button type="submit" class="p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow">
                                                <i class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $recom->id) ? '-fill text-red-500' : '' }}"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="absolute top-2 right-2 z-10 p-2 bg-white rounded-full shadow-sm hover:shadow-md transition-shadow">
                                            <i class="bi bi-heart"></i>
                                        </a>
                                    @endauth

                                    @if ($recom->diskon > 0)
                                        <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-md text-xs">
                                            {{ $recom->diskon }}%
                                        </span>
                                    @endif

                                    <!-- Product Image -->
                                    <img src="{{ asset('storage/' . $recom->gambar) }}"
                                        class="w-full h-48 object-cover rounded-t-lg"
                                        alt="{{ $recom->nama_produk }}"
                                        loading="lazy">
                                </div>

                                <!-- Product Info -->
                                <div class="p-3">
                                    <h3 class="text-sm font-medium text-gray-900 line-clamp-2 min-h-[40px]">
                                        {{ $recom->nama_produk }}
                                    </h3>

                                    <!-- Price -->
                                    <div class="mt-2">
                                        <span class="block text-base font-bold text-red-500">
                                            Rp{{ number_format($recom->harga_diskon, 0, ',', '.') }}
                                        </span>
                                        @if ($recom->diskon > 0)
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                <span class="text-[11px] text-gray-500 line-through">
                                                    Rp{{ number_format($recom->harga, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[11px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded">
                                                    {{ $recom->diskon }}%
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Rating & Sales -->
                                    <div class="mt-2 flex items-center text-xs text-gray-500">
                                        <i class="bi bi-star-fill text-yellow-400 mr-1"></i>
                                        <span>{{ number_format($recom->rating, 1) }}</span>
                                        <span class="mx-1">•</span>
                                        <span class="truncate">{{ $recom->terjual }}+ terjual</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-12">
                    <i class="bi bi-box-seam text-gray-400 text-6xl"></i>
                    <h5 class="mt-4 text-gray-500">Tidak ada produk yang direkomendasikan</h5>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection