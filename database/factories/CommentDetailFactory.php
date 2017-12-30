<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CommentDetail::class, function (Faker $faker) {
    return [
        'content' => json_encode([
            ['type' => 'text', ['data' => ['text' => $faker->sentence()]]],
            ['type' => 'image', ['data' => ['url' => $faker->imageUrl(400, 600)]]],
        ], JSON_UNESCAPED_UNICODE)
    ];
});
