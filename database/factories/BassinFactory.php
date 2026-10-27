<?php

namespace Database\Factories;

use App\Models\Bassin;
use App\Models\Exploitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bassin>
 */
class BassinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exploitation_id' => Exploitation::factory(),
            'nom' => fake()->words(2, true),
            'type' => fake()->randomElement(['etang', 'vivier', 'bac', 'autre']),
            'superficie_m2' => fake()->optional()->randomFloat(2, 10, 10000),
            'profondeur_m' => fake()->optional()->randomFloat(2, 0.5, 10),
            'capacite_m3' => fake()->optional()->randomFloat(2, 10, 100000),
            'surface_eau_m2' => fake()->optional()->randomFloat(2, 10, 10000),
            'actif' => true,
            'observations' => fake()->optional()->sentence(),
        ];
    }
}
