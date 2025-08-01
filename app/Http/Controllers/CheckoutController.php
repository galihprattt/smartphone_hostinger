<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Keranjang belanja kosong.');
        }

        try {
            DB::beginTransaction();

            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            $transaction = Transaction::create([
                'user_id'     => auth()->check() ? auth()->id() : null,
                'name'        => $request->name,
                'address'     => $request->address,
                'notes'       => $request->notes,
                'total_price' => $total,
                'status'      => 'proses',
            ]);

            foreach ($cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_name'   => $item['name'],
                    'price'          => $item['price'],
                    'quantity'       => $item['quantity'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            // Setelah checkout, arahkan ke halaman instruksi pembayaran
            return redirect()->route('transactions.payment', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'Terjadi kesalahan saat menyimpan transaksi.');
        }
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->get();

        return view('checkout.history', compact('transactions'));
    }

    public function adminOrders()
    {
        $transactions = Transaction::with('items')->latest()->get();
        return view('admin.orders', compact('transactions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,dikirim,selesai',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('admin.orders')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function downloadInvoice($id)
    {
        $transaction = Transaction::with('items')->findOrFail($id);

        $pdf = Pdf::loadView('admin.invoice', compact('transaction'));

        return $pdf->download("invoice-{$transaction->id}.pdf");
    }

    public function simulatePayment($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transaction->is_paid = 1;
        $transaction->save();

        return view('checkout.payment_success', compact('transaction'));
    }

    public function userHistory()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->get();

        return view('transactions.userHistory', compact('transactions'));
    }
}
