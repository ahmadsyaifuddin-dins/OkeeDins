@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">Games Zone</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Ping Pong Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105">
                    <div class="relative">
                        <img src="{{ asset('images/logopingpong.png') }}" alt="Ping Pong Game"
                            class="w-full h-48 object-contain">
                        <div class="absolute top-0 right-0 bg-blue-500 text-white px-3 py-1 rounded-bl-lg">
                            Game
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Ping Pong</h3>
                        <p class="text-gray-600 mb-4">Mainkan game klasik Ping Pong dan dapatkan voucher menarik!</p>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                            <span class="text-sm text-gray-500">Hadiah: Voucher Diskon</span>
                            <a href="https://ahmadsyaifuddin-dins.github.io/gamePingPong.html"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 hover:animate-[glitch_0.3s_ease-in-out_infinite]">
                                <i class="bi bi-controller mr-2"></i>
                                Main Sekarang
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Gunting Batu Kertas Card -->
                <div
                    class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105">
                    <div class="relative">
                        <img src="{{ asset('images/rps.jpg') }}" alt="Rock Paper Scissors Game"
                            class="w-full h-48 object-contain">
                        <div class="absolute top-0 right-0 bg-green-500 text-white px-3 py-1 rounded-bl-lg">
                            Game
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Gunting Batu Kertas</h3>
                        <p class="text-gray-600 mb-4">Tantang komputer dalam permainan Gunting Batu Kertas!</p>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                            <span class="text-sm text-gray-500">Hadiah: Voucher Diskon</span>
                            <a href="{{ route('games.rock-paper-scissors') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 hover:animate-[glitch_0.3s_ease-in-out_infinite]">
                                <i class="bi bi-controller mr-2"></i>
                                Main Sekarang
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tic Tac Toe Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105">
                    <div class="relative">
                        <img src="{{ asset('images/tictactoe.png') }}" alt="Tic Tac Toe Game"
                            class="w-full h-48 object-contain">
                        <div class="absolute top-0 right-0 bg-purple-500 text-white px-3 py-1 rounded-bl-lg">
                            Game
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tic Tac Toe</h3>
                        <p class="text-gray-600 mb-4">Bermain Tic Tac Toe melawan komputer dan menangkan hadiah menarik!</p>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                            <span class="text-sm text-gray-500">Hadiah: Voucher Diskon</span>
                            <a href="{{ route('games.tictactoe') }}"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 hover:animate-[glitch_0.3s_ease-in-out_infinite]">
                                <i class="bi bi-controller mr-2"></i>
                                Main Sekarang
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>

    <style>
        @keyframes glitch {
            0% {
                transform: translate(0);
                filter: hue-rotate(0deg);
            }

            10% {
                transform: translate(-2px, 2px);
                filter: hue-rotate(90deg);
            }

            20% {
                transform: translate(2px, -2px);
                filter: hue-rotate(180deg);
            }

            30% {
                transform: translate(-2px, 2px);
                filter: hue-rotate(270deg);
            }

            40% {
                transform: translate(2px, -2px);
                filter: hue-rotate(360deg);
            }

            50% {
                transform: translate(-2px, 2px);
                filter: hue-rotate(0deg);
            }

            100% {
                transform: translate(0);
                filter: hue-rotate(0deg);
            }
        }
    </style>
@endsection
