<!-- Sidebar -->
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href=" {{ route('admin.dashboard') }} " target="_blank">
            <img src="{{ asset('material-dashboard/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img"
                width="26" height="26" alt="main_logo">
            <span class="ms-1 text-sm text-dark">Food Fusion</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.pengguna.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.pengguna.index') }}">
                    <i class="material-symbols-rounded opacity-5">people</i>
                    <span class="nav-link-text ms-1">Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.kategori.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.kategori.index') }} ">
                    <i class="material-symbols-rounded opacity-5">category</i>
                    <span class="nav-link-text ms-1">Kategori Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.produk.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.produk.index') }} ">
                    <i class="material-symbols-rounded opacity-5">inventory</i>
                    <span class="nav-link-text ms-1">Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.pesanan.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.pesanan.index') }} ">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Pesanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.payments.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('admin.payments.index') }} ">
                    <i class="material-symbols-rounded opacity-5">payments</i>
                    <span class="nav-link-text ms-1">Pembayaran Transfer</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="../pages/billing.html">
                    <i class="material-symbols-rounded opacity-5">Summarize</i>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="../pages/billing.html">
                    <i class="material-symbols-rounded opacity-5">rate_review</i>
                    <span class="nav-link-text ms-1">Ulasan Produk</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark" href="../pages/notifications.html">
                    <i class="material-symbols-rounded opacity-5">notifications</i>
                    <span class="nav-link-text ms-1">Notifications</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Pengaturan Umum
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('admin.vouchers.index') }}">
                    <i class="material-symbols-rounded opacity-5">redeem</i>
                    <span class="nav-link-text ms-1">Vouchers</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('admin.pengguna.profile') }}">
                    <i class="material-symbols-rounded opacity-5">person</i>
                    <span class="nav-link-text ms-1">Profile Administrator</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-symbols-rounded opacity-5">logout</i>
                    <span class="nav-link-text ms-1">Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">

    </div>
</aside>
