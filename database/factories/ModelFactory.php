<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\Topic::class, function (Faker\Generator $faker) {
    static $users, $forums;
    if (empty($users))
    {
        $users = App\User::all();
    }
    if (empty($forums))
    {
        $forums = App\Models\Forum::all();
    }
    return [
        'title' => $faker->sentence,
        'uid' => function() use($users) {
            return $users->random()->id;
        },
        'fid' => function() use($forums) {
            return $forums->random()->id;
        },
    ];
});

$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    static $users, $topics;
    if (empty($users))
    {
        $users = App\User::all();
    }
    if (empty($topics))
    {
        $topics = App\Models\Topic::all();
    }
    
    return [
        'uid' => function() use($users) {
            return $users->random()->id;
        },
        'tid' => function() use($topics) {
            return $topics->random()->id;
        },
    ];
});

$factory->define(App\Models\CommentDetail::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->paragraph,
    ];
});