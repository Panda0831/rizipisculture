<?php

namespace App\Models;

use Database\Factories\MortaliteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'cycle_elevage_id',
    'date_deces',
    'nombre',
    'cause',
    'observations',
])]
class Mortalite extends Model
{
    /** @use HasFactory<MortaliteFactory> */
    use HasFactory;

    /**
     * Get the farming cycle concerned by the mortality record.
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
            'date_deces' => 'date',
            'nombre' => 'integer',
        ];
    }
}
