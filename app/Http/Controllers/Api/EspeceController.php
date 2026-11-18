<?php

namespace App\Http\Controllers\Api;

use App\Models\Espece;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class EspeceController extends ApiResourceController
{
    protected string $modelClass = Espece::class;

    protected function rules(User $user): array
    {
        return [
            'nom_commun' => ['required', 'string', 'max:255'],
            'nom_scientifique' => ['nullable', 'string', 'max:255'],
            'nom_local' => ['nullable', 'string', 'max:255'],
            'temperature_min' => ['nullable', 'numeric'],
            'temperature_max' => ['nullable', 'numeric'],
            'ph_min' => ['nullable', 'numeric', 'between:0,14'],
            'ph_max' => ['nullable', 'numeric', 'between:0,14'],
            'description' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Espece::query();
    }
}
