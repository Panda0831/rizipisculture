<?php

namespace App\Models;

use Database\Factories\DistributionAlimentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'cycle_elevage_id',
    'aliment_id',
    'date_distribution',
    'quantite_kg',
    'prix_unitaire_kg',
    'montant_total',
    'heure',
    'observations',
])]
class DistributionAliment extends Model
{
    /** @use HasFactory<DistributionAlimentFactory> */
    use HasFactory;

    /**
     * Get the farming cycle concerned by the distribution.
     */
    public function cycleElevage(): BelongsTo
    {
        return $this->belongsTo(CycleElevage::class);
    }

    /**
     * Get the feed item distributed.
     */
    public function aliment(): BelongsTo
    {
        return $this->belongsTo(Aliment::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_distribution' => 'date',
            'quantite_kg' => 'decimal:2',
            'prix_unitaire_kg' => 'decimal:2',
            'montant_total' => 'decimal:2',
        ];
    }
}
