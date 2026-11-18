<?php

namespace App\Http\Controllers\Api;

use App\Models\Mortalite;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class MortaliteController extends ApiResourceController
{
    protected string $modelClass = Mortalite::class;

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
            'date_deces' => ['required', 'date'],
            'nombre' => ['required', 'integer', 'min:1'],
            'cause' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Mortalite::query()->whereHas(
            'cycleElevage.bassin.exploitation',
            fn (Builder $query) => $query->where('user_id', $user->id),
        );
    }
}
