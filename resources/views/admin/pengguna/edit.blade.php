<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.admin_app')
</head>

<body class="g-sidenav-show bg-gray-100">
    @include('components.admin-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('components.admin-navbar')
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                                <h6 class="text-white text-capitalize ps-3">Edit Data Pengguna</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <form action="{{ route('admin.pengguna.update', $pengguna->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="name" class="mb-2">Nama Pengguna</label>
                                            <div class="input-group input-group-outline">
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ $pengguna->name }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="email" class="mb-2">Email</label>
                                            <div class="input-group input-group-outline">
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ $pengguna->email }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="password" class="mb-2">Password Baru (Kosongkan jika tidak
                                                diubah)</label>
                                            <div class="input-group input-group-outline">
                                                <input type="password" name="password" id="password"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="role" class="mb-2">Role Pengguna</label>
                                            <div class="input-group input-group-outline">
                                                <select name="role" id="role" class="form-control" required>
                                                    <option value="Pelanggan"
                                                        {{ $pengguna->role == 'Pelanggan' ? 'selected' : '' }}>Pelanggan
                                                    </option>
                                                    <option value="Administrator"
                                                        {{ $pengguna->role == 'Administrator' ? 'selected' : '' }}>
                                                        Administrator
                                                    </option>
                                                    <option value="Kasir"
                                                        {{ $pengguna->role == 'Kasir' ? 'selected' : '' }}>
                                                        Kasir
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="jenis_kelamin" class="mb-2">Jenis Kelamin</label>
                                            <div class="input-group input-group-outline">
                                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control"
                                                    required>
                                                    <option value="Laki-Laki"
                                                        {{ $pengguna->jenis_kelamin == 'Laki-Laki' ? 'selected' : '' }}>
                                                        Laki-Laki</option>
                                                    <option value="Perempuan"
                                                        {{ $pengguna->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="tgl_lahir" class="mb-2">Tanggal Lahir</label>
                                            <div class="input-group input-group-outline">
                                                <input type="date" name="tgl_lahir" id="tgl_lahir"
                                                    class="form-control" value="{{ $pengguna->tgl_lahir }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="telepon" class="mb-2">Telepon</label>
                                            <div class="input-group input-group-outline">
                                                <input type="text" name="telepon" id="telepon" class="form-control"
                                                    value="{{ $pengguna->telepon }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="makanan_fav" class="mb-2">Makanan Favorit</label>
                                            <div class="input-group input-group-outline">
                                                <input type="text" name="makanan_fav" id="makanan_fav"
                                                    class="form-control" value="{{ $pengguna->makanan_fav }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="type_char" class="mb-2">Tipe Karakter</label>
                                            <div class="input-group input-group-outline">
                                                <select name="type_char" id="type_char" class="form-control"
                                                    required>
                                                    <option value="Hero"
                                                        {{ $pengguna->type_char == 'Hero' ? 'selected' : '' }}>
                                                        Hero</option>
                                                    <option value="Villain"
                                                        {{ $pengguna->type_char == 'Villain' ? 'selected' : '' }}>
                                                        Villain
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="photo" class="mb-2">Foto Profil</label>
                                            <div class="input-group input-group-outline">
                                                <input type="file" name="photo" id="photo"
                                                    class="form-control">
                                            </div>
                                            @if ($pengguna->photo)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $pengguna->photo) }}"
                                                        alt="Current photo"
                                                        class="avatar avatar-md me-3 border-radius-lg">
                                                    <span class="text-sm">Current photo</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn bg-gradient-dark">Update</button>
                                        <a href="{{ route('admin.pengguna.index') }}"
                                            class="btn btn-outline-dark">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('components.function')
    <!--   Core JS Files   -->
    <script src="{{ asset('material-dashboard/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('material-dashboard/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('material-dashboard/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('material-dashboard/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('material-dashboard/assets/js/plugins/chartjs.min.js') }}"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('material-dashboard/assets/js/material-dashboard.min.js') }}"></script>
</body>

</html>
