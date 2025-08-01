<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // Menampilkan halaman instruksi pembayaran
    public function showPaymentInstructions(Transaction $transaction)
    {
        return view('checkout.payment_instructions', compact('transaction'));
    }

    // Mengonfirmasi bahwa pelanggan sudah transfer
    public function confirmPayment(Transaction $transaction)
    {
        // Update status transaksi menjadi 'menunggu' setelah pembayaran dikonfirmasi
        $transaction->update(['status' => 'menunggu']); 

        // Redirect ke halaman riwayat transaksi dengan pesan
        return redirect()->route('transactions.history')->with('success', 'Pembayaran Anda sedang kami verifikasi.');
    }

    // Menampilkan halaman upload bukti transfer
    public function showUploadBukti(Transaction $transaction)
    {
        // Pastikan hanya transaksi dengan status "proses" yang bisa upload bukti transfer
        if ($transaction->status !== 'proses') {
            return redirect()->route('transactions.history')->with('error', 'Transaksi tidak dapat mengupload bukti transfer.');
        }

        // Menampilkan halaman upload bukti transfer
        return view('transactions.upload-bukti', compact('transaction'));
    }

    // Menangani proses upload bukti transfer
    public function uploadBukti(Request $request, $transactionId)
    {
        // Cari transaksi berdasarkan ID
        $transaction = Transaction::findOrFail($transactionId);
    
        // Validasi bukti transfer
        $validated = $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);
    
        if ($request->hasFile('proof')) {
            // Ambil file bukti transfer
            $file = $request->file('proof');
            $path = $file->storeAs(
                'bukti_transfer',  // Folder tempat file akan disimpan
                'bukti_transfer_' . $transaction->id . '.' . $file->getClientOriginalExtension(),
                'public' // Menyimpan ke storage/app/public agar bisa diakses
            );
    
            // Update transaksi dengan bukti transfer dan ubah status pembayaran
            $transaction->update([
                'payment_proof' => 'storage/' . $path,  // Menyimpan path bukti transfer
                'payment_status' => 'Sudah Dibayar',   // Ubah status pembayaran menjadi "Sudah Dibayar"
                'status' => 'verifikasi',  // Status transaksi menjadi verifikasi
            ]);
        }
    
        // Redirect ke halaman riwayat transaksi dengan pesan sukses
        return redirect()->route('transactions.userHistory')->with('success', 'Bukti transfer berhasil diupload, kami akan segera memverifikasi pembayaran Anda.');
    }
}
