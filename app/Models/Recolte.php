<?php

namespace App\Models;

use Database\Factories\RecolteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'cycle_elevage_id',
    'date_recolte',
    'type',
    'quantite_kg',
    'nombre_poissons',
    'poids_moyen_g',
    'prix_vente_unitaire_kg',
    'observations',
])]
class Recolte extends Model
{
    /** @use HasFactory<RecolteFactory> */
    use HasFactory;

    /**
     * Get the farming cycle harvested.
     */
    public function cycleElevage(): BelongsTo
    {
        return $this->belongsTo(CycleElevage::class);
    }

    /**
     * Get the sales issued from the harvest.
     */
    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_recolte' => 'date',
            'quantite_kg' => 'decimal:2',
            'nombre_poissons' => 'integer',
            'poids_moyen_g' => 'decimal:2',
            'prix_vente_unitaire_kg' => 'decimal:2',
        ];
    }
}
