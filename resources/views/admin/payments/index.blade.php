@extends('layouts-admin.function')
@extends('layouts.app-admin')

@section('title', 'Kelola Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Daftar Pembayaran Transfer</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7">Order ID</th>
                                    <th class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7 ps-2">Pelanggan</th>
                                    <th class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7 ps-2">Total</th>
                                    <th class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7 ps-2">Tanggal Order</th>
                                    <th class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7 ps-2">Bukti Transfer</th>
                                    <th class="text-uppercase text-black-th text-xs font-weight-bolder opacity-7 ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingPayments as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">#{{ $order->id }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $order->user->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $order->created_at->format('d M Y H:i') }}</p>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#proofModal{{ $order->id }}">
                                                <i class="material-icons" style="font-size: 20px; vertical-align: middle;">image</i> Lihat Bukti
                                            </button>

                                            <!-- Modal Bukti Transfer -->
                                            <div class="modal fade" id="proofModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Bukti Transfer - Order #{{ $order->id }}</h5>
                                                            <button type="button" class="btn-close p-3" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="material-icons text-dark" style="font-size: 30px;">close</i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('uploads/payment_proofs/' . $order->payment_proof) }}" 
                                                                 class="img-fluid" 
                                                                 alt="Bukti Transfer">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <form action="{{ route('admin.payments.verify', $order->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')">
                                                                    <i class="material-icons" style="font-size: 20px; vertical-align: middle;" >check_circle</i> Verifikasi
                                                                </button>
                                                            </form>

                                                            <form action="{{ route('admin.payments.reject', $order->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">
                                                                    <i class="material-icons" style="font-size: 20px; vertical-align: middle;">cancel</i> Tolak
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <form action="{{ route('admin.payments.verify', $order->id) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')">
                                                        <i class="material-icons" style="font-size: 20px; vertical-align: middle;">check_circle</i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.payments.reject', $order->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">
                                                        <i class="material-icons" style="font-size: 20px; vertical-align: middle;">cancel</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Tidak ada pembayaran yang menunggu konfirmasi</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mx-3 mt-3">
                        {{ $pendingPayments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
