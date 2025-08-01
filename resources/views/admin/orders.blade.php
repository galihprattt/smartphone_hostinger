@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pesanan</h1>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                <td>{{ ucfirst($transaction->status ?? 'proses') }}</td>
                <td>
                    @if ($transaction->payment_proof)
                        <a href="{{ asset($transaction->payment_proof) }}" target="_blank">
                            <img src="{{ asset($transaction->payment_proof) }}" width="100">
                        </a>
                    @else
                        <em>Belum ada</em>
                    @endif
                </td>
                <td>
                    <!-- Form update status -->
                    <form action="{{ route('admin.orders.updateStatus', $transaction->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <div class="d-flex align-items-center gap-2">
                            <label for="status"><strong>Ubah Status:</strong></label>
                            <select name="status" id="status" class="form-select form-select-sm w-auto">
                                <option value="proses" {{ $transaction->status === 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Sudah Dibayar" {{ $transaction->status === 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                                <option value="dikirim" {{ $transaction->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $transaction->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
