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
                                <h6 class="text-white text-capitalize ps-3">Tambah Data Kategori Produk</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <form action="{{ route('admin.kategori.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-outline mb-4">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input type="text" name="slug" id="slug" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group input-group-outline mb-4">
                                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                            <input type="text" name="nama_kategori" id="nama_kategori"
                                                class="form-control" required>
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
                                        <a href="{{ route('admin.kategori.index') }}"
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

    {{-- Script Label TextArea Mengambang  --}}
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

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('material-dashboard/assets/js/material-dashboard.min.js') }}"></script>
</body>

</html>
