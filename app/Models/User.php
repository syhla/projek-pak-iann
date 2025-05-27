<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\Comment; // jangan lupa import model Comment

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // pastikan kolom ini ada di tabel 'users'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Cek apakah user punya role tertentu
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Relasi User punya banyak komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function transactions()
{
    return $this->hasMany(Transaksi::class);
}

}
