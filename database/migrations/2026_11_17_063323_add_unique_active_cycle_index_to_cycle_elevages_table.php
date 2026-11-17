<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * SQLite supports partial unique indexes, but the Schema Builder does not expose
     * them, so the constraint is created with raw SQL. On a driver without partial
     * indexes (MySQL), the rule must live in the validation layer instead.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
            create unique index cycle_elevages_unique_active_per_bassin
                on cycle_elevages (bassin_id)
                where statut = 'en_cours'
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('drop index if exists cycle_elevages_unique_active_per_bassin');
    }
};
