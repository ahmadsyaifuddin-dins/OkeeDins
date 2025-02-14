<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbo-cache-control" content="no-preview">
    <title>{{ config('app.name', 'OkeeDins') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/' . ($appSettings['favicon'] ?? 'default-favicon.ico')) }}"
        type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('storage/' . ($appSettings['favicon'] ?? 'default-favicon.ico')) }}"
        type="image/x-icon">
    <link rel="preload" as="image" href="{{ asset('images/banners/laptop alienware.webp') }}">


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=aspect-ratio"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom': '#D32F2F', // Merah - Primary
                        'custom-secondary': '#102863', // Biru malam - Secondary
                        'custom-accent': '#FDCB6E', // Kuning tua - Accent
                        'bg-primary': '#ffffff', // Putih - Background
                        'dark': {
                            'bg-primary': '#1a1a1a',
                            'text': '#ffffff',
                            'custom': '#FF4545'
                        }
                    },
                }
            }
        }
    </script>


    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --customColor: #D32F2F;
        }

        /* Custom Radio Button */
        input[type="radio"] {
            appearance: none;
            width: 1.15em;
            height: 1.15em;
            border: 0.15em solid var(--customColor) !important;
            border-radius: 50%;
            margin-right: 0.5em;
            outline: none;
            cursor: pointer;
        }

        input[type="radio"]:checked {
            background-color: var(--customColor) !important;
            position: relative;
        }

        input[type="radio"]:checked::before {
            content: "";
            position: absolute;
            width: 0.5em;
            height: 0.5em;
            background-color: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Custom Checkbox */
        input[type="checkbox"] {
            appearance: none;
            width: 1.15em;
            height: 1.15em;
            border: 0.15em solid var(--customColor) !important;
            border-radius: 0.15em;
            margin-right: 0.5em;
            outline: none;
            cursor: pointer;
        }

        input[type="checkbox"]:checked {
            background-color: var(--customColor) !important;
            position: relative;
        }

        input[type="checkbox"]:checked::before {
            content: "✓";
            position: absolute;
            color: white;
            font-size: 0.9em;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Dropdown Transition */
        .transition {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .transform {
            transform: translateZ(0);
        }

        .duration-75 {
            transition-duration: 75ms;
        }

        .duration-100 {
            transition-duration: 100ms;
        }

        .opacity-0 {
            opacity: 0;
        }

        .opacity-100 {
            opacity: 1;
        }

        .scale-95 {
            transform: scale(0.95);
        }

        .scale-100 {
            transform: scale(1);
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }

        .swiper-button-next {
            transform: translateX(20px) scale(0.8);
        }

        .swiper-button-prev {
            transform: translateX(-20px) scale(0.8);
        }

        .swiper:hover .swiper-button-next {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .swiper:hover .swiper-button-prev {
            opacity: 1;
            transform: translateX(0) scale(1);
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--customColor) !important;
            transform: scale(1.1) !important;
        }

        .swiper-button-next:hover i,
        .swiper-button-prev:hover i {
            color: white !important;
        }

        .swiper-button-next.swiper-button-disabled,
        .swiper-button-prev.swiper-button-disabled {
            opacity: 0 !important;
            cursor: not-allowed;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            display: none;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #ccc;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .swiper-pagination-bullet-active {
            background: var(--customColor);
            width: 24px;
            border-radius: 4px;
        }

        .swiper-slide {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .swiper-slide-active {
            transform: scale(1);
        }

        @media (max-width: 768px) {

            .swiper-button-next,
            .swiper-button-prev {
                display: none;
            }
        }

        /* Skeleton Loader Styles */

        /* Dark mode transitions */
        .dark-mode-transition {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>

</head>

<body class="bg-gray-50 dark:bg-gray-900 dark-mode-transition">
    @include('layouts.navbar')

    <main class="min-h-screen pb-16 md:pb-0">
        @yield('content')
    </main>

    @stack('scripts')
    @include('layouts.bottom-nav')
    @include('layouts.footer')

    @extends('layouts.flash-messages')

</body>

</html>