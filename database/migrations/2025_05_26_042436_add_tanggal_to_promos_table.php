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
    Schema::table('promos', function (Blueprint $table) {
        $table->date('tanggal_mulai')->nullable();
        $table->date('tanggal_akhir')->nullable();
    });
}

public function down()
{
    Schema::table('promos', function (Blueprint $table) {
        $table->dropColumn(['tanggal_mulai', 'tanggal_akhir']);
    });
}
};
