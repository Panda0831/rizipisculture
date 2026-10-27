<?php

namespace Database\Factories;

use App\Models\Aliment;
use App\Models\Exploitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aliment>
 */
class AlimentFactory extends Factory
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
            'type' => fake()->optional()->word(),
            'proteines_pourcent' => fake()->optional()->randomFloat(2, 0, 100),
            'prix_unitaire' => fake()->optional()->randomFloat(2, 0, 100000),
            'unite' => 'kg',
            'stock_actuel' => fake()->randomFloat(2, 0, 10000),
            'stock_minimum' => fake()->randomFloat(2, 0, 1000),
        ];
    }
}
