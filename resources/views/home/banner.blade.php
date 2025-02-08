<!-- Banner Slider -->
<div class="relative overflow-hidden bg-gray-100 mt-16 md:mt-20 my-4">
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
                            <img src="{{ asset('images/banners/laptop alienware.jpg') }}"
                                class="absolute inset-0 w-full h-full object-contain" alt="Flash Sale">
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
                            <img src="{{ asset('images/banners/banner-kecap-bango.png') }}"
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
                            <img src="{{ asset('images/banners/buah-buahan.jpg') }}"
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
                            <img src="{{ asset('images/banners/t-virus3.jpg') }}"
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bannerSwiper = new Swiper('.bannerSwiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
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
        });
    </script>
@endpush
