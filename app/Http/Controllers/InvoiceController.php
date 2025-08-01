<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use PDF;

class InvoiceController extends Controller
{
    public function download(Transaction $transaction)
    {
        $transaction->load('items.product'); // ✅ Sudah benar eager load

        $pdf = PDF::loadView('invoices.download', compact('transaction'));
        return $pdf->download('invoice-transaksi-' . $transaction->id . '.pdf');
    }

    public function confirm(Transaction $transaction)
    {
        $transaction->load('items.product'); // ✅ Eager load relasi

        return view('invoices.confirm', compact('transaction'));
    }
}
