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
            <a class="btn position-relative" href="{{ route('cart.index') }}">
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
                    {{-- <a class="btn btn-outline-primary me-2" href="{{ route('market.notifications') }}"> --}}
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
                <li class="nav-item">
                    {{-- <a class="nav-link" href="{{ route('market.wishlist') }}"> --}}
                    <a class="nav-link" href="#">
                        <i class="bi bi-heart me-1"></i> Favorit
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block position-relative">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        {{-- <a class="nav-link" href="#"> --}}
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
            <form class="d-flex align-items-center me-3">
                <input class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search">
                <button class="btn btn-outline-primary d-flex align-items-center justify-content-center" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            @auth
                <!-- User Menu for Desktop -->
                <div class="dropdown d-none d-lg-block">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button"
                        id="userDropdown" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                            alt="Foto Profil" class="rounded-circle me-2 img-fluid"
                            style="width: 40px; height: 40px; object-fit: cover; min-width: 40px;">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu mx-auto" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile') }}"><i
                                    class="bi bi-person me-2"></i> Profil</a></li>
                        {{-- <li><a class="dropdown-item" href="{{ route('market.orders') }}"><i --}}
                        <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Riwayat
                                Pesanan</a></li>
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
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button"
                        id="userDropdownMobile" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                            alt="Foto Profil" class="rounded-circle me-2 img-fluid"
                            style="width: 40px; height: 40px; object-fit: cover; min-width: 40px;">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu mx-auto" aria-labelledby="userDropdownMobile">
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile') }}"><i
                                    class="bi bi-person me-2"></i> Profil</a></li>
                        {{-- <li><a class="dropdown-item" href="{{ route('market.orders') }}"><i --}}
                        <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Riwayat
                                Pesanan</a></li>
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
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
