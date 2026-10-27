<?php

namespace App\Models;

use Database\Factories\BassinFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
