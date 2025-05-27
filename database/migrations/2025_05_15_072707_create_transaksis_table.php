<?php

// Migration untuk tabel transaksis
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('no_hp');
            $table->text('alamat');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->integer('total_harga');
            $table->string('payment_method');    // Tambahkan kolom ini jika di model/logic ada
            $table->string('shipping_method');   // Tambahkan kolom ini jika di model/logic ada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
