<?php

namespace App\Http\Controllers\Api;

use App\Models\DistributionAliment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class DistributionAlimentController extends ApiResourceController
{
    protected string $modelClass = DistributionAliment::class;

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
            'aliment_id' => [
                'required', 'integer',
                Rule::exists('aliments', 'id')->where(fn ($query) => $query
                    ->whereNull('exploitation_id')
                    ->orWhereIn('exploitation_id', fn ($subquery) => $subquery
                        ->select('id')->from('exploitations')->where('user_id', $user->id))),
            ],
            'date_distribution' => ['required', 'date'],
            'quantite_kg' => ['required', 'numeric', 'gt:0'],
            'prix_unitaire_kg' => ['required', 'numeric', 'min:0'],
            'heure' => ['nullable', 'date_format:H:i'],
            'observations' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return DistributionAliment::query()->whereHas(
            'cycleElevage.bassin.exploitation',
            fn (Builder $query) => $query->where('user_id', $user->id),
        );
    }

    protected function prepareForStore(array $data, User $user): array
    {
        return $this->withTotal($data);
    }

    protected function prepareForUpdate(array $data, User $user, Model $record): array
    {
        return $this->withTotal(array_replace($record->getAttributes(), $data));
    }

    private function withTotal(array $data): array
    {
        $data['montant_total'] = round((float) $data['quantite_kg'] * (float) $data['prix_unitaire_kg'], 2);

        return $data;
    }
}
