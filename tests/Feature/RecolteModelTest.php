<?php

namespace Tests\Feature;

use App\Models\CycleElevage;
use App\Models\Recolte;
use App\Models\Vente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecolteModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_harvest_has_sales_and_sale_belongs_to_harvest(): void
    {
        $cycle = CycleElevage::factory()->create();
        $recolte = Recolte::factory()->for($cycle)->create(['quantite_kg' => 150.5]);
        $vente = Vente::factory()->for($recolte)->create([
            'quantite_kg' => 100,
            'prix_unitaire_kg' => 2500,
            'montant_total' => 250000,
        ]);

        $this->assertTrue($recolte->cycleElevage->is($cycle));
        $this->assertTrue($recolte->ventes->contains($vente));
        $this->assertTrue($vente->recolte->is($recolte));
        $this->assertSame('150.50', $recolte->quantite_kg);
        $this->assertSame('250000.00', $vente->montant_total);
    }
}
