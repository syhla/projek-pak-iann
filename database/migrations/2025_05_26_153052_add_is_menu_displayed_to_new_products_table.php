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
        $table->boolean('is_menu_displayed')->default(false);
    });
}

public function down()
{
    Schema::table('new_products', function (Blueprint $table) {
        $table->dropColumn('is_menu_displayed');
    });
}
};
