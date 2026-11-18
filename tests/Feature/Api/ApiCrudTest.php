<?php

namespace Tests\Feature\Api;

use App\Models\Aliment;
use App\Models\Bassin;
use App\Models\CycleElevage;
use App\Models\Espece;
use App\Models\Exploitation;
use App\Models\Recolte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ApiCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        /** @var \Illuminate\Contracts\Auth\Authenticatable $authUser */
        $authUser = $this->user;
        $this->actingAs($authUser);
    }

    public function test_all_business_resources_support_list_create_show_update_and_delete(): void
    {
        foreach ([
            'exploitations',
            'bassins',
            'especes',
            'cycles-elevage',
            'aliments',
            'distributions-aliment',
            'mortalites',
            'prelevements-eau',
            'traitements',
            'recoltes',
            'ventes',
        ] as $resource) {
            [$payload, $updates, $updatedField, $updatedValue] = $this->payloadFor($resource);
            $baseUrl = "/api/{$resource}";

            $created = $this->postJson($baseUrl, $payload)->assertCreated();
            $id = $created->assertJsonStructure(['id'])->json('id');

            $this->getJson($baseUrl)
                ->assertOk()
                ->assertJsonPath('data.0.id', $id);
            $this->getJson("{$baseUrl}/{$id}")
                ->assertOk()
                ->assertJsonPath('id', $id);
            $updated = $this->patchJson("{$baseUrl}/{$id}", $updates)
                ->assertOk()
                ->assertJsonPath($updatedField, $updatedValue);

            if ($resource === 'distributions-aliment' || $resource === 'ventes') {
                $this->assertSame('28.00', $updated->json('montant_total'));
            }

            $this->deleteJson("{$baseUrl}/{$id}")->assertNoContent();
            $this->getJson("{$baseUrl}/{$id}")->assertNotFound();
        }
    }

    public function test_api_endpoints_require_authentication(): void
    {
        Auth::logout();

        $this->getJson('/api/exploitations')->assertUnauthorized();
    }

    public function test_users_cannot_read_or_list_another_users_exploitations(): void
    {
        $otherUserExploitation = Exploitation::factory()->create();

        $this->getJson('/api/exploitations')
            ->assertOk()
            ->assertJsonCount(0, 'data');
        $this->getJson("/api/exploitations/{$otherUserExploitation->id}")->assertNotFound();
    }

    public function test_a_basin_cannot_have_two_active_cycles(): void
    {
        $exploitation = Exploitation::factory()->for($this->user)->create();
        $bassin = Bassin::factory()->for($exploitation)->create();
        $espece = Espece::factory()->create();
        CycleElevage::factory()->for($bassin)->for($espece)->create(['statut' => 'en_cours']);

        $this->postJson('/api/cycles-elevage', [
            'bassin_id' => $bassin->id,
            'espece_id' => $espece->id,
            'date_debut' => now()->toDateString(),
            'effectif_depart' => 100,
            'statut' => 'en_cours',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('bassin_id');
    }

    public function test_users_cannot_create_records_linked_to_another_users_data(): void
    {
        $foreignExploitation = Exploitation::factory()->create();
        $foreignBassin = Bassin::factory()->for($foreignExploitation)->create();
        $foreignCycle = CycleElevage::factory()->for($foreignBassin)->create();

        $this->postJson('/api/bassins', [
            'exploitation_id' => $foreignExploitation->id,
            'nom' => 'Bassin interdit',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('exploitation_id');

        $this->postJson('/api/mortalites', [
            'cycle_elevage_id' => $foreignCycle->id,
            'date_deces' => now()->toDateString(),
            'nombre' => 1,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('cycle_elevage_id');
    }

    /** @return array{array<string, mixed>, array<string, mixed>, string, mixed} */
    private function payloadFor(string $resource): array
    {
        $exploitation = fn (): Exploitation => Exploitation::factory()->for($this->user)->create();
        $bassin = function () use ($exploitation): Bassin {
            return Bassin::factory()->for($exploitation())->create();
        };
        $cycle = function () use ($bassin): CycleElevage {
            return CycleElevage::factory()
                ->for($bassin())
                ->for(Espece::factory()->create())
                ->create();
        };

        return match ($resource) {
            'exploitations' => [
                ['nom' => 'Ferme API'], ['nom' => 'Ferme API modifiée'], 'nom', 'Ferme API modifiée',
            ],
            'bassins' => [
                ['exploitation_id' => $exploitation()->id, 'nom' => 'Bassin API'],
                ['nom' => 'Bassin API modifié'], 'nom', 'Bassin API modifié',
            ],
            'especes' => [
                ['nom_commun' => 'Tilapia API'], ['nom_commun' => 'Tilapia modifié'], 'nom_commun', 'Tilapia modifié',
            ],
            'cycles-elevage' => (function () use ($bassin): array {
                $pond = $bassin();

                return [[
                    'bassin_id' => $pond->id,
                    'espece_id' => Espece::factory()->create()->id,
                    'date_debut' => now()->toDateString(),
                    'effectif_depart' => 250,
                ], ['notes' => 'Mise à jour API'], 'notes', 'Mise à jour API'];
            })(),
            'aliments' => [
                ['exploitation_id' => $exploitation()->id, 'nom' => 'Granulés API'],
                ['nom' => 'Granulés API modifiés'], 'nom', 'Granulés API modifiés',
            ],
            'distributions-aliment' => (function () use ($cycle, $exploitation): array {
                $feedingCycle = $cycle();
                $feed = Aliment::factory()->for($exploitation())->create();

                return [[
                    'cycle_elevage_id' => $feedingCycle->id,
                    'aliment_id' => $feed->id,
                    'date_distribution' => now()->toDateString(),
                    'quantite_kg' => 2.5,
                    'prix_unitaire_kg' => 8,
                ], ['quantite_kg' => 3.5], 'quantite_kg', '3.50'];
            })(),
            'mortalites' => (function () use ($cycle): array {
                return [[
                    'cycle_elevage_id' => $cycle()->id,
                    'date_deces' => now()->toDateString(),
                    'nombre' => 2,
                ], ['cause' => 'Test API'], 'cause', 'Test API'];
            })(),
            'prelevements-eau' => (function () use ($bassin): array {
                $pond = $bassin();
                $activeCycle = CycleElevage::factory()
                    ->for($pond)
                    ->for(Espece::factory()->create())
                    ->create();

                return [[
                    'bassin_id' => $pond->id,
                    'cycle_elevage_id' => $activeCycle->id,
                    'date_prelevement' => now()->toDateTimeString(),
                    'ph' => 7,
                ], ['ph' => 7.5], 'ph', '7.5'];
            })(),
            'traitements' => (function () use ($bassin): array {
                $pond = $bassin();

                return [[
                    'bassin_id' => $pond->id,
                    'date_traitement' => now()->toDateString(),
                    'produit' => 'Produit API',
                    'motif' => 'Prévention',
                ], ['motif' => 'Motif modifié'], 'motif', 'Motif modifié'];
            })(),
            'recoltes' => (function () use ($cycle): array {
                return [[
                    'cycle_elevage_id' => $cycle()->id,
                    'date_recolte' => now()->toDateString(),
                    'quantite_kg' => 12,
                ], ['quantite_kg' => 13], 'quantite_kg', '13.00'];
            })(),
            'ventes' => (function () use ($cycle): array {
                $harvest = Recolte::factory()->for($cycle())->create();

                return [[
                    'recolte_id' => $harvest->id,
                    'date_vente' => now()->toDateString(),
                    'client_nom' => 'Client API',
                    'quantite_kg' => 2.5,
                    'prix_unitaire_kg' => 8,
                ], ['quantite_kg' => 3.5], 'quantite_kg', '3.50'];
            })(),
            default => throw new \InvalidArgumentException("Ressource API non prise en charge : {$resource}"),
        };
    }
}
