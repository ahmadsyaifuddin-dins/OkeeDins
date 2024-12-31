<!-- Enhanced Navbar -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('market.index') }}">
            <span class="text-primary">Food Fusion</span>
        </a>

        <!-- User Menu for Mobile -->
        @auth
            <div class="dropdown user-menu-mobile d-lg-none">
                <button class="btn p-0 me-2" type="button" id="userDropdownMobile" data-bs-toggle="dropdown">
                    @if (Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Foto Profil" class="user-avatar">
                    @else
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profil" class="user-avatar">
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                    <li class="dropdown-header">
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('pelanggan.profile.show') }}">
                            <i class="bi bi-person me-2"></i>Profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('market.orders') }}">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Pesanan
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth

        <!-- Toggler Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('market.wishlist') }}">
                        <i class="bi bi-heart me-1"></i>
                        <span>Favorit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('market.cart') }}">
                        <i class="bi bi-cart me-1"></i>
                        <span>Keranjang</span>
                    </a>
                </li>
            </ul>

            <!-- Search Form -->
            <div class="search-form me-3">
                <div class="input-group">
                    <input class="form-control search-input" type="search" name="q" placeholder="Cari produk...">
                    <button class="btn btn-search" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <!-- User Menu for Desktop -->
            @auth
                <div class="dropdown user-menu d-none d-lg-block">
                    <button class="btn dropdown-toggle user-button" type="button" id="userDropdown"
                        data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            @if (Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Foto Profil"
                                    class="user-avatar">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" alt="Default Profil"
                                    class="user-avatar">
                            @endif
                            <span class="ms-2 user-name">{{ Auth::user()->name }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end animate-dropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('pelanggan.profile.show') }}">
                                <i class="bi bi-person me-2"></i>Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('market.orders') }}">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Pesanan
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary ms-2">Daftar</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
