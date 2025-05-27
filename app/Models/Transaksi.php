<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Transaksi extends Model
{
    protected $table = 'transaksis'; // atau 'transactions' jika ikut konvensi Laravel

protected $fillable = [
    'user_id',
    'status',
    'no_hp',
    'alamat',
    'total_harga',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    // Scope untuk filter berdasarkan status transaksi
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
