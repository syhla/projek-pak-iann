<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi: Membuat tabel 'customs'.
     */
    public function up(): void
    {
        Schema::create('customs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // kolom untuk relasi user
            $table->string('nama');
            $table->string('no_wa');
            $table->text('desain');
            $table->string('gambar_referensi')->nullable(); // untuk file gambar referensi
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->timestamps();

            // Jika kamu ingin foreign key ke tabel users
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Balikkan migrasi: Hapus tabel 'customs'.
     */
    public function down(): void
    {
        Schema::dropIfExists('customs');
    }
};
