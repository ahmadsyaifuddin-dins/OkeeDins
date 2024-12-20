<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodFusion')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Tambahkan jika ada -->
</head>

<body>
    @include('components.header') <!-- Header -->

    <main>
        @yield('content') <!-- Konten dinamis -->
    </main>

    @include('components.footer') <!-- Footer -->

    <script src="{{ asset('js/app.js') }}"></script> <!-- Tambahkan jika ada -->
</body>

</html>
