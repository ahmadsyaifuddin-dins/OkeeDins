@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-body p-3 p-md-4">
                    <h4 class="text-center mb-4 fs-5">Detail Pembayaran</h4>

                    <!-- Order Info -->
                    <div class="alert alert-info p-3">
                        <p class="mb-2 small"><strong>Nomor Order:</strong> #{{ $order->id }}</p>
                        <p class="mb-2 small"><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        <p class="mb-0 small"><strong>Batas Waktu Pembayaran:</strong> {{ $order->created_at->addDays(1)->format('d M Y H:i') }}</p>
                    </div>

                    <!-- Bank Transfer Info -->
                    <div class="bank-info mb-4">
                        <h5 class="mb-3 fs-6">Transfer ke Rekening Berikut:</h5>
                        <div class="card mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <h6 class="mb-1 fs-6">Seabank</h6>
                                        <p class="mb-0 small">No. Rekening: <strong>901139597160</strong></p>
                                        <p class="mb-0 small">a.n <strong>Ahmad Syaifuddin (CEO Food Fusion Indonesia)</strong></p>
                                    </div>
                                    <img src="{{ asset('images/SeaBank.png') }}" alt="seabank" class="img-fluid" style="height: 35px">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti Transfer -->
                    <div class="upload-section">
                        <h5 class="mb-3 fs-6">Upload Bukti Transfer</h5>
                        <form action="{{ route('orders.upload-payment', $order->id) }}" method="POST" enctype="multipart/form-data" id="payment-form">
                            @csrf
                            <div class="mb-3">
                                <input type="file" class="form-control form-control-sm" name="payment_proof" accept="image/*" required>
                                <div class="form-text small">Format yang didukung: JPG, JPEG, PNG (Maks. 2MB)</div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 py-2" id="btn-confirm-payment">
                                Konfirmasi Pembayaran
                            </button>
                        </form>
                    </div>

                    <!-- Cara Pembayaran -->
                    <div class="mt-4">
                        <h5 class="mb-3 fs-6">Cara Pembayaran:</h5>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item py-2 small">Transfer sesuai nominal ke rekening yang tertera</li>
                            <li class="list-group-item py-2 small">Simpan bukti transfer</li>
                            <li class="list-group-item py-2 small">Upload bukti transfer pada form di atas</li>
                            <li class="list-group-item py-2 small">Tunggu konfirmasi dari admin (1x24 jam)</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('payment-form')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const fileInput = this.querySelector('input[type="file"]');
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
</script>
@endpush
