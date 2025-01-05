@extends('layouts.app')

@section('content')

    <!-- Catatan untuk Penjual -->
    <div class="mb-4">
        <label for="notes" class="form-label">Catatan untuk Penjual (Opsional)</label>
        <textarea id="notes" name="notes" class="form-control" rows="1" placeholder="Contoh: Warna navy, ukuran M, dll"
            maxlength="255"></textarea>
        <small class="text-muted">Maksimal 255 karakter</small>
    </div>
