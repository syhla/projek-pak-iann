<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
public function up()
{
    DB::statement("ALTER TABLE customs MODIFY status ENUM('menunggu', 'disetujui', 'ditolak') NOT NULL DEFAULT 'menunggu'");
}

public function down()
{
    DB::statement("ALTER TABLE customs MODIFY status ENUM('approved', 'rejected') NOT NULL DEFAULT 'approved'");
}
};
