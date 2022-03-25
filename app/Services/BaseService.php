<?php 
namespace App\Services;

use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $repository;

    /**
     * @param BaseRepository|null $repository
     */
    public function __construct(BaseRepository $repository = null)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->repository->all($columns, $relations);
    }

    /**
     * Create a model.
     *
     * @param array $payload
     * @return Model|null
     */
    public function create(array $payload): ?Model
    {
        return $this->repository->create($payload);
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
        return $this->repository->update($payload, $model);
    }

    /**
     * Delete existing model.
     *
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $this->repository->deleteById($model);
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function findById(int $id): ?Model
    {
        return $this->repository->findById($id);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate(int $perPage = 15, array $columns = ["*"])
    {
        return $this->repository->paginate($perPage, $columns);
    }

    /**
     * Permanently delete model by id.
     *
     * @param int $modelId
     * @return bool
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->repository->permanentlyDeleteById($modelId);
    }
}
