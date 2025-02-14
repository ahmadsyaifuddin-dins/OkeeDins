@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tentang Kami</h1>
            <p class="text-lg text-gray-600">Mengenal lebih dekat <span class="text-custom-secondary font-medium">{{
                    substr($appSettings['app_name'], 0, -4)
                    }}</span><span class="text-custom font-medium">{{ substr($appSettings['app_name'], -4) }}</span></p>
        </div>

        <!-- About Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Company Overview -->
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Visi Kami</h2>
                <p class="text-gray-600 leading-relaxed">
                    Menjadi platform kuliner terdepan yang menghubungkan pecinta makanan dengan pengalaman kuliner
                    terbaik.
                </p>
            </div>

            <!-- Mission Section -->
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Misi Kami</h2>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li>Menyediakan informasi kuliner terlengkap</li>
                    <li>Memberikan pengalaman berbelanja yang mudah dan aman</li>
                    <li>Mendukung UMKM lokal dalam berkembang</li>
                </ul>
            </div>
        </div>

        <!-- Team Section -->
        <div class="mt-20">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Tim Kami</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/testimonials/Pro.png') }}" alt="Ahmad Syaifuddin"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Ahmad Syaifuddin</h3>
                    <p class="text-gray-600">Founder & CEO</p>
                </div>

                <!-- Team Member 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/testimonials/aldy.jpg') }}" alt="Muhammad Aldy Rahmatillah"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Muhammad Aldy Rahmatillah</h3>
                    <p class="text-gray-600">Head of Product</p>
                </div>

                <!-- Team Member 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/testimonials/maul.jpg') }}" alt="Muhammad Maulidi"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Muhammad Maulidi</h3>
                    <p class="text-gray-600">Marketing Manager</p>
                </div>

                <!-- Team Member 4 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/user.svg') }}" alt="M. Haldi"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">M. Haldi</h3>
                    <p class="text-gray-600">Head of Operations</p>
                </div>

                <!-- Team Member 5 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/user.svg') }}" alt="Muhammad Rio Bisma Saputra"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Muhammad Rio Bisma Saputra</h3>
                    <p class="text-gray-600">CTO</p>
                </div>

                <!-- Team Member 6 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/testimonials/ryandy.png') }}" alt="Ryandy Rhamadany"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Ryandy Rhamadany</h3>
                    <p class="text-gray-600">Head of Customer Experience</p>
                </div>

                <!-- Team Member 7 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/user.svg') }}" alt="Rudi Hartono"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Cewe 1</h3>
                    <p class="text-gray-600">Lead Developer</p>
                </div>

                <!-- Team Member 8 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="{{ asset('images/user.svg') }}" alt="Dian Sastro"
                        class="w-20 h-20 mx-auto mb-4 rounded-full object-cover">
                    <h3 class="text-xl font-bold text-gray-900">Cewe 2</h3>
                    <p class="text-gray-600">Finance Director</p>
                </div>
            </div>
        </div>

        <!-- Expert Recommendations Section -->
        <div class="mt-20">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Rekomendasi Ahli</h2>
            <p class="text-center text-gray-600 mb-10">Berikut adalah rekomendasi dari para ahli industri yang membantu
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
                        <p class="text-gray-600 mb-3 italic">Pakar E-Commerce & Digital Marketing</p>
                        <p class="text-gray-700 leading-relaxed">
                            "Kunci sukses platform kuliner online adalah personalisasi. OkeeDins harus menggunakan AI
                            untuk mempelajari preferensi pelanggan dan memberikan rekomendasi yang tepat. Fokus pada UX
                            yang intuitif dan sistem pencarian yang canggih akan menjadi pembeda utama."
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
                        <p class="text-gray-600 mb-3 italic">Konsultan UMKM Kuliner</p>
                        <p class="text-gray-700 leading-relaxed">
                            "OkeeDins perlu membangun sistem onboarding yang sederhana namun komprehensif untuk penjual
                            UMKM. Sediakan tools yang memudahkan mereka mengelola stok, pengiriman, dan analisis
                            penjualan. Program pelatihan digital untuk UMKM kuliner juga akan menjadi nilai tambah."
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
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Lina Hartono</h3>
                        <p class="text-gray-600 mb-3 italic">Food Blogger & Content Creator</p>
                        <p class="text-gray-700 leading-relaxed">
                            "Konten yang menarik adalah jantung platform kuliner. OkeeDins harus menghadirkan foto-foto
                            profesional, ulasan jujur, dan cerita di balik setiap makanan. Kolaborasi dengan influencer
                            lokal dan program loyalitas akan memperkuat komunitas pengguna."
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
                            "Standar keamanan pangan harus menjadi prioritas. OkeeDins perlu memiliki sistem verifikasi
                            vendor yang ketat dan monitoring kualitas yang konsisten. Investasi pada packaging ramah
                            lingkungan dan rantai dingin (cold chain) untuk makanan segar akan membedakan OkeeDins dari
                            kompetitor."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection