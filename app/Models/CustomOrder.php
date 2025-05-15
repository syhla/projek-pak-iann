<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'whatsapp', 'jenis_kue', 'deskripsi_custom', 'tanggal_pesan', 'status'
    ];
}
