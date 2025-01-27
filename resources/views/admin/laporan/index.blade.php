@extends('layouts.app-admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <form action="" method="GET" class="row align-items-center" id="exportForm">
                        <div class="col-md-4">
                            <div class="input-group input-group-static mb-0">
                                <label>Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-static mb-0">
                                <label>Tanggal Akhir</label>
                                <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group">
                                <button type="button" class="btn btn-dark" onclick="exportExcel()">
                                    <i class="material-symbols-rounded">table_view</i>
                                    Excel
                                </button>
                                <button type="button" class="btn btn-dark" onclick="exportPDF()">
                                    <i class="material-symbols-rounded">picture_as_pdf</i>
                                    PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Statistik Umum -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pendapatan</p>
                                <h5 class="font-weight-bolder mb-0">
                                    Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                <i class="material-symbols-rounded opacity-10" aria-hidden="true">payments</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Pesanan</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($statistik['total_pesanan'], 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                <i class="material-symbols-rounded opacity-10" aria-hidden="true">receipt_long</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Produk</p>
                                <h5 class="font-weight-bolder mb-0">
                                    {{ number_format($statistik['total_produk'], 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow text-center border-radius-md">
                                <i class="material-symbols-rounded opacity-10" aria-hidden="true">restaurant_menu</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Rating Rata-rata</p>
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

        <!-- Grafik Pendapatan -->
        <div class="col-xl-8 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header p-3 pb-0">
                    <h6 class="mb-0">Pendapatan Bulanan</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-pendapatan" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Terlaris -->
        <div class="col-xl-4 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header p-3 pb-0">
                    <h6 class="mb-0">Produk Terlaris</h6>
                </div>
                <div class="card-body p-3">
                    <div class="timeline timeline-one-side">
                        @foreach($produkTerlaris as $produk)
                        <div class="timeline-block mb-3">
                            <div class="timeline-content">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" class="avatar avatar-sm me-2">
                                    <div class="flex-grow-1">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $produk->nama_produk }}</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="text-success me-1">{{ number_format($produk->total_terjual) }}</span>
                                            <span class="text-sm text-muted">terjual</span>
                                        </div>
                                    </div>
                                    <span class="text-sm text-dark ms-2">
                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById("chart-pendapatan").getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: "Pendapatan",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#D32F2F",
                data: {!! json_encode($data) !!},
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
            interaction: {
                intersect: false,
                mode: 'index',
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
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        },
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
<script>
    function exportExcel() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        window.location.href = `/admin/laporan/export-excel?start_date=${startDate}&end_date=${endDate}`;
    }

    function exportPDF() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        window.location.href = `/admin/laporan/export-pdf?start_date=${startDate}&end_date=${endDate}`;
    }
</script>
@endpush
@endsection
