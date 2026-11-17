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
        Schema::create('prelevement_eaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bassin_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cycle_elevage_id')->nullable()->constrained('cycle_elevages')->nullOnDelete();
            $table->dateTime('date_prelevement');
            $table->decimal('temperature_c', 4, 1)->nullable();
            $table->decimal('ph', 3, 1)->nullable();
            $table->decimal('oxygene_mg_l', 4, 2)->nullable();
            $table->decimal('ammoniac_mg_l', 4, 2)->nullable();
            $table->decimal('nitrites_mg_l', 4, 2)->nullable();
            $table->decimal('nitrates_mg_l', 4, 2)->nullable();
            $table->unsignedInteger('transparence_cm')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prelevement_eaux');
    }
};
