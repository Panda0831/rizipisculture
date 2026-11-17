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
        Schema::create('especes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_commun');
            $table->string('nom_scientifique')->nullable();
            $table->string('nom_local')->nullable();
            $table->decimal('temperature_min', 4, 1)->nullable();
            $table->decimal('temperature_max', 4, 1)->nullable();
            $table->decimal('ph_min', 3, 1)->nullable();
            $table->decimal('ph_max', 3, 1)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especes');
    }
};
