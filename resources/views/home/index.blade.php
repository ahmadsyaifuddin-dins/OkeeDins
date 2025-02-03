@extends('layouts.app')

@section('content')
    <div class="min-h-screen">

        <!-- Tagline -->
        @include('home.tagline')

        <!-- Banner -->
        @include('home.banner')

        <div class="container mx-auto px-4">

            {{-- @include('home.categories') --}}

            <!-- Kategori Slider -->
            @include('home.kategori_slider')

            <!-- Flash Sale Section -->
            @include('home.flash-sale')

            <!-- Recommended Products -->
            @include('home.produk')

        </div>
    </div>


    <link rel="stylesheet" href="{{ asset('css/swiper-kategori-items/kategori_items.css') }}">
@endsection
