<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE medicines MODIFY unit ENUM('strip', 'bottle', 'box', 'others') NOT NULL DEFAULT 'strip'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE medicines MODIFY unit ENUM('strip', 'bottle', 'box') NOT NULL DEFAULT 'strip'");
        }
    }
};