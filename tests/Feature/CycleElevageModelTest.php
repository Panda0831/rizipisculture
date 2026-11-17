<?php

namespace Tests\Feature;

use App\Models\Bassin;
use App\Models\CycleElevage;
use App\Models\DistributionAliment;
use App\Models\Espece;
use App\Models\Mortalite;
use App\Models\PrelevementEau;
use App\Models\Recolte;
use App\Models\Traitement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CycleElevageModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_cycle_elevage_links_basin_species_and_tracking_records(): void
    {
        $cycle = CycleElevage::factory()->create([
            'effectif_depart' => 1000,
            'poids_moyen_depart_g' => 12.5,
        ]);
        $distribution = DistributionAliment::factory()->for($cycle)->create();
        $mortalite = Mortalite::factory()->for($cycle)->create();
        $prelevement = PrelevementEau::factory()->forCycle($cycle)->create();
        $traitement = Traitement::factory()->forCycle($cycle)->create();
        $recolte = Recolte::factory()->for($cycle)->create();

        $this->assertInstanceOf(Bassin::class, $cycle->bassin);
        $this->assertInstanceOf(Espece::class, $cycle->espece);
        $this->assertTrue($cycle->espece->cycleElevages->contains($cycle));
        $this->assertTrue($cycle->distributionAliments->contains($distribution));
        $this->assertTrue($distribution->aliment->distributionAliments->contains($distribution));
        $this->assertTrue($cycle->mortalites->contains($mortalite));
        $this->assertTrue($cycle->prelevementEaux->contains($prelevement));
        $this->assertTrue($cycle->traitements->contains($traitement));
        $this->assertTrue($cycle->recoltes->contains($recolte));
        $this->assertSame(1000, $cycle->effectif_depart);
        $this->assertSame('12.50', $cycle->poids_moyen_depart_g);
    }

    public function test_bassin_exposes_only_its_current_cycle(): void
    {
        $bassin = Bassin::factory()->create();
        $cycleEnCours = CycleElevage::factory()->for($bassin)->create();
        $cycleTermine = CycleElevage::factory()->for($bassin)->termine()->create();

        $this->assertTrue($bassin->cycleEnCours->is($cycleEnCours));
        $this->assertFalse($bassin->cycleEnCours->is($cycleTermine));
        $this->assertCount(2, $bassin->cycleElevages);
    }

    public function test_deleting_cycle_removes_its_distributions_mortalities_and_harvests(): void
    {
        $cycle = CycleElevage::factory()->create();
        $distribution = DistributionAliment::factory()->for($cycle)->create();
        $mortalite = Mortalite::factory()->for($cycle)->create();
        $recolte = Recolte::factory()->for($cycle)->create();

        $cycle->delete();

        $this->assertDatabaseMissing('distribution_aliments', ['id' => $distribution->id]);
        $this->assertDatabaseMissing('mortalites', ['id' => $mortalite->id]);
        $this->assertDatabaseMissing('recoltes', ['id' => $recolte->id]);
    }

    public function test_deleting_cycle_unlinks_its_water_samples_and_treatments(): void
    {
        $cycle = CycleElevage::factory()->create();
        $prelevement = PrelevementEau::factory()->forCycle($cycle)->create();
        $traitement = Traitement::factory()->forCycle($cycle)->create();

        $cycle->delete();

        $this->assertDatabaseHas('prelevement_eaux', [
            'id' => $prelevement->id,
            'bassin_id' => $cycle->bassin_id,
            'cycle_elevage_id' => null,
        ]);
        $this->assertDatabaseHas('traitements', [
            'id' => $traitement->id,
            'bassin_id' => $cycle->bassin_id,
            'cycle_elevage_id' => null,
        ]);
    }
}
