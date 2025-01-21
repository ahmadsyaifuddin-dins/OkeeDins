@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">My Wishlist</h1>
            <a href="{{ route('home.index') }}" class="btn btn-outline-custom btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($wishlists->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($wishlists as $wishlist)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $wishlist->produk->gambar) }}"
                                    alt="{{ $wishlist->produk->nama }}" class="card-img-top object-fit-cover"
                                    style="height: 200px;">

                                @if ($wishlist->produk->diskon > 0)
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-danger">{{ $wishlist->produk->diskon }}% OFF</span>
                                    </div>
                                @endif

                                <form action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST"
                                    class="position-absolute top-0 end-0 m-2"
                                    onsubmit="return confirm('Are you sure you want to remove this item from your wishlist?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm shadow-sm">
                                        <i class="bi bi-heart-fill text-danger"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title h6 mb-2">{{ $wishlist->produk->nama_produk }}</h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="h5 text-custom mb-0">Rp
                                            {{ number_format($wishlist->produk->harga_diskon, 0, ',', '.') }}</span>
                                        @if ($wishlist->produk->diskon > 0)
                                            <br>
                                            <small class="text-muted text-decoration-line-through">
                                                Rp {{ number_format($wishlist->produk->harga, 0, ',', '.') }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="rating">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="ms-1">4.5</span>
                                    </div>
                                </div>

                                <form
                                    onsubmit="return addToCart(event, this, {{ $wishlist->produk->id }}, {{ $wishlist->produk->harga_diskon }})"
                                    class="d-grid">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $wishlist->produk->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="price" value="{{ $wishlist->produk->harga_diskon }}">
                                    <input type="hidden" name="amount" value="{{ $wishlist->produk->harga_diskon }}">
                                    <button type="submit" class="btn btn-custom">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-heart display-1 text-muted mb-3"></i>
                <h2 class="h4 text-muted">Your wishlist is empty</h2>
                <p class="text-muted mb-4">Browse our products and add your favorites to the wishlist!</p>
                <a href="{{ route('home.index') }}" class="btn btn-custom">
                    <i class="bi bi-shop me-1"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>

    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .card-img-top {
            border-top-left-radius: calc(0.375rem - 1px);
            border-top-right-radius: calc(0.375rem - 1px);
        }
    </style>
@endsection

@push('scripts')
    <script>
        function addToCart(event, form, productId, price) {
            event.preventDefault();

            const button = form.querySelector('button');
            const originalText = button.innerHTML;

            // Disable button and show loading state
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

            const formData = {
                produk_id: productId,
                quantity: 1,
                price: price,
                amount: price,
                _token: form.querySelector('input[name="_token"]').value
            };

            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count in navbar if it exists
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cartCount;
                        }

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        throw new Error(data.message || 'Failed to add product to cart');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Something went wrong!'
                    });
                })
                .finally(() => {
                    // Restore button state
                    button.disabled = false;
                    button.innerHTML = originalText;
                });

            return false;
        }
    </script>
@endpush
