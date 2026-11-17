<?php

namespace App\Models;

use Database\Factories\BassinFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'exploitation_id',
    'nom',
    'type',
    'superficie_m2',
    'profondeur_m',
    'capacite_m3',
    'surface_eau_m2',
    'actif',
    'observations',
])]
class Bassin extends Model
{
    /** @use HasFactory<BassinFactory> */
    use HasFactory;

    /**
     * Get the exploitation that owns the basin.
     */
    public function exploitation(): BelongsTo
    {
        return $this->belongsTo(Exploitation::class);
    }

    /**
     * Get the farming cycles recorded in the basin.
     */
    public function cycleElevages(): HasMany
    {
        return $this->hasMany(CycleElevage::class);
    }

    /**
     * Get the current farming cycle of the basin, if any.
     */
    public function cycleEnCours(): HasOne
    {
        return $this->hasOne(CycleElevage::class)->where('statut', 'en_cours');
    }

    /**
     * Get the water quality samples taken from the basin.
     */
    public function prelevementEaux(): HasMany
    {
        return $this->hasMany(PrelevementEau::class);
    }

    /**
     * Get the sanitary treatments applied to the basin.
     */
    public function traitements(): HasMany
    {
        return $this->hasMany(Traitement::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'superficie_m2' => 'decimal:2',
            'profondeur_m' => 'decimal:2',
            'capacite_m3' => 'decimal:2',
            'surface_eau_m2' => 'decimal:2',
            'actif' => 'boolean',
        ];
    }
}
