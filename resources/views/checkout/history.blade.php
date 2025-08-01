@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Transaksi</h1>

    <!-- Menampilkan pesan sukses jika ada -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($transactions->isEmpty())
        <p>Kamu belum memiliki transaksi.</p>
    @else
        @foreach($transactions as $transaction)
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Transaksi #{{ $transaction->id }}</strong><br>
                    Total: Rp {{ number_format($transaction->total_price, 0, ',', '.') }}<br>
                    Alamat: {{ $transaction->address }}<br>
                    <strong>Status Pembayaran:</strong> 
                    <!-- Menampilkan status pembayaran dengan badge -->
                    <span class="badge 
                        @if ($transaction->status === 'belum dibayar')
                            bg-warning text-dark
                        @elseif ($transaction->status === 'menunggu konfirmasi')
                            bg-info text-dark
                        @elseif ($transaction->status === 'dibayar')
                            bg-success
                        @elseif ($transaction->status === 'verifikasi')
                            bg-primary text-dark
                        @else
                            bg-secondary
                        @endif">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach($transaction->items as $item)
                            <li>{{ $item->product_name }} ({{ $item->quantity }}x) - Rp {{ number_format($item->price, 0, ',', '.') }}</li>
                        @endforeach
                    </ul>

                    @if($transaction->notes)
                        <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
                    @endif

                    {{-- Tombol bayar hanya jika belum dibayar --}}
                    @if(!$transaction->is_paid)
                        <form action="{{ route('payment.simulate', $transaction->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Bayar</button>
                        </form>
                    @else
                        <div class="alert alert-success mt-2 mb-0 py-1 px-2" role="alert">
                            Sudah Dibayar
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
