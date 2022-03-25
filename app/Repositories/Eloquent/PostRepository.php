<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostRepository extends BaseRepository
{
    /**
     * @param Post $model
     */
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    /**
     * @param User $user
     * @return LengthAwarePaginator
     */
    public function userPost(User $user): LengthAwarePaginator
    {
        // return $user->events()->orderByDesc('created_at')->paginate();
    }

    /**
     * @param int $modelId
     * @param array $columns
     * @param array $relations
     * @return Model|null
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations = []): ?Model
    {
        $event = $this->model->select($columns)->with($relations)->findOrFail($modelId);

        $event->page_visits += 1;
        $event->save();

        return $event;
    }

    /**
     * @param Category $category
     * @return BelongsToMany
     */
    public function categoryPost(Category $category): BelongsToMany
    {
        // return $category->events()->orderByDesc('created_at');
    }

    public function myPost()
    {
        // return Auth::user()->events()->orderByDesc('created_at');
    }
}
