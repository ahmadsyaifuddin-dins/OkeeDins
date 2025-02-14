<nav x-data="{ mobileMenu: false }" x-init="mobileMenu = false"
    class="sticky top-0 left-0 right-0 bg-white border-b border-gray-200 z-30">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home.index') }}" class="flex items-center">
                <img src="{{ asset('storage/' . ($appSettings['app_logo'] ?? 'default-logo.png')) }}"
                    alt="{{ $appSettings['app_name'] ?? 'App Logo' }}" class="h-16 md:h-20 w-auto">
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <form action="{{ route('home.search') }}" method="GET" class="w-full">
                    <div class="relative">
                        <input type="text" name="query"
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-custom"
                            placeholder="Cari produk favoritmu..." value="{{ request('query') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Mobile Search Bar (Centered) -->
            <div class="flex-1 md:hidden mx-4">
                <form action="{{ route('home.search') }}" method="GET">
                    <div class="relative flex items-center">
                        <input type="text" name="query"
                            class="w-full pl-10 pr-4 py-2 text-sm rounded-full border border-gray-300 focus:outline-none focus:border-custom"
                            placeholder="Cari Produk..." value="{{ request('query') }}">
                        <button type="submit" class="absolute left-3 text-gray-400">
                            <i class="bi bi-search text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mobile Icons (Right-aligned) -->
            <div class="flex items-center gap-2 md:hidden flex-shrink-0">
                @auth
                <a href="{{ route('cart.index') }}" class="p-2 text-gray-600 hover:text-custom relative">
                    <i class="bi bi-cart text-xl"></i>
                    @if (Auth::user()->cart->count() > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                        {{ Auth::user()->cart->count() }}
                    </span>
                    @endif
                </a>
                @else
                <button onclick="showLoginAlert()" class="p-2 text-gray-600 hover:text-custom relative">
                    <i class="bi bi-cart text-xl"></i>
                </button>
                @endauth

                <!-- Mobile Menu Button -->
                <button type="button" class="p-2 text-gray-600 hover:text-custom" @click="mobileMenu = !mobileMenu">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                <a href="{{ route('wishlist.index') }}" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-heart text-xl"></i>
                    @if (Auth::user()->wishlist->count() > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                        {{ Auth::user()->wishlist->count() }}
                    </span>
                    @endif
                </a>
                @else
                <button onclick="showLoginAlert()" name="wishlist" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-heart text-xl"></i>
                </button>
                @endauth

                @auth
                <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-cart text-xl"></i>
                    @if (Auth::user()->cart->count() > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                        {{ Auth::user()->cart->count() }}
                    </span>
                    @endif
                </a>
                @else
                <button onclick="showLoginAlert()" name="cart" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-cart text-xl"></i>
                </button>
                @endauth

                @auth
                <a href="{{ route('payment.index') }}" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-credit-card text-xl"></i>
                    @if ($bayarCount > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                        {{ $bayarCount }}
                    </span>
                    @endif
                </a>
                @else
                <button name="pembayaran" onclick="showLoginAlert()" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-credit-card text-xl"></i>
                </button>
                @endauth
                @guest
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-custom relative">
                    <i class="bi bi-info-circle text-xl"></i>

                </a>
                @endguest
                @auth
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 text-gray-700 hover:text-custom">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/user.svg') }}"
                            alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                        <spa class="capitalize">Hi, {{ explode(' ', Auth::user()->name)[0] }}</spa>
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-100">
                        <a href="{{ route('pelanggan.profile') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-person-circle mr-2"></i>
                            Profile
                        </a>
                        <a href="{{ route('home.riwayat-pesanan') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-bag mr-2"></i>
                            Pesanan
                        </a>

                        <a href="{{ route('transactions.index') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-receipt mr-2"></i>
                            Riwayat Transaksi
                        </a>

                        <a href="{{ route('games.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-controller mr-2"></i>
                            Games
                        </a>
                        <a href="{{ route('about') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                            <i class="bi bi-info-circle mr-2"></i>
                            Tentang Kami
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:text-custom ">
                                <i class="bi bi-box-arrow-right mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-custom">
                    <i class="bi bi-person-circle text-xl"></i>
                </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90" class="md:hidden fixed inset-0 z-50 bg-white">
            <div class="flex flex-col h-full">
                @auth
                <!-- Mobile Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <div class="flex items-center gap-2">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/user.svg') }}"
                            alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                        <span class="font-medium">Hi, {{ explode(' ', Auth::user()->name)[0] }}</span>
                    </div>
                    <button @click="mobileMenu = false" class="text-gray-500 hover:text-gray-700">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
                @else
                <!-- Guest Mobile Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/user.svg') }}" alt="Guest User"
                            class="w-10 h-10 rounded-full object-cover">
                        <span class="font-medium">Guest</span>
                    </div>
                    <button @click="mobileMenu = false" class="text-gray-500 hover:text-gray-700">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
                @endauth
                <div class="py-2">

                    @auth
                    <a href="{{ route('transactions.index') }}"
                        class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                    <a href="{{ route('games.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-controller mr-2"></i>
                        Games
                    </a>
                    <a href="{{ route('about') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                        <i class="bi bi-info-circle mr-2"></i>
                        Tentang Kami
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 text-gray-700 hover:text-custom">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                    @else
                    <div class="p-4 space-y-3">
                        <a href="{{ route('about') }}"
                            class="block w-full text-center px-4 py-2.5 border border-custom text-custom rounded-lg hover:bg-red-50 transition duration-150">
                            Tentang Kami
                        </a>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('login') }}"
                                class="block w-full text-center px-4 py-2.5 border border-custom text-custom rounded-lg hover:bg-red-50 transition duration-150">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="block w-full text-center px-4 py-2.5 bg-custom text-white rounded-lg hover:bg-red-600 transition duration-150">
                                Daftar
                            </a>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function showLoginAlert() {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Silakan login terlebih dahulu untuk mengakses fitur ini',
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#EF4444'
        });
    }
</script>