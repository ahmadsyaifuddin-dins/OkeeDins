@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Data Pengguna</h6>
                        </div>
                        <a href="{{ route('admin.pengguna.create') }}" class=" btn bg-gradient-dark btn-md me-3 mt-3">
                            <i class="material-symbols-rounded me-1" style="font-size: 18px;">person_add</i> Tambah
                            Data Pengguna </a>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            No</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Foto</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Nama Pengguna & Email</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Password</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Role Pengguna</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Jenis Kelamin</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Tanggal Lahir</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Telepon</th>
                                        <th class="text-uppercase text-black-th text-xxs font-weight-bolder opacity-7 ps-2">
                                            Makanan Favorit</th>
                                        <th
                                            class="text-center text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Tipe Karakter</th>
                                        <th
                                            class="text-center text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Tanggal Daftar</th>
                                        <th
                                            class="text-center text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Status Pengguna</th>
                                        <th
                                            class="text-center text-uppercase text-black-th text-xxs font-weight-bolder opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengguna as $index => $user)
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
                                                <div>
                                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('storage/user.svg') }}"
                                                        class="avatar avatar-md me-3 border-radius-lg" alt="user photo">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $user->password }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span
                                                    class="badge badge-sm 
                                                        {{ $user->role == 'Administrator'
                                                            ? 'bg-gradient-primary'
                                                            : ($user->role == 'Kasir'
                                                                ? 'bg-gradient-warning'
                                                                : ($user->role == 'Pelanggan'
                                                                    ? 'bg-gradient-info'
                                                                    : 'bg-gradient-secondary')) }}">
                                                    {{ $user->role }}
                                                </span>
                                            </td>

                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->jenis_kelamin }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->tgl_lahir }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->telepon }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->makanan_fav }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $user->type_char == 'Hero' ? 'success' : 'danger' }}">{{ $user->type_char }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $user->created_at }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $user->role == 'Administrator' ? 'success' : 'secondary' }}">{{ $user->role == 'Administrator' ? 'Online' : 'Offline' }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('admin.pengguna.edit', $user->id) }}"
                                                    class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                                    data-original-title="Edit user">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.pengguna.destroy', $user->id) }}"
                                                    method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-danger font-weight-bold text-xs"
                                                        style="border: none; background: none;"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
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
