<?php

namespace App\Models;

use Database\Factories\VenteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'recolte_id',
    'date_vente',
    'client_nom',
    'client_contact',
    'quantite_kg',
    'prix_unitaire_kg',
    'montant_total',
    'mode_paiement',
    'statut',
    'numero_facture',
    'notes',
])]
class Vente extends Model
{
    /** @use HasFactory<VenteFactory> */
    use HasFactory;

    /**
     * Get the harvest sold.
     */
    public function recolte(): BelongsTo
    {
        return $this->belongsTo(Recolte::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_vente' => 'date',
            'quantite_kg' => 'decimal:2',
            'prix_unitaire_kg' => 'decimal:2',
            'montant_total' => 'decimal:2',
        ];
    }
}
