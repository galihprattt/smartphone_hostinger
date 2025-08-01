<h2>Instruksi Pembayaran</h2>
<p>Silakan transfer ke:</p>
<ul>
    <li><strong>Bank:</strong> BCA</li>
    <li><strong>No Rekening:</strong> 123456789</li>
    <li><strong>Atas Nama:</strong> Toko Smartphone</li>
</ul>

<p><strong>Total yang harus ditransfer:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>

<a href="{{ route('payment.proof.form', $transaction->id) }}">Saya Sudah Transfer</a>
