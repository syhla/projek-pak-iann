<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending')->change();
        });
    }
};
