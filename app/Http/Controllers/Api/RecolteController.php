<?php

namespace App\Http\Controllers\Api;

use App\Models\Recolte;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class RecolteController extends ApiResourceController
{
    protected string $modelClass = Recolte::class;

    protected function rules(User $user): array
    {
        return [
            'cycle_elevage_id' => [
                'required', 'integer',
                Rule::exists('cycle_elevages', 'id')->where(fn ($query) => $query->whereIn(
                    'bassin_id',
                    fn ($subquery) => $subquery->select('bassins.id')
                        ->from('bassins')
                        ->join('exploitations', 'exploitations.id', '=', 'bassins.exploitation_id')
                        ->where('exploitations.user_id', $user->id),
                )),
            ],
            'date_recolte' => ['required', 'date'],
            'type' => ['sometimes', Rule::in(['partielle', 'totale'])],
            'quantite_kg' => ['required', 'numeric', 'gt:0'],
            'nombre_poissons' => ['nullable', 'integer', 'min:0'],
            'poids_moyen_g' => ['nullable', 'numeric', 'min:0'],
            'prix_vente_unitaire_kg' => ['nullable', 'numeric', 'min:0'],
            'observations' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Recolte::query()->whereHas(
            'cycleElevage.bassin.exploitation',
            fn (Builder $query) => $query->where('user_id', $user->id),
        );
    }
}
