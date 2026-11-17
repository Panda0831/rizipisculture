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
        Schema::create('recoltes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_elevage_id')->constrained('cycle_elevages')->cascadeOnDelete();
            $table->date('date_recolte');
            $table->enum('type', ['partielle', 'totale'])->default('partielle');
            $table->decimal('quantite_kg', 10, 2);
            $table->unsignedInteger('nombre_poissons')->nullable();
            $table->decimal('poids_moyen_g', 8, 2)->nullable();
            $table->decimal('prix_vente_unitaire_kg', 10, 2)->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recoltes');
    }
};
