<?php

namespace App\Http\Controllers\Api;

use App\Models\Bassin;
use App\Models\Traitement;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class TraitementController extends ApiResourceController
{
    protected string $modelClass = Traitement::class;

    protected function rules(User $user): array
    {
        return [
            'bassin_id' => [
                'required', 'integer',
                Rule::exists('bassins', 'id')->where(fn ($query) => $query->whereIn(
                    'exploitation_id', fn ($subquery) => $subquery->select('id')->from('exploitations')->where('user_id', $user->id),
                )),
            ],
            'cycle_elevage_id' => ['nullable', 'integer', 'exists:cycle_elevages,id'],
            'date_traitement' => ['required', 'date'],
            'produit' => ['required', 'string', 'max:255'],
            'motif' => ['required', 'string'],
            'dose' => ['nullable', 'string', 'max:100'],
            'quantite_appliquee' => ['nullable', 'string', 'max:100'],
            'duree_jours' => ['nullable', 'integer', 'min:0'],
            'avec_arret_recolte' => ['sometimes', 'boolean'],
            'responsable' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Traitement::query()->whereHas(
            'bassin.exploitation',
            fn (Builder $query) => $query->where('user_id', $user->id),
        );
    }

    protected function validateBusinessRules(array $data, User $user, ?Model $record = null): void
    {
        if (! empty($data['cycle_elevage_id']) && ! Bassin::query()
            ->whereKey($data['bassin_id'])
            ->whereHas('cycleElevages', fn (Builder $query) => $query->whereKey($data['cycle_elevage_id']))
            ->exists()) {
            $this->invalid('cycle_elevage_id', 'Le cycle indiqué ne correspond pas au bassin sélectionné.');
        }
    }
}
