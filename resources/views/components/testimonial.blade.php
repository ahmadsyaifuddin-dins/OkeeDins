@php
$testimonials = [
// 1
[
'image' => asset('images/testimonials/susilo.jpg'),
'quote' => 'OkeeDins sangat membantu saya menemukan produk yang saya butuhkan!',
'name' => 'Susilo Hartanto',
'role' => 'Customer',
'rating' => 5
],
// 2
[
'image' => asset('images/testimonials/dharma2.jpg'),
'quote' => 'UI nya terlihat modern dan bagus si gw liat, user friendly responsif di layar HP juga',
'name' => 'Rizky Dharma',
'role' => 'Customer',
'rating' => 5
],
// 3
[
'image' => asset('images/user.svg'),
'quote' => 'OkeeDins smooth banget, gak ribet!',
'name' => 'Hafizh',
'role' => 'Customer',
'rating' => 5
],
// 4
[
'image' => asset('images/user.svg'),
'quote' => 'Tampilan UInya Bagus dan simpel, sistem registrasinya juga enak sama kek aplikasi atau web lainnya',
'name' => 'Stepen',
'role' => 'Customer',
'rating' => 5
],
// 5
[
'image' => asset('images/testimonials/parida.jpg'),
'quote' => 'OkeeDins bagus sudah gw coba, gw coba bikin akun terus gw coba checkout barang mantap',
'name' => 'Parida Rahmawati',
'role' => 'Customer',
'rating' => 5
],
// 6
[
'image' => asset('images/testimonials/team-1.jpg'),
'quote' => 'OkeeDins top banget! Setiap interaksi bikin saya ngerasa dihargai dan pastinya puas banget',
'name' => 'Mei Chan',
'role' => 'Customer',
'rating' => 5
],
// 7
[
'image' => asset('images/user.svg'),
'quote' => 'Overall udah bagus, UI nya enak dipandang, loadnya cepat, dan fitur-fiturnya mayan lengkap',
'name' => 'Gilang',
'role' => 'Customer',
'rating' => 5
],
// 8
[
'image' => asset('images/testimonials/Adolf-Hitler-1933.webp'),
'quote' => 'OkeeDins sangat membantu saya menemukan produk yang saya butuhkan!',
'name' => 'Adolf Hitler',
'role' => 'Customer',
'rating' => 5
],
// 9
[
'image' => asset('images/user.svg'),
'quote' => 'jadi kaya?, Aku mau tiga aplikasi ini!',
'name' => 'El Rio De Janeiro',
'role' => 'Customer',
'rating' => 5
],
// 10
[
'image' => asset('images/testimonials/lutfi.jpg'),
'quote' => 'Setiap pemakaian bikin hidup saya lebih praktis. Gak cuma keren, tapi juga super efisien!',
'name' => 'Lutfi Aulia Sidik',
'role' => 'Customer',
'rating' => 5
],
// 11
[
'image' => asset('images/testimonials/CRVbR7yOVDL3bDUXlFd8B1oxBuaTOn8QBPNxGr8U.jpg'),
'quote' => 'Cepat, tepat, dan memuaskan! Layanan bintang lima yang bikin saya selalu balik lagi.',
'name' => 'Dewi Pratiwi',
'role' => 'Customer',
'rating' => 5,
],
// 12
[
'image' => asset('images/user.svg'),
'quote' => 'Pengalaman belanja yang super menyenangkan—recommended banget buat yang nyari kualitas tanpa ribet!',
'name' => 'Renny Melanda Febriyanti',
'role' => 'Customer',
'rating' => 5
],
// 13
[
'image' => asset('images/testimonials/photo_2025-02-11_10-48-02.jpg'),
'quote' => 'Tetap stylish meskipun sibuk, karena hidup itu harus balance!',
'name' => 'Fadila Salsabila',
'role' => 'Customer',
'rating' => 5
],
// 14
[
'image' => asset('images/user.svg'),
'quote' => 'Wiih, OkeeDins keren kayak shoppe gitu, keren sekali developernya effort banget, idenya juga bagus, mantap
pokoknya!',
'name' => 'Fitriani',
'role' => 'Customer',
'rating' => 5
],
// 15
[
'image' => asset('images/user.svg'),
'quote' => 'Pelayanannya ramah dan profesional. Suka sama sistem checkout yang simple dan jelas!',
'name' => 'Maulida Rahmah',
'role' => 'Customer',
'rating' => 5
],
// 16
[
'image' => asset('images/testimonials/icibos.jpeg'),
'quote' => 'Metode pembayaran yang fleksibel bikin belanja makin gampang. COD jalan, transfer juga aman!',
'name' => 'Muhammad Aldy Rahmatillah',
'role' => 'Customer',
'rating' => 5
],
// 17
[
'image' => asset('images/user.svg'),
'quote' => 'OkeeDins punya banyak pilihan produk dengan harga yang kompetitif. Recommended banget!',
'name' => 'Muhammad Maulidi',
'role' => 'Customer',
'rating' => 5
],
// 18
[
'image' => asset('images/user.svg'),
'quote' => 'Suka sama tampilan websitenya yang clean dan mudah digunakan. Transaksi lancar jaya!',
'name' => 'M. Haldi',
'role' => 'Customer',
'rating' => 5
],
// 19
[
'image' => asset('images/user.svg'),
'quote' => 'Customer service-nya responsif banget! Ada kendala langsung dibantu sampai beres.',
'name' => 'Ryandy Rhamadhany',
'role' => 'Customer',
'rating' => 5
],
// 20
[
'image' => asset('images/user.svg'),
'quote' => 'Belanja di OkeeDins gampang banget! Prosesnya cepat, barangnya juga sesuai deskripsi.',
'name' => 'Gusti Randa',
'role' => 'Customer',
'rating' => 5
],

// [
// 'image' => asset('images/testimonials/aya.jpg'),
// 'quote' => 'Mariyani',
// 'name' => 'Unknown',
// 'role' => 'Customer',
// 'rating' => 5
// ],
// [
];
@endphp

