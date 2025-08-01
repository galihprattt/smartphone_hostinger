<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi
        $transactions = Transaction::with('items')->latest()->get();
        return view('admin.index', compact('transactions'));
    }
    public function updateStatus(\Illuminate\Http\Request $request, Transaction $transaction)
{
    $request->validate([
        'status' => 'required|string'
    ]);

    $transaction->update([
        'status' => $request->status,
    ]);

    return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
}
}
