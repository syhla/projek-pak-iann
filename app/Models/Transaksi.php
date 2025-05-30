<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TransaksiItem;

class Transaksi extends Model
{
    protected $fillable = [
        'user_id', 'status', 'no_hp', 'alamat',
        'payment_method', 'shipping_method', 'total_harga'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function items()
{
    return $this->hasMany(TransaksiItem::class, 'transaksi_id');
}

public function transaksiItems()
{
    return $this->hasMany(TransaksiItem::class, 'transaksi_id');
}

}
