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
        Schema::create('bassins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exploitation_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->enum('type', ['etang', 'vivier', 'bac', 'autre'])->default('etang');
            $table->decimal('superficie_m2', 10, 2)->nullable();
            $table->decimal('profondeur_m', 5, 2)->nullable();
            $table->decimal('capacite_m3', 10, 2)->nullable();
            $table->decimal('surface_eau_m2', 10, 2)->nullable();
            $table->boolean('actif')->default(true);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bassins');
    }
};
