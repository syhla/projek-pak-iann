<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    protected $table = 'customs';

protected $fillable = [
    'nama',
    'no_wa',
    'desain',
    'status',
    'customer_id',
    'qr_code',  // tambahkan ini supaya bisa mass assignment
];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
