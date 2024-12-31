<!-- Enhanced Navbar with Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-primary" href="{{ route('market.index') }}">
            Food Fusion
        </a>

        <!-- Toggler Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('market.wishlist') }}">
                        <i class="bi bi-heart me-1"></i> Favorit
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('market.cart') }}">
                        <i class="bi bi-cart me-1"></i> Keranjang
                    </a>
                </li>
            </ul>

            <!-- Search Form -->
            <form class="d-flex me-3">
                <input class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- User Section -->
            @auth
                <!-- User Menu for Desktop -->
                <div class="dropdown d-none d-lg-block">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button"
                        id="userDropdown" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                            alt="Foto Profil" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu mx-auto" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile.show') }}"><i
                                    class="bi bi-person me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('market.orders') }}"><i
                                    class="bi bi-clock-history me-2"></i> Riwayat Pesanan</a></li>
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

                <!-- User Menu for Mobile (Dropdown) -->
                <div class="dropdown d-lg-none py-3 d-flex justify-content-center">
                    <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button"
                        id="userDropdownMobile" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-profile.png') }}"
                            alt="Foto Profil" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu mx-auto" aria-labelledby="userDropdownMobile">
                        <li><a class="dropdown-item" href="{{ route('pelanggan.profile.show') }}"><i
                                    class="bi bi-person me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('market.orders') }}"><i
                                    class="bi bi-clock-history me-2"></i> Riwayat Pesanan</a></li>
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
                <!-- Auth Buttons -->
                <div class="d-flex my-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
