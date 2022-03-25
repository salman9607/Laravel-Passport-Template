<?php

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    // protected $defaultIncludes = [
    //     //
    // ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    // protected $availableIncludes = [
    //     //
    // ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'title' => $post->title,
            'content' => $post->content,
        ];
    }
}
