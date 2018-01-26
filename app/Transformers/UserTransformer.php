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
            'avatar' => $user->avatarUrl(40, 40),
        ];
    }
    
}

