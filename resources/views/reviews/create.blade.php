@extends('layouts.app')

@section('title', 'Beri Ulasan')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Back Button & Title -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-link text-dark p-0 me-3">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <h4 class="mb-0 fw-bold">Beri Ulasan</h4>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Products to Review -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-4">Produk yang Belum Diulas</h5>
                    
                    @foreach($products as $product)
                    <div class="border-bottom mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex mb-3">
                            <img src="{{ asset('storage/' . $product->gambar) }}" 
                                alt="{{ $product->nama_produk }}" 
                                class="rounded" 
                                style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="ms-3">
                                <h6 class="mb-1">{{ $product->nama_produk }}</h6>
                                <p class="text-muted mb-0 small">Order #{{ $order->order_number }}</p>
                            </div>
                        </div>

                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $product->id }}">
                            
                            <!-- Rating -->
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}_{{ $product->id }}" 
                                        class="rating-input" required>
                                    <label for="star{{ $i }}_{{ $product->id }}" class="rating-label">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                    @endfor
                                </div>
                                @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Review Text -->
                            <div class="mb-3">
                                <label for="ulasan_{{ $product->id }}" class="form-label">Ulasan</label>
                                <textarea class="form-control @error('ulasan') is-invalid @enderror" 
                                    id="ulasan_{{ $product->id }}" 
                                    name="ulasan" rows="3" required 
                                    minlength="10" maxlength="255"
                                    placeholder="Bagaimana pengalaman Anda dengan produk ini?">{{ old('ulasan') }}</textarea>
                                <div class="form-text">Minimal 10 karakter, maksimal 255 karakter</div>
                                @error('ulasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-custom">Kirim Ulasan</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.3rem;
    font-size: 1.5rem;
    justify-content: flex-end;
}

.rating-input {
    display: none;
}

.rating-label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.5rem;
}

.rating-label:hover,
.rating-label:hover ~ .rating-label,
.rating-input:checked ~ .rating-label {
    color: #ffc107;
}
</style>
@endsection
