@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Data Kategori Produk</h6>
                        </div>
                        <a href="{{ route('admin.kategori.create') }}" class=" btn bg-gradient-dark btn-md me-3 mt-3">
                            <i class="material-symbols-rounded me-1" style="font-size: 18px;">add_box</i> Tambah
                            Data Kategori Produk </a>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            No</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Slug</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Nama Kategori</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Deskripsi</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Ditambahkan pada tanggal</th>
                                        <th
                                            class="text-center text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategori as $index => $kat)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-2">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $index + 1 }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $kat->slug }}
                                                </p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $kat->nama_kategori }}
                                                </p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ Str::limit(strip_tags($kat->deskripsi), 50) }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $kat->created_at }}</span>
                                            </td>

                                            <td class="align-middle">
                                                <a href="{{ route('admin.kategori.edit', $kat->id) }}"
                                                    class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                                    data-original-title="Edit user">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.kategori.destroy', $kat->id) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger font-weight-bold text-xs"
                                                        style="border: none; background: none;"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                        Hapus
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
