@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Voucher</h1>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Kode Voucher</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                id="code" name="code" value="{{ old('code', $voucher->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Voucher</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $voucher->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Tipe Diskon</label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                                <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                <option value="percentage" {{ old('type', $voucher->type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="value">Nilai Diskon</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                    id="value" name="value" value="{{ old('value', $voucher->value) }}" required step="0.01">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="value-addon">
                                        {{ $voucher->type === 'percentage' ? '%' : 'Rp' }}
                                    </span>
                                </div>
                            </div>
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
                                    id="min_purchase" name="min_purchase" value="{{ old('min_purchase', $voucher->min_purchase) }}" required>
                            </div>
                            @error('min_purchase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="max_uses">Batas Penggunaan (kosongkan jika tidak ada batas)</label>
                            <input type="number" class="form-control @error('max_uses') is-invalid @enderror" 
                                id="max_uses" name="max_uses" value="{{ old('max_uses', $voucher->max_uses) }}">
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="valid_from">Berlaku Dari</label>
                            <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror" 
                                id="valid_from" name="valid_from" 
                                value="{{ old('valid_from', $voucher->valid_from->format('Y-m-d\TH:i')) }}" required>
                            @error('valid_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="valid_until">Berlaku Sampai</label>
                            <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror" 
                                id="valid_until" name="valid_until" 
                                value="{{ old('valid_until', $voucher->valid_until->format('Y-m-d\TH:i')) }}" required>
                            @error('valid_until')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                            value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Aktifkan Voucher</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Voucher</button>
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
