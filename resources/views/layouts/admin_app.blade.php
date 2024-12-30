<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material-dashboard/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('material-dashboard/assets/img/favicon.png') }}">

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />

    <!-- Nucleo Icons -->
    <link href="{{ asset('material-dashboard/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('material-dashboard/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- CSS Files -->

    <!-- Add Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" /> --}}
    <link id="pagestyle" href="{{ asset('material-dashboard/assets/css/material-dashboard.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/admin-index.css') }}">

    <!-- Add jQuery and Toastr JS (add before closing head tag) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <style>
        /* Override toastr default styles */
        #toast-container>div {
            opacity: 1 !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .toast-success {
            background-color: #28a745 !important;
        }

        .toast-error {
            background-color: #dc3545 !important;
        }

        .toast-info {
            background-color: #17a2b8 !important;
        }

        .toast-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        #toast-container>div {
            padding: 15px 15px 15px 50px;
            width: 350px;
            border-radius: 4px;
        }
    </style>

</head>

<body>






</body>

</html>
