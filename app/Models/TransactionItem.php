<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product; // ✅ Import model Product

class TransactionItem extends Model
{
    // Menentukan kolom mana yang bisa diisi secara massal
    protected $fillable = [
        'transaction_id', 'product_name', 'price', 'quantity', 'product_id'
    ];

    // Relasi ke Transaction (setiap item dimiliki oleh satu transaksi)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ✅ Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
