@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">

            <!-- Statistik Ulasan -->
            <div class="col-12">
                <div class="row">
                    <!-- Grafik Trend Ulasan -->
                    <div class="col-xl-8 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pb-0">
                                <h6 class="mb-0">Trend Ulasan Bulanan</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-ulasan" class="chart-canvas" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Produk -->
                    <div class="col-xl-4 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pb-0">
                                <h6 class="mb-0">Top Produk Rating Tertinggi</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="timeline timeline-one-side">
                                    @foreach ($topProduk ?? [] as $produk)
                                        <div class="timeline-block mb-3">
                                            <div class="timeline-content">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . $produk->gambar) }}" class="avatar avatar-sm me-2">
                                                    <div>
                                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $produk->nama_produk }}</h6>
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-warning me-1">{{ number_format($produk->rating_rata_rata, 1) }}</span>
                                                            <div class="rating-stars">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i class="material-symbols-rounded"
                                                                        style="font-size: 14px; color: {{ $i <= $produk->rating_rata_rata ? '#ffd700' : '#ccc' }}">
                                                                        star
                                                                    </i>
                                                                @endfor
                                                            </div>
                                                            <span class="text-sm text-muted ms-1">({{ $produk->jumlah_ulasan }} ulasan)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total dan Rata-rata -->
                    <div class="col-xl-4 col-sm-12 mb-4">
                        <div class="row">
                            <!-- Total Ulasan -->
                            <div class="col-xl-12 col-sm-6 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Ulasan</p>
                                                    <h5 class="font-weight-bolder mb-0">
                                                        {{ $statistik['total_ulasan'] }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">reviews</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rata-rata Rating -->
                            <div class="col-xl-12 col-sm-6 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata-rata Rating</p>
                                                    <h5 class="font-weight-bolder mb-0">
                                                        {{ number_format($statistik['rata_rata_rating'], 1) }}
                                                        <small class="text-success text-sm font-weight-bolder">/ 5</small>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                                    <i class="material-symbols-rounded opacity-10" aria-hidden="true">star</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribusi Rating -->
                    <div class="col-xl-8 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <p class="text-sm mb-2 text-capitalize font-weight-bold">Distribusi Rating</p>
                                <div class="rating-stats">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2 rating-star" style="min-width: 25px">{{ $i }}★</span>
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-gradient-dark"
                                                    style="width: {{ $statistik['total_ulasan'] > 0 ? ($statistik['rating_' . $i] / $statistik['total_ulasan']) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                            <small class="ms-2 rating-count" style="min-width: 20px">{{ $statistik['rating_' . $i] }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Ulasan -->
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Daftar Ulasan</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-black-th text-sm font-weight-bolder opacity-7">
                                            Pengguna</th>
                                        <th
                                            class="text-uppercase text-black-th text-sm font-weight-bolder opacity-7 ps-2">
                                            Produk</th>
                                        <th
                                            class="text-uppercase text-black-th text-sm font-weight-bolder opacity-7 ps-2">
                                            Rating</th>
                                        <th
                                            class="text-uppercase text-black-th text-sm font-weight-bolder opacity-7 ps-2">
                                            Ulasan</th>
                                        <th
                                            class="text-uppercase text-black-th text-sm font-weight-bolder opacity-7 ps-2">
                                            Tanggal</th>
                                        <th
                                            class="text-uppercase text-black-th text-sm font-weight-bolder opacity-7 ps-2">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ulasan as $review)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="{{ asset('storage/' . ($review->foto_pengguna ?? 'user.svg')) }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $review->nama_pengguna }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . $review->foto_produk) }}"
                                                        class="avatar avatar-sm me-3 border-radius-lg">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $review->nama_produk }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="material-symbols-rounded"
                                                            style="font-size: 16px; color: {{ $i <= $review->rating ? '#ffd700' : '#ccc' }}">
                                                            star
                                                        </i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ Str::limit($review->ulasan, 50) }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y H:i') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <a href="{{ route('admin.ulasan.show', $review->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="material-symbols-rounded"
                                                            style="font-size: 20px; vertical-align: middle;">visibility</i>
                                                    </a>
                                                    <form action="{{ route('admin.ulasan.destroy', $review->id) }}"
                                                        method="POST" class="d-inline-block m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                                            <i class="material-symbols-rounded"
                                                                style="font-size: 20px; vertical-align: middle;">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .rating-stats {
            max-width: 100%;
        }

        .rating-star {
            color: #f0ad4e;
            font-weight: bold;
        }

        .progress {
            background-color: #f0f2f5;
            border-radius: 4px;
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .rating-count {
            color: #666;
        }

        @media (max-width: 576px) {
            .rating-stats {
                padding: 0 5px;
            }

            .progress {
                height: 6px !important;
            }
        }
    </style>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById("chart-ulasan").getContext("2d");
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: {!! json_encode($statistik['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
                    datasets: [{
                        label: "Jumlah Ulasan",
                        tension: 0.4,
                        borderWidth: 2,
                        borderColor: "#D32F2F",
                        backgroundColor: "rgba(211, 47, 47, 0.1)",
                        fill: true,
                        data: {!! json_encode($statistik['data'] ?? [10, 15, 12, 18, 20, 25]) !!},
                        maxBarThickness: 6
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5],
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#9ca2b7'
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#9ca2b7',
                                padding: 10
                            }
                        },
                    },
                },
            });
        </script>
    @endpush
@endsection
