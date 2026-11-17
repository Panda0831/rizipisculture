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
        Schema::create('distribution_aliments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_elevage_id')->constrained('cycle_elevages')->cascadeOnDelete();
            $table->foreignId('aliment_id')->constrained()->restrictOnDelete();
            $table->date('date_distribution');
            $table->decimal('quantite_kg', 10, 2);
            $table->decimal('prix_unitaire_kg', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->time('heure')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_aliments');
    }
};
