<!-- Footer -->
<footer class="bg-white border-t border-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand Section -->
            <div class="space-y-4">
                <h2 class="text-xl font-bold">Food <span class="text-custom">Fusion</span></h2>
                <p class="text-gray-600 text-sm">
                    Nikmati pengalaman kuliner terbaik dengan berbagai pilihan makanan lezat dari Food Fusion.
                </p>
                <!-- Social Media Icons -->
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-custom transition-colors">
                        <i class="bi bi-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-custom transition-colors">
                        <i class="bi bi-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-custom transition-colors">
                        <i class="bi bi-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-custom transition-colors">
                        <i class="bi bi-youtube text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Menu Cepat</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home.index') }}" class="text-gray-600 hover:text-custom text-sm transition-colors">Beranda</a>
                    </li>
                    <li>
                        <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-custom text-sm transition-colors">Keranjang</a>
                    </li>
                    <li>
                        <a href="{{ route('wishlist.index') }}" class="text-gray-600 hover:text-custom text-sm transition-colors">Wishlist</a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-custom text-sm transition-colors">Riwayat Transaksi</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Hubungi Kami</h3>
                <ul class="space-y-2">
                    <li class="flex items-start space-x-3 text-sm">
                        <i class="bi bi-geo-alt text-custom mt-1"></i>
                        <span class="text-gray-600">Jl. Raya Food Fusion No. 123, Surabaya, Indonesia</span>
                    </li>
                    <li class="flex items-center space-x-3 text-sm">
                        <i class="bi bi-telephone text-custom"></i>
                        <span class="text-gray-600">+62 821-4567-8901</span>
                    </li>
                    <li class="flex items-center space-x-3 text-sm">
                        <i class="bi bi-envelope text-custom"></i>
                        <span class="text-gray-600">info@foodfusion.com</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Newsletter</h3>
                <p class="text-gray-600 text-sm mb-4">Berlangganan untuk mendapatkan info terbaru dan promo menarik</p>
                <form class="space-y-3">
                    <div class="relative">
                        <input type="email" 
                            class="w-full pl-4 pr-12 py-2 text-sm rounded-lg border border-gray-300 focus:outline-none focus:border-custom"
                            placeholder="Masukkan email Anda">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-custom hover:text-custom-dark">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-100 mt-8 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} Food Fusion. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="text-sm text-gray-600 hover:text-custom transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-custom transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </div>
</footer>