@extends('layouts.app-admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3">Detail Ulasan</h6>
                            <a href="{{ route('admin.ulasan.index') }}" class="btn btn-info me-3">
                                <i class="material-symbols-rounded">arrow_back</i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Info Pengguna -->
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('storage/' . ($ulasan->foto_pengguna ?? 'user.svg')) }}" 
                             class="avatar avatar-xl me-3 border-radius-lg">
                        <div>
                            <h5 class="mb-0">{{ $ulasan->nama_pengguna }}</h5>
                            <p class="text-sm text-secondary mb-0">{{ $ulasan->email }}</p>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($ulasan->created_at)->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>

                    <!-- Info Produk -->
                    <div class="border rounded p-3 mb-4">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $ulasan->foto_produk) }}" 
                                 class="avatar avatar-lg me-3 border-radius-lg">
                            <div>
                                <h6 class="mb-0">{{ $ulasan->nama_produk }}</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Rating dan Ulasan -->
                    <div class="border rounded p-3">
                        <div class="mb-3">
                            <h6 class="text-sm mb-2">Rating</h6>
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="material-symbols-rounded" style="font-size: 24px; color: {{ $i <= $ulasan->rating ? '#ffd700' : '#ccc' }}">
                                        star
                                    </i>
                                @endfor
                                <span class="ms-2 text-sm">{{ $ulasan->rating }}/5</span>
                            </div>
                        </div>

                        <div>
                            <h6 class="text-sm mb-2">Ulasan</h6>
                            <p class="text-sm mb-0">{{ $ulasan->ulasan }}</p>
                        </div>
                    </div>

                    <!-- Tombol Hapus -->
                    <div class="text-end mt-4">
                        <form action="{{ route('admin.ulasan.destroy', $ulasan->id) }}" 
                              method="POST" 
                              style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                <i class="material-symbols-rounded">delete</i>
                                Hapus Ulasan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
