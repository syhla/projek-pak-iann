<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('custom_orders', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('whatsapp');
        $table->string('jenis_kue');
        $table->text('deskripsi_custom');
        $table->date('tanggal_pesan');
        $table->enum('status', ['Belum Diproses', 'Diproses', 'Selesai'])->default('Belum Diproses');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
