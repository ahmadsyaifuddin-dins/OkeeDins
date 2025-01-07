@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Edit Data Kategori Produk</h6>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="slug" class="mb-2">Slug</label>
                                        <div class="input-group input-group-outline">
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                value="{{ $kategori->slug }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="nama_kategori" class="mb-2">Nama Kategori</label>
                                        <div class="input-group input-group-outline">
                                            <input type="text" name="nama_kategori" id="nama_kategori"
                                                class="form-control" value="{{ $kategori->nama_kategori }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="deskripsi" class="mb-2">Deskripsi</label>
                                        <div class="input-group input-group-outline">
                                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required>{{ $kategori->deskripsi }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn bg-gradient-dark">Update</button>
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
