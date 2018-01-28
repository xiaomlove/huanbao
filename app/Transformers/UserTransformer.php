<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    public function transform(User $user)
    {
        return [
            'key' => $user->id,
            'id' => $user->id,
            'name' => $user->name,
            'point_counts' => $user->point_counts,
            'topic_counts' => $user->topic_counts,
            'comment_counts' => $user->comment_counts,
            'following_counts' => $user->following_counts,
            'fans_counts' => $user->fans_counts,
            'avatar' => $user->avatarUrl(40, 40),
        ];
    }
    
}

