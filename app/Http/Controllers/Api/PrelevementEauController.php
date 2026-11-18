<?php

namespace App\Http\Controllers\Api;

use App\Models\Bassin;
use App\Models\PrelevementEau;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PrelevementEauController extends ApiResourceController
{
    protected string $modelClass = PrelevementEau::class;

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
            'date_prelevement' => ['required', 'date'],
            'temperature_c' => ['nullable', 'numeric'],
            'ph' => ['nullable', 'numeric', 'between:0,14'],
            'oxygene_mg_l' => ['nullable', 'numeric', 'min:0'],
            'ammoniac_mg_l' => ['nullable', 'numeric', 'min:0'],
            'nitrites_mg_l' => ['nullable', 'numeric', 'min:0'],
            'nitrates_mg_l' => ['nullable', 'numeric', 'min:0'],
            'transparence_cm' => ['nullable', 'integer', 'min:0'],
            'observations' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return PrelevementEau::query()->whereHas(
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
