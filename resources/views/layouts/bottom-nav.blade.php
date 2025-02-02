<div class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 md:hidden">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-2">
            <!-- Home -->
            <a href="{{ route('home.index') }}"
                class="flex flex-col items-center gap-1 {{ Route::currentRouteName() == 'home.index' ? 'text-custom' : 'text-gray-500' }} hover:text-red-600">
                <i class="bi bi-house text-xl"></i>
                <span class="text-xs">Home</span>
            </a>

            <!-- Favorit -->
            @auth
                <a href="{{ route('wishlist.index') }}"
                    class="flex flex-col items-center gap-1 relative {{ Route::currentRouteName() == 'wishlist.index' ? 'text-custom' : 'text-gray-500' }} hover:text-red-600">
                    <i class="bi bi-heart text-xl"></i>
                    <span class="text-xs">Favorit</span>
                    @if ($wishlistCount > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>
            @else
                <button onclick="showLoginAlert()"
                    class="flex flex-col items-center gap-1 text-gray-500 hover:text-red-600">
                    <i class="bi bi-heart text-xl"></i>
                    <span class="text-xs">Favorit</span>
                </button>
            @endauth

            <!-- Pembayaran -->
            @auth
                <a href="{{ route('payment.index') }}"
                    class="flex flex-col items-center gap-1 relative {{ Route::currentRouteName() == 'payment.index' ? 'text-custom' : 'text-gray-500' }} hover:text-red-600">
                    <i class="bi bi-credit-card text-xl"></i>
                    <span class="text-xs">Pembayaran</span>
                    @if ($bayarCount > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                            {{ $bayarCount }}
                        </span>
                    @endif
                </a>
            @else
                <button onclick="showLoginAlert()"
                    class="flex flex-col items-center gap-1 text-gray-500 hover:text-red-600">
                    <i class="bi bi-credit-card text-xl"></i>
                    <span class="text-xs">Pembayaran</span>
                </button>
            @endauth

            <!-- Pesanan -->
            @auth
                <a href="{{ route('home.riwayat-pesanan') }}"
                    class="flex flex-col items-center gap-1 {{ Route::currentRouteName() == 'home.riwayat-pesanan' ? 'text-custom' : 'text-gray-500' }} hover:text-red-600">
                    <i class="bi bi-receipt text-xl"></i>
                    <span class="text-xs">Pesanan</span>
                </a>
            @else
                <button onclick="showLoginAlert()"
                    class="flex flex-col items-center gap-1 text-gray-500 hover:text-red-600">
                    <i class="bi bi-receipt text-xl"></i>
                    <span class="text-xs">Pesanan</span>
                </button>
            @endauth

            <!-- Profile -->
            @auth
                <a href="{{ route('pelanggan.profile') }}"
                    class="flex flex-col items-center gap-1 {{ Route::currentRouteName() == 'pelanggan.profile' ? 'text-custom' : 'text-gray-500' }} hover:text-red-600">
                    <i class="bi bi-person text-xl"></i>
                    <span class="text-xs">Profil</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 text-gray-500 hover:text-red-600">
                    <i class="bi bi-person text-xl"></i>
                    <span class="text-xs">Masuk</span>
                </a>
            @endauth
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function showLoginAlert() {
            alert('Silakan login terlebih dahulu untuk mengakses fitur ini');
        }
    </script>
@endpush
