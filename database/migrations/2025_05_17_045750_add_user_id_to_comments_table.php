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
    Schema::table('comments', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable(); // bisa null karena pengunjung bukan user login
    });
}

public function down()
{
    Schema::table('comments', function (Blueprint $table) {
        $table->dropColumn('user_id');
    });
}
};
