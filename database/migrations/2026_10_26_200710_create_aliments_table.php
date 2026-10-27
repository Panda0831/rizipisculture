<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aliments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exploitation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nom');
            $table->string('type', 100)->nullable();
            $table->decimal('proteines_pourcent', 5, 2)->nullable();
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->string('unite', 20)->default('kg');
            $table->decimal('stock_actuel', 10, 2)->default(0);
            $table->decimal('stock_minimum', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aliments');
    }
};
