@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Upload Bukti Transfer</h2>

    <p>Transaksi #{{ $transaction->id }}</p>
    <p>Total: <strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></p>

    <form action="{{ route('payment.proof.upload', $transaction->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="proof">Bukti Transfer (jpg, jpeg, png, pdf, max 2MB)</label><br>
            <input type="file" name="proof" id="proof" required>
            @error('proof')
                <p style="color:red;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Kirim Bukti Transfer</button>
    </form>
</div>
@endsection
