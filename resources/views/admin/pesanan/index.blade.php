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
                                                    <form action="{{ route('admin.admin.pesanan.confirm', $order->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pesanan ini?')">Konfirmasi</button>
                                                    </form>
                                                @endif

                                                @if ($order->payment_method === 'Cash on Delivery' && $order->status === 'confirmed')
                                                    <form action="{{ route('admin.admin.pesanan.process', $order->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin memproses pesanan ini?')">Proses</button>
                                                    </form>
                                                @endif

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
                            <div>
                                {{ $pesanan->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
