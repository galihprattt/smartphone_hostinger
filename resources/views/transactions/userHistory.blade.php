@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Riwayat Transaksi</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Total</th>
                    <th>Alamat</th>
                    <th>Detail Produk</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                        <td>{{ $transaction->address }}</td>
                        <td>
                            <ul>
                                @foreach ($transaction->items as $item)
                                    <li>{{ $item->product_name }} ({{ $item->quantity }}x) - Rp {{ number_format($item->price, 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $transaction->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
