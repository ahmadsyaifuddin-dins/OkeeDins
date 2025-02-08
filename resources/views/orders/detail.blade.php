@extends('layouts.app')

@section('content')


<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Detail Pesanan</h1>
                <p class="text-gray-600">Nomor Pesanan: ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Status Banner -->
                <div class="bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 mb-3 sm:mb-0">
                            <span class="text-gray-600">Status Pesanan:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        @if(in_array($order->status, ['pending', 'awaiting payment', 'confirmed', 'processing', 'delivered', 'completed']))
                        <a href="{{ route('orders.track', $order->id) }}" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-custom text-white text-sm font-medium rounded-lg hover:bg-red-700 transition duration-200">
                            <i class="bi bi-truck mr-2"></i>Lacak Pesanan
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Produk yang Dipesan</h2>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center space-x-4 py-4 border-b border-gray-100 last:border-0">
                            <img src="{{ asset('storage/' . $item->produk->gambar) }}" 
                                alt="{{ $item->produk->nama_produk }}" 
                                class="w-20 h-20 object-cover rounded-lg">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->produk->nama_produk }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                @if($item->discount > 0)
                                <span class="text-sm text-custom">Diskon {{ $item->discount }}%</span>
                                @endif
                            </div>
                            {{-- <div class="text-right">
                                <span class="font-medium text-gray-900">
                                    Rp{{ number_format($item->quantity * $item->price * (1 - $item->discount/100), 0, ',', '.') }}
                                </span>
                            </div> --}}
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp{{ number_format($order->total_original_price, 0, ',', '.') }}</span>
                        </div>
                        @if($order->voucher_discount > 0)
                        <div class="flex justify-between text-custom">
                            <span>Voucher Diskon</span>
                            <span>-Rp{{ number_format($order->voucher_discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($order->total_discount > 0)
                        <div class="flex justify-between text-custom">
                            <span>Produk Diskon</span>
                            <span>-Rp{{ number_format($order->total_discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-semibold text-gray-900 pt-3 border-t border-gray-200">
                            <span>Total Pembayaran</span>
                            <span class="text-lg text-custom">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengiriman</h2>
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-gray-600 mb-1 sm:mb-0">Nama Penerima</span>
                            <span class="text-gray-900 font-medium">{{ $order->address->receiver_name }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-gray-600 mb-1 sm:mb-0">Nomor Telepon</span>
                            <span class="text-gray-900 font-medium">{{ $order->address->phone_number }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-gray-600 mb-1 sm:mb-0">Alamat</span>
                            <span class="text-gray-900 font-medium sm:text-right">{{ $order->address->full_address }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h2>
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:justify-between">
                            <span class="text-gray-600 mb-1 sm:mb-0">Metode Pembayaran</span>
                            <span class="text-gray-900 font-medium">
                                @if($order->payment_method === 'transfer')
                                    Transfer Bank
                                @elseif($order->payment_method === 'Cash on Delivery')
                                    Cash on Delivery (COD)
                                @endif
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Status Pembayaran</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status_badge }}">
                                {{ $order->payment_status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                            </span>
                        </div>
                    </div>

                    @if($order->payment_method === 'transfer' && $order->payment_proof)
                    <div class="mt-4">
                        <span class="block text-gray-600 mb-2">Bukti Pembayaran:</span>
                        <!-- Thumbnail Image -->
                        <div class="relative inline-block">
                            <img src="{{ asset('uploads/payment_proofs/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="w-32 h-32 object-cover rounded-lg shadow-sm cursor-pointer hover:opacity-75" onclick="showModal(this.src)" loading="lazy">
                        </div>

                        <!-- Modal -->
                        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
                            <div class="relative max-w-4xl w-full bg-white rounded-lg p-2">
                                <!-- Close button -->
                                <button type="button" onclick="hideModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                                    <i class="bi bi-x-lg text-2xl"></i>
                                </button>

                                <!-- Image container -->
                                <div class="relative">
                                    <!-- Loading spinner -->
                                    <div id="loadingSpinner" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                                        <div class="animate-spin rounded-full h-8 w-8 border-4 border-custom border-t-transparent"></div>
                                    </div>

                                    <!-- Modal image -->
                                    <img id="modalImage" src="" alt="Preview Bukti Pembayaran" class="w-full h-auto cursor-zoom-in" onclick="toggleZoom(this)" onload="document.getElementById('loadingSpinner').style.display='none'">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                <a href="{{ route('home.riwayat-pesanan') }}" 
                    class="w-full sm:w-1/2 px-6 py-3 text-center border-2 border-custom text-custom font-semibold rounded-lg hover:bg-red-50 transition duration-200">
                    <i class="bi bi-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
                </a>
                @if($order->status === 'delivered')
                    @if($order->payment_method === 'transfer')
                        <button type="button"
                            onclick="openConfirmationModal('Transfer')"
                            class="w-full sm:w-1/2 px-6 py-3 text-center bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-200">
                            <i class="bi bi-check-circle mr-2"></i>Konfirmasi Penerimaan
                        </button>
                    @elseif($order->payment_method === 'Cash on Delivery')
                        <button type="button"
                            onclick="openConfirmationModal('COD')"
                            class="w-full sm:w-1/2 px-6 py-3 text-center bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-200">
                            <i class="bi bi-check-circle mr-2"></i>Konfirmasi Penerimaan
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi dan Rating untuk Transfer -->
<div id="confirmationModalTransfer" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('orders.confirm-transfer', $order->id) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Konfirmasi Penerimaan & Beri Rating
                            </h3>

                            <!-- Info Alert -->
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center text-blue-800">
                                    <i class="bi bi-info-circle text-xl mr-2"></i>
                                    <p class="text-sm">
                                        Dengan mengkonfirmasi penerimaan, Anda menyatakan bahwa barang telah diterima dalam kondisi baik.
                                    </p>
                                </div>
                            </div>

                            <!-- Rating Stars -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating Produk</label>
                                <div class="flex items-center justify-center space-x-1 rating-stars-transfer">
                                    @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="starTransfer{{ $i }}" name="rating" value="{{ $i }}" class="hidden" required>
                                    <label for="starTransfer{{ $i }}" class="star-label cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 transition-colors duration-150">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Review Text -->
                            <div class="mb-4">
                                <label for="ulasanTransfer" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ulasan Anda
                                </label>
                                <textarea id="ulasanTransfer" name="ulasan" rows="3" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom"
                                    placeholder="Bagikan pengalaman Anda menggunakan produk ini..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-custom text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom sm:ml-3 sm:w-auto sm:text-sm">
                        Konfirmasi & Kirim Ulasan
                    </button>
                    <button type="button"
                        onclick="closeConfirmationModal('Transfer')"
                        class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi dan Rating untuk COD -->
<div id="confirmationModalCOD" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('orders.confirm-cod', $order->id) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Konfirmasi Penerimaan & Beri Rating
                            </h3>

                            <!-- Info Alert -->
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center text-blue-800">
                                    <i class="bi bi-info-circle text-xl mr-2"></i>
                                    <p class="text-sm">
                                        Dengan mengkonfirmasi penerimaan, Anda menyatakan bahwa barang telah diterima dalam kondisi baik.
                                    </p>
                                </div>
                            </div>

                            <!-- Rating Stars -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating Produk</label>
                                <div class="flex items-center justify-center space-x-1 rating-stars-cod">
                                    @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="starCOD{{ $i }}" name="rating" value="{{ $i }}" class="hidden" required>
                                    <label for="starCOD{{ $i }}" class="star-label cursor-pointer text-2xl text-gray-300 hover:text-yellow-400 transition-colors duration-150">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Review Text -->
                            <div class="mb-4">
                                <label for="ulasanCOD" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ulasan Anda
                                </label>
                                <textarea id="ulasanCOD" name="ulasan" rows="3" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom"
                                    placeholder="Bagikan pengalaman Anda menggunakan produk ini..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-custom text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom sm:ml-3 sm:w-auto sm:text-sm">
                        Konfirmasi & Kirim Ulasan
                    </button>
                    <button type="button"
                        onclick="closeConfirmationModal('COD')"
                        class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')

<script src="{{ asset('js/detail_orders/image-modal.js') }}"></script>
<script src="{{ asset('js/detail_orders/konfirmasi_rating.js') }}"></script>

<script>

    // Tampilkan SweetAlert jika ada pesan sukses
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                position: 'top-start',
                showConfirmButton: false,
                timer: 3000,
                toast: true
            });
        @endif
    
        // Tampilkan SweetAlert jika ada pesan error
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                position: 'top-start',
                showConfirmButton: false,
                timer: 3000,
                toast: true
            });
        @endif
</script>
@endpush


@endsection
