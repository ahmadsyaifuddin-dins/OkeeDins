<!-- Tagline Section -->
<div class="bg-gradient-to-b from-gray-50 to-white py-14 overflow-hidden">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-6 animate-fade-in text-custom-secondary">
            {{ substr($appSettings['app_name'], 0, -4) }}<span class="text-custom">{{ substr($appSettings['app_name'],
                -4) }}</span>
        </h1>
        <div class="space-y-3 max-w-2xl mx-auto relative min-h-[10px] md:min-h-[0px]">
            <div class="tagline-slider">
                <div class="tagline-slide opacity-0 transition-all duration-500 absolute w-full px-4">
                    <p class="text-2xl font-semibold text-gray-800">Belanja Cerdas, Harga Pas!</p>
                </div>
                <div class="tagline-slide opacity-0 transition-all duration-500 absolute w-full px-4">
                    <p class="text-xl text-gray-700">Cepat, Lengkap, <span class="text-custom-secondary">{{
                            substr($appSettings['app_name'], 0, -4)
                            }}</span><span class="text-custom">{{ substr($appSettings['app_name'], -4) }}</span>
                        Solusinya!</p>
                </div>
                <div class="tagline-slide opacity-0 transition-all duration-500 absolute w-full px-4">
                    <p class="text-lg italic text-custom">From Cart to Heart - Only at <span
                            class="text-custom-secondary">{{
                            substr($appSettings['app_name'], 0, -4) }}</span><span class="text-custom">{{
                            substr($appSettings['app_name'], -4) }}</span>
                    </p>
                </div>
                <div class="tagline-slide opacity-0 transition-all duration-500 absolute w-full px-4">
                    <p class="text-lg text-gray-700 leading-relaxed">Nikmati Kemudahan Berbelanja dengan Harga Terbaik
                    </p>
                </div>
                <div class="tagline-slide opacity-0 transition-all duration-500 absolute w-full px-4">
                    <p class="text-md text-gray-600">Kami Hadir untuk Memenuhi Kebutuhan Anda</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.tagline-slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => {
            slide.style.opacity = '0';
            slide.style.transform = 'translateY(20px)';
        });
        
        slides[index].style.opacity = '1';
        slides[index].style.transform = 'translateY(0)';
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Tampilkan slide pertama
    showSlide(0);

    // Mulai slider otomatis
    setInterval(nextSlide, 3500);
});
</script>