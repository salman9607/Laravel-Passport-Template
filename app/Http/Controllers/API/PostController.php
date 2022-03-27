<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Http\JsonResponse as JsonResponse;

class PostController extends Controller
{
    protected PostService $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

	public function index()
	{
		$posts = $this->service->all();
		return PostResource::collection($posts);
    	// return response()->noContent();
	}

	public function show($id)
	{
		$get_post = $this->service->findById($id);
		return new PostResource($get_post);
	}

	public function store(PostRequest $postRequest): PostResource
	{
		$post = $this->service->create($postRequest->validated());
		return new PostResource($post);
	}

	public function update(Post $post, PostRequest $postRequest): JsonResponse
	{
		$this->service->update($postRequest->validated(), $post);
		return response()->success();
	}

	public function destroy(Post $post): JsonResponse
	{
		$this->service->delete($post);
		return response()->success();
	}

}
