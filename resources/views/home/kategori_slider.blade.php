<div class="py-4" data-aos="fade-up">
    <div class="swiper kategoriSwiper">
        <div class="swiper-wrapper">
            <!-- Semua Kategori -->
            <div class="swiper-slide w-auto">
                <a href="{{ route('home.index') }}"
                    class="inline-block px-4 py-2 rounded-full {{ !request()->query('kategori') ? 'bg-custom text-white font-medium' : 'bg-white text-gray-700 border border-gray-200' }}">
                    Semua Kategori
                </a>
            </div>

            <!-- Kategori Items -->
            @foreach ($kategori as $kat)
                <div class="swiper-slide w-auto">
                    <a href="{{ route('home.index', ['kategori' => $kat->slug]) }}"
                        class="inline-block px-4 py-2 rounded-full {{ request()->query('kategori') == $kat->slug ? 'bg-custom text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
                        {{ $kat->nama_kategori }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
