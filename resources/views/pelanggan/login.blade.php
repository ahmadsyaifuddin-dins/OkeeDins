<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Pelanggan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#f8f4f3]">
            <div>
                <div class="text-center">
                    <div class="flex justify-center items-center mb-4">
                        <img src="{{ asset('storage/' . ($appSettings['app_logo'] ?? 'default-logo.png')) }}" alt="{{ $appSettings['app_name'] ?? 'App Logo' }}" class=" h-24 w-auto">
                    </div>
                    <p class="text-lg font-semibold">Cepat, Lengkap, <span class="text-[#102863]">Okee</span><span class="text-[#D32F2F]">Dins</span> Solusinya!</p>
                </div>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Menampilkan error jika ada -->
                @if ($errors->any())
                    <div class="bg-red-700 text-white p-4 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="py-8">
                        <center>
                            <span class="text-2xl font-semibold">Log In</span>
                        </center>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700" for="email">Email</label>
                        <input type="email" name="email" placeholder="Email"
                            class="w-full rounded-md py-2.5 px-4 border text-sm outline-[#D32F2F]" required />

                    </div>

                    <div class="mt-4">
                        <label class="block font-medium text-sm text-gray-700" for="password">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" placeholder="Password" required
                                autocomplete="current-password"
                                class="w-full rounded-md py-2.5 px-4 border text-sm outline-[#D32F2F]" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <button type="button" id="togglePassword"
                                    class="text-gray-500 focus:outline-none focus:text-gray-600 hover:text-gray-600">
                                    <i class='bx bx-hide text-xl'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="flex items-center">
                            <input type="checkbox" id="remember_me" name="remember"
                                class="rounded border-gray-300 text-[#D32F2F] shadow-sm focus:border-[#D32F2F] focus:ring focus:ring-[#D32F2F] focus:ring-opacity-50">
                            <span class="ms-2 text-sm text-gray-600">Remember Me</span>
                        </label>
                    </div>
                    <br>

                    <!-- Button and Register Link in a Flex Container -->
                    <div class="flex justify-between items-center">
                        <button
                            class="ms-4 inline-flex items-center px-4 py-2 bg-[#D32F2F] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Masuk
                        </button>

                        <p class="text-sm">
                            Belum Punya Akun? <a href="{{ route('register') }}" class="text-[#D32F2F]">Daftar!</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');
        const toggleIcon = togglePasswordButton.querySelector('i');

        togglePasswordButton.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle icon between show and hide
            if (type === 'password') {
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            } else {
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            }
        });
    </script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

</body>

</html>
