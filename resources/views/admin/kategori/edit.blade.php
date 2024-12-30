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
                                <h6 class="text-white text-capitalize ps-3">Edit Data Kategori Produk</h6>
                            </div>
                        </div>
                        <div class="card-body px-4 pb-2">
                            <form action="{{ route('admin.kategori.update', $kategori->kategori_id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="nama_kategori" class="mb-2">Nama Kategori</label>
                                            <div class="input-group input-group-outline">
                                                <input type="text" name="nama_kategori" id="nama_kategori"
                                                    class="form-control" value="{{ $kategori->nama_kategori }}"
                                                    required>
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
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('material-dashboard/assets/js/material-dashboard.min.js') }}"></script>
</body>

</html>
