<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Vente;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class VenteController extends ApiResourceController
{
    protected string $modelClass = Vente::class;

    protected function rules(User $user): array
    {
        return [
            'recolte_id' => [
                'required', 'integer',
                Rule::exists('recoltes', 'id')->where(fn ($query) => $query->whereIn(
                    'cycle_elevage_id',
                    fn ($subquery) => $subquery->select('cycle_elevages.id')
                        ->from('cycle_elevages')
                        ->join('bassins', 'bassins.id', '=', 'cycle_elevages.bassin_id')
                        ->join('exploitations', 'exploitations.id', '=', 'bassins.exploitation_id')
                        ->where('exploitations.user_id', $user->id),
                )),
            ],
            'date_vente' => ['required', 'date'],
            'client_nom' => ['required', 'string', 'max:255'],
            'client_contact' => ['nullable', 'string', 'max:255'],
            'quantite_kg' => ['required', 'numeric', 'gt:0'],
            'prix_unitaire_kg' => ['required', 'numeric', 'min:0'],
            'mode_paiement' => ['sometimes', Rule::in(['especes', 'cheque', 'virement', 'autre'])],
            'statut' => ['sometimes', Rule::in(['en_attente', 'payee', 'annulee'])],
            'numero_facture' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function query(User $user): Builder
    {
        return Vente::query()->whereHas(
            'recolte.cycleElevage.bassin.exploitation',
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
