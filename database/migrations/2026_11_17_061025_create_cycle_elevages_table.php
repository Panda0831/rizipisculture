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
        Schema::create('cycle_elevages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bassin_id')->constrained()->cascadeOnDelete();
            $table->foreignId('espece_id')->constrained()->restrictOnDelete();
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours');
            $table->unsignedInteger('effectif_depart');
            $table->unsignedInteger('effectif_actuel')->nullable();
            $table->decimal('poids_moyen_depart_g', 8, 2)->nullable();
            $table->decimal('densite_alevins_m2', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['bassin_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycle_elevages');
    }
};
