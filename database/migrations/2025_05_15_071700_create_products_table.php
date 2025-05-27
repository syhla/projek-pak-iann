<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('image'); // path ke gambar produk
        $table->decimal('price', 10, 2);
        $table->boolean('is_today_available')->default(false); // produk tersedia hari ini
        $table->boolean('is_recommended')->default(false);     // produk rekomendasi (opsional)
        $table->unsignedTinyInteger('rating')->default(0);     // 0–5 (opsional)
        $table->timestamps();
    });    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
