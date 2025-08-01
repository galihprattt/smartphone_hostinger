<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function show(Transaction $transaction)
    {
        return view('payments.info', compact('transaction'));
    }

    public function uploadForm(Transaction $transaction)
    {
        return view('payments.upload', compact('transaction'));
    }

    public function upload(Request $request, Transaction $transaction)
    {
        // Validasi file yang diupload
        $request->validate([
            'proof' => 'required|image|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max size
        ]);

        // Proses unggah file bukti transfer
        $file = $request->file('proof')->store('public/payment_proofs');

        // Simpan path bukti transfer ke transaksi
        $transaction->payment_proof = $file;
        $transaction->status = 'menunggu konfirmasi'; // Update status jika diperlukan
        $transaction->save();

        // Redirect dengan pesan sukses
        return redirect()->route('transactions.userHistory')->with('success', 'Bukti transfer berhasil dikirim!');
    }
}
