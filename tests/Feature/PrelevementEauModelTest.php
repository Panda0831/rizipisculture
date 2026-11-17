<?php

namespace Tests\Feature;

use App\Models\Bassin;
use App\Models\CycleElevage;
use App\Models\PrelevementEau;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrelevementEauModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_water_sample_links_basin_and_optional_cycle_with_cast_values(): void
    {
        $cycle = CycleElevage::factory()->create();
        $prelevement = PrelevementEau::factory()->forCycle($cycle)->create([
            'date_prelevement' => '2026-05-01 08:30:00',
            'oxygene_mg_l' => 2.5,
        ]);

        $this->assertTrue($prelevement->bassin->is($cycle->bassin));
        $this->assertTrue($prelevement->cycleElevage->is($cycle));
        $this->assertSame('2026-05-01 08:30:00', $prelevement->date_prelevement->toDateTimeString());
        $this->assertSame('2.50', $prelevement->oxygene_mg_l);
    }

    public function test_water_sample_can_be_recorded_outside_a_cycle(): void
    {
        $prelevement = PrelevementEau::factory()->create();

        $this->assertInstanceOf(Bassin::class, $prelevement->bassin);
        $this->assertNull($prelevement->cycle_elevage_id);
        $this->assertNull($prelevement->cycleElevage);
    }

    public function test_deleting_basin_deletes_its_water_samples(): void
    {
        $bassin = Bassin::factory()->create();
        $prelevement = PrelevementEau::factory()->for($bassin)->create();

        $bassin->delete();

        $this->assertDatabaseMissing('prelevement_eaux', ['id' => $prelevement->id]);
    }
}
