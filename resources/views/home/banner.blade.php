<!-- Banner Skeleton Loader -->
<div id="banner-skeleton" class="relative overflow-hidden animate-pulse bg-gray-100 mt-16 md:mt-20 my-4">
    <div class="container mx-auto px-4 h-full">
        <div class="flex flex-col md:flex-row items-center justify-between h-[400px] md:h-[500px] py-8 md:py-12">
            <!-- Bagian Teks -->
            <div class="w-full md:w-1/2 space-y-4 text-center md:text-left mb-4 md:mb-0">
                <!-- Headline Placeholder -->
                <div class="h-8 bg-gray-300 rounded w-2/3 mx-auto md:mx-0"></div>
                <!-- Subteks Placeholder -->
                <div class="h-4 bg-gray-300 rounded w-3/4 mx-auto md:mx-0"></div>
                <!-- Tombol Placeholder -->
                <div class="h-10 bg-gray-300 rounded w-1/2 mx-auto md:mx-0"></div>
            </div>
            <!-- Bagian Gambar -->
            <div class="w-full md:w-1/2 h-48 md:h-80 relative">
                <div class="w-full h-full bg-gray-300 rounded-lg"></div>
            </div>
        </div>
    </div>
</div>

<!-- Banner Slider -->
<div class="relative overflow-hidden bg-gray-50 mt-16 md:mt-20 my-2 hidden" id="banner-slider">
    <div class="swiper bannerSwiper">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide h-[400px] md:h-[500px]">
                <div class="container mx-auto px-4 h-full">
                    <div class="flex flex-col md:flex-row items-center justify-between h-full py-8 md:py-12">
                        <div class="w-full md:w-1/2 space-y-4 text-center md:text-left mb-4 md:mb-0">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Flash Sale!</h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600">
                                Diskon hingga <span class="font-bold text-custom">70%</span> untuk produk tertentu.
                                Jangan lewatkan!
                            </p>
                            <a href="#flash-sale"
                                class="inline-block px-6 py-3 text-sm sm:text-base bg-custom text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 h-48 md:h-80 relative">
                            <picture>
                                <img src="{{ asset('images/banners/laptop alienware.webp') }}" width="800" height="600"
                                    alt="Flash Sale" loading="eager"
                                    class="absolute inset-0 w-full h-full object-contain">
                            </picture>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide h-[400px] md:h-[500px]">
                <div class="container mx-auto px-4 h-full">
                    <div class="flex flex-col md:flex-row items-center justify-between h-full py-8 md:py-12">
                        <div class="w-full md:w-1/2 space-y-4 text-center md:text-left mb-4 md:mb-0">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Produk Pilihan Terbaik
                            </h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600">
                                Temukan berbagai produk berkualitas dengan harga terbaik untuk Anda.
                            </p>
                            <a href="#recommended"
                                class="inline-block px-6 py-3 text-sm sm:text-base bg-custom text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 h-48 md:h-80 relative">
                            <img src="{{ asset('images/banners/banner-kecap-bango.png') }}" width="800" height="600"
                                class="absolute inset-0 w-full h-full object-contain" alt="Produk Pilihan">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="swiper-slide h-[400px] md:h-[500px]">
                <div class="container mx-auto px-4 h-full">
                    <div class="flex flex-col md:flex-row items-center justify-between h-full py-8 md:py-12">
                        <div class="w-full md:w-1/2 space-y-4 text-center md:text-left mb-4 md:mb-0">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Produk Pilihan Terbaik
                            </h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600">
                                Temukan berbagai produk berkualitas dengan harga terbaik untuk Anda.
                            </p>
                            <a href="#recommended"
                                class="inline-block px-6 py-3 text-sm sm:text-base bg-custom text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 h-48 md:h-80 relative">
                            <img src="{{ asset('images/banners/buah apel.webp') }}" width="800" height="600"
                                class="absolute inset-0 w-full h-full object-contain" alt="Produk Pilihan">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="swiper-slide h-[400px] md:h-[500px]">
                <div class="container mx-auto px-4 h-full">
                    <div class="flex flex-col md:flex-row items-center justify-between h-full py-8 md:py-12">
                        <div class="w-full md:w-1/2 space-y-4 text-center md:text-left mb-4 md:mb-0">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Produk Pilihan Terbaik
                            </h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600">
                                Temukan berbagai produk berkualitas dengan harga terbaik untuk Anda.
                            </p>
                            <a href="#recommended"
                                class="inline-block px-6 py-3 text-sm sm:text-base bg-custom text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 h-48 md:h-80 relative">
                            <img src="{{ asset('images/banners/Yukinoshita_Yukino.webp') }}" width="800" height="600"
                                class="absolute inset-0 w-full h-full object-contain" alt="Produk Pilihan">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="swiper-button-next after:content-[''] !hidden md:!flex">
            <i class="bi bi-chevron-right text-2xl text-custom"></i>
        </div>
        <div class="swiper-button-prev after:content-[''] !hidden md:!flex">
            <i class="bi bi-chevron-left text-2xl text-custom"></i>
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</div>

@push('scripts')
{{-- <script src="{{ asset('js/skeleton-loader.js') }}"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            // Hapus skeleton loader
            document.getElementById('banner-skeleton').style.display = 'none';
            // Tampilkan slider banner
            const bannerSlider = document.getElementById('banner-slider');
            bannerSlider.classList.remove('hidden');
            
            // Inisialisasi ulang Swiper setelah container slider terlihat
            const bannerSwiper = new Swiper('.bannerSwiper', {
                loop: true,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }, 1000); // Delay 1 detik, sesuaikan jika perlu
    });
</script>

<style>
    .swiper-pagination-bullet {
        margin: 6px;
        touch-action: manipulation;
    }
</style>
@endpush