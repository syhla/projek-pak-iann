<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('payment_method', ['transfer_bank', 'ovo', 'gopay', 'dana', 'shopeepay'])->default('transfer_bank')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('payment_method')->change(); // balikin ke string kalau rollback
        });
    }
};
