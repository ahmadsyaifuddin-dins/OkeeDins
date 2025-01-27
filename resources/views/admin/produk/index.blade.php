@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Data Produk</h6>
                        </div>
                        <a href="{{ route('admin.produk.create') }}" class="btn bg-gradient-dark btn-md me-3 mt-3">
                            <i class="material-symbols-rounded me-1" style="font-size: 18px;">box_add</i> Tambah
                            Data Produk
                        </a>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            No</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Gambar Produk</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Nama Produk</th>
                                        {{-- <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Deskripsi</th> --}}
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Harga</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Stok</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Diskon</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Harga Diskon</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Kategori</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Ditambahkan pada tanggal</th>

                                        <th
                                            class="text-center text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $index => $prod)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-2">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $index + 1 }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <img src="{{ $prod->gambar ? asset('storage/' . $prod->gambar) : asset('storage/user.svg') }}"
                                                        class="avatar avatar-lg me-3 border-radius-lg" alt="product photo">
                                                </div>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $prod->nama_produk }}
                                                </p>
                                            </td>

                                            {{-- <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ Str::limit(strip_tags($prod->deskripsi), 50) }}
                                                </span>
                                            </td> --}}

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Rp{{ number_format($prod->harga, 0, ',', '.') }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 {{ $prod->stok <= 10 ? 'text-danger fw-bold' : '' }}">
                                                    {{ $prod->stok }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $prod->diskon }}%
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Rp{{ number_format($prod->harga_diskon, 0, ',', '.') }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $prod->kategori ? $prod->kategori->nama_kategori : '-' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $prod->created_at }}
                                                </span>
                                            </td>

                                            <td class="align-middle">
                                                <a href="{{ route('admin.produk.show', $prod->id) }}"
                                                    class="btn btn-info btn-sm me-1">
                                                    <i class="material-symbols-rounded" style="font-size: 20px; vertical-align: middle;">visibility</i>
                                                </a>
                                                <a href="{{ route('admin.produk.edit', $prod->id) }}"
                                                    class="btn btn-warning btn-sm me-1">
                                                    <i class="material-symbols-rounded" style="font-size: 20px; vertical-align: middle;">edit</i>
                                                </a>
                                                <form action="{{ route('admin.produk.destroy', $prod->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                        <i class="material-symbols-rounded" style="font-size: 20px; vertical-align: middle;">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
