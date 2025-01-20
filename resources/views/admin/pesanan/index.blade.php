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
                                            <td class="d-flex gap-2 align-items-center">
                                                <a href="{{ route('admin.pesanan.show', $order->order_number) }}"
                                                    class="btn btn-dark btn-sm">Detail</a>

                                                @if ($order->payment_method === 'Cash on Delivery')
                                                    <form action="{{ route('admin.pesanan.updateStatus', $order->id) }}"
                                                        method="POST" id="statusForm-{{ $order->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status"
                                                            class="form-select form-select-sm status-select status-{{ $order->status }}"
                                                            onchange="if(confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) { this.form.submit(); }">
                                                            <option value="pending"
                                                                {{ $order->status === 'pending' ? 'selected' : '' }}
                                                                class="status-bg-pending">Pending</option>
                                                            <option value="confirmed"
                                                                {{ $order->status === 'confirmed' ? 'selected' : '' }}
                                                                class="status-bg-confirmed">Konfirmasi</option>
                                                            <option value="processing"
                                                                {{ $order->status === 'processing' ? 'selected' : '' }}
                                                                class="status-bg-processing">Proses</option>
                                                            <option value="delivered"
                                                                {{ $order->status === 'delivered' ? 'selected' : '' }}
                                                                class="status-bg-delivery">Kirim</option>
                                                            <option value="completed"
                                                                {{ $order->status === 'completed' ? 'selected' : '' }}
                                                                class="status-bg-completed">Selesai</option>
                                                        </select>
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
                                                    method="POST" class="mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                                        Hapus
                                                    </button>
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


    .status-select {
        min-width: 120px;
        height: 31px;
        padding: 2px 8px;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        border: 1px solid #d2d6da;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    /* Warna background untuk select berdasarkan status aktif */
    .status-pending {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }

    .status-confirmed {
        background-color: #e0f2fe !important;
        color: #075985 !important;
    }

    .status-processing {
        background-color: #f3e8ff !important;
        color: #6b21a8 !important;
    }

    .status-delivered {
        background-color: #dcfce7 !important;
        color: #166534 !important;
    }

    .status-completed {
        background-color: #bbf7d0 !important;
        color: #15803d !important;
    }

    /* Warna background untuk options */
    .status-select option.status-bg-pending {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }

    .status-select option.status-bg-confirmed {
        background-color: #e0f2fe !important;
        color: #075985 !important;
    }

    .status-select option.status-bg-processing {
        background-color: #f3e8ff !important;
        color: #6b21a8 !important;
    }

    .status-select option.status-bg-delivery {
        background-color: #dcfce7 !important;
        color: #166534 !important;
    }

    .status-select option.status-bg-completed {
        background-color: #bbf7d0 !important;
        color: #15803d !important;
    }

    .status-select:focus {
        border-color: #344767;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(52, 71, 103, 0.25);
    }

    td.d-flex {
        padding: 0.5rem !important;
    }

    td.d-flex form {
        margin-right: 0.5rem;
    }

    td.d-flex form:last-child {
        margin-right: 0;
    }
</style>

<style>

</style>
