<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
use HasFactory;

    // user_id dihapus karena tidak dipakai
    protected $fillable = ['nama', 'pesan'];
}
