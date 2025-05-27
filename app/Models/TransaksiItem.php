<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
protected $fillable = [
    'transaksi_id',
    'product_id',
    'quantity',
    'price',
];


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
