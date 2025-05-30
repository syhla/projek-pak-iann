<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class TransaksiItem extends Model
{
    protected $fillable = ['transaksi_id', 'product_id', 'jumlah', 'total_harga'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
