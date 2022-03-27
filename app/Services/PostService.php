<?php
namespace App\Services;
use App\Models\Post;

class PostService extends BaseService
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }
}
