<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $transaction->id }}</title>
</head>
<body>
    <h1>Invoice Transaksi #{{ $transaction->id }}</h1>
    <p>Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
    <p>Status: {{ $transaction->status }}</p>
    <hr>
    <ul>
        @foreach ($transaction->items as $item)
            <li>
                {{ $item->product?->name ?? $item->product_name ?? 'Produk tidak ditemukan' }}
                ({{ $item->quantity }}x) -
                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
            </li>
        @endforeach
    </ul>
</body>
</html>
