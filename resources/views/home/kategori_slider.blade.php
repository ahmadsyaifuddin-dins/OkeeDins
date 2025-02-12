<div class="sticky top-16 z-20 bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4">
        <!-- Skeleton Loader Kategori -->
        <div id="kategori-skeleton" class="swiper kategoriSwiper">
            <div class="swiper-wrapper text-sm md:text-base">
                @for ($i = 0; $i < 9; $i++) <div class="swiper-slide w-auto">
                    <!-- Placeholder kategori berupa chip dengan animasi pulse -->
                    <div class="inline-block px-4 py-2 rounded-full bg-gray-200 animate-pulse"
                        style="min-width: 120px;">
                    </div>
            </div>
            @endfor
        </div>
    </div>

    <!-- Actual Kategori (Tersembunyi saat loading) -->
    <div id="kategori-actual" class="hidden">
        <div class="swiper kategoriSwiper">
            <div class="swiper-wrapper text-sm md:text-base">
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
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan kategori setelah loading
        setTimeout(() => {
            document.getElementById('kategori-skeleton').style.display = 'none';
            document.getElementById('kategori-actual').classList.remove('hidden');
            
            // Inisialisasi Swiper (pastikan Anda sudah memuat library Swiper)
            if (window.Swiper) {
                new Swiper('.kategoriSwiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    freeMode: true
                });
            }
        }, 1500);
    });
</script>