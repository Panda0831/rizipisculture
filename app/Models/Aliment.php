<?php

namespace App\Models;

use Database\Factories\AlimentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'exploitation_id',
    'nom',
    'type',
    'proteines_pourcent',
    'prix_unitaire',
    'unite',
    'stock_actuel',
    'stock_minimum',
])]
class Aliment extends Model
{
    /** @use HasFactory<AlimentFactory> */
    use HasFactory;

    /**
     * Get the exploitation that owns the feed item.
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
            'proteines_pourcent' => 'decimal:2',
            'prix_unitaire' => 'decimal:2',
            'stock_actuel' => 'decimal:2',
            'stock_minimum' => 'decimal:2',
        ];
    }
}
