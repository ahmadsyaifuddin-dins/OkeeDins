@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 px-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white text-capitalize mb-0">Edit Produk</h6>
                                <p class="text-white text-sm mb-0 opacity-8">{{ $produk->nama_produk }}</p>
                            </div>
                            <a href="{{ route('admin.produk.index') }}" class="btn btn-light btn-sm">
                                <span class="material-symbols-rounded" >arrow_back</span> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <!-- Informasi Dasar -->
                                <div class="col-12">
                                    <h6 class="text-dark text-sm mb-3">Informasi Dasar</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-1">
                                        <label for="nama_produk" class="ms-0">Nama Produk</label>
                                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                                        <small class="text-muted">Masukkan nama produk yang akan dijual</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-1">
                                        <label for="slug" class="ms-0">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" value="{{ $produk->slug }}" required>
                                        <small class="text-muted">Slug akan otomatis terisi dari nama produk</small>
                                    </div>
                                </div>

                                <!-- Kategori & Harga -->
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-1">
                                        <label for="kategori" class="ms-0">Kategori</label>
                                        <select name="kategori_id" id="kategori" class="form-control" required>
                                            <option value="" disabled>Pilih Kategori</option>
                                            @foreach ($kategori as $kat)
                                                <option value="{{ $kat->id }}" {{ $produk->kategori_id == $kat->id ? 'selected' : '' }}>
                                                    {{ $kat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-1">
                                        <label for="harga" class="ms-0">Harga (Rp)</label>
                                        <input type="text" name="harga" id="harga" class="form-control" value="{{ $produk->harga }}" required>
                                        <small class="text-muted">Masukkan harga dalam format angka</small>
                                    </div>
                                </div>

                                <!-- Stok & Diskon -->
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-1">
                                        <label for="stok" class="ms-0">Stok</label>
                                        <input type="number" name="stok" id="stok" class="form-control" value="{{ $produk->stok }}" required min="0">
                                        <small class="text-muted">Jumlah stok yang tersedia</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-1">
                                        <label for="diskon" class="ms-0">Diskon (%)</label>
                                        <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $produk->diskon }}" step="0.01" min="0" max="100">
                                        <small class="text-muted">Opsional - Masukkan angka 0-100</small>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                        <div class="input-group input-group-outline">
                                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required>{!! $produk->deskripsi !!}</textarea>
                                        </div>
                                        <small class="text-muted mt-2 d-block">Deskripsikan produk secara detail</small>
                                    </div>
                                </div>

                                <!-- Upload Gambar -->
                                <div class="col-12">
                                    <h6 class="text-dark text-sm mb-3">Foto Produk</h6>
                                    
                                    <!-- Gambar Utama -->
                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header p-3 pb-0">
                                            <h6 class="mb-0">Gambar Utama</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('storage/user.svg') }}"
                                                         class="rounded shadow-sm" 
                                                         style="width: 100px; height: 100px; object-fit: cover;"
                                                         alt="current main photo">
                                                </div>
                                                <div class="col">
                                                    <div class="input-group input-group-outline">
                                                        <input type="file" name="gambar" class="form-control" accept="image/*">
                                                    </div>
                                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar utama</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Galeri Produk -->
                                    <div class="card shadow-sm">
                                        <div class="card-header p-3 pb-0">
                                            <h6 class="mb-0">Galeri Produk</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <!-- Current Images -->
                                            <div class="row g-3 mb-4">
                                                @forelse($produk->productImages as $image)
                                                    <div class="col-6 col-md-3">
                                                        <div class="position-relative">
                                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                                 class="img-fluid rounded shadow-sm" 
                                                                 style="width: 100%; height: 120px; object-fit: cover;"
                                                                 alt="additional product photo">
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-hapus-foto"
                                                                    data-image-id="{{ $image->id }}">
                                                                <i class="material-icons" style="font-size: 10px;">delete</i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12">
                                                        <div class="alert alert-info text-sm text-white mb-0" role="alert">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            Belum ada gambar tambahan untuk produk ini
                                                        </div>
                                                    </div>
                                                @endforelse
                                            </div>

                                            <!-- Add New Images -->
                                            <div class="row" id="new-images-container">
                                                <div class="col-12 mb-3">
                                                    <button type="button" class="btn btn-outline-dark btn-sm" id="tambahFoto">
                                                        <i class="fas fa-plus me-2"></i>Tambah Foto Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="material-symbols-rounded me-2">save</i>Simpan Perubahan
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

    // Handle hapus foto
    $('.btn-hapus-foto').click(function() {
        if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
            let imageId = $(this).data('image-id');
            // Tambahkan input hidden untuk menandai gambar yang akan dihapus
            $('#formProduk').append(`<input type="hidden" name="delete_images[]" value="${imageId}">`);
            $(this).closest('.col-6').remove();
        }
    });

    // Handle tambah foto baru
    $('#tambahFoto').click(function() {
        let newImageInput = `
            <div class="col-md-3 mb-3">
                <div class="image-upload-wrap">
                    <input type="file" name="additional_images[]" class="file-upload-input" accept="image/*">
                    <div class="text-center py-3">
                        <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                        <p class="mb-0">Klik untuk upload</p>
                    </div>
                </div>
            </div>
        `;
        $(newImageInput).insertBefore($(this).closest('.col-12'));
    });

    // Preview untuk gambar baru
    $(document).on('change', 'input[type="file"]', function(e) {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            let $input = $(this);
            
            reader.onload = function(e) {
                let $wrap = $input.closest('.image-upload-wrap');
                $wrap.html(`
                    <img src="${e.target.result}" class="img-fluid" style="width: 100%; height: 120px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-new-image">
                        <i class="material-icons" style="font-size: 10px;">close</i>
                    </button>
                    <input type="file" name="additional_images[]" class="d-none" accept="image/*">
                `).css('padding', '0');
                
                // Pindahkan file ke input yang baru
                $wrap.find('input[type="file"]').prop('files', $input.prop('files'));
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Hapus gambar baru
    $(document).on('click', '.remove-new-image', function() {
        $(this).closest('.col-md-3').remove();
    });
});
</script>
@endpush

@push('styles')
<style>
.btn-hapus-foto {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.position-relative:hover .btn-hapus-foto {
    opacity: 1;
}

.image-upload-wrap {
    border: 2px dashed #ccc;
    border-radius: 8px;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 120px;
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
</style>
@endpush
