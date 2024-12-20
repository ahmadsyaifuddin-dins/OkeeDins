<!-- resources/views/layouts/admin.blade.php -->

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('cleopatra/src/img/fav.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('cleopatra/dist/css/style.css') }}">
    <title>Welcome Administrator</title>
</head>

<body class="bg-gray-100">

    <!-- start navbar -->
    <div
        class="md:fixed md:w-full md:top-0 md:z-20 flex flex-row flex-wrap items-center bg-white p-6 border-b border-gray-300">
        <!-- logo -->
        <div class="flex-none w-56 flex flex-row items-center">
            <img src="{{ asset('cleopatra/src/img/fav.png') }}" class="w-10 flex-none">
            <strong class="capitalize ml-1 flex-1">Food Fusion Administrator</strong>
            <button id="sliderBtn" class="flex-none text-right text-gray-900 hidden md:block">
                <i class="fad fa-list-ul"></i>
            </button>
        </div>
        <!-- end logo -->

        <!-- navbar content toggle -->
        <button id="navbarToggle" class="hidden md:block md:fixed right-0 mr-6">
            <i class="fad fa-chevron-double-down"></i>
        </button>
        <!-- end navbar content toggle -->

        <!-- navbar content -->
        <div id="navbar"
            class="animated md:hidden md:fixed md:top-0 md:w-full md:left-0 md:mt-16 md:border-t md:border-b md:border-gray-200 md:p-10 md:bg-white flex-1 pl-3 flex flex-row flex-wrap justify-between items-center md:flex-col md:items-center">
            <!-- left -->
            <div
                class="text-gray-600 md:w-full md:flex md:flex-row md:justify-evenly md:pb-10 md:mb-10 md:border-b md:border-gray-200">
                <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i
                        class="fad fa-envelope-open-text"></i></a>
                <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i
                        class="fad fa-comments-alt"></i></a>
                <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i
                        class="fad fa-check-circle"></i></a>
                <a class="mr-2 transition duration-500 ease-in-out hover:text-gray-900" href="#" title="email"><i
                        class="fad fa-calendar-exclamation"></i></a>
            </div>
            <!-- end left -->

            <!-- right -->
            <div class="flex flex-row-reverse items-center">
                <!-- user -->
                <div class="dropdown relative md:static">
                    <button class="menu-btn focus:outline-none focus:shadow-outline flex flex-wrap items-center">
                        <div class="w-8 h-8 overflow-hidden rounded-full">
                            <img class="w-full h-full object-cover" src="{{ asset('cleopatra/src/img/user.svg') }}">
                        </div>
                        <div class="ml-2 capitalize flex ">
                            <h1 class="text-sm text-gray-800 font-semibold m-0 p-0 leading-none">Administrator</h1>
                            <i class="fad fa-chevron-down ml-2 text-xs leading-none"></i>
                        </div>
                    </button>
                    <div
                        class="text-gray-500 menu hidden md:mt-10 md:w-full rounded bg-white shadow-md absolute z-20 right-0 w-40 mt-5 py-2 animated faster">
                        <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                            href="#">
                            <i class="fad fa-user-edit text-xs mr-1"></i>
                            edit my profile
                        </a>
                        <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                            href="#">
                            <i class="fad fa-inbox-in text-xs mr-1"></i>
                            my inbox
                        </a>
                        <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                            href="#">
                            <i class="fad fa-badge-check text-xs mr-1"></i>
                            tasks
                        </a>

                        <hr>
                        <a class="px-4 py-2 block capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 hover:text-gray-900 transition-all duration-300 ease-in-out"
                            href="#">
                            <i class="fad fa-user-times text-xs mr-1"></i>
                            log out
                        </a>
                    </div>
                </div>
                <!-- end user -->
            </div>
            <!-- end right -->
        </div>
        <!-- end navbar content -->
    </div>
    <!-- end navbar -->

    <!-- start wrapper -->
    <div class="h-screen flex flex-row flex-wrap">
        <!-- start sidebar -->
        <div id="sideBar"
            class="relative flex flex-col flex-wrap bg-white border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animated faster">
            <div class="flex flex-col">
                <div class="text-right hidden md:block mb-4">
                    <button id="sideBarHideBtn">
                        <i class="fad fa-times-circle"></i>
                    </button>
                </div>
                <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">homes</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-chart-pie text-xs mr-2"></i>
                    Analytics dashboard
                </a>
                <a href="#"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-shopping-cart text-xs mr-2"></i>
                    ecommerce dashboard
                </a>
                <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">Data Center</p>
                <a href="./email.html"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-user text-xs mr-2"></i>
                    Pengguna
                </a>

                <a href="#"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-shopping-cart text-xs mr-2"></i>
                    {{-- <i class="fad fa-shield-check text-xs mr-2"></i> --}}
                    Pesanan
                </a>
                <a href="#"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-calendar-edit text-xs mr-2"></i>
                    Kalendar
                </a>
                <a href="#"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-wallet text-xs mr-2"></i>
                    {{-- <i class="fad fa-file-invoice-dollar text-xs mr-2"></i> --}}
                    Pembayaran
                </a>
                <a href="#"
                    class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                    <i class="fad fa-folder-open text-xs mr-2"></i>
                    file manager
                </a>

            </div>
        </div>
        <!-- end sidebar -->

        <!-- start content -->
        <div class="bg-gray-100 flex-1 p-6 md:mt-16">
            <div class="mt-4">
                @yield('content')
            </div>
            <!-- General Report -->
            <div class="grid grid-cols-4 gap-6 xl:grid-cols-1">

                <!-- Card for Total Products -->
                <div class="report-card">
                    <br>
                    <div class="card">
                        <div class="card-body flex flex-col">
                            <div class="flex flex-row justify-between items-center">
                                <div class="h6 text-yellow-600 fad fa-sitemap"></div>
                                <span class="rounded-full text-white badge bg-teal-400 text-xs">72%<i
                                        class="fal fa-chevron-up ml-1"></i></span>
                            </div>
                            <div class="mt-8">
                                <h1 class="h5 num-4">1,500</h1>
                                <p>total Products</p>
                            </div>
                        </div>
                    </div>
                    <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
                </div>

                <!-- card sales-->
                <div class="report-card">
                    <br>
                    <div class="card">
                        <div class="card-body flex flex-col">
                            <div class="flex flex-row justify-between items-center">
                                <div class="h6 text-indigo-700 fad fa-shopping-cart"></div>
                                <span class="rounded-full text-white badge bg-teal-400 text-xs">12%<i
                                        class="fal fa-chevron-up ml-1"></i></span>
                            </div>
                            <div class="mt-8">
                                <h1 class="h5 num-4"></h1>
                                <p>items sales</p>
                            </div>

                        </div>

                    </div>

                    <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
                </div>
                <!-- end card -->
                <!-- Additional cards can be added here -->

                <!-- Card for New Orders -->
                <div class="report-card">
                    <br>
                    <div class="card">
                        <div class="card-body flex flex-col">
                            <div class="flex flex-row justify-between items-center">
                                <div class="h6 text-red-700 fad fa-store"></div>
                                <span class="rounded-full text-white badge bg-red-400 text-xs">6%<i
                                        class="fal fa-chevron-down ml-1"></i></span>
                            </div>
                            <div class="mt-8">
                                <h1 class="h5 num-4">567</h1>
                                <p>new orders</p>
                            </div>

                        </div>

                    </div>

                    <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
                </div>

                <!-- Card for New Visitors -->
                <div class="report-card">
                    <br>
                    <div class="card">
                        <div class="card-body flex flex-col">
                            <div class="flex flex-row justify-between items-center">
                                <div class="h6 text-green-700 fad fa-users"></div>
                                <span class="rounded-full text-white badge bg-teal-400 text-xs">150%<i
                                        class="fal fa-chevron-up ml-1"></i></span>
                            </div>
                            <div class="mt-8">
                                <h1 class="h5 num-4">2,345</h1>
                                <p>new Visitors</p>
                            </div>
                        </div>
                    </div>
                    <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
                </div>
                <!-- End Card -->
                <!-- End General Report -->

                <!-- Additional content sections can be added here -->
            </div>
            <!-- end content -->
        </div>
        <!-- end wrapper -->




        <!-- Additional content sections can be added here -->

        <!-- script -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="{{ asset('cleopatra/dist/js/scripts.js') }}"></script>
        <!-- end script -->

</body>

</html>
