<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomCake extends Model
{
    protected $fillable = [
        'nama',
        'no_wa',
        'desain',
        'gambar_referensi',
        'status',
        'customer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
