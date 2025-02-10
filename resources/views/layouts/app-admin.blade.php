<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? 'Admin OkeeDins' }}</title>

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material-dashboard/assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('material-dashboard/assets/img/favicon.png') }}" />

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />

    <!-- Nucleo Icons -->
    <link href="{{ asset('material-dashboard/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('material-dashboard/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/admin-css/pagination-custom.css') }}">

    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.13/iconfont/material-icons.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- CSS Files -->

    <!-- Add Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{--
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" /> --}}
    <link id="pagestyle" href="{{ asset('material-dashboard/assets/css/material-dashboard.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin-index.css') }}">

</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- start sidebar -->
    @include('layouts-admin.sidebar')
    <!-- end sidebar -->

    <!-- start main -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <!-- start navbar -->
        @include('layouts-admin.navbar')
        <!-- end navbar -->

        <div class="container-fluid py-2">
            @yield('content')
            @stack('scripts')
            {{-- @include('layouts.footer') --}}
        </div>
        @include('layouts-admin.function')
    </main>

    <!-- end main -->
    @yield('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <!-- Add jQuery and Toastr JS (add before closing head tag) -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- Script TinyMCE --}}

    <script>
        tinymce.init({
            selector: '#deskripsi',
            height: 300,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
            menubar: true,
            branding: false,
            promotion: false,
            language: 'id',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Ensure content is saved to textarea
                });
            }
        });
    </script>
    {{-- Script AutoNumeric --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi AutoNumeric dengan konfigurasi untuk format Indonesia
            const hargaInput = document.querySelector('#harga');
            if (hargaInput) {
                new AutoNumeric(hargaInput, {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    decimalPlaces: 0,
                    currencySymbol: 'Rp ',
                    currencySymbolPlacement: 'p',
                    unformatOnSubmit: true,
                    minimumValue: '0',
                    maximumValue: '999999999999',
                    modifyValueOnWheel: false,
                    watchExternalChanges: true,
                    formulaMode: false
                });
            }

            // Tambahkan validasi form
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const numericInstance = AutoNumeric.getAutoNumericElement(hargaInput);
                    if (numericInstance) {
                        // Dapatkan nilai tanpa format
                        const unformattedValue = numericInstance.getNumericString();
                        // Update nilai input sebelum submit
                        hargaInput.value = unformattedValue;
                    }
                });
            }
        });
    </script>

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

    <!--   Core JS Files   -->
    <script src="{{ asset('js-internal/AutoNumeric-4.5.4.js') }}"></script>
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