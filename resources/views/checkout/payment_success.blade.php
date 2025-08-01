@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pembayaran Berhasil!</h2>
    <p>Terima kasih! Pembayaran Anda telah berhasil diproses.</p>

    <p>Invoice Transaksi #{{ $transaction->id }}</p>
    <p>Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
    <p>Status: {{ $transaction->status }}</p>

    <a href="{{ route('payment.info', $transaction->id) }}">Lihat Info Pembayaran</a> |
    <a href="{{ route('transactions.history') }}">Lihat Riwayat Transaksi</a>
</div>
@endsection
