<!-- resources/views/admin/pengguna/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Pengguna</title>
    <link rel="shortcut icon" href="{{ asset('cleopatra/src/img/fav.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('cleopatra/dist/css/style.css') }}">
</head>

<body class="bg-gray-100">
    <!-- start navbar -->
    @include('components.admin-navbar')
    <!-- end navbar -->

    <!-- start wrapper -->
    <div class="h-screen flex md:flex-row">
        <!-- start Sidebar -->
        @include('components.admin-sidebar')
        <!-- end sidebar -->

        <!-- Start Form Edit Pengguna -->
        <div class="flex-1 p-6 mt-32 md:mt-16">
            <div class="card w-full">
                <div class="card-header">Edit Pengguna</div>
                <div class="card-body">
                    <!-- Form Edit Pengguna -->
                    <form action="{{ route('admin.pengguna.update', $pengguna->user_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Nama Pengguna -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $pengguna->name) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600"
                                required>
                            @error('name')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Email Pengguna -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $pengguna->email) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600"
                                required>
                            @error('email')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Role Pengguna -->
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role Pengguna</label>
                            <select name="role" id="role"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                                <option value="Administrator"
                                    {{ $pengguna->role === 'Administrator' ? 'selected' : '' }}>Administrator</option>
                                <option value="Pelanggan" {{ $pengguna->role === 'Pelanggan' ? 'selected' : '' }}>
                                    Pelanggan</option>
                            </select>
                            @error('role')
                                <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mb-4">
                            <button type="submit"
                                class="w-full bg-teal-600 text-black py-2 rounded-md hover:bg-teal-700 transition duration-200">
                                Perbarui Pengguna
                            </button>
                        </div>

                    </form>
                    <!-- End Form Edit Pengguna -->

                    <!-- Tombol Menuju Form Tambah Pengguna -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.pengguna.create') }}"
                            class="w-full bg-blue-600 text-black py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            Tambah Pengguna Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end wrapper -->
    </div>

    <script src="{{ asset('cleopatra/dist/js/scripts.js') }}"></script>
</body>

</html>
