@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Instruksi Pembayaran</h2>
    <p>Terima kasih telah melakukan pemesanan. Silakan lakukan pembayaran ke rekening berikut:</p>

    <div class="card p-3 mb-3">
        <p><strong>Bank:</strong> BCA</p>
        <p><strong>No. Rekening:</strong> 1234567890</p>
        <p><strong>Atas Nama:</strong> Toko Smartphone</p>
        <p><strong>Total yang harus dibayar:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
    </div>

    <!-- Tombol "Saya Sudah Bayar" diubah menjadi link untuk mengarahkan ke halaman upload bukti transfer -->
    <a href="{{ route('transactions.upload-bukti', $transaction) }}" class="btn btn-success">Saya Sudah Bayar</a>
</div>
@endsection
