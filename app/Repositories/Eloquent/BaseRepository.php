<?php

namespace App\Repositories\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Paginate model
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $columns = ["*"])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Get all trashed models.
     *
     * @return Collection
     */
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->findOrFail($modelId);
    }

    /**
     * Find model by code
     *
     * @param string $code
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findByCode(string $code, array $columns = ["*"], array $relations = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->where("code", $code)->first();
    }

    /**
     * Find trashed model by id.
     *
     * @param int $modelId
     * @return Model|null
     */
    public function findTrashedById(int $modelId): ?Model
    {
        return $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * Find only trashed model by id.
     *
     * @param int $modelId
     * @return Model|null
     */
    public function findOnlyTrashedById(int $modelId): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($modelId);
    }

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model
    {
        return $this->model->create(Arr::only($payload, $this->model->fillable));
    }

    /**
     * Creates of finds a user based on some criteria
     * @param array $key
     * @param array $payload
     * @return Model|null
     */
    public function firstOrCreate(array $key, array $payload): ?Model
    {
        return $this->model->firstOrCreate($key, $payload);
    }

    /**
     * Update existing model.
     *
     * @param array $payload
     * @param Model $model
     * @return Model|null
     */
    public function update(array $payload, Model $model): ?Model
    {
        $model->update(Arr::only($payload, $model->fillable));

        return $model;
    }

    /**
     * Delete model by id.
     *
     * @param Model $model
     * @return bool
     */
    public function deleteById(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Restore model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }
}
