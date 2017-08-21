<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    public function transform(User $user)
    {
        $avatar = $user->avatars->first();
        return [
            'key' => $user->id,
            'id' => $user->id,
            'name' => $user->name,
            //'avatar' => asset(sprintf("storage/%s/%s%s", $avatar->dirname, $avatar->basename, "!r40x40")),
        ];
    }
    
}

