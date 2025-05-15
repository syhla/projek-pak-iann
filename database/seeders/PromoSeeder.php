<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Add this line
class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promos')->insert([
            [
                'title' => 'Promo 1',
                'description' => 'Deskripsi Promo 1',
                'discount_percentage' => 20.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Promo 2',
                'description' => 'Deskripsi Promo 2',
                'discount_percentage' => 15.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Promo 3',
                'description' => 'Deskripsi Promo 3',
                'discount_percentage' => 25.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
