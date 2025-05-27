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
        $table->decimal('original_price', 15, 2)->after('description')->default(0);
    });
}


public function down()
{
    Schema::table('promos', function (Blueprint $table) {
        $table->dropColumn('original_price');
    });
}
};
