<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.admin_app')
</head>

<body class="g-sidenav-show bg-gray-100">
    @include('components.admin-sidebar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        @include('components.admin-navbar')
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-gradient-dark text-white">
                            <h6 class="text-white text-capitalize">Tambah Data Pengguna</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.pengguna.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Nama -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nama Pengguna</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Masukkan Nama" required>
                                    </div>
                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Masukkan Email" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Masukkan Password" required>
                                    </div>
                                    <!-- Role -->
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">Role Pengguna</label>
                                        <select name="role" id="role" class="form-select" required>
                                            <option value="Administrator">Administrator</option>
                                            <option value="Pelanggan">Pelanggan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Jenis Kelamin -->
                                    <div class="col-md-6 mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <!-- Tanggal Lahir -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Telepon -->
                                    <div class="col-md-6 mb-3">
                                        <label for="telepon" class="form-label">Telepon</label>
                                        <input type="text" name="telepon" id="telepon" class="form-control"
                                            placeholder="Masukkan Telepon" required>
                                    </div>
                                    <!-- Makanan Favorit -->
                                    <div class="col-md-6 mb-3">
                                        <label for="makanan_fav" class="form-label">Makanan Favorit</label>
                                        <input type="text" name="makanan_fav" id="makanan_fav" class="form-control"
                                            placeholder="Masukkan Makanan Favorit" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Tipe Karakter -->
                                    <div class="col-md-6 mb-3">
                                        <label for="type_char" class="form-label">Tipe Karakter</label>
                                        <select name="type_char" id="type_char" class="form-select" required>
                                            <option value="Hero">Hero</option>
                                            <option value="Villain">Villain</option>
                                        </select>
                                    </div>
                                    <!-- Foto -->
                                    <div class="col-md-6 mb-3">
                                        <label for="photo" class="form-label">Foto Profil</label>
                                        <input type="file" name="photo" id="photo" class="form-control">
                                    </div>
                                </div>
                                <!-- Tombol Submit -->
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <a href="{{ route('admin.pengguna.index') }}"
                                            class="btn btn-secondary">Batal</a>
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
