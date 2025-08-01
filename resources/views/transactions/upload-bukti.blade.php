@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Upload Bukti Transfer untuk Transaksi #{{ $transaction->id }}</h1>

    <!-- Tampilkan pesan sukses atau error -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Form Upload Bukti Transfer -->
    <form action="{{ route('transactions.upload-bukti', $transaction->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="proof">Pilih Bukti Transfer</label>
            <input type="file" class="form-control" id="proof" name="proof" required>
            @error('proof')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Bukti Transfer</button>
    </form>
</div>
@endsection
