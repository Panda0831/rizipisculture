<?php

namespace App\Http\Controllers\Api;

use App\Models\Bassin;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class BassinController extends ApiResourceController
{
    protected string $modelClass = Bassin::class;

    protected function rules(User $user): array
    {
        return [
            'exploitation_id' => [
                'required', 'integer',
                Rule::exists('exploitations', 'id')->where('user_id', $user->id),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(['etang', 'vivier', 'bac', 'autre'])],
            'superficie_m2' => ['nullable', 'numeric', 'min:0'],
            'profondeur_m' => ['nullable', 'numeric', 'min:0'],
            'capacite_m3' => ['nullable', 'numeric', 'min:0'],
            'surface_eau_m2' => ['nullable', 'numeric', 'min:0'],
            'actif' => ['sometimes', 'boolean'],
            'observations' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Bassin::query()->whereHas('exploitation', fn (Builder $query) => $query->where('user_id', $user->id));
    }
}
