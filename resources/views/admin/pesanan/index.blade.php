@extends('layouts.app-admin')

@section('content')
    <div class="container-fluid py-2">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Daftar Pesanan Masuk</h6>
                        </div>
                        <div class="table-responsive p-0">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-black-th">#</th>
                                        <th class="text-black-th">Nomor Pesanan</th>
                                        <th class="text-black-th">Nama Pelanggan</th>
                                        <th class="text-black-th">Tanggal</th>
                                        <th class="text-black-th">Total</th>
                                        <th class="text-black-th">Status</th>
                                        <th class="text-black-th">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pesanan as $key => $order)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>{{ $order->formatted_total }}</td>
                                            <td>
                                                <span class="badge {{ $order->status_badge }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pesanan.show', $order->order_number) }}"
                                                    class="btn btn-primary btn-sm">Detail</a>

                                                @if ($order->payment_method === 'Cash on Delivery' && $order->status === 'pending')
                                                    <form action="{{ route('admin.pesanan.confirm', $order->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pesanan ini?')">Konfirmasi</button>
                                                    </form>
                                                @endif

                                                @if ($order->payment_method === 'Cash on Delivery' && $order->status === 'confirmed')
                                                    <form action="{{ route('admin.pesanan.process', $order->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin memproses pesanan ini?')">Proses</button>
                                                    </form>
                                                @endif

                                                @if ($order->payment_method === 'Cash on Delivery' && $order->status === 'processing')
                                                    <form action="{{ route('admin.pesanan.delivery', $order->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm"
                                                            onclick="return confirm('Proses Pengiriman Pesanan ini?')">Kirim
                                                            Pesanan</button>
                                                    </form>
                                                @endif

                                                {{-- @if ($order->payment_method === 'Cash on Delivery' && $order->status === 'completed')
                                                    <form action="{{ route('admin.pesanan.complete', $order->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm"
                                                            onclick="return confirm('Konfirmasi Pembayaran?')">Konfirmasi
                                                            Pembayaran</button>
                                                    </form>
                                                @endif --}}

                                                <form action="{{ route('admin.pesanan.destroy', $order->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">Hapus</button>
                                                </form>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">Tidak ada pesanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination yang diperbaiki -->
                            <div class="card-footer py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Info showing results -->
                                    <div class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ $pesanan->firstItem() }}</span>
                                        to
                                        <span class="font-medium">{{ $pesanan->lastItem() }}</span>
                                        of
                                        <span class="font-medium">{{ $pesanan->total() }}</span>
                                        results
                                    </div>

                                    <!-- Pagination links -->
                                    <div>
                                        {{ $pesanan->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* Tambahkan CSS berikut ke file CSS Anda */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        color: #344767;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }

    .page-item.active .page-link {
        color: #fff;
        background-color: #344767;
        border-color: #344767;
    }

    .page-link:hover {
        color: #344767;
        background-color: #e9ecef;
    }

    .card-footer {
        background-color: #fff;
        border-top: 1px solid #dee2e6;
    }
</style>
