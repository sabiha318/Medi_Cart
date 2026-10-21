<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->unsignedInteger('tablets_per_strip')->nullable()->after('unit');
        });

        DB::table('medicines')
            ->where('unit', 'strip')
            ->whereNull('tablets_per_strip')
            ->update(['tablets_per_strip' => 10]);
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn('tablets_per_strip');
        });
    }
};