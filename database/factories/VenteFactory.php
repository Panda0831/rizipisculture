<?php

namespace Database\Factories;

use App\Models\Recolte;
use App\Models\Vente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vente>
 */
class VenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantiteKg = fake()->randomFloat(2, 5, 1000);
        $prixUnitaireKg = fake()->randomFloat(2, 500, 3000);

        return [
            'recolte_id' => Recolte::factory(),
            'date_vente' => fake()->dateTimeBetween('-2 months', 'now'),
            'client_nom' => fake()->name(),
            'client_contact' => fake()->optional()->phoneNumber(),
            'quantite_kg' => $quantiteKg,
            'prix_unitaire_kg' => $prixUnitaireKg,
            'montant_total' => round($quantiteKg * $prixUnitaireKg, 2),
            'mode_paiement' => fake()->randomElement(['especes', 'cheque', 'virement', 'autre']),
            'statut' => fake()->randomElement(['en_attente', 'payee', 'annulee']),
            'numero_facture' => fake()->optional()->numerify('FAC-#####'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
