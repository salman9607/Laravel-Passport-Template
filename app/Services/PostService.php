<?php

namespace App\Services;

// use App\Models\Category;
// use App\Models\Media;
// use App\Models\User;
use App\Repositories\Eloquent\PostRepository;
// use App\Repositories\Eloquent\LocationRepository;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use League\Event\Event;

class PostService extends BaseService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;

        parent::__construct($postRepository);
    }

    public function createPost(array $data): ?Model
    {
//        $data['user_id'] = Auth::user()->id;
        $data['user_id'] = 1;
		dd($data);
        return $this->postRepository->create($data);
    }

    /**
     * @param array $data
     * @param $event
     * @return Model|null
     */
    public function updateEvent(array $data, $event): ?Model
    {
        return $this->postRepository->update($data, $event);

    }

    /**
     * @param User $user
     * @return LengthAwarePaginator
     */
    public function userEvents(User $user): LengthAwarePaginator
    {
        return $this->postRepository->userEvents($user);
    }

    /**
     * @param Category $category
     * @return mixed
     */
    public function categoryEvents(Category $category, $params)
    {
        return $this->repository->categoryEvents($category)
            ->paginate($params['per_page']);
    }

    public function myEvents($params)
    {
        return $this->repository->myEvents()
            ->paginate($params['per_page']);
    }
}
