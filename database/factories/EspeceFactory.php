<?php

namespace Database\Factories;

use App\Models\Espece;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Espece>
 */
class EspeceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom_commun' => fake()->randomElement(['Tilapia', 'Carpe commune', 'Poisson-chat', 'Truite arc-en-ciel', 'Carpin']),
            'nom_scientifique' => fake()->optional()->word(),
            'nom_local' => fake()->optional()->word(),
            'temperature_min' => fake()->randomFloat(1, 15, 25),
            'temperature_max' => fake()->randomFloat(1, 26, 35),
            'ph_min' => fake()->randomFloat(1, 6, 7),
            'ph_max' => fake()->randomFloat(1, 7, 9),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