<section id="testimonials" class="bg-gray-50 py-12 px-4 md:px-6">
    <div class="container mx-auto">
        <h3 class="text-lg md:text-3xl font-bold text-center mb-8">
            Apa kata mereka tentang <span class="text-custom-secondary">{{
                substr($appSettings['app_name'], 0, -4)
                }}</span><span class="text-custom">{{ substr($appSettings['app_name'], -4) }}</span> ?
        </h3>

        <div class="relative max-w-[1350px] mx-auto overflow-hidden">
            <div class="testimonial-track py-2 flex">
                {{-- Original testimonials --}}
                @foreach($testimonials as $testimonial)
                <div
                    class="testimonial-card flex-shrink-0 w-[280px] md:w-[300px] bg-white p-4 md:p-6 rounded-lg shadow-md mx-2 md:mx-3">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4 rounded-full overflow-hidden bg-gray-200">
                            <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}"
                                class="w-full h-full object-cover" loading="lazy">
                        </div>

                        <div class="text-center">
                            <p class="text-gray-700 italic mb-3 md:mb-4 text-sm md:text-base">
                                "{{ $testimonial['quote'] }}"
                            </p>

                            <div class="flex items-center justify-center gap-1 md:gap-2 mb-2">
                                @for($i = 0; $i < $testimonial['rating']; $i++) <i
                                    class="bi bi-star-fill text-yellow-400 text-sm md:text-base"></i>
                                    @endfor
                            </div>

                            <h5 class="font-semibold text-gray-900">{{ $testimonial['name'] }}</h5>
                            <p class="text-xs md:text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Duplicated testimonials for infinite scroll --}}
                @foreach($testimonials as $testimonial)
                <div
                    class="testimonial-card flex-shrink-0 w-[280px] md:w-[300px] bg-white p-4 md:p-6 rounded-lg shadow-md mx-2 md:mx-3">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 mb-3 md:mb-4 rounded-full overflow-hidden bg-gray-200">
                            <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}"
                                class="w-full h-full object-cover" loading="lazy">
                        </div>

                        <div class="text-center">
                            <p class="text-gray-700 italic mb-3 md:mb-4 text-sm md:text-base">
                                "{{ $testimonial['quote'] }}"
                            </p>

                            <div class="flex items-center justify-center gap-1 md:gap-2 mb-2">
                                @for($i = 0; $i < $testimonial['rating']; $i++) <i
                                    class="bi bi-star-fill text-yellow-400 text-sm md:text-base"></i>
                                    @endfor
                            </div>

                            <h5 class="font-semibold text-gray-900">{{ $testimonial['name'] }}</h5>
                            <p class="text-xs md:text-sm text-gray-500">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(calc(-280px * 20 - 16px * 20));
        }
    }

    @media (min-width: 768px) {
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(calc(-300px * 20 - 24px * 20));
            }
        }
    }

    .testimonial-track {
        animation: scroll 85s linear infinite;
        will-change: transform;
    }

    .testimonial-track:hover {
        animation-play-state: paused;
    }

    .testimonial-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>