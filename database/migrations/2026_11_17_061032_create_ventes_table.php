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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recolte_id')->constrained()->restrictOnDelete();
            $table->date('date_vente');
            $table->string('client_nom');
            $table->string('client_contact')->nullable();
            $table->decimal('quantite_kg', 10, 2);
            $table->decimal('prix_unitaire_kg', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->enum('mode_paiement', ['especes', 'cheque', 'virement', 'autre'])->default('especes');
            $table->enum('statut', ['en_attente', 'payee', 'annulee'])->default('en_attente');
            $table->string('numero_facture', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
