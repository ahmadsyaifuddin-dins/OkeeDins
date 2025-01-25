<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">

        <a class="text-decoration-none h5 fw-bold text-custom" href="{{ route('home.index') }}">
            Food <span class="text-primary">Fusion</span>
        </a>

        <!-- Mobile Icons -->
        <div class="d-lg-none d-flex align-items-center ms-auto me-2">

            <a class="btn" href="#">
                <i class="bi bi-bell"></i>
            </a>

            <a class="btn" href="#">
                <i class="bi bi-envelope"></i>
            </a>

            <a class="btn position-relative" id="cartBadgeMobile" href="{{ route('cart.index') }}">
                <i class="bi bi-cart"></i>
                @auth
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $cartCount }}
                    </span>
                @endauth
            </a>
        </div>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Desktop Icons -->
                <li class="nav-item d-none d-lg-block">
                    {{-- <a class="btn btn-outline-custom me-2" href="{{ route('market.notifications') }}"> --}}
                    <a class="nav-link" href="#">
                        <i class="bi bi-bell me-1"></i> Notifikasi
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    {{-- <a class="nav-link" href="{{ route('market.messages') }}"> --}}
                    <a class="nav-link" href="#">
                        <i class="bi bi-envelope me-1"></i> Pesan
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link position-relative" href="{{ route('wishlist.index') }}">
                        <i class="bi bi-heart me-1"></i> Favorit
                        @auth
                            <span class="position-absolute translate-middle badge rounded-pill bg-danger"
                                style="top: 0px; right: -15px;">
                                {{ $wishlistCount }}
                            </span>
                        @endauth
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block position-relative">
                    <a class="nav-link" id="cartBadge" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart me-1"></i> Keranjang
                        @auth
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endauth
                    </a>
                </li>
            </ul>

            <!-- Search Form -->
            <form action="{{ route('home.search') }}" method="GET" class="d-flex align-items-center me-3">
                <input class="form-control me-2" type="search" name="query" placeholder="Cari produk..."
                    aria-label="Search" value="{{ request('query') }}">
                <button class="btn btn-outline-custom d-flex align-items-center justify-content-center" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            @auth
                <!-- User Menu for Desktop -->
                <div class="dropdown d-none d-lg-block">
                    <button class="btn btn-outline-custom dropdown-toggle d-flex align-items-center" type="button"
                        id="userDropdown" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/user.svg') }}"
                            alt="Foto Profil" class="rounded-circle me-2 img-fluid"
                            style="width: 35px; height: 35px; object-fit: cover; min-width: 35px;">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu mx-auto" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile') }}"><i
                                    class="bi bi-person me-2"></i> Profil</a></li>
                        {{-- <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-basket me-2"></i>
                                Pesanan Saya</a></li> --}}
                        <li><a class="dropdown-item" href="{{ route('payment.index') }}"><i
                                    class="bi bi-credit-card me-2"></i> Pembayaran
                            </a></li>
                        <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i
                                    class="bi bi-clock-history me-2"></i> Riwayat
                                Pesanan</a></li>
                        <li><a class="dropdown-item" href="{{ route('transactions.index') }}"><i
                                    class="bi bi-wallet2 me-2"></i> Transaksi</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

                <!-- User Menu for Mobile -->
                <div class="dropdown d-lg-none py-3 d-flex justify-content-center">
                    <button class="btn btn-outline-custom dropdown-toggle d-flex align-items-center" type="button"
                        id="userDropdownMobile" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/user.svg') }}"
                            alt="Foto Profil" class="rounded-circle me-2 img-fluid"
                            style="width: 30px; height: 30px; object-fit: cover; min-width: 30px;"
                            onchange="updateNavbarProfilePhoto(this.src)">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu mx-auto" aria-labelledby="userDropdownMobile">
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile') }}"><i
                                    class="bi bi-person me-2"></i> Profil</a></li>
                        {{-- <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i
                                    class="bi bi-basket me-2"></i>
                                Pesanan Saya</a></li> --}}
                        <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i
                                    class="bi bi-clock-history me-2"></i> Riwayat
                                Pesanan</a></li>
                        <li><a class="dropdown-item" href="{{ route('transactions.index') }}"><i
                                    class="bi bi-wallet2 me-2"></i> Transaksi</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="d-flex align-items-center">
                    <a href="{{ route('login') }}" class="btn btn-outline-custom me-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-custom">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<!-- Script untuk perubahan profil realtime pada navbar nya -->
@push('scripts')
    <script src="{{ asset('js/profile-pelanggan.js') }}"></script>
@endpush
