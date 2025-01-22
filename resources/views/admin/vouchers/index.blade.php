@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Voucher</h1>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Voucher
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Min. Pembelian</th>
                            <th>Penggunaan</th>
                            <th>Status</th>
                            <th>Berlaku Sampai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $voucher)
                        <tr>
                            <td>{{ $voucher->code }}</td>
                            <td>{{ $voucher->name }}</td>
                            <td>{{ $voucher->type === 'fixed' ? 'Nominal' : 'Persentase' }}</td>
                            <td>
                                @if($voucher->type === 'fixed')
                                    Rp {{ number_format($voucher->value, 0, ',', '.') }}
                                @else
                                    {{ $voucher->value }}%
                                @endif
                            </td>
                            <td>Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</td>
                            <td>
                                @if($voucher->max_uses)
                                    {{ $voucher->used_count }}/{{ $voucher->max_uses }}
                                @else
                                    {{ $voucher->used_count }}/∞
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $voucher->is_active ? 'success' : 'danger' }}">
                                    {{ $voucher->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>{{ $voucher->valid_until->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $vouchers->links() }}
        </div>
    </div>
</div>
@endsection
