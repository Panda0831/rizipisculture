<?php

namespace Database\Factories;

use App\Models\CycleElevage;
use App\Models\Mortalite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mortalite>
 */
class MortaliteFactory extends Factory
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
            'date_deces' => fake()->dateTimeBetween('-6 months', 'now'),
            'nombre' => fake()->numberBetween(1, 50),
            'cause' => fake()->optional()->randomElement(['maladie', 'prédation', 'stress thermique', 'qualité de l\'eau', 'inconnue']),
            'observations' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Link the mortality record to a cycle with a coherent death date.
     */
    public function forCycle(CycleElevage $cycle): static
    {
        return $this->state(fn (array $attributes) => [
            'cycle_elevage_id' => $cycle->id,
            'date_deces' => fake()->dateTimeBetween($cycle->date_debut, $cycle->date_fin ?? now()),
        ]);
    }
}
