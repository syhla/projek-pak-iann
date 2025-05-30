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
    Schema::table('customs', function (Blueprint $table) {
        $table->string('qr_code')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('customs', function (Blueprint $table) {
        $table->dropColumn('qr_code');
    });
}
};