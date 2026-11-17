<?php

namespace Database\Factories;

use App\Models\Aliment;
use App\Models\CycleElevage;
use App\Models\DistributionAliment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DistributionAliment>
 */
class DistributionAlimentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantiteKg = fake()->randomFloat(2, 5, 500);
        $prixUnitaireKg = fake()->randomFloat(2, 100, 1500);

        return [
            'cycle_elevage_id' => CycleElevage::factory(),
            'aliment_id' => Aliment::factory(),
            'date_distribution' => fake()->dateTimeBetween('-6 months', 'now'),
            'quantite_kg' => $quantiteKg,
            'prix_unitaire_kg' => $prixUnitaireKg,
            'montant_total' => round($quantiteKg * $prixUnitaireKg, 2),
            'heure' => fake()->optional()->time('H:i'),
            'observations' => fake()->optional()->sentence(),
        ];
    }
}
