<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     */
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();  // Kolom id, auto increment
            $table->string('image_path');  // Kolom untuk menyimpan path gambar
            $table->string('title')->nullable();  // Kolom untuk judul, bisa kosong
            $table->text('description')->nullable();  // Kolom untuk deskripsi, bisa kosong
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Membalikkan perubahan yang dilakukan di up().
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');  // Menghapus tabel slides jika rollback
    }
};
