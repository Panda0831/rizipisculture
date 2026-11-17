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
        Schema::create('mortalites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_elevage_id')->constrained('cycle_elevages')->cascadeOnDelete();
            $table->date('date_deces');
            $table->unsignedInteger('nombre');
            $table->string('cause')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mortalites');
    }
};
