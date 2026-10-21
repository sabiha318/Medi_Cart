<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('medicine_id');
            $table->unsignedInteger('tablets_per_strip')->nullable()->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['unit', 'tablets_per_strip']);
        });
    }
};