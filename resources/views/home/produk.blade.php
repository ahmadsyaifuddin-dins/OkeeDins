<div class="py-8">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Rekomendasi Untukmu</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach ($recommendedProducts as $product)
            <div>
                <a href="{{ route('produk.detail', $product->slug) }}" class="block">
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <!-- Container Gambar dan Overlay -->
                        <div class="relative">
                            <!-- Gambar dengan Aspect Square supaya konsisten -->
                            <div
                                class="w-full aspect-square flex items-center aspect-w-16 aspect-h-9 justify-center bg-gray-100">
                                <img src="{{ asset('storage/' . $product->gambar) }}"
                                    class="w-full h-full object-contain p-2 rounded-lg" alt="{{ $product->nama_produk }}"
                                    loading="lazy">
                            </div>

                            <!-- Wishlist Button (selalu di pojok kanan atas) -->
                            {{-- <div class="absolute top-2 right-2 z-10">
                                @auth
                                    <form
                                        action="{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? route('wishlist.destroy', Auth::user()->wishlist->where('produk_id', $product->id)->first()) : route('wishlist.store') }}"
                                        method="POST">
                                        @csrf
                                        @if (Auth::user()->wishlist->contains('produk_id', $product->id))
                                            @method('DELETE')
                                        @endif
                                        <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                        <button type="submit"
                                            class="p-2.5 rounded-full shadow-sm hover:shadow-md transition-shadow">
                                            <i
                                                class="bi bi-heart{{ Auth::user()->wishlist->contains('produk_id', $product->id) ? '-fill text-custom' : '' }} text-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="p-2.5 rounded-full shadow-sm hover:shadow-md transition-shadow">
                                        <i class="bi bi-heart text-lg"></i>
                                    </a>
                                @endauth
                            </div> --}}

                            <!-- Discount Badge (jika ada diskon) -->
                            @if ($product->diskon > 0)
                                <div class="absolute top-2 left-2 z-10">
                                    <span
                                        class="bg-custom text-white px-1.5 md:px-2.5 py-1.5 md:py-1.5 rounded-md text-xs md:text-sm">
                                        {{ $product->diskon }}%
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Info Produk -->
                        <div class="p-2 md:p-2">
                            <a href="{{ route('produk.detail', $product->slug) }}">
                                <h3
                                    class="text-sm md:text-base font-medium text-gray-900 truncate hover:text-custom hover:scale-105 transform transition-all duration-300 ease-in-out">
                                    {{ $product->nama_produk }}
                                </h3>
                            </a>
                            <!-- Harga -->
                            <div class="mt-2.5">
                                <div class="flex flex-col md:flex-col sm:flex-row xl:flex-row gap-2">
                                    <span class="text-lg md:text-base font-bold text-custom">
                                        Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}
                                    </span>
                                    @if ($product->diskon > 0)
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm md:text-base text-gray-500 line-through">
                                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- Rating & Sales -->
                            <div class="mt-2.5 flex items-center text-xs md:text-base text-gray-500">
                                <i class="bi bi-star-fill text-yellow-400 mr-1.5"></i>
                                <span class="text-gray-600">{{ number_format($product->rating, 1) }}</span>
                                <span class="mx-1.5">•</span>
                                <span class="text-gray-600">{{ $product->total_terjual }}+ terjual</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
