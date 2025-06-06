<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'pesan', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
