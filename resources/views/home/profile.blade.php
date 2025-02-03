@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 mt-16">
        <!-- Header -->
        <div class="mb-6" data-aos="fade-right">
            <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
            <p class="text-gray-600">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-4 xl:col-span-3" data-aos="fade-left">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <!-- Profile Photo -->
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-32 h-32 relative mx-auto">
                                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/user.svg') }}"
                                    class="w-full h-full object-cover rounded-full border-4 border-white shadow"
                                    alt="Profile Photo">

                                <form id="profilePhotoForm" action="{{ route('pelanggan.profile.update') }}" method="POST"
                                    enctype="multipart/form-data" class="absolute -bottom-2 -right-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="photoUpload"
                                        class="w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                                        <i class="bi bi-camera-fill text-custom"></i>
                                    </label>
                                    <input type="file" id="photoUpload" name="photo" class="hidden" accept="image/*"
                                        onchange="uploadProfilePhoto(this)">
                                </form>
                            </div>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-500 mb-4">
                            <i class="bi bi-person-badge me-2"></i>Pelanggan
                        </p>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Total Pesanan</div>
                                <div class="text-sm font-bold text-custom-secondary">{{ Auth::user()->order ? Auth::user()->order->count() : 0 }}</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm text-gray-600">Bergabung Sejak</div>
                                <div class=" text-sm font-bold text-custom-secondary">
                                    {{ Auth::user()->created_at->locale('id')->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-3">
                            <a href="{{ route('home.index') }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali ke Beranda
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-custom rounded-lg text-custom hover:bg-red-50 transition-colors">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-8 xl:col-span-9" data-aos="fade-up">
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px overflow-x-auto no-scrollbar">
                            <button onclick="showTab('profile')" id="profileTab" 
                                class="tab-button px-4 sm:px-6 py-3 sm:py-4 border-b-2 font-medium transition-colors whitespace-nowrap">
                                <i class="bi bi-person me-2"></i><span class="hidden sm:inline">Informasi Pribadi</span><span class="sm:hidden">Profil</span>
                            </button>
                            <button onclick="showTab('orders')" id="ordersTab"
                                class="tab-button px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-transparent font-medium transition-colors whitespace-nowrap">
                                <i class="bi bi-bag me-2"></i><span class="hidden sm:inline">Riwayat Pesanan</span><span class="sm:hidden">Pesanan</span>
                            </button>
                            <button onclick="showTab('addresses')" id="addressesTab"
                                class="tab-button px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-transparent font-medium transition-colors whitespace-nowrap">
                                <i class="bi bi-geo-alt me-2"></i><span class="hidden sm:inline">Alamat Tersimpan</span><span class="sm:hidden">Alamat</span>
                            </button>
                            <button onclick="showTab('stats')" id="statsTab"
                                class="tab-button px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-transparent font-medium transition-colors whitespace-nowrap">
                                <i class="bi bi-graph-up me-2"></i><span class="hidden sm:inline">Statistik Aktivitas</span><span class="sm:hidden">Statistik</span>
                            </button>
                        </nav>
                    </div>

                    <!-- Profile Content -->
                    <div id="profileContent" class="tab-content">
                        <div class="p-6">
                            <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Personal Information -->
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-person me-2 text-custom"></i>Nama Lengkap
                                            </label>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-envelope me-2 text-custom"></i>Email
                                            </label>
                                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                                disabled
                                                class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-600">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-phone me-2 text-custom"></i>Nomor Telepon
                                            </label>
                                            <input type="tel" name="telepon" value="{{ Auth::user()->telepon }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-egg-fried me-2 text-custom"></i>Makanan Favorit
                                            </label>
                                            <input type="text" name="makanan_fav" value="{{ Auth::user()->makanan_fav }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom">
                                        </div>

                                    </div>

                                    <!-- Additional Information -->
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-calendar me-2 text-custom"></i>Tanggal Lahir
                                            </label>
                                            <input type="date" name="tgl_lahir" value="{{ Auth::user()->tgl_lahir }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-gender-ambiguous me-2 text-custom"></i>Jenis Kelamin
                                            </label>
                                            <input type="text" name="jenis_kelamin"
                                                value="{{ Auth::user()->jenis_kelamin }}" disabled
                                                class="w-full px-4 py-2 border border-gray-300 text-gray-600 rounded-lg focus:ring-custom focus:border-custom"
                                                placeholder="Jenis Kelamin">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <i class="bi bi-geo-alt me-2 text-custom"></i>Alamat
                                            </label>
                                            @if ($userAddresses = Auth::user()->addresses)
                                                <div class="mb-2">
                                                    <select name="selected_address" 
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom"
                                                        id="addressSelector">
                                                        <option value="">-- Pilih Alamat Tersimpan --</option>
                                                        @foreach ($userAddresses->unique('full_address') as $address)
                                                            <option value="{{ $address->id }}"
                                                                data-address="{{ $address->full_address }}"
                                                                data-receiver="{{ $address->receiver_name }}"
                                                                data-phone="{{ $address->phone_number }}"
                                                                {{ $address->is_primary ? 'selected' : '' }}>
                                                                {{ $address->label }}
                                                                {{ $address->is_primary ? '(Utama)' : '' }} -
                                                                {{ $address->receiver_name }}
                                                                ({{ $address->phone_number }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                            <textarea name="alamat" id="alamatTextarea" rows="3"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom"
                                                placeholder="Masukkan alamat lengkap"
                                                {{ $userAddresses && $userAddresses->where('is_primary', true)->count() > 0 ? 'readonly' : '' }}>{{ old('alamat', $userAddresses?->where('is_primary', true)->first()?->full_address ?? Auth::user()->alamat) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-6 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2 bg-custom text-white rounded-lg hover:bg-red-600 transition-colors">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Orders Content -->
                    <div id="ordersContent" class="tab-content hidden">
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse(Auth::user()->order as $order)
                                    <a href="{{ route('orders.detail', $order->id) }}" class="block">
                                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 transition duration-300 ease-in-out hover:shadow-md">
                                            <div class="flex justify-between items-center mb-2">
                                                <div>
                                                    <span class="text-sm text-gray-500">#{{ $order->order_number }}</span>
                                                    <span class="ml-2 text-sm font-medium text-custom">{{ $order->created_at->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                                <span class="px-3 py-1 text-sm rounded-full 
                                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'awaiting payment') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'delivered') bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($order->status_label) }}
                                                </span>
                                            </div>
                                            <div class="border-t border-gray-100 pt-2">
                                                <div class="text-sm text-gray-600">
                                                    Total Pembayaran: <span class="font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-sm text-custom">
                                                <i class="bi bi-eye-fill mr-1"></i> Lihat Detail
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center py-8">
                                        <i class="bi bi-bag-x text-4xl text-gray-400"></i>
                                        <p class="mt-2 text-gray-500">Belum ada riwayat pesanan</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Addresses Content -->
                    <div id="addressesContent" class="tab-content hidden">
                        <div class="p-6">
                            <div class="space-y-4">
                                @forelse(Auth::user()->addresses as $address)
                                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="flex items-center">
                                                <span class="font-medium text-gray-900">{{ $address->label }}</span>
                                                @if($address->is_primary)
                                                    <span class="ml-2 px-2 py-1 text-xs bg-custom-secondary text-white rounded-full">Utama</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if(!$address->is_primary)
                                                    <form action="{{ route('addresses.make-primary', $address) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="text-sm text-custom hover:text-red-600">
                                                            Set sebagai Utama
                                                        </button>
                                                    </form>
                                                @endif
                                                {{-- <button class="ml-2 text-sm text-gray-500 hover:text-gray-700" onclick="editAddress({{ $address->id }})">
                                                    Edit
                                                </button>
                                                @if(!$address->is_primary)
                                                    <button class="ml-2 text-sm text-gray-500 hover:text-gray-700" onclick="deleteAddress({{ $address->id }})">
                                                        Hapus
                                                    </button>
                                                @endif --}}
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <p><i class="bi bi-person me-2"></i>{{ $address->receiver_name }}</p>
                                            <p><i class="bi bi-telephone me-2"></i>{{ $address->phone_number }}</p>
                                            <p class="mt-1"><i class="bi bi-geo-alt me-2"></i>{{ $address->full_address }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i class="bi bi-house-x text-4xl text-gray-400"></i>
                                        <p class="mt-2 text-gray-500">Belum ada alamat tersimpan</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Stats Content -->
                    <div id="statsContent" class="tab-content hidden">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Grafik Pesanan per Bulan -->
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        <i class="bi bi-calendar3 me-2 text-custom"></i>Aktivitas Pesanan
                                    </h3>
                                    <canvas id="orderChart" height="300"></canvas>
                                </div>

                                <!-- Grafik Status Pesanan -->
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        <i class="bi bi-pie-chart-fill me-2 text-custom"></i>Status Pesanan
                                    </h3>
                                    <canvas id="statusChart" height="300"></canvas>
                                </div>

                                <!-- Rangkuman Statistik -->
                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 md:col-span-2">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                                        <i class="bi bi-clipboard-data me-2 text-custom"></i>Ringkasan Aktivitas
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <div class="text-base md:text-2xl font-bold text-custom">{{ Auth::user()->order->count() }}</div>
                                            <div class="text-sm text-gray-600">Total Pesanan</div>
                                        </div>
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <div class="text-base md:text-2xl font-bold text-custom">Rp {{ number_format(Auth::user()->order->whereIn('status', ['paid', 'completed'])->sum('total_amount'), 0, ',', '.') }}</div>
                                            <div class="text-sm text-gray-600">Total Pengeluaran</div>
                                        </div>
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <div class="text-base md:text-2xl font-bold text-custom">{{ Auth::user()->order->where('status', 'completed')->count() }}</div>
                                            <div class="text-sm text-gray-600">Pesanan Selesai</div>
                                        </div>
                                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                                            <div class="text-base md:text-2xl font-bold text-custom">{{ Auth::user()->addresses->count() }}</div>
                                            <div class="text-sm text-gray-600">Alamat Tersimpan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // SweetAlert2 configuration
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false,
                        background: '#fff',
                        iconColor: '#4CAF50'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: "{{ session('error') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif

                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: "{{ $errors->first() }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addressSelector = document.getElementById('addressSelector');
                const alamatTextarea = document.getElementById('alamatTextarea');

                if (addressSelector) {
                    addressSelector.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption.value !== '') {
                            alamatTextarea.value = selectedOption.dataset.address;
                            alamatTextarea.readOnly = true;
                        } else {
                            alamatTextarea.value = '';
                            alamatTextarea.readOnly = false;
                        }
                    });
                }
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            function showTab(tabName) {
                // Sembunyikan semua konten tab
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.add('hidden');
                });

                // Reset semua tombol tab ke status tidak aktif
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('border-custom', 'text-custom');
                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });

                // Tampilkan konten tab yang dipilih
                document.getElementById(tabName + 'Content').classList.remove('hidden');
                
                // Aktifkan tombol tab yang dipilih
                const activeTab = document.getElementById(tabName + 'Tab');
                activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                activeTab.classList.add('border-custom', 'text-custom');
            }

            // Set tab default saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                showTab('profile');
            });
        </script>
    @endpush

    @push('scripts')
        <script src="{{ asset('js/profile.js') }}"></script>
    @endpush

    @push('scripts')
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <script>
            // Data untuk grafik
            const orderData = @json([
                'labels' => Auth::user()->order->groupBy(function($order) {
                    return $order->created_at->format('M Y');
                })->map->count()->keys(),
                'data' => Auth::user()->order->groupBy(function($order) {
                    return $order->created_at->format('M Y');
                })->map->count()->values()
            ]);

            const statusData = @json([
                'labels' => Auth::user()->order->groupBy('status')->map(function($orders, $status) {
                    return ucfirst($orders->first()->status_label);
                })->values(),
                'data' => Auth::user()->order->groupBy('status')->map->count()->values()
            ]);

            // Inisialisasi grafik saat tab statistik dibuka
            function initCharts() {
                // Grafik Pesanan per Bulan
                new Chart(document.getElementById('orderChart'), {
                    type: 'bar',
                    data: {
                        labels: orderData.labels,
                        datasets: [{
                            label: 'Jumlah Pesanan',
                            data: orderData.data,
                            backgroundColor: '#EF4444',
                            borderColor: '#EF4444',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Grafik Status Pesanan
                new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: statusData.labels,
                        datasets: [{
                            data: statusData.data,
                            backgroundColor: [
                                '#FCD34D', // pending
                                '#60A5FA', // processing
                                '#34D399', // completed
                                '#F87171'  // cancelled
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Inisialisasi grafik saat tab statistik dibuka pertama kali
            document.getElementById('statsTab').addEventListener('click', function() {
                setTimeout(initCharts, 100);
            });
        </script>
    @endpush
@endsection
