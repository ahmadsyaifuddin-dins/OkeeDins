@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Hero Section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Tentang Kami</h1>
                <p class="text-lg text-gray-600">Mengenal lebih dekat <span
                        class="text-custom-secondary font-medium">{{ substr($appSettings['app_name'], 0, -4) }}</span><span
                        class="text-custom font-medium">{{ substr($appSettings['app_name'], -4) }}</span></p>
            </div>

            <!-- About Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Company Overview -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center md:text-left">Visi Kami</h2>
                    <p class="text-gray-600 leading-relaxed text-center">
                        Menjadi pionir inovasi digital yang nggak cuma canggih, tapi juga nyambung sama kebutuhan
                        zaman - menginspirasi generasi muda untuk berkreasi tanpa batas.
                    </p>
                </div>

                <!-- Mission Section -->
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center md:text-left">Misi Kami</h2>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Mendorong <span class="text-custom font-medium">kolaborasi</span> dan kreativitas dalam setiap
                            langkah,
                            karena kerja bareng itu
                            lebih asik
                            daripada kerja sendiri.
                        </li>
                        <li>Menghadirkan <span class="text-custom font-medium">solusi digital</span> yang praktis, aman, dan
                            berkelanjutan buat generasi sekarang dan
                            masa depan.
                        </li>
                        <li>Menjadi <span class="text-custom font-medium">wadah pengembangan talenta muda</span> yang siap
                            menghadapi tantangan global dengan <span
                                class="text-custom-secondary font-medium italic">ide-ide
                                yang out of the box</span>.
                        </li>
                        <li> <span class="text-custom font-medium">Terus belajar dan beradaptasi</span> dengan perkembangan
                            teknologi, <span class="text-custom-secondary font-medium">karena di dunia digital, stagnasi
                                itu berarti ketinggalan.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Team Section -->
            <div class="mt-20">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Tim Kami</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    <!-- Team Members 1-8 remain the same -->
                    <!-- Team Member 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/Pro.png') }}" alt="Ahmad Syaifuddin"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Ahmad Syaifuddin</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Founder &amp; CEO ⚡</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Tuan Besar ⚡
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/aldy.jpg') }}" alt="Muhammad Aldy Rahmatillah"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Muhammad Aldy Rahmatillah</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Head of Product 🎯</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Pemikir Produk 🎯
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/maul.jpg') }}" alt="Muhammad Maulidi"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Muhammad Maulidi</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Marketing Manager 📢</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Ahli Promosi 📢
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 4 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/haldi-2.png') }}" alt="M. Haldi"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">M. Haldi</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Head of Operations ⚙️</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Penggerak Tim ⚙️
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 5 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/user.svg') }}" alt="Muhammad Rio Bisma Saputra"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Muhammad Rio Bisma Saputra</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Chief Technology Officer (CTO) 💻</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Jagoan Teknologi 💻
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 6 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/ryandy.png') }}" alt="Ryandy Rhamadhany"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Ryandy Rhamadhany</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Head of Customer Experience 🤝</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Pakar Kepuasan Pelanggan 🤝
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 7 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/user.svg') }}" alt="Gusti Randa"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Gusti Randa</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Lead Developer 🖥️</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Kode Master 🖥️
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 8 -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/photo_2025-02-11_10-48-02.jpg') }}" alt="Aca Salsabila"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Aca Salsabila</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">UI/UX Designer 🎨</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Ahli Desain Tampilan 🎨
                            </div>
                        </div>
                    </div>
                    <!-- Create a separate div for the 9th member with centering classes --
                    <!-- Team Member 9 - Centered -->
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/Tiara-Desmitha-Oliviany.jpg') }}"
                            alt="Tiara Desmitha Oliviany" class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Tiara Desmitha Oliviany</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">CSR (Corporate Social Responsibility) Manager 🌍</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Pahlawan Sosial 🌍
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/gita.jpg') }}" alt="Gita"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Gita</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">HR Manager (HRD) 👥</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Penjaga Talenta 👥
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/liza.jpg') }}" alt="Haliza Sabila Halim"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Haliza Sabila Halim</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Digital Ads Specialist 📈</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Jagoan Iklan Digital 📈
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <img src="{{ asset('images/testimonials/diah.jpg') }}" alt="Diah Kurniawati"
                            class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                        <h3 class="text-xl font-bold text-gray-900">Diah Kurniawati</h3>
                        <!-- Container Tooltip -->
                        <div class="relative inline-block tooltip-container">
                            <!-- Teks Utama -->
                            <p class="text-gray-600 cursor-pointer tooltip-trigger">Copywriter ✍️</p>
                            <!-- Tooltip -->
                            <div
                                class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                Penulis Karya ✍️
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Container for the last two members -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-8">
                    <!-- Last row with centered content on desktop -->
                    <div
                        class="col-span-1 sm:col-span-2 md:col-span-2 lg:col-span-2 flex flex-col sm:flex-row justify-center gap-8">
                        <!-- Team Member - Fitriani -->
                        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-full sm:max-w-xs">
                            <img src="{{ asset('images/testimonials/fitriani - kompress.jpg') }}" alt="Fitriani"
                                class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                            <h3 class="text-xl font-bold text-gray-900">Fitriani</h3>
                            <!-- Container Tooltip -->
                            <div class="relative inline-block tooltip-container">
                                <!-- Teks Utama -->
                                <p class="text-gray-600 cursor-pointer tooltip-trigger">Innovation & Strategy Lead 🚀</p>
                                <!-- Tooltip -->
                                <div
                                    class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                    Otak Inovasi 🚀
                                </div>
                            </div>
                        </div>

                        <!-- Team Member - Maulida Rahmah -->
                        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-full sm:max-w-xs">
                            <img src="{{ asset('images/testimonials/maulida.jpg') }}" alt="Maulida Rahmah"
                                class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                            <h3 class="text-xl font-bold text-gray-900">Maulida Rahmah</h3>
                            <!-- Container Tooltip -->
                            <div class="relative inline-block tooltip-container">
                                <!-- Teks Utama -->
                                <p class="text-gray-600 cursor-pointer tooltip-trigger">Event & Campaign Manager 🎉</p>
                                <!-- Tooltip -->
                                <div
                                    class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 opacity-0 transition-opacity duration-300 bg-custom-secondary text-white text-sm rounded py-1 px-4 pointer-events-none min-w-[200px] tooltip-text">
                                    Panglima Acara 🎉
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expert Recommendations Section -->
                <div class="mt-20">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Rekomendasi Ahli</h2>
                    <p class="text-center text-gray-600 mb-10">Berikut adalah rekomendasi dari para ahli industri yang
                        membantu
                        mengembangkan OkeeDins</p>

                    <div class="grid grid-cols-1 gap-8">
                        <!-- Expert 1 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            {{-- <img src="{{ asset('images/advisors/advisor1.jpg') }}" alt="Dr. Surya Wijaya" --}} {{--
                            class="w-24 h-24 rounded-full object-cover"> --}}
                            <img src="{{ asset('images/user.svg') }}" alt="Dr. Surya Wijaya"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Dr. Surya Wijaya</h3>
                                <p class="text-gray-600 mb-3 italic">Pakar Teknologi dan Inovasi</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Jangan takut bereksperimen! Dunia digital itu bergerak super cepat, jadi terus update
                                    dan
                                    coba hal-hal out-of-the-box. Agile bukan cuma metodologi, tapi mindset untuk terus
                                    berkembang."
                                </p>
                            </div>
                        </div>

                        <!-- Expert 2 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="{{ asset('images/user.svg') }}" alt="Anita Pratiwi"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Anita Pratiwi</h3>
                                <p class="text-gray-600 mb-3 italic">Praktisi UX & Desain Digital</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Fokus utama harus ke pengalaman pengguna. Desain yang simpel, intuitif, dan responsif
                                    itu
                                    bikin pengguna betah dan nambah kepercayaan. Ingat, kesederhanaan adalah kunci untuk
                                    engagement maksimal!"
                                </p>
                            </div>
                        </div>

                        <!-- Expert 3 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="{{ asset('images/user.svg') }}" alt="Budi Santoso"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Budi Santoso</h3>
                                <p class="text-gray-600 mb-3 italic">Tech Lead di Startup Unicorn</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Arsitektur backend yang scalable sangat krusial untuk pertumbuhan OkeeDins. Gunakan
                                    microservices dan pastikan sistem pembayaran yang aman. Integrasi dengan berbagai jasa
                                    pengiriman dan real-time tracking akan meningkatkan kepercayaan pelanggan."
                                </p>
                            </div>
                        </div>

                        <!-- Expert 4 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="{{ asset('images/user.svg') }}" alt="Lina Hartono"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Dika Permana</h3>
                                <p class="text-gray-600 mb-3 italic">Ahli Keamanan Siber</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Keamanan data bukan opsi, tapi keharusan. Terapkan proteksi kelas dunia di setiap
                                    inovasi
                                    OkeeDins supaya pengguna merasa aman dan nyaman. Data mereka itu mahal, jadi jangan
                                    main-main."
                                </p>
                            </div>
                        </div>

                        <!-- Expert 5 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="{{ asset('images/user.svg') }}" alt="Raden Aditya"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Raden Aditya</h3>
                                <p class="text-gray-600 mb-3 italic">Pakar Keamanan Pangan & Logistik</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Standar keamanan pangan harus menjadi prioritas. OkeeDins perlu memiliki sistem
                                    verifikasi
                                    vendor yang ketat dan monitoring kualitas yang konsisten. Investasi pada packaging ramah
                                    lingkungan dan rantai dingin (cold chain) untuk makanan segar akan membedakan OkeeDins
                                    dari
                                    kompetitor."
                                </p>
                            </div>
                        </div>

                        <!-- Expert 6 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="{{ asset('images/user.svg') }}" alt="Siti Nuraini"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Siti Nuraini</h3>
                                <p class="text-gray-600 mb-3 italic">Spesialis Pengembangan Bisnis Digital</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Pasar itu dinamis, jadi jangan terpaku pada satu strategi. Gabungkan teknologi dengan
                                    pendekatan bisnis yang fleksibel untuk menggapai peluang global. Inovasi yang nyata itu
                                    harus punya visi bisnis yang tajam."
                                </p>
                            </div>
                        </div>
                        <!-- Expert 7 -->
                        <div
                            class="bg-white p-8 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start gap-6">
                            <img src="{{ asset('images/user.svg') }}" alt="Arif Rahman"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Arif Rahman</h3>
                                <p class="text-gray-600 mb-3 italic">Konsultan Transformasi Digital</p>
                                <p class="text-gray-700 leading-relaxed">
                                    "Kolaborasi lintas disiplin itu wajib. Bangun ekosistem di mana ide-ide segar bisa
                                    berkembang tanpa
                                    batasan. Koneksi antara berbagai bidang baru menghasilkan solusi yang benar-benar
                                    inovatif
                                    dan
                                    berdampak."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script untuk meng-handle hover dan klik -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get all tooltip containers
                const containers = document.querySelectorAll('.tooltip-container');

                // For each container, set up event listeners
                containers.forEach(container => {
                    const tooltipText = container.querySelector('.tooltip-text');
                    let toggled = false; // State untuk setiap tooltip individual

                    // Fungsi untuk tampilkan tooltip
                    function showTooltip() {
                        tooltipText.classList.remove('opacity-0');
                        tooltipText.classList.add('opacity-100');
                    }

                    // Fungsi untuk sembunyikan tooltip
                    function hideTooltip() {
                        tooltipText.classList.remove('opacity-100');
                        tooltipText.classList.add('opacity-0');
                    }

                    // Saat hover (mouseenter) tampilkan tooltip, kecuali sedang di-toggle
                    container.addEventListener('mouseenter', () => {
                        if (!toggled) {
                            showTooltip();
                        }
                    });

                    // Saat mouse leave sembunyikan tooltip, kecuali sedang di-toggle
                    container.addEventListener('mouseleave', () => {
                        if (!toggled) {
                            hideTooltip();
                        }
                    });

                    // Saat diklik, toggle state tooltip
                    container.addEventListener('click', () => {
                        toggled = !toggled;
                        if (toggled) {
                            showTooltip();
                        } else {
                            hideTooltip();
                        }
                    });
                });
            });
        </script>
    @endsection
