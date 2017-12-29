<?php

use Faker\Generator as Faker;
use App\Models\Forum;
use App\User;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    static $forums, $users;
    if (empty($forums))
    {
        $forums = Forum::all();
    }
    if (empty($users))
    {
        $users = User::all();
    }
    return [
        'title' => $faker->sentence,
        'fid' => $forums->random(),
        'uid' => $users->random(),
    ];
});
