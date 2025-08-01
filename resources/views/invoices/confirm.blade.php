<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Unduh Invoice</title>
</head>
<body>
    <h1>Konfirmasi Unduh Invoice</h1>
    <p>Transaksi #{{ $transaction->id }}</p>
    <p>Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
    <p>Status: {{ $transaction->status }}</p>

    <hr>
    <p>Apakah Anda yakin ingin mengunduh invoice ini?</p>

    <form action="{{ route('invoice.download', $transaction->id) }}" method="GET" style="display:inline;">
        <button type="submit">Download Sekarang</button>
    </form>

    <a href="{{ url('/transactions') }}">
        <button type="button">Kembali</button>
    </a>
</body>
</html>
