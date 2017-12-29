<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CommentDetail::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph,
    ];
});
