<?php

namespace App\Models;

use Database\Factories\EspeceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable(['nom_commun', 'nom_scientifique', 'nom_local', 'temperature_min', 'temperature_max', 'ph_min', 'ph_max', 'description'])]

class Espece extends Model
{
    @use HasFactory<EspeceFactory>
    use HasFactory;
    public function bassins(): HasMany
    {
        return $this->hasMany(Bassin::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function cycleElevage()
    {
        return $this->hasMany(CycleElevage::class);
    }
    public function casts(): array
    {
        return [
            'temperature_min' => 'decimal:1',
            'temperature_max' => 'decimal:1',
            'ph_min' => 'decimal:1',
            'ph_max' => 'decimal:1',
        ];
    }
}
