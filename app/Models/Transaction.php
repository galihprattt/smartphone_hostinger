<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Menentukan kolom mana yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'notes',
        'total_price',
        'status',
        'is_paid',  // ✅ tambahkan ini
        'payment_proof', // ✅ tambahkan payment_proof
    ];

    // Relasi ke TransactionItem (satu transaksi memiliki banyak item)
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
