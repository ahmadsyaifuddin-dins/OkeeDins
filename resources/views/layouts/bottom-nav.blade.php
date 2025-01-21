<div class="d-block d-lg-none bg-light fixed-bottom">
    <div class="container d-flex justify-content-around py-2">
        <a href="{{ route('home.index') }}" class="text-center text-decoration-none {{ Route::currentRouteName() == 'home.index' ? 'text-custom' : 'text-secondary' }}">
            <i class="bi bi-house fs-4"></i>
            <div>Home</div>
        </a>
        <a href="{{ route('wishlist.index') }}" class="text-center text-decoration-none position-relative {{ Route::currentRouteName() == 'wishlist.index' ? 'text-custom' : 'text-secondary' }}">
            <i class="bi bi-heart fs-4"></i>
            <div>Wishlist</div>
            @auth
                @if($wishlistCount > 0)
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger" style="top: 0; right: 10px;">
                        {{ $wishlistCount }}
                    </span>
                @endif
            @endauth
        </a>
        <a href="#" class="text-center text-decoration-none {{ Route::currentRouteName() == 'transactions' ? 'text-custom' : 'text-secondary' }}">
            <i class="bi bi-receipt fs-4"></i>
            <div>Transaksi</div>
        </a>
        <a href="{{ route('pelanggan.profile') }}" class="text-center text-decoration-none {{ Route::currentRouteName() == 'pelanggan.profile' ? 'text-custom' : 'text-secondary' }}">
            <i class="bi bi-person fs-4"></i>
            <div>Profil</div>
        </a>
    </div>
</div>
