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
                                {{-- <div class="text-2xl font-bold text-custom">{{ Auth::user()->orders->count() }}</div> --}}
                                <div class="text-sm text-gray-600">Total Pesanan</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                {{-- <div class="text-2xl font-bold text-custom">{{ Auth::user()->wishlist->count() }}</div> --}}
                                <div class="text-sm text-gray-600">Wishlist</div>
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
                        <nav class="flex -mb-px">
                            <button class="px-6 py-4 border-b-2 border-custom text-custom font-medium">
                                Informasi Pribadi
                            </button>
                            <button class="px-6 py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Riwayat Pesanan
                            </button>
                            <button class="px-6 py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Wishlist
                            </button>
                        </nav>
                    </div>

                    <!-- Profile Form -->
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
                                        <textarea name="alamat" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-custom focus:border-custom">{{ Auth::user()->alamat }}</textarea>
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

    {{-- @push('scripts')
<script src="{{ asset('js/profile-pelanggan.js') }}"></script>
@endpush --}}

    @push('scripts')
        <script src="{{ asset('js/profile.js') }}"></script>
    @endpush
@endsection
