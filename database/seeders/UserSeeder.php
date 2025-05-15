<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@nami.com'],
            [
                'name' => 'Admin Nami',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
