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
        $table->decimal('price', 10, 2)->default(0)->after('title');
    });
}

public function down()
{
    Schema::table('new_products', function (Blueprint $table) {
        $table->dropColumn('price');
    });
}
};
