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
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
    
}

