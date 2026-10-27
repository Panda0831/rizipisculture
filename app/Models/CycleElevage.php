<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['bassin_id', 'espece_id', 'date_debut', 'date_fin', 'statut', 'effectif_depart', 'effectif_actuel', 'poids_moyen_depart_g', 'densite_alevins_m2', 'notes'])]
class CycleElevage extends Model
{
    use HasFactory;
    public function bassin(): BelongsTo
    {
        return  $this->belongsTo(Bassin::class);
    }
    public function espece(): BelongsTo
    {
        return  $this->belongsTo(Espece::class);
    }
    public function DistributionAliment()
    {
        return $this->hasMany(DistributionALiment ::class);

    }
    public function mortalite()
    {
        return $this->hasMany(Mortalite :: class);
    }
    public function prelevementEaux()
    {
        return $this->hasMany(PrelevementEaux::class);
    }
     public function traitement()
     {
         return $this->hasMany(Traitement::class);
    }



}
