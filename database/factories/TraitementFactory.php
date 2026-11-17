<?php

namespace Database\Factories;

use App\Models\Bassin;
use App\Models\CycleElevage;
use App\Models\Traitement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Traitement>
 */
class TraitementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bassin_id' => Bassin::factory(),
            'cycle_elevage_id' => null,
            'date_traitement' => fake()->dateTimeBetween('-6 months', 'now'),
            'produit' => fake()->randomElement(['Chaux', 'Probiotique', 'Désinfectant', 'Antiparasitaire']),
            'motif' => fake()->sentence(),
            'dose' => fake()->optional()->numerify('## g/m3'),
            'quantite_appliquee' => fake()->optional()->numerify('## kg'),
            'duree_jours' => fake()->optional()->numberBetween(1, 14),
            'avec_arret_recolte' => false,
            'responsable' => fake()->optional()->name(),
            'observations' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Link the treatment to a cycle and its basin.
     */
    public function forCycle(CycleElevage $cycle): static
    {
        return $this->state(fn (array $attributes) => [
            'bassin_id' => $cycle->bassin_id,
            'cycle_elevage_id' => $cycle->id,
        ]);
    }
}
