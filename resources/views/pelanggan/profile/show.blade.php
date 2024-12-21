@extends('layouts.styling_global')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Sidebar Profile -->
                <div class="col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3 position-relative">
                                @if (Auth::user()->photo)
                                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Photo Pelanggan"
                                        class="rounded-circle img-fluid"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/user.svg') }}" alt="Profile Photo"
                                        class="rounded-circle img-fluid"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                @endif
                            </div>

                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted mb-3">Pelanggan</p>
                            <div class="list-group">
                                <a href="{{ route('pelanggan.profile.show') }}"
                                    class="list-group-item list-group-item-action bg-warning ">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="fas fa-shopping-bag me-2"></i> My Orders
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="fas fa-heart me-2"></i> Wishlist
                                </a>
                                <br>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Tombol Kembali -->
                                    <a href="{{ route('market') }}" class="btn btn-warning text-white">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <!-- Tombol Logout -->
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger text-white">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="col-lg-9">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h5 class="card-title mb-0">Informasi Profil Saya
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('pelanggan.profile.update') }}" enctype="multipart/form-data"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <label for="name" class="col-sm-3 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', Auth::user()->email) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="telepon" class="col-sm-3 col-form-label">No. WhatsApp</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                            id="telepon" name="telepon"
                                            value="{{ old('telepon', Auth::user()->telepon) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="tgl_lahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror"
                                            id="tgl_lahir" name="tgl_lahir"
                                            value="{{ old('tgl_lahir', Auth::user()->tgl_lahir) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="makanan_fav" class="col-sm-3 col-form-label">Makanan Favorit</label>
                                    <div class="col-sm-9">
                                        <input class="form-control @error('makanan_fav') is-invalid @enderror"
                                            id="makanan_fav" name="makanan_fav"
                                            value="{{ old('makanan_fav', Auth::user()->makanan_fav) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                    <div class="col-sm-9">
                                        <input readonly class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                            id="jenis_kelamin" name="jenis_kelamin"
                                            value="{{ old('jenis_kelamin', Auth::user()->jenis_kelamin) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="role" class="col-sm-3 col-form-label">Jenis Pengguna</label>
                                    <div class="col-sm-9">
                                        <input readonly class="form-control @error('role') is-invalid @enderror"
                                            id="role" name="role"
                                            value="{{ old('role', Auth::user()->role) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="created_at" class="col-sm-3 col-form-label">Tanggal Daftar
                                        Akun</label>
                                    <div class="col-sm-9">
                                        <input readonly class="form-control @error('created_at') is-invalid @enderror"
                                            id="created_at" name="created_at"
                                            value="{{ old('created_at', Auth::user()->created_at) }}" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="photo" class="col-sm-3 col-form-label">Foto Profil</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                            id="photo" name="photo" accept="image/*">
                                        <small class="text-muted">Upload foto dengan format JPG, JPEG, PNG, SVG, atau GIF
                                            (Max.
                                            2MB)</small>
                                        @if (Auth::user()->photo)
                                            <div class="mt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="remove_photo"
                                                        name="remove_photo">
                                                    <label class="form-check-label" for="remove_photo">
                                                        Hapus foto profil saat ini
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- <div class="row mb-3">
                                    <label for="current_password" class="col-sm-3 col-form-label">Password saat
                                        ini</label>
                                    <div class="col-sm-9">
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password">
                                    </div>
                                </div> --}}

                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                                    <div class="col-sm-9">
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password">
                                        <small class="text-muted">Biarkan kosong jika Anda tidak ingin mengubah
                                            Password

                                        </small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-warning">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
@endsection
