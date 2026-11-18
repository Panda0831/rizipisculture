<?php

namespace App\Http\Controllers\Api;

use App\Models\Aliment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class AlimentController extends ApiResourceController
{
    protected string $modelClass = Aliment::class;

    protected function rules(User $user): array
    {
        return [
            'exploitation_id' => [
                'nullable', 'integer',
                Rule::exists('exploitations', 'id')->where('user_id', $user->id),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'proteines_pourcent' => ['nullable', 'numeric', 'between:0,100'],
            'prix_unitaire' => ['nullable', 'numeric', 'min:0'],
            'unite' => ['sometimes', 'string', 'max:20'],
            'stock_actuel' => ['sometimes', 'numeric', 'min:0'],
            'stock_minimum' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Aliment::query()->where(function ($query) use ($user): void {
            $query->whereNull('exploitation_id')
                ->orWhereHas('exploitation', fn (Builder $owner) => $owner->where('user_id', $user->id));
        });
    }
}
