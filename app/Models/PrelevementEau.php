<?php

namespace App\Models;

use Database\Factories\PrelevementEauFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'bassin_id',
    'cycle_elevage_id',
    'date_prelevement',
    'temperature_c',
    'ph',
    'oxygene_mg_l',
    'ammoniac_mg_l',
    'nitrites_mg_l',
    'nitrates_mg_l',
    'transparence_cm',
    'observations',
])]
class PrelevementEau extends Model
{
    /** @use HasFactory<PrelevementEauFactory> */
    use HasFactory;

    /** @var string */
    protected $table = 'prelevement_eaux';

    /**
     * Get the basin where the sample was taken.
     */
    public function bassin(): BelongsTo
    {
        return $this->belongsTo(Bassin::class);
    }

    /**
     * Get the optional farming cycle linked to the sample.
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
            'date_prelevement' => 'datetime',
            'temperature_c' => 'decimal:1',
            'ph' => 'decimal:1',
            'oxygene_mg_l' => 'decimal:2',
            'ammoniac_mg_l' => 'decimal:2',
            'nitrites_mg_l' => 'decimal:2',
            'nitrates_mg_l' => 'decimal:2',
            'transparence_cm' => 'integer',
        ];
    }
}
