@extends('layouts.app-admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Voucher Baru</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Voucher</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code" class="font-weight-bold">Kode Voucher <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm @error('code') is-invalid @enderror"
                                id="code" name="code" value="{{ old('code') }}" required placeholder="Masukkan kode voucher">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Voucher</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Tipe Diskon</label>
                            <select class="form-control @error('type') is-invalid @enderror"
                                id="type" name="type" required>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="value" class="font-weight-bold">Nilai Diskon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="value-addon">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('value') is-invalid @enderror"
                                    id="value" name="value" value="{{ old('value') }}" required step="0.01"
                                    placeholder="Contoh: 50000" style="text-align: right;">
                            </div>
                            <small class="text-muted">Masukkan angka tanpa tanda titik atau koma</small>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="min_purchase">Minimal Pembelian</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('min_purchase') is-invalid @enderror"
                                    id="min_purchase" name="min_purchase" value="{{ old('min_purchase', 0) }}"
                                    required placeholder="Contoh: 100000" style="text-align: right;">
                            </div>
                            <small class="text-muted">Masukkan angka tanpa tanda titik atau koma</small>
                            @error('min_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="max_uses">Batas Penggunaan</label>
                            <input type="number" class="form-control @error('max_uses') is-invalid @enderror"
                                id="max_uses" name="max_uses" value="{{ old('max_uses') }}"
                                placeholder="Kosongkan jika tidak ada batas" style="text-align: right;">
                            <small class="text-muted">Masukkan jumlah maksimal penggunaan voucher</small>
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="valid_from">Berlaku Dari</label>
                            <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror"
                                id="valid_from" name="valid_from" value="{{ old('valid_from') }}" required>
                            @error('valid_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="valid_until">Berlaku Sampai</label>
                            <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror"
                                id="valid_until" name="valid_until" value="{{ old('valid_until') }}" required>
                            @error('valid_until')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold" for="is_active">Aktifkan Voucher</label>
                    </div>
                </div>

                <hr>
                <div class="text-right">
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-sm mr-2">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('type').addEventListener('change', function() {
        const valueAddon = document.getElementById('value-addon');
        if (this.value === 'percentage') {
            valueAddon.textContent = '%';
        } else {
            valueAddon.textContent = 'Rp';
        }
    });
</script>
@endpush

<style>
    /* Menghilangkan spinner pada input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Memperbaiki style input */
    .form-control {
        border: 1px solid #d1d3e2;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        height: calc(1.5em + 0.75rem + 2px);
    }

    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fc;
        border: 1px solid #d1d3e2;
    }

    /* Label style */
    label {
        color: #4e73df;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    /* Placeholder style */
    ::placeholder {
        color: #858796 !important;
        opacity: 0.6 !important;
    }
</style>
@endsection
