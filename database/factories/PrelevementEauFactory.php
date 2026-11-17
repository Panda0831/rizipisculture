<?php

namespace Database\Factories;

use App\Models\Bassin;
use App\Models\CycleElevage;
use App\Models\PrelevementEau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrelevementEau>
 */
class PrelevementEauFactory extends Factory
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
            'date_prelevement' => fake()->dateTimeBetween('-6 months', 'now'),
            'temperature_c' => fake()->randomFloat(1, 18, 34),
            'ph' => fake()->randomFloat(1, 5.5, 9),
            'oxygene_mg_l' => fake()->randomFloat(2, 1, 10),
            'ammoniac_mg_l' => fake()->randomFloat(2, 0, 2),
            'nitrites_mg_l' => fake()->randomFloat(2, 0, 1),
            'nitrates_mg_l' => fake()->randomFloat(2, 0, 50),
            'transparence_cm' => fake()->numberBetween(10, 80),
            'observations' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Link the sample to a cycle and its basin.
     */
    public function forCycle(CycleElevage $cycle): static
    {
        return $this->state(fn (array $attributes) => [
            'bassin_id' => $cycle->bassin_id,
            'cycle_elevage_id' => $cycle->id,
        ]);
    }
}
