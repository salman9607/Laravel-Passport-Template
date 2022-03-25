<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    /**
     * Get all models
     *
     * @param array $columns
     * @param array $relations
     * @retuns Collection
     */
    public function all(array $columns = ["*"], array $relations = []): Collection;

    /**
     * Paginate model
     * @param int $perPage
     * @param array $columns
     */
    public function paginate(int $perPage = 15, array $columns = ["*"]);

    /**
     * Get all trashed models.
     *
     * @return Collection
     */
    public function allTrashed(): Collection;

    /**
     * Find model by id.
     *
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ["*"], array $relations = []): ?Model;

    /**
     * Find model by code
     *
     * @param string $code
     * @param array $columns
     * @param array $relations
     * @return Model
     */
    public function findByCode(string $code, array $columns = ["*"], array $relations = []): ?Model;

    /**
     * Find trashed model by id.
     *
     * @param int $modelId
     * @return Model|null
     */
    public function findTrashedById(int $modelId): ?Model;

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model;

    /**
     * Creates of finds a user based on some criteria
     * @param array $key
     * @param array $payload
     * @return Model|null
     */
    public function firstOrCreate(array $key, array $payload): ?Model;

    /**
     * Update existing model.
     *
     * @param array $payload
     * @param Model $model
     * @return Model|null
     */
    public function update(array $payload, Model $model): ?Model;

    /**
     * Delete model by id.
     *
     * @param Model $model
     * @return bool
     */
    public function deleteById(Model $model): bool;

    /**
     * Restore model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function restoreById(int $modelId): bool;

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool;
}
