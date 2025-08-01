@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Keranjang Belanja</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (!empty($cart))
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach ($cart as $id => $item)
                                @php $total = $item['price'] * $item['quantity']; $grandTotal += $total; @endphp
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    <td>
                                        <!-- Hapus item dari keranjang -->
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h4>Total Belanja: <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></h4>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">Lanjut ke Checkout</a>
                </div>
            </div>
        </div>
    @else
        <p class="text-center">Keranjang belanja kosong.</p>
    @endif
</div>
@endsection
