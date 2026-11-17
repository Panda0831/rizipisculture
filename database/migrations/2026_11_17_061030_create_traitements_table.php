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
        Schema::create('traitements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bassin_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cycle_elevage_id')->nullable()->constrained('cycle_elevages')->nullOnDelete();
            $table->date('date_traitement');
            $table->string('produit');
            $table->text('motif');
            $table->string('dose', 100)->nullable();
            $table->string('quantite_appliquee', 100)->nullable();
            $table->unsignedInteger('duree_jours')->nullable();
            $table->boolean('avec_arret_recolte')->default(false);
            $table->string('responsable')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traitements');
    }
};
