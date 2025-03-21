@extends('layouts.app-admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Voucher Baru</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Form Voucher</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="code" class="form-label fw-bold">Kode Voucher <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" name="code" value="{{ old('code') }}" required 
                                    placeholder="Contoh: WELCOME50">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label fw-bold">Nama Voucher <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Contoh: Diskon Welcome">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="type" class="form-label fw-bold">Tipe Diskon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                <select class="form-select @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                    <option value="">Pilih tipe diskon</option>
                                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="min_purchase" class="form-label fw-bold">Minimal Pembelian</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('min_purchase') is-invalid @enderror"
                                    id="harga" name="min_purchase" value="{{ old('min_purchase') }}"
                                    placeholder="0">
                                @error('min_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="value" class="form-label fw-bold">Nilai Diskon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="value-addon">Rp</span>
                                <input type="number" class="form-control @error('value') is-invalid @enderror"
                                    id="value" name="value" value="{{ old('value') }}" required
                                    placeholder="0">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="max_uses" class="form-label fw-bold">Maksimal Penggunaan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                <input type="number" class="form-control @error('max_uses') is-invalid @enderror"
                                    id="max_uses" name="max_uses" value="{{ old('max_uses') }}"
                                    placeholder="Kosongkan jika tidak ada batas">
                                @error('max_uses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_date" class="form-label fw-bold">Tanggal Mulai</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror"
                                    id="start_date" name="valid_from" value="{{ old('valid_from') }}">
                                @error('valid_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_date" class="form-label fw-bold">Tanggal Berakhir</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror"
                                    id="end_date" name="valid_until" value="{{ old('valid_until') }}">
                                @error('valid_until')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                            {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktifkan Voucher</label>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Voucher
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
@endsection
