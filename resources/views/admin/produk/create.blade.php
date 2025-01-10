@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Tambah Produk</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="nama_produk" class="form-label">Nama Produk</label>
                                        <input type="text" name="nama_produk" id="nama_produk" class="form-control"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label class="form-label">Kategori</label>
                                        <select name="kategori_id" id="kategori" class="form-control" required>
                                            <option value="" disabled selected></option>
                                            @foreach ($kategori as $kat)
                                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="harga" class="form-label">Harga</label>
                                        <input type="text" name="harga" id="harga" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="stok" class="form-label">Stok</label>
                                        <input type="number" name="stok" id="stok" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="diskon" class="form-label">Diskon (%)</label>
                                        <input type="number" name="diskon" id="diskon" class="form-control"
                                            step="0.01" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-4">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <div class="input-group input-group-outline">
                                            <input placeholder="Gambar Produk" type="file" name="gambar" id="gambar"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn bg-gradient-dark">Simpan</button>
                                    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-dark">Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script> --}}

{{-- Script Label TextArea Mengambang  --}}
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

{{-- Script Label Select Mengambang  --}}
@push('scripts')
    <script>
        document.querySelectorAll('.input-group-outline select').forEach(function(select) {
            select.addEventListener('focus', function() {
                this.closest('.input-group').classList.add('is-focused');
            });

            select.addEventListener('blur', function() {
                this.closest('.input-group').classList.remove('is-focused');
                if (this.value !== '') {
                    this.closest('.input-group').classList.add('is-filled');
                } else {
                    this.closest('.input-group').classList.remove('is-filled');
                }
            });

            // Check initial value
            if (select.value !== '') {
                select.closest('.input-group').classList.add('is-filled');
            }
        });
    </script>
@endpush
