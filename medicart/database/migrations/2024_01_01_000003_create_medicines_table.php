<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->enum('unit', ['strip', 'bottle', 'box', 'others'])->default('strip');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['name', 'brand']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
