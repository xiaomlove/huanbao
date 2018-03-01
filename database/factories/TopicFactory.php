<?php

use Faker\Generator as Faker;
use App\Models\Forum;
use App\User;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    static $forums, $users;
    return [
        'key' => \Uuid::uuid4(),
        'title' => $faker->sentence,
        'fid' => ($forums ?: $forums = Forum::all())->random(),
        'uid' => ($users ?: $users = User::limit(50)->get())->random(),
    ];
});
