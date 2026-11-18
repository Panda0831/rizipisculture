<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

abstract class ApiResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $modelClass;

    /** @return array<string, mixed> */
    abstract protected function rules(User $user): array;

    abstract protected function query(User $user): Builder;

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 15), 1), 100);

        return response()->json($this->query($request->user())->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate($this->rules($user));
        $this->validateBusinessRules($data, $user);
        $data = $this->prepareForStore($data, $user);
        $modelClass = $this->modelClass;

        return response()->json($modelClass::query()->create($data), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json($this->findForUser($request->user(), $id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $record = $this->findForUser($user, $id);
        $rules = $this->rules($user);

        foreach ($rules as $field => $fieldRules) {
            $rules[$field] = array_map(
                static fn (mixed $rule): mixed => $rule === 'required' ? 'sometimes' : $rule,
                (array) $fieldRules,
            );
        }

        $data = $request->validate($rules);
        $this->validateBusinessRules(array_replace($record->getAttributes(), $data), $user, $record);
        $data = $this->prepareForUpdate($data, $user, $record);
        $record->update($data);

        return response()->json($record->refresh());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $record = $this->findForUser($request->user(), $id);

        try {
            $record->delete();
        } catch (QueryException) {
            return response()->json([
                'message' => 'Cette ressource est encore utilisée et ne peut pas être supprimée.',
            ], 409);
        }

        return response()->json(null, 204);
    }

    protected function validateBusinessRules(array $data, User $user, ?Model $record = null): void
    {
        // Override in a resource controller when cross-field validation is needed.
    }

    protected function prepareForStore(array $data, User $user): array
    {
        return $data;
    }

    protected function prepareForUpdate(array $data, User $user, Model $record): array
    {
        return $data;
    }

    protected function findForUser(User $user, int $id): Model
    {
        return $this->query($user)->findOrFail($id);
    }

    protected function invalid(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => $message]);
    }
}
