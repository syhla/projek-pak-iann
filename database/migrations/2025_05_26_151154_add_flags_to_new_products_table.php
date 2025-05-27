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
    Schema::table('new_products', function (Blueprint $table) {
        $table->boolean('is_today_available')->default(false);
        $table->boolean('is_new_product')->default(true); // ini default true karena memang produk baru
    });
}

public function down()
{
    Schema::table('new_products', function (Blueprint $table) {
        $table->dropColumn(['is_today_available', 'is_new_product']);
    });
}
};
