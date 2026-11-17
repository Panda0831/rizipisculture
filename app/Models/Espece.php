<?php

namespace App\Models;

use Database\Factories\EspeceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nom_commun',
    'nom_scientifique',
    'nom_local',
    'temperature_min',
    'temperature_max',
    'ph_min',
    'ph_max',
    'description',
])]
class Espece extends Model
{
    /** @use HasFactory<EspeceFactory> */
    use HasFactory;

    /**
     * Get the farming cycles involving this species.
     */
    public function cycleElevages(): HasMany
    {
        return $this->hasMany(CycleElevage::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'temperature_min' => 'decimal:1',
            'temperature_max' => 'decimal:1',
            'ph_min' => 'decimal:1',
            'ph_max' => 'decimal:1',
        ];
    }
}
