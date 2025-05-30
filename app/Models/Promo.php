<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'title',
        'original_price',
        'discount_percentage',
        'tanggal_mulai',
        'tanggal_akhir',
        'gambar',
    ];
}
