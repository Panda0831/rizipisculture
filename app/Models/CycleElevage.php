<?php

namespace App\Models;

use Database\Factories\CycleElevageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'bassin_id',
    'espece_id',
    'date_debut',
    'date_fin',
    'statut',
    'effectif_depart',
    'effectif_actuel',
    'poids_moyen_depart_g',
    'densite_alevins_m2',
    'notes',
])]
class CycleElevage extends Model
{
    /** @use HasFactory<CycleElevageFactory> */
    use HasFactory;

    /**
     * Get the basin where the cycle takes place.
     */
    public function bassin(): BelongsTo
    {
        return $this->belongsTo(Bassin::class);
    }

    /**
     * Get the species raised during the cycle.
     */
    public function espece(): BelongsTo
    {
        return $this->belongsTo(Espece::class);
    }

    /**
     * Get the feed distributions of the cycle.
     */
    public function distributionAliments(): HasMany
    {
        return $this->hasMany(DistributionAliment::class);
    }

    /**
     * Get the mortality records of the cycle.
     */
    public function mortalites(): HasMany
    {
        return $this->hasMany(Mortalite::class);
    }

    /**
     * Get the water samples linked to the cycle.
     */
    public function prelevementEaux(): HasMany
    {
        return $this->hasMany(PrelevementEau::class);
    }

    /**
     * Get the sanitary treatments linked to the cycle.
     */
    public function traitements(): HasMany
    {
        return $this->hasMany(Traitement::class);
    }

    /**
     * Get the harvests of the cycle.
     */
    public function recoltes(): HasMany
    {
        return $this->hasMany(Recolte::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin' => 'date',
            'effectif_depart' => 'integer',
            'effectif_actuel' => 'integer',
            'poids_moyen_depart_g' => 'decimal:2',
            'densite_alevins_m2' => 'decimal:2',
        ];
    }
}
