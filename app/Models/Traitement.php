<?php

namespace App\Models;

use Database\Factories\TraitementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'bassin_id',
    'cycle_elevage_id',
    'date_traitement',
    'produit',
    'motif',
    'dose',
    'quantite_appliquee',
    'duree_jours',
    'avec_arret_recolte',
    'responsable',
    'observations',
])]
class Traitement extends Model
{
    /** @use HasFactory<TraitementFactory> */
    use HasFactory;

    /**
     * Get the basin where the treatment was applied.
     */
    public function bassin(): BelongsTo
    {
        return $this->belongsTo(Bassin::class);
    }

    /**
     * Get the optional farming cycle linked to the treatment.
     */
    public function cycleElevage(): BelongsTo
    {
        return $this->belongsTo(CycleElevage::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_traitement' => 'date',
            'duree_jours' => 'integer',
            'avec_arret_recolte' => 'boolean',
        ];
    }
}
