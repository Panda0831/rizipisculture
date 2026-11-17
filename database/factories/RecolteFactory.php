<?php

namespace Database\Factories;

use App\Models\CycleElevage;
use App\Models\Recolte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recolte>
 */
class RecolteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cycle_elevage_id' => CycleElevage::factory(),
            'date_recolte' => fake()->dateTimeBetween('-3 months', 'now'),
            'type' => fake()->randomElement(['partielle', 'totale']),
            'quantite_kg' => fake()->randomFloat(2, 10, 5000),
            'nombre_poissons' => fake()->optional()->numberBetween(10, 2000),
            'poids_moyen_g' => fake()->randomFloat(2, 100, 2000),
            'prix_vente_unitaire_kg' => fake()->optional()->randomFloat(2, 500, 3000),
            'observations' => fake()->optional()->sentence(),
        ];
    }
}
