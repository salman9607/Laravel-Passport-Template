<?php

namespace App\Http\Controllers\API;

use App\Transformers\PostTransformer;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
// use Illuminate\Http\Request;

class PostController extends BaseController
{
    protected PostService $service;

    public function __construct(PostService $service, PostTransformer $transformer)
    {
        $this->service = $service;
        $this->transformer = $transformer;

        parent::__construct();
    }

	public function index()
	{
		echo "strings";
	}

	public function store(PostRequest $postRequest): Response
	{
		$post = $this->service->createPost($postRequest->validated());
		dd($post);
	}
}
