<?php

namespace Database\Factories;

use App\Models\Bassin;
use App\Models\CycleElevage;
use App\Models\Espece;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CycleElevage>
 */
class CycleElevageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $effectifDepart = fake()->numberBetween(500, 10000);

        return [
            'bassin_id' => Bassin::factory(),
            'espece_id' => Espece::factory(),
            'date_debut' => fake()->dateTimeBetween('-6 months', '-1 month'),
            'date_fin' => null,
            'statut' => 'en_cours',
            'effectif_depart' => $effectifDepart,
            'effectif_actuel' => $effectifDepart,
            'poids_moyen_depart_g' => fake()->randomFloat(2, 1, 50),
            'densite_alevins_m2' => fake()->randomFloat(2, 1, 100),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the cycle has been completed.
     */
    public function termine(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_fin' => fake()->dateTimeBetween('-1 month', 'now'),
            'statut' => 'termine',
        ]);
    }
}
