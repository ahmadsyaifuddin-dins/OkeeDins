@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Tambah Data Kategori Produk</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form action="{{ route('admin.kategori.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn bg-gradient-dark">Simpan</button>
                                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-dark">Kembali</a>
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
        document.querySelectorAll('.input-group-outline textarea').forEach(function(textarea) {
            textarea.addEventListener('focus', function() {
                this.closest('.input-group').classList.add('is-focused');
            });

            textarea.addEventListener('blur', function() {
                this.closest('.input-group').classList.remove('is-focused');
                if (this.value !== '') {
                    this.closest('.input-group').classList.add('is-filled');
                } else {
                    this.closest('.input-group').classList.remove('is-filled');
                }
            });

            // Check initial value
            if (textarea.value !== '') {
                textarea.closest('.input-group').classList.add('is-filled');
            }
        });
    </script>
@endpush
