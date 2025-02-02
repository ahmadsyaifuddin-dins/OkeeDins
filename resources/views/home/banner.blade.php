<!-- Banner Slider -->
<div class="relative overflow-hidden bg-gray-100 mt-16 md:mt-20 my-4">
    <div class="swiper bannerSwiper h-[300px] sm:h-[350px] md:h-[400px] lg:h-[450px]">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="container mx-auto px-4 h-full">
                    <div class="flex flex-col md:flex-row items-center justify-center h-full py-2 md:py-12">
                        <div class="w-full md:w-1/2 space-y-2 md:space-y-4 text-center md:text-left">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Flash Sale!</h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600">
                                Diskon hingga <span class="font-bold text-custom">70%</span> untuk produk tertentu.
                                Jangan lewatkan!
                            </p>
                            <a href="#flash-sale"
                                class="inline-block px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base bg-custom text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 mt-2 md:mt-0 flex items-center justify-center">
                            <img src="{{ asset('images/banner-kecap-bango.png') }}"
                                class="w-full max-w-[180px] sm:max-w-[220px] md:max-w-lg object-contain"
                                alt="Flash Sale">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="container mx-auto px-4 h-full">
                    <div class="flex flex-col md:flex-row items-center justify-center h-full py-2 md:py-12">
                        <div class="w-full md:w-1/2 space-y-2 md:space-y-4 text-center md:text-left">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Produk Pilihan Terbaik
                            </h2>
                            <p class="text-sm sm:text-base md:text-lg text-gray-600">
                                Temukan berbagai produk berkualitas dengan harga terbaik untuk Anda.
                            </p>
                            <a href="#recommended"
                                class="inline-block px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base bg-custom text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 mt-2 md:mt-0 flex items-center justify-center">
                            <img src="{{ asset('images/banner-default-2.jpg') }}"
                                class="w-full max-w-[180px] sm:max-w-[220px] md:max-w-lg object-contain"
                                alt="Produk Pilihan">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="swiper-button-next after:content-['']">
            <i class="bi bi-chevron-right text-2xl text-custom"></i>
        </div>
        <div class="swiper-button-prev after:content-['']">
            <i class="bi bi-chevron-left text-2xl text-custom"></i>
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</div>

@push('scripts')
    <script>
        // Initialize Swiper
        document.addEventListener('DOMContentLoaded', function() {
            const bannerSwiper = new Swiper('.bannerSwiper', {
                loop: true,
                autoplay: {
                    delay: 3000,
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