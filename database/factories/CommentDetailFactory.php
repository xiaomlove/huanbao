<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CommentDetail::class, function (Faker $faker) {
    $content = [
        ['type' => 'text', 'data' => ['text' => $faker->sentence()]],
    ];
    if (rand(1, 10) > 7)
    {
        $content[] = ['type' => 'image', 'data' => ['url' => $faker->imageUrl(800, 400)]];
    }
    return [
        'content' => json_encode($content, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
    ];
});
