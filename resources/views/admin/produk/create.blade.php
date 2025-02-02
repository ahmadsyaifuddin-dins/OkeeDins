@extends('layouts.app-admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Tambah Produk Baru</h6>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-info me-3">Kembali</a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" id="formProduk">
                        @csrf
                        <div class="row g-4">
                            <!-- Informasi Dasar -->
                            <div class="col-12">
                                <h6 class="text-dark text-sm mb-3">Informasi Dasar</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-1">
                                    <label for="nama_produk" class="ms-0">Nama Produk</label>
                                    <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
                                    <small class="text-muted">Masukkan nama produk yang akan dijual</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-1">
                                    <label for="slug" class="ms-0">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control" required>
                                    <small class="text-muted">Slug akan otomatis terisi dari nama produk</small>
                                </div>
                            </div>

                            <!-- Kategori & Harga -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-1">
                                    <label for="kategori" class="ms-0">Kategori</label>
                                    <select name="kategori_id" id="kategori" class="form-control" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-1">
                                    <label for="harga" class="ms-0">Harga (Rp)</label>
                                    <input type="text" name="harga" id="harga" class="form-control" required min="0">
                                    <small class="text-muted">Masukkan harga dalam format angka</small>
                                </div>
                            </div>

                            <!-- Stok & Diskon -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-1">
                                    <label for="stok" class="ms-0">Stok</label>
                                    <input type="number" name="stok" id="stok" class="form-control" required min="0">
                                    <small class="text-muted">Jumlah stok yang tersedia</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-1">
                                    <label for="diskon" class="ms-0">Diskon (%)</label>
                                    <input type="number" name="diskon" id="diskon" class="form-control" step="0.01" min="0" max="100" value="0">
                                    <small class="text-muted">Opsional - Masukkan angka 0-100</small>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <div class="mb-4">
                                    <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                    <div class="input-group input-group-outline">
                                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Deskripsikan produk secara detail</small>
                                </div>
                            </div>

                            <!-- Upload Gambar -->
                            <div class="col-12">
                                <h6 class="text-dark text-sm mb-3">Foto Produk</h6>
                                <div class="row" id="image-preview-container">
                                    <div class="col-12 mb-3">
                                        <button type="button" class="btn btn-outline-dark btn-sm" id="tambahFoto">
                                            <i class="fas fa-plus me-2"></i>Tambah Foto Produk
                                        </button>
                                    </div>
                                </div>
                                <div class="row" id="preview-container">
                                    <div class="col-md-4 mb-3 preview-item">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <div class="image-upload-wrap">
                                                    <input type="file" name="foto[]" class="file-upload-input" accept="image/*" onchange="readURL(this);" required>
                                                    <div class="text-center py-3">
                                                        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                                        <p class="mb-0">Klik atau seret foto di sini</p>
                                                    </div>
                                                    <img class="img-preview img-fluid d-none mb-2" src="#" alt="Preview">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm w-100 mt-2 d-none btn-hapus">
                                                    <i class="fas fa-trash me-2"></i>Hapus Foto
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB per foto</small>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save me-2"></i>Simpan Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto generate slug
    $('#nama_produk').on('input', function() {
        let nama = $(this).val();
        let slug = nama.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        $('#slug').val(slug);
    });

    // Validasi form
    $('#formProduk').on('submit', function(e) {
        let harga = $('#harga').val();
        let stok = $('#stok').val();
        let diskon = $('#diskon').val();

        if (harga < 0 || stok < 0 || diskon < 0 || diskon > 100) {
            e.preventDefault();
            alert('Mohon periksa kembali input harga, stok, dan diskon!');
        }
    });

    // Handle tambah foto
    $('#tambahFoto').click(function() {
        let previewItem = $('.preview-item').first().clone();
        previewItem.find('.img-preview').attr('src', '#').addClass('d-none');
        previewItem.find('.text-center').removeClass('d-none');
        previewItem.find('input[type="file"]').val('');
        previewItem.find('.btn-hapus').addClass('d-none');
        $('#preview-container').append(previewItem);

        // Hapus required dari input pertama jika ada lebih dari satu input
        if ($('.preview-item').length > 1) {
            $('.preview-item').first().find('input[type="file"]').removeAttr('required');
        }
    });

    // Handle hapus foto
    $(document).on('click', '.btn-hapus', function() {
        let previewItems = $('.preview-item');
        if (previewItems.length > 1) {
            $(this).closest('.preview-item').remove();
            // Tambahkan required ke input pertama jika hanya tersisa satu input
            if (previewItems.length === 2) {
                $('.preview-item').first().find('input[type="file"]').attr('required', 'required');
            }
        } else {
            alert('Minimal harus ada satu foto produk!');
        }
    });
});

// Preview gambar
function readURL(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        let previewItem = $(input).closest('.preview-item');
        
        reader.onload = function(e) {
            let imgPreview = previewItem.find('.img-preview');
            imgPreview.attr('src', e.target.result);
            imgPreview.removeClass('d-none');
            previewItem.find('.text-center').addClass('d-none');
            previewItem.find('.btn-hapus').removeClass('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@push('styles')
<style>
.image-upload-wrap {
    border: 2px dashed #ccc;
    border-radius: 8px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 150px;
}

.image-upload-wrap:hover {
    border-color: #666;
}

.file-upload-input {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.img-preview {
    max-height: 200px;
    object-fit: cover;
    width: 100%;
}

.text-center {
    position: relative;
    z-index: 1;
}

.preview-item {
    transition: all 0.3s ease;
}

.preview-item:hover {
    transform: translateY(-2px);
}
</style>
@endpush
