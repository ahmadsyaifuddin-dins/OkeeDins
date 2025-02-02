@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-white text-capitalize ps-3 mb-0">Tambah Kategori Produk</h6>
                                <div class="pe-3">
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-info mb-0">
                                        <i class="material-icons text-sm">arrow_back</i>&nbsp;&nbsp;Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form action="{{ route('admin.kategori.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="nama_kategori" class="form-label fw-bold mb-2">Nama Kategori <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-outline {{ old('nama_kategori') ? 'is-filled' : '' }}">
                                            <input type="text" name="nama_kategori" id="nama_kategori" 
                                                   class="form-control @error('nama_kategori') is-invalid @enderror"
                                                   value="{{ old('nama_kategori') }}" required>
                                        </div>
                                        @error('nama_kategori')
                                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Nama kategori akan ditampilkan di halaman produk</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="slug" class="form-label fw-bold mb-2">Slug <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-outline {{ old('slug') ? 'is-filled' : '' }}">
                                            <input type="text" name="slug" id="slug" 
                                                   class="form-control @error('slug') is-invalid @enderror"
                                                   value="{{ old('slug') }}" required>
                                        </div>
                                        @error('slug')
                                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Slug akan digunakan untuk URL kategori</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="deskripsi" class="form-label fw-bold mb-2">Deskripsi <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-outline {{ old('deskripsi') ? 'is-filled' : '' }}">
                                            <textarea name="deskripsi" id="deskripsi" 
                                                      class="form-control @error('deskripsi') is-invalid @enderror" 
                                                      rows="4" required>{{ old('deskripsi') }}</textarea>
                                        </div>
                                        @error('deskripsi')
                                            <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Berikan deskripsi yang jelas tentang kategori ini</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="material-icons text-sm">save</i>&nbsp;&nbsp;Simpan Kategori
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
    // Auto generate slug from nama_kategori
    const namaKategori = document.getElementById('nama_kategori');
    const slug = document.getElementById('slug');
    
    namaKategori.addEventListener('keyup', function() {
        slug.value = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    });

    // Input group focus handling
    document.querySelectorAll('.input-group-outline input, .input-group-outline textarea').forEach(function(element) {
        element.addEventListener('focus', function() {
            this.closest('.input-group').classList.add('is-focused');
        });

        element.addEventListener('blur', function() {
            this.closest('.input-group').classList.remove('is-focused');
            if (this.value !== '') {
                this.closest('.input-group').classList.add('is-filled');
            } else {
                this.closest('.input-group').classList.remove('is-filled');
            }
        });

        // Check initial value
        if (element.value !== '') {
            element.closest('.input-group').classList.add('is-filled');
        }
    });
</script>
@endpush
