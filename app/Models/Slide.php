<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    // Ganti 'image' menjadi 'image_path' untuk konsistensi dengan nama kolom di migration
    protected $fillable = ['image_path'];
}
