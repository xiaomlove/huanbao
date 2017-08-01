<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Forum;

class ForumTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    public function transform(Forum $forum)
    {
        return [
            'id' => $forum->id,
            'key' => $forum->id,
            'name' => $forum->name,
        ];
    }
    
    public function includeChildren(Forum $forum)
    {
        return $this->collection($forum->children, $this);
    }
}

