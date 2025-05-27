<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewProduct extends Model
{
protected $fillable = [
    'title',
    'price',
    'stock',
    'image',
    'is_today_available',
    'is_menu_displayed',
    'is_new_product',
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
