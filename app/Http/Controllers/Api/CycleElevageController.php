<?php

namespace App\Http\Controllers\Api;

use App\Models\CycleElevage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CycleElevageController extends ApiResourceController
{
    protected string $modelClass = CycleElevage::class;

    protected function rules(User $user): array
    {
        return [
            'bassin_id' => [
                'required', 'integer',
                Rule::exists('bassins', 'id')->where(fn ($query) => $query->whereIn(
                    'exploitation_id',
                    fn ($subquery) => $subquery->select('id')->from('exploitations')->where('user_id', $user->id),
                )),
            ],
            'espece_id' => ['required', 'integer', 'exists:especes,id'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'statut' => ['sometimes', Rule::in(['en_cours', 'termine', 'annule'])],
            'effectif_depart' => ['required', 'integer', 'min:0'],
            'effectif_actuel' => ['nullable', 'integer', 'min:0'],
            'poids_moyen_depart_g' => ['nullable', 'numeric', 'min:0'],
            'densite_alevins_m2' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return CycleElevage::query()->whereHas(
            'bassin.exploitation',
            fn (Builder $query) => $query->where('user_id', $user->id),
        );
    }

    protected function validateBusinessRules(array $data, User $user, ?Model $record = null): void
    {
        if (($data['statut'] ?? null) === 'en_cours') {
            $activeCycleExists = CycleElevage::query()
                ->where('bassin_id', $data['bassin_id'])
                ->where('statut', 'en_cours')
                ->when($record, fn (Builder $query) => $query->where('id', '!=', $record->getKey()))
                ->exists();

            if ($activeCycleExists) {
                $this->invalid('bassin_id', 'Ce bassin possède déjà un cycle d’élevage en cours.');
            }
        }
    }
}
