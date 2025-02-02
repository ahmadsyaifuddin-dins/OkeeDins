@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Button & Title -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                <i class="bi bi-arrow-left text-xl mr-2"></i>
                <span class="text-sm font-medium">Kembali</span>
            </a>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Detail Pembayaran</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <!-- Order Info -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6">
                <div class="space-y-3 sm:space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <div class="flex items-center gap-2 mb-1 sm:mb-0">
                            <i class="bi bi-receipt text-blue-600 text-lg"></i>
                            <span class="text-blue-900 font-medium">Nomor Order:</span>
                        </div>
                        <p class="text-blue-900 font-medium ml-6 sm:ml-0">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <div class="flex items-center gap-2 mb-1 sm:mb-0">
                            <i class="bi bi-wallet2 text-blue-600 text-lg"></i>
                            <span class="text-blue-900 font-medium">Total Pembayaran:</span>
                        </div>
                        <p class="text-blue-900 font-medium ml-6 sm:ml-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <div class="flex items-center gap-2 mb-1 sm:mb-0">
                            <i class="bi bi-clock text-blue-600 text-lg"></i>
                            <span class="text-blue-900 font-medium">Batas Waktu:</span>
                        </div>
                        <p class="text-blue-900 font-medium ml-6 sm:ml-0">
                            <span id="countdown" data-deadline="{{ $order->created_at->addDays(1)->format('Y-m-d H:i:s') }}"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Info -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Transfer ke Rekening Berikut:</h2>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                        <div class="space-y-1 mb-4 sm:mb-0">
                            <h3 class="font-semibold text-gray-900">Seabank</h3>
                            <p class="text-gray-600">No. Rekening: <span class="font-medium text-gray-900">901139597160</span></p>
                            <p class="text-gray-600">a.n <span class="font-medium text-gray-900">Ahmad Syaifuddin <br> (CEO OkeeDins Indonesia)</span></p>
                        </div>
                        <img src="{{ asset('images/SeaBank.png') }}" alt="seabank" class="h-8 w-auto mt-2 sm:mt-0">
                    </div>
                </div>
            </div>

            <!-- Upload Bukti Transfer -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Upload Bukti Transfer</h2>
                <form action="{{ route('orders.upload-payment', $order->id) }}" method="POST" enctype="multipart/form-data" id="payment-form">
                    @csrf
                    <div class="mb-4">
                        <div class="flex items-center justify-center w-full">
                            <label for="payment_proof" class="flex flex-col w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 relative">
                                <!-- Default Upload State -->
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                    <i class="bi bi-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">
                                        <span class="font-medium text-custom">Klik untuk upload</span> atau drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                                </div>

                                <!-- Preview Container -->
                                <div id="preview-container" class="absolute inset-0 hidden">
                                    <div class="relative w-full h-full">
                                        <!-- Preview Image -->
                                        <img id="preview-image" src="#" alt="preview" class="w-full h-full object-contain p-2">
                                        
                                        <!-- File Info -->
                                        <div class="absolute bottom-0 left-0 right-0 bg-gray-900 bg-opacity-75 text-white p-2 text-sm">
                                            <p id="file-name" class="truncate"></p>
                                            <p id="file-size" class="text-xs text-gray-300"></p>
                                        </div>

                                        <!-- Remove Button -->
                                        <button type="button" id="remove-file" 
                                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-200">
                                            <i class="bi bi-x-lg text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                <input type="file" id="payment_proof" name="payment_proof" class="hidden" accept="image/*" required>
                            </label>
                        </div>
                    </div>
                    <button type="submit" id="btn-confirm-payment" 
                            class="w-full bg-custom text-white font-medium py-3 px-4 rounded-lg hover:bg-red-700 transition duration-200 flex items-center justify-center">
                        <i class="bi bi-check2-circle mr-2"></i>
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>

            <!-- Cara Pembayaran -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cara Pembayaran:</h2>
                <ol class="list-none p-0 space-y-4">
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-custom text-white text-sm font-medium flex items-center justify-center mr-3">1</span>
                        <div class="flex-grow">
                            <p class="text-gray-600">Upload bukti transfer pada form di atas</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-custom text-white text-sm font-medium flex items-center justify-center mr-3">2</span>
                        <div class="flex-grow">
                            <p class="text-gray-600">Transfer sesuai nominal ke rekening yang tertera</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-custom text-white text-sm font-medium flex items-center justify-center mr-3">3</span>
                        <div class="flex-grow">
                            <p class="text-gray-600">Simpan bukti transfer</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-custom text-white text-sm font-medium flex items-center justify-center mr-3">4</span>
                        <div class="flex-grow">
                            <p class="text-gray-600">Tunggu konfirmasi dari admin (1x24 jam)</p>
                        </div>
                    </li>
                </ol>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-custom {
        background-color: #D32F2F;
    }
    .text-custom {
        color: #D32F2F;
    }
</style>
@endpush

@push('scripts')

<script>
    function updateCountdown() {
        const countdownElement = document.getElementById('countdown');
        const deadline = new Date(countdownElement.dataset.deadline).getTime();
        const now = new Date().getTime();
        const timeLeft = deadline - now;

        if (timeLeft > 0) {
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            countdownElement.textContent = `${hours}j ${minutes}m ${seconds}d`;
            setTimeout(updateCountdown, 1000);
        } else {
            countdownElement.textContent = 'Waktu Habis';
        }
    }

    document.addEventListener('DOMContentLoaded', updateCountdown);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const fileInput = document.getElementById('payment_proof');
    const previewContainer = document.getElementById('preview-container');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewImage = document.getElementById('preview-image');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeButton = document.getElementById('remove-file');

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                uploadPlaceholder.classList.add('hidden');
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle remove button click
    removeButton.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.value = '';
        previewContainer.classList.add('hidden');
        uploadPlaceholder.classList.remove('hidden');
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const file = fileInput.files[0];

        if (!file) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Pilih file bukti transfer terlebih dahulu!'
            });
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal 2MB'
            });
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Sesuai',
                text: 'Upload file gambar dengan format JPG, JPEG, atau PNG'
            });
            return;
        }

        // Submit form if validation passes
        this.submit();
    });

    // Handle drag and drop
    const dropZone = document.querySelector('label[for="payment_proof"]');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-custom', 'bg-red-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-custom', 'bg-red-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        fileInput.files = dt.files;
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                uploadPlaceholder.classList.add('hidden');
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
});
</script>
@endpush

@endsection
