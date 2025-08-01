@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Tampilkan pesan sukses jika ada -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Smartphone Terbaru</h1>
        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
            🛒 Lihat Keranjang
        </a>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse ($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm rounded">
                    <!-- Menampilkan gambar produk jika ada -->
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top rounded-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top rounded-top" alt="No Image">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->brand }}</p>
                        <p class="card-text text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="card-text"><small>Stok: {{ $product->stock }}</small></p>
                    </div>
                    <div class="card-footer text-center">
                        <!-- Form untuk menambah produk ke keranjang -->
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success w-100">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Tidak ada produk tersedia.</p>
        @endforelse
    </div>
</div>
@endsection
