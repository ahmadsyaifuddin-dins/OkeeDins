<div id="statsContent" class="tab-content hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Grafik Pesanan per Bulan -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-calendar3 me-2 text-custom"></i>Aktivitas Pesanan
                </h3>
                <canvas id="orderChart"></canvas>
            </div>

            <!-- Grafik Status Pesanan -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-pie-chart-fill me-2 text-custom"></i>Status Pesanan
                </h3>
                <canvas id="statusChart"></canvas>
            </div>

            <!-- Rangkuman Statistik -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="bi bi-clipboard-data me-2 text-custom"></i>Ringkasan Aktivitas
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-base md:text-2xl font-bold text-custom">
                            {{ Auth::user()->order->count() }}</div>
                        <div class="text-sm text-gray-600">Total Pesanan</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-base md:text-2xl font-bold text-custom">Rp
                            {{ number_format(Auth::user()->order->whereIn('status', ['paid', 'completed'])->sum('total_amount'),0,',','.') }}
                        </div>
                        <div class="text-sm text-gray-600">Total Pengeluaran</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-base md:text-2xl font-bold text-custom">
                            {{ Auth::user()->order->where('status', 'completed')->count() }}</div>
                        <div class="text-sm text-gray-600">Pesanan Selesai</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-base md:text-2xl font-bold text-custom">
                            {{ Auth::user()->addresses->count() }}</div>
                        <div class="text-sm text-gray-600">Alamat Tersimpan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>