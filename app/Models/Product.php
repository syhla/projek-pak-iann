<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'is_featured',
        'is_today_available',
        'is_recommended',
        'show_on_welcome',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor untuk URL gambar produk
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-product.png'); // fallback jika tidak ada gambar
        }
        return asset('storage/products/' . $this->image);
    }
}
